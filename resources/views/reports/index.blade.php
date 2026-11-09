@extends('layouts.app')
@section('title', __('Report'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Report') }}</span>
@endsection

@section('page-header')
@php
    $selectedPeriod = request('period', '30');

    $periodLabel = match ($selectedPeriod) {
        '7'    => __('Last 7 Days'),
        '30'   => __('Last 30 Days'),
        '90'   => __('Last 90 Days'),
        'year' => __('This Year'),
        default => __('Last 30 Days'),
    };

    $startDate = match ($selectedPeriod) {
        '7'    => now()->subDays(7),
        '30'   => now()->subDays(30),
        '90'   => now()->subDays(90),
        'year' => now()->startOfYear(),
        default => now()->subDays(30),
    };
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Reports & Analytics') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Access complete reports on stock, transactions, and user activity') }}
        </p>
    </div>
    <div class="flex items-center gap-2">
        {{-- Filter Dropdown --}}
        <div class="relative" id="reportPeriodWrapper">
            <button type="button" id="reportPeriodToggle"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $periodLabel }}</span>
                <svg id="reportPeriodChevron" class="w-3 h-3 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <div id="reportPeriodMenu"
                 class="hidden absolute right-0 z-50 mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl w-48 py-1">
                <a href="{{ route('reports.index', ['period' => 7]) }}"
                   class="block px-4 py-2 text-sm transition {{ $selectedPeriod == '7' ? 'bg-blue-600 text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    {{ __('Last 7 Days') }}
                </a>
                <a href="{{ route('reports.index', ['period' => 30]) }}"
                   class="block px-4 py-2 text-sm transition {{ $selectedPeriod == '30' ? 'bg-blue-600 text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    {{ __('Last 30 Days') }}
                </a>
                <a href="{{ route('reports.index', ['period' => 90]) }}"
                   class="block px-4 py-2 text-sm transition {{ $selectedPeriod == '90' ? 'bg-blue-600 text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    {{ __('Last 90 Days') }}
                </a>
                <a href="{{ route('reports.index', ['period' => 'year']) }}"
                   class="block px-4 py-2 text-sm transition {{ $selectedPeriod == 'year' ? 'bg-blue-600 text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    {{ __('This Year') }}
                </a>
            </div>
        </div>

        {{-- Export Button --}}
        <a href="{{ route('reports.export', ['period' => $selectedPeriod]) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            {{ __('Export') }}
        </a>
    </div>
</div>
@endsection

@section('content')

@php
    $transactionsInPeriod = \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)->count();
    $txnLabel = $selectedPeriod === 'year' ? __('Transactions This Year') : __('Transactions This Month');

    $stockIn  = (int) \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)->where('type', 'in')->sum('quantity');
    $stockOut = (int) \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)->where('type', 'out')->sum('quantity');
    $netFlow  = $stockIn - $stockOut;
