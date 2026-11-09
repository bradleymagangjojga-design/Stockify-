<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = [];

        // 1. Search Products
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($products as $product) {
            $results[] = [
                'type'  => 'Product',
                'title' => $product->name,
                'sub'   => $product->sku . ' · Stok: ' . $product->stock,
                'url'   => route('products.show', $product->id),
                'icon'  => '📦',
            ];
        }

        // 2. Search Suppliers
        $suppliers = Supplier::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($suppliers as $supplier) {
            $results[] = [
                'type'  => 'Supplier',
                'title' => $supplier->name,
                'sub'   => $supplier->phone ?? 'Supplier',
                'url'   => route('suppliers.show', $supplier->id),
                'icon'  => '🏭',
            ];
        }

        // 3. Search Transactions (by product name)
        $transactions = Transaction::with('product')
            ->whereHas('product', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        foreach ($transactions as $txn) {
            $results[] = [
                'type'  => 'Transaction',
                'title' => ($txn->type === 'in' ? 'Masuk: ' : 'Keluar: ') . ($txn->product->name ?? 'Produk'),
                'sub'   => $txn->quantity . ' unit · ' . $txn->transaction_date->format('d M Y'),
                'url'   => route('stock.index'), // Or a specific transaction route if you have one
                'icon'  => $txn->type === 'in' ? '⬇️' : '⬆️',
            ];
        }

        return response()->json($results);
    }
}
