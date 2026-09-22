<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnameController extends Controller
{
    public function index()
    {
        return view('stock.opname', [
            'opnames'  => StockOpname::with(['product', 'user'])
                ->latest('opname_date')->paginate(15),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'physical_stock' => 'required|integer|min:0',
            'opname_date'    => 'required|date',
            'note'           => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $difference = $data['physical_stock'] - $product->stock;

            StockOpname::create([
                'product_id'     => $data['product_id'],
                'system_stock'   => $product->stock,
                'physical_stock' => $data['physical_stock'],
                'difference'     => $difference,
                'note'           => $data['note'] ?? null,
                'user_id'        => Auth::id(),
                'opname_date'    => $data['opname_date'],
            ]);

            if ($difference !== 0) {
                $product->increment('stock', $difference);
            }
        });

        return redirect()->route('opname.index')
            ->with('success', 'Stock opname berhasil dicatat.');
    }
}