@endphp

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Products --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                📦
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
            <p class="text-3xl font-bold">{{ number_format(\App\Models\Product::count()) }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('Active SKUs') }}</p>
        </div>
    </div>

    {{-- Transactions --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">🔄</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ $txnLabel }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($transactionsInPeriod) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('In + Out') }}</p>
    </div>

    {{-- Low Stock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">⚠️</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Low Stock') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ number_format(\App\Models\Product::lowStock()->count()) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Need restock') }}</p>
    </div>

    {{-- Active Users --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">👥</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Active Users') }}</p>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format(\App\Models\User::where('status', 'active')->count()) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Online now') }}</p>
    </div>
</div>

@php
    $dailyIn = \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)
        ->where('type', 'in')
        ->selectRaw('DATE(transaction_date) as d, SUM(quantity) as total')
        ->groupBy('d')->pluck('total', 'd');

    $dailyOut = \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)
        ->where('type', 'out')
        ->selectRaw('DATE(transaction_date) as d, SUM(quantity) as total')
        ->groupBy('d')->pluck('total', 'd');

    $chartDates = collect();
    $cursor = $startDate->copy()->startOfDay();
    $end = now()->endOfDay();
    while ($cursor <= $end) {
        $chartDates->push($cursor->copy());
        $cursor->addDay();
    }

    $chartLabels   = $chartDates->map(fn($d) => $d->format('d M'))->values();
    $chartInData   = $chartDates->map(fn($d) => (int) ($dailyIn[$d->toDateString()] ?? 0))->values();
    $chartOutData  = $chartDates->map(fn($d) => (int) ($dailyOut[$d->toDateString()] ?? 0))->values();

    // ✅ Stock by Category — with sorted data and percentage calculations
    $categoryStock = \App\Models\Category::with('products')->get()
        ->map(function($c) {
            $stock = (int) $c->products->sum('stock');
            $value = (float) $c->products->sum(fn($p) => $p->stock * $p->purchase_price);
            return [
                'name'  => $c->name,
                'stock' => $stock,
                'value' => $value,
            ];
        })
        ->filter(fn($c) => $c['stock'] > 0)
        ->sortByDesc('stock')
        ->values();

    $totalCategoryStock = $categoryStock->sum('stock');
    $categoryStock = $categoryStock->map(function($c) use ($totalCategoryStock) {
        $c['percent'] = $totalCategoryStock > 0 ? ($c['stock'] / $totalCategoryStock) * 100 : 0;
        return $c;
    });

    $topCategories  = $categoryStock->take(5);
    $remainingCount = max(0, $categoryStock->count() - 5);

    $topProducts = \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)
        ->selectRaw('product_id, SUM(quantity) as total')
        ->groupBy('product_id')
        ->orderByDesc('total')
        ->limit(5)
        ->with('product')
        ->get()
        ->map(fn($t) => [
            'name'  => $t->product->name ?? __('Deleted'),
            'value' => (int) $t->total,
        ]);

    $allProducts = \App\Models\Product::all();
    $safeCount   = $allProducts->filter(fn($p) => $p->is_safe_stock)->count();
    $lowCount    = $allProducts->filter(fn($p) => $p->is_low_stock)->count();
    $outCount    = $allProducts->filter(fn($p) => $p->is_out_of_stock)->count();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Stock Movement Chart --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Stock Movement') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Incoming vs outgoing goods') }} · {{ $periodLabel }}</p>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> {{ __('In') }}
                </span>
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> {{ __('Out') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-100 dark:border-green-500/20 px-3 py-2">
                <p class="text-[10px] font-semibold text-green-700 dark:text-green-400 uppercase tracking-widest">{{ __('Total In') }}</p>
                <p class="text-lg font-bold text-green-700 dark:text-green-400">+{{ number_format($stockIn) }}</p>
            </div>
            <div class="rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 px-3 py-2">
                <p class="text-[10px] font-semibold text-red-700 dark:text-red-400 uppercase tracking-widest">{{ __('Total Out') }}</p>
                <p class="text-lg font-bold text-red-700 dark:text-red-400">-{{ number_format($stockOut) }}</p>
            </div>
            <div class="rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 px-3 py-2">
                <p class="text-[10px] font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-widest">{{ __('Net Flow') }}</p>
                <p class="text-lg font-bold {{ $netFlow >= 0 ? 'text-blue-700 dark:text-blue-400' : 'text-orange-700 dark:text-orange-400' }}">
                    {{ $netFlow >= 0 ? '+' : '' }}{{ number_format($netFlow) }}
                </p>
            </div>
        </div>

        <div class="relative h-64">
            <canvas id="stockMovementChart"></canvas>
        </div>
    </div>

    {{-- ✅ Stock by Category — Redesigned ranked list --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm flex flex-col">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Stock by Category') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Top 5 categories by units') }}</p>
            </div>
        </div>

        @if ($categoryStock->isEmpty())
            <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">📊</div>
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('No stock data') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Add products to see category breakdown') }}</p>
            </div>
        @else
            {{-- Total summary at top --}}
            <div class="mb-4 p-3 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Total Units') }}</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalCategoryStock) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-2xl shadow-sm">
                        📦
                    </div>
                </div>
            </div>

            {{-- Ranked list with progress bars --}}
            <div class="space-y-3 flex-1">
                @foreach ($topCategories as $index => $cat)
                    @php
                        $colors = [
                            ['bar' => 'from-blue-500 to-indigo-600',   'bg' => 'bg-blue-500',    'text' => 'text-blue-600 dark:text-blue-400',    'light' => 'bg-blue-100 dark:bg-blue-500/20'],
                            ['bar' => 'from-emerald-500 to-green-600', 'bg' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400','light' => 'bg-emerald-100 dark:bg-emerald-500/20'],
                            ['bar' => 'from-amber-500 to-orange-600',  'bg' => 'bg-amber-500',   'text' => 'text-amber-600 dark:text-amber-400',  'light' => 'bg-amber-100 dark:bg-amber-500/20'],
                            ['bar' => 'from-violet-500 to-purple-600', 'bg' => 'bg-violet-500',  'text' => 'text-violet-600 dark:text-violet-400','light' => 'bg-violet-100 dark:bg-violet-500/20'],
                            ['bar' => 'from-rose-500 to-pink-600',     'bg' => 'bg-rose-500',    'text' => 'text-rose-600 dark:text-rose-400',    'light' => 'bg-rose-100 dark:bg-rose-500/20'],
                        ];
                        $c = $colors[$index % 5];
                    @endphp
                    <div class="group">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="w-5 h-5 rounded-md {{ $c['light'] }} {{ $c['text'] }} flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $cat['name'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ms-2">
                                <span class="text-xs font-semibold text-slate-900 dark:text-white">{{ number_format($cat['stock']) }}</span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 w-10 text-right">{{ number_format($cat['percent'], 1) }}%</span>
                            </div>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $c['bar'] }} transition-all duration-700 ease-out"
                                 style="width: {{ max(2, $cat['percent']) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ✅ FIXED: "And N more" — now points to the reports.categories route --}}
            @if ($remainingCount > 0)
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('reports.categories') }}"
                       class="flex items-center justify-between gap-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition group">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold">
                                +{{ $remainingCount }}
                            </span>
                            {{ __('more categories') }}
                        </span>
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Top Moving Products --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Top Moving Products') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('By total quantity') }} · {{ $periodLabel }}</p>
            </div>
            <a href="{{ route('reports.transactions') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">{{ __('View all') }} →</a>
        </div>

        @if ($topProducts->isEmpty())
            <div class="h-48 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl mb-3">🏆</div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No product movement yet') }}</p>
            </div>
        @else
            <div class="relative h-56">
                <canvas id="topProductsChart"></canvas>
            </div>
        @endif
    </div>

    {{-- Stock Health --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Stock Health') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Current status') }}</p>
            </div>
        </div>

        <div class="relative h-48">
            <canvas id="stockHealthChart"></canvas>
        </div>

        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span class="text-slate-600 dark:text-slate-400">{{ __('Safe Stock') }}</span>
                </div>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $safeCount }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                    <span class="text-slate-600 dark:text-slate-400">{{ __('Low Stock') }}</span>
                </div>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $lowCount }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span class="text-slate-600 dark:text-slate-400">{{ __('Out of Stock') }}</span>
                </div>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $outCount }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Report Cards --}}
