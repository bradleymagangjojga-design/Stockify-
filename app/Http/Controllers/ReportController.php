<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    use LogsActivity;

    public function index()
    {
        return view('reports.index');
    }

    public function stock()
    {
        $products = Product::with(['category', 'supplier'])
            ->orderBy('name')
            ->paginate(30);

        return view('reports.stock', compact('products'));
    }

    /**
     * ✅ NEW: Show all categories with stock + value breakdown.
     * Accessible by Admin & Manager (same as the reports section).
     */
    public function categories()
    {
        $categories = Category::with(['products'])
            ->withCount('products')
            ->get()
            ->map(function ($c) {
                $stock = (int) $c->products->sum('stock');
                $value = (float) $c->products->sum(fn($p) => $p->stock * $p->purchase_price);
                $low   = $c->products->filter(fn($p) => $p->is_low_stock)->count();

                return [
                    'id'             => $c->id,
                    'name'           => $c->name,
                    'description'    => $c->description,
                    'products_count' => $c->products_count,
                    'stock'          => $stock,
                    'value'          => $value,
                    'low_stock'      => $low,
                ];
            })
            ->sortByDesc('stock')
            ->values();

        $totalStock    = $categories->sum('stock');
        $totalValue    = $categories->sum('value');
        $totalProducts = $categories->sum('products_count');

        return view('reports.categories', compact(
            'categories',
            'totalStock',
            'totalValue',
            'totalProducts'
        ));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['product', 'supplier', 'user']);

        // ✅ STEP 3: Restrict Staff to only see their own transactions
        if (auth()->user()->role === 'Staff Gudang') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query
            ->when($request->type,      fn($q, $v) => $q->where('type', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('transaction_date', '>=', $v))
            ->when($request->date_to,   fn($q, $v) => $q->whereDate('transaction_date', '<=', $v))
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('reports.transactions', compact('transactions'));
    }

    /**
     * ✅ Activity report — now pulls REAL activity logs.
     */
    public function activities(Request $request)
    {
        $query = ActivityLog::with('user');

        // ✅ STEP 3: Restrict Staff to only see their own activity logs
        if (auth()->user()->role === 'Staff Gudang') {
            $query->where('user_id', auth()->id());
        }

        $activities = $query
            ->when($request->action,    fn($q, $v) => $q->where('action', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->date_to,   fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest('created_at')
            ->latest('id')
            ->paginate(50)
            ->withQueryString();

        return view('reports.activities', compact('activities'));
    }

    /**
     * ✅ Dedicated "All Activities" page (Simple list).
     * - Admin & Manager see ALL activities.
     * - Staff only see THEIR OWN activities.
     * - ONLY shows Login, Logout, Stock, and Stock Opname activities.
     */
    public function allActivities(Request $request)
    {
        $user = auth()->user();
        $query = ActivityLog::with('user');

        // Restrict Staff to only see their own activity logs
        if ($user->role === 'Staff Gudang') {
            $query->where('user_id', $user->id);
        }

        // ✅ FILTER: Only include Login, Logout, Stock, and Opname activities
        $query->where(function ($q) {
            $q->whereIn('action', ['login', 'logout'])
              ->orWhere('description', 'LIKE', '%stock%')
              ->orWhere('description', 'LIKE', '%opname%')
              ->orWhere('description', 'LIKE', '%incoming%')
              ->orWhere('description', 'LIKE', '%outgoing%')
              ->orWhere('description', 'LIKE', '%goods%');
        });

        $activities = $query
            ->latest('created_at')
            ->latest('id')
            ->paginate(30);

        return view('reports.all-activities', compact('activities', 'user'));
    }

    // ============================================================
    // ✅ JSON API endpoints (used for any AJAX needs)
    // ============================================================

    public function productStock(Product $product)
    {
        $product->load(['category', 'supplier']);

        return response()->json([
            'report_id'      => 'PRD-' . str_pad($product->id, 5, '0', STR_PAD_LEFT),
            'printed_at'     => Carbon::now()->translatedFormat('d M Y, H:i'),
            'name'           => $product->name,
            'sku'            => $product->sku,
            'category'       => $product->category->name ?? '—',
            'supplier'       => $product->supplier->name ?? '—',
            'stock'          => format_number($product->stock, 0),
            'min_stock'      => format_number($product->min_stock, 0),
            'purchase_price' => format_currency($product->purchase_price),
            'selling_price'  => format_currency($product->selling_price ?? 0),
            'total_value'    => format_currency($product->stock * $product->purchase_price),
        ]);
    }

    public function transactionShow(Transaction $transaction)
    {
        // ✅ STEP 3: Prevent Staff from viewing details of transactions they don't own
        if (auth()->user()->role === 'Staff Gudang' && $transaction->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $transaction->load(['product.category', 'supplier', 'user']);

        return response()->json([
            'transaction_id' => 'TRX-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT),
            'printed_at'     => Carbon::now()->translatedFormat('d M Y, H:i'),
            'type'           => $transaction->type,
            'type_label'     => $transaction->type === 'in'
                                    ? __('INCOMING GOODS')
                                    : __('OUTGOING GOODS'),
            'product'        => $transaction->product->name ?? '—',
            'sku'            => $transaction->product->sku  ?? '—',
            'category'       => optional($transaction->product->category)->name ?? '—',
            'supplier'       => $transaction->supplier->name ?? '—',
            'quantity'       => ($transaction->type === 'in' ? '+' : '-') . $transaction->quantity,
            'datetime'       => $transaction->transaction_date->translatedFormat('d M Y, H:i'),
            'user'           => $transaction->user->name ?? '—',
            'note'           => $transaction->note ?: '—',
        ]);
    }

    // ============================================================
    // ✅ Server-rendered print views
    // ============================================================

    /**
     * Server-rendered print view for individual product stock report.
     */
    public function printProduct(Product $product)
    {
        $product->load(['category', 'supplier']);

        return view('reports.print-product', compact('product'));
    }

    /**
     * Server-rendered print view for individual transaction report.
     * Staff can only print their own transactions.
     */
    public function printTransaction(Transaction $transaction)
    {
        // ✅ Security: Staff can only print their own transactions
        if (auth()->user()->role === 'Staff Gudang' && $transaction->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $transaction->load(['product.category', 'supplier', 'user']);

        return view('reports.print-transaction', compact('transaction'));
    }

    // ============================================================
    // CSV EXPORT
    // ============================================================

    public function export(Request $request): StreamedResponse
    {
        $selectedPeriod = $request->get('period', '30');

        $startDate = match ($selectedPeriod) {
            '7'    => now()->subDays(7),
            '30'   => now()->subDays(30),
            '90'   => now()->subDays(90),
            'year' => now()->startOfYear(),
            default => now()->subDays(30),
        };

        $filename = 'stockify-report-' . $selectedPeriod . '-' . now()->format('Ymd-His') . '.csv';

        // ✅ LOG the export
        $this->logActivity(
            'exported',
            "Exported report as CSV for period: {$selectedPeriod} days",
            null
        );

        $currencyCode = app_currency();
        $currencyCfg  = currency_config();

        return response()->streamDownload(function () use ($startDate, $currencyCode, $currencyCfg) {

            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            // ---- Section 1: Summary ----
            fputcsv($out, ['STOCKIFY — REPORT EXPORT']);
            fputcsv($out, ['Generated', now()->translatedFormat('d M Y, H:i')]);
            fputcsv($out, ['Period Start', $startDate->format('Y-m-d')]);
            fputcsv($out, ['Period End', now()->format('Y-m-d')]);
            fputcsv($out, ['Currency', $currencyCode]);
            fputcsv($out, []);

            // ---- Section 2: Transactions ----
            fputcsv($out, ['TRANSACTIONS']);
            fputcsv($out, ['ID', 'Date', 'Type', 'Product', 'SKU', 'Supplier', 'Quantity', 'User', 'Notes']);

            Transaction::with(['product', 'supplier', 'user'])
                ->whereDate('transaction_date', '>=', $startDate)
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $t) {
                        fputcsv($out, [
                            '#'.str_pad($t->id, 5, '0', STR_PAD_LEFT),
                            optional($t->transaction_date)->format('Y-m-d H:i'),
                            strtoupper($t->type),
                            $t->product->name ?? '—',
                            $t->product->sku  ?? '—',
                            $t->supplier->name ?? '—',
                            ($t->type === 'in' ? '+' : '-') . $t->quantity,
                            $t->user->name ?? '—',
                            $t->note ?: '',
                        ]);
                    }
                });

            fputcsv($out, []);

            // ---- Section 3: Current Stock ----
            fputcsv($out, ['CURRENT STOCK']);
            fputcsv($out, [
                'SKU', 'Product', 'Category', 'Supplier', 'Stock', 'Min Stock',
                'Purchase Price (' . $currencyCfg['symbol'] . ')',
                'Value (' . $currencyCfg['symbol'] . ')',
                'Status',
            ]);

            Product::with(['category', 'supplier'])
                ->orderBy('name')
                ->chunk(500, function ($rows) use ($out, $currencyCfg) {
                    foreach ($rows as $p) {
                        $status = match ($p->stock_status) {
                            'out'   => 'OUT',
                            'low'   => 'LOW',
                            default => 'SAFE',
                        };

                        $purchasePrice = number_format(
                            (float) $p->purchase_price,
                            $currencyCfg['decimals'], $currencyCfg['dec'], $currencyCfg['thou']
                        );
                        $value = number_format(
                            (float) ($p->stock * $p->purchase_price),
                            $currencyCfg['decimals'], $currencyCfg['dec'], $currencyCfg['thou']
                        );

                        fputcsv($out, [
                            $p->sku, $p->name,
                            $p->category->name ?? '—',
                            $p->supplier->name ?? '—',
                            $p->stock, $p->min_stock,
                            $purchasePrice, $value, $status,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
