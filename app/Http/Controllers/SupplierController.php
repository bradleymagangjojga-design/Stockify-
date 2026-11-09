<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index()
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::withCount('products')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|max:150',
            'contact' => 'nullable|max:50',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);

        Supplier::create($data);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Show supplier detail with their products.
     */
    public function show(Supplier $supplier)
    {
        $products = Product::with('category')
            ->where('supplier_id', $supplier->id)
            ->orderBy('name')
            ->get();

        $stats = [
            'total_products' => $products->count(),
            'total_stock'    => $products->sum('stock'),
            'total_value'    => $products->sum(fn($p) => $p->stock * $p->purchase_price),
            // FIX: strict less-than, excludes out-of-stock
            'low_stock'      => $products->filter(fn($p) => $p->is_low_stock)->count(),
        ];

        return view('suppliers.show', compact('supplier', 'products', 'stats'));
    }

    /**
     * Show a printable supplier report.
     */
    public function print(Supplier $supplier)
    {
        $products = Product::with('category')
            ->where('supplier_id', $supplier->id)
            ->orderBy('name')
            ->get();

        $stats = [
            'total_products' => $products->count(),
            'total_stock'    => $products->sum('stock'),
            'total_value'    => $products->sum(fn($p) => $p->stock * $p->purchase_price),
        ];

        return view('suppliers.print', compact('supplier', 'products', 'stats'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'    => 'required|max:150',
            'contact' => 'nullable|max:50',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);

        $supplier->update($data);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    /**
     * Export all suppliers as CSV.
     *
     * Route: GET /suppliers/export
     * Name:  suppliers.export
     */
    public function export(): StreamedResponse
    {
        $filename = 'stockify-suppliers-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($out, "\xEF\xBB\xBF");

            // ---- Section 1: Summary ----
            fputcsv($out, ['STOCKIFY — SUPPLIER EXPORT']);
            fputcsv($out, ['Generated', now()->translatedFormat('d M Y, H:i')]);
            fputcsv($out, ['Total Suppliers', Supplier::count()]);
            fputcsv($out, []);

            // ---- Section 2: Column Headers ----
            fputcsv($out, [
                'ID',
                'Supplier Name',
                'Contact Person',
                'Email',
                'Address',
                'Products Count',
                'Total Stock',
                'Total Value (Rp)',
                'Status',
                'Created At',
            ]);

            // ---- Section 3: Data Rows ----
            Supplier::with(['products'])
                ->withCount('products')
                ->orderBy('name')
                ->chunk(500, function ($suppliers) use ($out) {
                    foreach ($suppliers as $s) {
                        $totalStock = (int) $s->products->sum('stock');
                        $totalValue = (float) $s->products->sum(fn($p) => $p->stock * $p->purchase_price);

                        fputcsv($out, [
                            $s->id,
                            $s->name,
                            $s->contact ?? '—',
                            $s->email ?? '—',
                            $s->address ?? '—',
                            $s->products_count,
                            $totalStock,
                            number_format($totalValue, 0, ',', '.'),
                            $s->products_count > 0 ? 'Active' : 'No Products Yet',
                            optional($s->created_at)->format('Y-m-d H:i'),
                        ]);
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}