@php
    $totalProducts = \App\Models\Product::count();
    $lowStockCount = \App\Models\Product::lowStock()->count();
    $txnInPeriod   = $transactionsInPeriod;
    $txnInCount    = \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)->where('type', 'in')->count();
    $txnOutCount   = \App\Models\Transaction::whereDate('transaction_date', '>=', $startDate)->where('type', 'out')->count();
    $activityCount = $txnInPeriod;
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <a href="{{ route('reports.stock') }}"
       class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-xl hover:border-blue-500 dark:hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-3xl shadow-lg shadow-blue-500/20 group-hover:scale-110 transition">
                📊
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 px-2.5 py-1 rounded-full">
                {{ __('Stock') }}
            </span>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Stock Report') }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            {{ __('Detail stock per category, inventory value, and low stock products') }}
        </p>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($totalProducts) }}</span>
                    {{ __('SKUs') }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                    <span class="font-semibold text-orange-600 dark:text-orange-400">{{ number_format($lowStockCount) }}</span>
                    {{ __('Low') }}
                </span>
            </div>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 group-hover:gap-2 transition-all">
                {{ __('View') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    <a href="{{ route('reports.transactions', ['date_from' => $startDate->format('Y-m-d')]) }}"
       class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-xl hover:border-green-500 dark:hover:border-green-500 hover:-translate-y-1 transition-all duration-200 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-3xl shadow-lg shadow-green-500/20 group-hover:scale-110 transition">
                🔄
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full">
                {{ __('transactions') }}
            </span>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Transaction Report') }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            {{ __('Detail incoming and outgoing goods by period, product, or user') }}
        </p>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                    <span class="font-semibold text-green-600 dark:text-green-400">+{{ number_format($txnInCount) }}</span>
                    {{ __('In') }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    <span class="font-semibold text-red-600 dark:text-red-400">-{{ number_format($txnOutCount) }}</span>
                    {{ __('Out') }}
                </span>
            </div>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-green-600 dark:text-green-400 group-hover:gap-2 transition-all">
                {{ __('View') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </a>

    @if (auth()->user()->isAdmin())
        <a href="{{ route('reports.activities') }}"
           class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-xl hover:border-purple-500 dark:hover:border-purple-500 hover:-translate-y-1 transition-all duration-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-3xl shadow-lg shadow-purple-500/20 group-hover:scale-110 transition">
                    👤
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 px-2.5 py-1 rounded-full">
                    {{ __('Activity Report') }}
                </span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Activity Report') }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                {{ __('Complete user activity log, data changes, and access history') }}
            </p>

            <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($activityCount) }}</span>
                        {{ __('Events') }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $periodLabel }}</span>
                    </span>
                </div>
                <span class="inline-flex items-center gap-1 text-sm font-semibold text-purple-600 dark:text-purple-400 group-hover:gap-2 transition-all">
                    {{ __('View') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </div>
        </a>
    @endif

</div>

{{-- Recent Activity --}}
@php
    $recentActivities = \App\Models\Transaction::with(['product', 'user'])
        ->whereDate('transaction_date', '>=', $startDate)
        ->latest('transaction_date')
        ->latest('id')
        ->limit(5)
        ->get();

    $recentCount = $recentActivities->count();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $recentCount }} {{ __('transactions') }} · {{ $periodLabel }}</p>
            </div>
            <a href="{{ route('stock.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">{{ __('View all') }} →</a>
        </div>

        @if ($recentActivities->isEmpty())
            <div class="py-12 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl mb-3">📋</div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('No activity yet.') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Transactions will appear here after activity') }}</p>
                </div>
            </div>
        @else
            <div class="relative">
                <div class="absolute top-6 bottom-6 left-[34px] w-0.5 bg-slate-200 dark:bg-slate-800"></div>

                <div class="space-y-1">
                    @foreach ($recentActivities as $t)
                        <div class="relative flex items-center gap-4 py-3 pl-4 pr-3 -mx-1 rounded-xl
                                    hover:bg-slate-50 dark:hover:bg-slate-800/40
                                    transition-colors duration-150 group/row">

                            <div class="relative z-10 w-9 h-9 rounded-full flex-shrink-0
                                        {{ $t->type === 'in' ? 'bg-green-100 dark:bg-green-500/20' : 'bg-red-100 dark:bg-red-500/20' }}
                                        flex items-center justify-center text-base
                                        ring-4 ring-white dark:ring-slate-900
                                        group-hover/row:scale-110 transition-transform duration-150">
                                {{ $t->type === 'in' ? '📥' : '📤' }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate
                                                  group-hover/row:text-blue-600 dark:group-hover/row:text-blue-400 transition-colors">
                                            {{ $t->product->name ?? __('Product deleted') }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                            {{ __('by') }}
                                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $t->user->name ?? __('Unknown') }}</span>
                                            · {{ $t->transaction_date->diffForHumans() }}
                                        </p>
                                    </div>

                                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0
                                                 {{ $t->type === 'in'
                                                    ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400'
                                                    : 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' }}">
                                        {{ $t->type === 'in' ? '+' : '-' }}{{ $t->quantity }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
        <h3 class="font-semibold text-slate-900 dark:text-white mb-4">{{ __('Quick Actions') }}</h3>
        <div class="space-y-2">

            <a href="{{ route('reports.stock') }}"
               class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-blue-50 dark:hover:bg-blue-500/10 border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-500/30 transition group">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition">
                    📊
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Stock Report') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Stock report per category') }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('reports.transactions', ['date_from' => $startDate->format('Y-m-d')]) }}"
               class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-green-50 dark:hover:bg-green-500/10 border border-slate-100 dark:border-slate-800 hover:border-green-200 dark:hover:border-green-500/30 transition group">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition">
                    🔄
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Transaction Report') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Incoming Goods') }} / {{ __('Outgoing Goods') }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-green-500 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('reports.activities') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-purple-50 dark:hover:bg-purple-500/10 border border-slate-100 dark:border-slate-800 hover:border-purple-200 dark:hover:border-purple-500/30 transition group">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition">
                        👤
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Activity Report') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Complete user activity log, data changes, and access history') }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-purple-500 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endif

            <a href="{{ route('reports.export', ['period' => $selectedPeriod]) }}"
               class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-amber-50 dark:hover:bg-amber-500/10 border border-slate-100 dark:border-slate-800 hover:border-amber-200 dark:hover:border-amber-500/30 transition group">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition">
                    📥
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Export Data') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Download CSV / Excel') }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-500 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

        </div>

        <div class="mt-5 p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-lg">
                    💡
                </div>
                <div>
                    <p class="text-xs font-semibold text-blue-800 dark:text-blue-300">{{ __('Tips') }}</p>
                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5 leading-relaxed">
                        {{ __('Use period filters to view data within a specific time range') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle  = document.getElementById('reportPeriodToggle');
    const menu    = document.getElementById('reportPeriodMenu');
    const chevron = document.getElementById('reportPeriodChevron');

    if (toggle && menu) {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        });
    }

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor   = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(148,163,184,0.15)';
    const textColor   = isDark ? '#94a3b8' : '#64748b';
    const fontFamily  = "'Inter', 'Segoe UI', system-ui, sans-serif";

    Chart.defaults.font.family = fontFamily;
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = textColor;

    // ============================================================
    // STOCK MOVEMENT CHART
    // ============================================================
    const movementEl = document.getElementById('stockMovementChart');
    if (movementEl) {
        const ctx = movementEl.getContext('2d');

        const greenGrad = ctx.createLinearGradient(0, 0, 0, 260);
        greenGrad.addColorStop(0, 'rgba(16,185,129,0.85)');
        greenGrad.addColorStop(1, 'rgba(16,185,129,0.25)');

        const redGrad = ctx.createLinearGradient(0, 0, 0, 260);
        redGrad.addColorStop(0, 'rgba(239,68,68,0.85)');
        redGrad.addColorStop(1, 'rgba(239,68,68,0.25)');

        const lineGrad = ctx.createLinearGradient(0, 0, 0, 260);
        lineGrad.addColorStop(0, 'rgba(59,130,246,0.25)');
        lineGrad.addColorStop(1, 'rgba(59,130,246,0.0)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: '{{ __('In') }}',
                        data: @json($chartInData),
                        backgroundColor: greenGrad,
                        borderColor: '#10b981',
                        borderWidth: 0,
                        borderRadius: 4,
                        barPercentage: 0.7,
                        categoryPercentage: 0.6,
                        order: 2,
                    },
                    {
                        label: '{{ __('Out') }}',
                        data: @json($chartOutData),
                        backgroundColor: redGrad,
                        borderColor: '#ef4444',
                        borderWidth: 0,
                        borderRadius: 4,
                        barPercentage: 0.7,
                        categoryPercentage: 0.6,
                        order: 3,
                    },
                    {
                        label: '{{ __('Net Flow') }}',
                        data: @json($chartInData).map((v, i) => v - @json($chartOutData)[i]),
                        type: 'line',
                        borderColor: '#3b82f6',
                        backgroundColor: lineGrad,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#3b82f6',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        order: 1,
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        titleColor: isDark ? '#f1f5f9' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 4,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: textColor,
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        border: { display: false, dash: [4, 4] },
                        ticks: { color: textColor, precision: 0 }
                    }
                }
            }
        });
    }

    // ============================================================
    // TOP MOVING PRODUCTS
    // ============================================================
    const topEl = document.getElementById('topProductsChart');
    if (topEl) {
        const labels = @json($topProducts->pluck('name'));
        const values = @json($topProducts->pluck('value'));

        const ctx = topEl.getContext('2d');
        const barGrad = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
        barGrad.addColorStop(0, '#3b82f6');
        barGrad.addColorStop(1, '#8b5cf6');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: barGrad,
                    borderRadius: 6,
                    barPercentage: 0.65,
                    categoryPercentage: 0.8,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        titleColor: isDark ? '#f1f5f9' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: (ctx) => ` ${ctx.parsed.x.toLocaleString()} {{ __('units') }}`
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        border: { display: false },
                        ticks: { color: textColor, precision: 0 }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: isDark ? '#e2e8f0' : '#334155',
                            font: { size: 11, weight: '500' },
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                return label.length > 22 ? label.substring(0, 22) + '…' : label;
                            }
                        }
                    }
                }
            }
        });
    }

    // ============================================================
    // STOCK HEALTH
    // ============================================================
    const healthEl = document.getElementById('stockHealthChart');
    if (healthEl) {
        const ctx = healthEl.getContext('2d');

        const greenGrad = ctx.createLinearGradient(0, 0, 0, 180);
        greenGrad.addColorStop(0, '#10b981');
        greenGrad.addColorStop(1, '#34d399');

        const orangeGrad = ctx.createLinearGradient(0, 0, 0, 180);
        orangeGrad.addColorStop(0, '#f59e0b');
        orangeGrad.addColorStop(1, '#fbbf24');

        const redGrad = ctx.createLinearGradient(0, 0, 0, 180);
        redGrad.addColorStop(0, '#ef4444');
        redGrad.addColorStop(1, '#f87171');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['{{ __('Safe') }}', '{{ __('Low') }}', '{{ __('Out') }}'],
                datasets: [{
                    data: [{{ $safeCount }}, {{ $lowCount }}, {{ $outCount }}],
                    backgroundColor: [greenGrad, orangeGrad, redGrad],
                    borderRadius: 8,
                    barPercentage: 0.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        titleColor: isDark ? '#f1f5f9' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: textColor, font: { size: 10, weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        border: { display: false },
                        ticks: { color: textColor, precision: 0, font: { size: 10 } }
                    }
                }
            }
        });
    }
});
</script>

@endsection
