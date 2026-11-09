<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\StockOpname;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $transactions = Transaction::with(['product', 'user'])
            ->latest('transaction_date')->latest('id')->paginate(15);
        return view('stock.index', compact('transactions'));
    }

    public function incoming(Request $request)
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

        $product = Product::findOrFail($data['product_id']);

        DB::transaction(function () use ($data, $product) {
            Transaction::create($data + [
                'type'    => 'in',
                'user_id' => Auth::id(),
            ]);
            $product->increment('stock', $data['quantity']);
        });

        // ✅ LOG
        $this->logActivity(
            'created',
            "Recorded incoming stock: +{$data['quantity']} units of {$product->name}",
            $product
        );

        return redirect($this->safeRedirect($request, 'stock.index'))
            ->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function outgoing(Request $request)
    {
        return view('stock.outgoing', [
            'products' => Product::orderBy('name')->get(),
        ]);
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

        DB::transaction(function () use ($data, $product) {
            Transaction::create($data + [
                'type'    => 'out',
                'user_id' => Auth::id(),
            ]);
            $product->decrement('stock', $data['quantity']);
        });

        // ✅ LOG
        $this->logActivity(
            'created',
            "Recorded outgoing stock: -{$data['quantity']} units of {$product->name}",
            $product
        );

        return redirect($this->safeRedirect($request, 'stock.index'))
            ->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function storeOpname(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'physical_stock' => 'required|integer|min:0',
            'opname_date'    => 'required|date',
            'note'           => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $systemStock = $product->stock;
        $physicalStock = $data['physical_stock'];
        $difference = $physicalStock - $systemStock;

        DB::transaction(function () use ($data, $product, $systemStock, $physicalStock, $difference) {
            StockOpname::create([
                'product_id'     => $product->id,
                'user_id'        => Auth::id(),
                'system_stock'   => $systemStock,
                'physical_stock' => $physicalStock,
                'difference'     => $difference,
                'note'           => $data['note'],
                'opname_date'    => $data['opname_date'],
            ]);

            $product->update(['stock' => $physicalStock]);

            if ($difference != 0) {
                Transaction::create([
                    'product_id'       => $product->id,
                    'user_id'          => Auth::id(),
                    'type'             => $difference > 0 ? 'in' : 'out',
                    'quantity'         => abs($difference),
                    'note'             => 'Penyesuaian Stok Opname: ' . ($data['note'] ?? 'Tanpa catatan'),
                    'transaction_date' => $data['opname_date'],
                ]);
            }
        });

        // ✅ LOG
        $this->logActivity(
            'updated',
            "Stock Opname for {$product->name}: system {$systemStock} → physical {$physicalStock} (diff: {$difference})",
            $product
        );

        return redirect()->route('opname.index')
            ->with('success', 'Stock Opname berhasil disimpan dan stok telah disesuaikan.');
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function safeRedirect(Request $request, string $fallbackRoute): string
    {
        $appUrl = url('/');

        $redirect = $request->input('redirect_to');
        if ($redirect && str_starts_with($redirect, $appUrl)) {
            return $redirect;
        }

        $referer = $request->headers->get('referer');
        if ($referer && str_starts_with($referer, $appUrl)) {
            return $referer;
        }

        return route($fallbackRoute);
    }
}
