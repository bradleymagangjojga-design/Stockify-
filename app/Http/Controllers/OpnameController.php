<?php

namespace App\Http\Controllers;

use App\Models\StockOpname as Opname;   // ✅ THIS is the fix
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = Opname::with(['product', 'user'])
            ->when($request->filled('status'), function ($q) use ($request) {
                match ($request->status) {
                    'match' => $q->where('difference', 0),
                    'over'  => $q->where('difference', '>', 0),
                    'under' => $q->where('difference', '<', 0),
                    default => null,
                };
            })
            ->when($request->filled('date'), fn ($q) =>
                $q->whereDate('opname_date', $request->date)
            );

        match ($request->input('sort', 'newest')) {
            'oldest'    => $query->orderBy('opname_date')->orderBy('id'),
            'diff_desc' => $query->orderByRaw('ABS(difference) DESC')->orderByDesc('opname_date'),
            default     => $query->orderByDesc('opname_date')->orderByDesc('id'),
        };

        if ($request->input('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $opnames = $query->paginate(15)->withQueryString();

        $totalOpnames  = Opname::count();
        $todayCount    = Opname::whereDate('opname_date', today())->count();
        $thisMonth     = Opname::whereYear('opname_date', now()->year)
                               ->whereMonth('opname_date', now()->month)
                               ->count();
        $withDiff      = Opname::where('difference', '!=', 0)->count();
        $perfectCount  = Opname::where('difference', 0)->count();
        $netAdjustment = (int) Opname::sum('difference');

        $accuracyRate = $totalOpnames > 0
            ? round(($perfectCount / $totalOpnames) * 100, 1)
            : 0.0;

        $products = Product::orderBy('name')->get(['id', 'name', 'stock']);

        return view('opname.index', compact(
            'opnames', 'totalOpnames', 'todayCount', 'thisMonth',
            'withDiff', 'perfectCount', 'netAdjustment',
            'accuracyRate', 'products'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'     => ['required', 'integer', Rule::exists('products', 'id')],
            'physical_stock' => ['required', 'integer', 'min:0'],
            'opname_date'    => ['required', 'date'],
            'note'           => ['nullable', 'string', 'max:1000'],
        ]);

        $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

        $systemStock   = (int) $product->stock;
        $physicalStock = (int) $validated['physical_stock'];
        $difference    = $physicalStock - $systemStock;

        DB::transaction(function () use ($product, $systemStock, $physicalStock, $difference, $validated) {
            $opname = Opname::create([
                'product_id'     => $product->id,
                'user_id'        => auth()->id(),
                'opname_date'    => $validated['opname_date'],
                'system_stock'   => $systemStock,
                'physical_stock' => $physicalStock,
                'difference'     => $difference,
                'note'           => $validated['note'] ?? null,
            ]);

            $product->update(['stock' => $physicalStock]);

            if ($difference !== 0) {
                Transaction::create([
                    'product_id'       => $product->id,
                    'user_id'          => auth()->id(),
                    'type'             => $difference > 0 ? 'in' : 'out',
                    'quantity'         => abs($difference),
                    'transaction_date' => $validated['opname_date'],
                    'note'             => 'Stock opname adjustment — OPN-' . str_pad($opname->id, 5, '0', STR_PAD_LEFT),
                ]);
            }
        });

        return redirect()
            ->route('opname.index')
            ->with('success', __('Opname saved. Product stock has been reconciled.'));
    }

    public function print(Opname $opname)
    {
        $opname->load(['product.category', 'user']);

        $data = [
            'opname_id'      => 'OPN-' . str_pad($opname->id, 5, '0', STR_PAD_LEFT),
            'printed_at'     => now()->translatedFormat('d M Y, H:i'),
            'opname_date'    => $opname->opname_date->translatedFormat('d M Y'),
            'product'        => $opname->product->name ?? '—',
            'sku'            => $opname->product->sku ?? '—',
            'category'       => $opname->product->category->name ?? '—',
            'system_stock'   => (int) $opname->system_stock,
            'physical_stock' => (int) $opname->physical_stock,
            'difference'     => (int) $opname->difference,
            'status'         => $opname->difference < 0
                                    ? 'UNDER'
                                    : ($opname->difference > 0 ? 'OVER' : 'MATCH'),
            'status_label'   => $opname->difference < 0
                                    ? __('Under')
                                    : ($opname->difference > 0 ? __('Over') : __('Match')),
            'note'           => $opname->note,
            'user'           => $opname->user->name ?? '—',
        ];

        return view('opname.print', compact('data', 'opname'));
    }

    public function destroy(Opname $opname)
    {
        if (!in_array(auth()->user()->role, ['Admin', 'Manajer Gudang'], true)) {
            abort(403, 'Unauthorized to delete opname records.');
        }

        $opname->delete();

        return redirect()
            ->route('opname.index')
            ->with('success', __('Opname record deleted.'));
    }

    protected function exportCsv($rows)
    {
        $filename = 'opname-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID', 'Date', 'Product', 'SKU',
                'System', 'Physical', 'Difference',
                'Status', 'User', 'Note',
            ]);

            foreach ($rows as $r) {
                $status = $r->difference < 0
                    ? 'Under'
                    : ($r->difference > 0 ? 'Over' : 'Match');

                fputcsv($out, [
                    'OPN-' . str_pad($r->id, 5, '0', STR_PAD_LEFT),
                    optional($r->opname_date)->toDateString(),
                    $this->csvSafe($r->product->name ?? '—'),
                    $this->csvSafe($r->product->sku ?? '—'),
                    (int) $r->system_stock,
                    (int) $r->physical_stock,
                    ($r->difference > 0 ? '+' : '') . (int) $r->difference,
                    $status,
                    $this->csvSafe($r->user->name ?? '—'),
                    $this->csvSafe($r->note ?? ''),
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function csvSafe(?string $value): string
    {
        $value = (string) $value;

        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'], true)) {
            return "'" . $value;
        }

        return $value;
    }
}