<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['product', 'user'])
            ->latest('transaction_date')->latest('id')->paginate(15);
        return view('stock.index', compact('transactions'));
    }

    public function incoming()
    {
        return view('stock.incoming', [
            'products'  => Product::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function storeIncoming(Request $request)
    {
        $data = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'supplier_id'      => 'nullable|exists:suppliers,id',
            'quantity'         => 'required|integer|min:1',
            'note'             => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        DB::transaction(function () use ($data) {
            Transaction::create($data + [
                'type'    => 'in',
                'user_id' => Auth::id(),
            ]);
            Product::where('id', $data['product_id'])
                ->increment('stock', $data['quantity']);
        });

        return redirect()->route('stock.index')
            ->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function outgoing()
    {
        return view('stock.outgoing', ['products' => Product::orderBy('name')->get()]);
    }

    public function storeOutgoing(Request $request)
    {
        $data = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'note'             => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $product = Product::findOrFail($data['product_id']);
        if ($product->stock < $data['quantity']) {
            return back()
                ->with('error', "Stok tidak mencukupi. Tersedia: {$product->stock}")
                ->withInput();
        }

        DB::transaction(function () use ($data) {
            Transaction::create($data + [
                'type'    => 'out',
                'user_id' => Auth::id(),
            ]);
            Product::where('id', $data['product_id'])
                ->decrement('stock', $data['quantity']);
        });

        return redirect()->route('stock.index')
            ->with('success', 'Barang keluar berhasil dicatat.');
    }
}