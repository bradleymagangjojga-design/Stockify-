@extends('layouts.app')
@section('title', __('Stock Report'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('reports.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Report') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Stock') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Stock Report') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Detail stock per category, inventory value, and low stock products') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            {{ __('Print') }}
        </button>
        <a href="{{ route('reports.export', ['period' => 30]) }}"
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

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
@php
    $allProducts = \App\Models\Product::with('category')->get();
    $totalStock  = $allProducts->sum('stock');
    $totalValue  = $allProducts->sum(fn($p) => $p->stock * $p->purchase_price);
    $lowCount    = $allProducts->filter(fn($p) => $p->is_low_stock)->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Products --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                📊
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
            <p class="text-3xl font-bold">{{ $allProducts->count() }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('Active SKUs') }}</p>
        </div>
    </div>

    {{-- Total Units --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📦</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Units') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalStock, 0, ',', '.') }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Across all SKUs') }}</p>
    </div>

    {{-- Stock Value --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">💰</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Stock Value') }}</p>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ format_currency_compact($totalValue) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Total inventory') }}</p>
    </div>

    {{-- Need Restock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">⚠️</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Need Restock') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $lowCount }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Below minimum') }}</p>
    </div>
</div>

{{-- ============================================================
     STOCK REPORT — GROUPED BY CATEGORY
============================================================ --}}
@php
    $productsByCategory = \App\Models\Product::with(['category', 'supplier'])
        ->orderBy('name')
        ->get()
        ->groupBy(fn($p) => $p->category_id ?? 'none');

    $categoriesGrouped = \App\Models\Category::orderBy('name')->get()
        ->mapWithKeys(fn($c) => [$c->id => ['name' => $c->name, 'products' => $productsByCategory->get($c->id, collect())]])
        ->filter(fn($g) => $g['products']->isNotEmpty())
        ->all();

    $noCategory = $productsByCategory->get('none', collect());
@endphp

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    {{-- Toolbar --}}
    <div class="p-4 border-b border-slate-200 dark:border-slate-800">
        <div class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="relative flex-1 max-w-md w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="reportStockSearch" placeholder="{{ __('Search products by name or SKU...') }}"
                       class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <select id="reportStockStatus"
                        class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="in">🟢 {{ __('Safe Stock') }}</option>
                    <option value="low">🟡 {{ __('Low Stock') }}</option>
                    <option value="out">🔴 {{ __('Out of Stock') }}</option>
                </select>

                <div class="flex items-center gap-1 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                    <button type="button" onclick="toggleAllReportGroups(true)"
                            title="{{ __('Expand all') }}"
                            class="px-2.5 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
                    <button type="button" onclick="toggleAllReportGroups(false)"
                            title="{{ __('Collapse all') }}"
                            class="px-2.5 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-medium">SKU</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Product Name') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Supplier') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Stock') }}</th>
                    <th class="px-5 py-3 text-center font-medium">Min. {{ __('Stock') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Purchase Price') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Value') }}</th>
                    <th class="px-5 py-3 text-center font-medium w-16">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

                @foreach ($categoriesGrouped as $categoryId => $group)
                    @php
                        $catProducts = $group['products'];
                        $catStock    = $catProducts->sum('stock');
                        $catValue    = $catProducts->sum(fn($p) => $p->stock * $p->purchase_price);
                        $catLow      = $catProducts->filter(fn($p) => $p->is_low_stock)->count();
                    @endphp

                    {{-- Category Group Header --}}
                    <tr class="report-group-row cursor-pointer transition"
                        data-category-id="{{ $categoryId }}"
                        onclick="toggleCategoryGroup({{ $categoryId }})">
                        <td colspan="8"
                            class="px-5 py-3 bg-slate-100 dark:bg-slate-800 border-y border-slate-200 dark:border-slate-700 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 transition">
                            <div class="flex items-center gap-3">
                                <span id="category-chevron-{{ $categoryId }}"
                                      class="w-6 h-6 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-transform duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $group['name'] }}</span>
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                    {{ $catProducts->count() }} {{ __('products') }}
                                </span>
                                @if ($catLow > 0)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-0.5 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        {{ $catLow }} {{ __('Low Stock') }}
                                    </span>
                                @endif
                                <span class="ms-auto flex items-center gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                                    <span>{{ __('Stock') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ $catStock }}</span></span>
                                    <span>{{ __('Value') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ format_currency($catValue) }}</span></span>
                                </span>
                            </div>
                        </td>
                    </tr>

                    {{-- Products under this category --}}
                    @foreach ($catProducts as $p)
                        @php
                            $status      = $p->stock_status;
                            $statusColor = $p->stock_status_color;
                            $statusKey   = $status === 'safe' ? 'in' : $status;
                        @endphp
                        <tr class="report-stock-row report-category-{{ $categoryId }} hidden hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group"
                            data-category-id="{{ $categoryId }}"
                            data-name="{{ strtolower($p->name . ' ' . $p->sku) }}"
                            data-status="{{ $statusKey }}">
                            <td class="px-5 py-4 ps-12">
                                <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-1 rounded font-mono">{{ $p->sku }}</code>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $p->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-400 text-sm">{{ $p->supplier->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold {{ $statusColor === 'red' ? 'text-red-600 dark:text-red-400' : ($statusColor === 'orange' ? 'text-orange-600 dark:text-orange-400' : 'text-slate-900 dark:text-white') }}">
                                    {{ $p->stock }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center text-slate-500 dark:text-slate-400">{{ $p->min_stock }}</td>
                            <td class="px-5 py-4 text-right text-slate-700 dark:text-slate-300">
                                {{ format_currency($p->purchase_price) }}
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-900 dark:text-white">
                                {{ format_currency($p->stock * $p->purchase_price) }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('reports.stock.print', $p) }}" target="_blank"
                                   title="{{ __('Print this product report') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-blue-400 transition group/print">
                                    <svg class="w-4 h-4 group-hover/print:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                {{-- Products WITHOUT category --}}
                @if ($noCategory->isNotEmpty())
                    <tr class="report-group-row cursor-pointer transition"
                        data-category-id="none"
                        onclick="toggleCategoryGroup('none')">
                        <td colspan="8"
                            class="px-5 py-3 bg-slate-100 dark:bg-slate-800 border-y border-slate-200 dark:border-slate-700 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 transition">
                            <div class="flex items-center gap-3">
                                <span id="category-chevron-none"
                                      class="w-6 h-6 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-transform duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ __('No Category') }}</span>
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                    {{ $noCategory->count() }} {{ __('products') }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    @foreach ($noCategory as $p)
                        @php
                            $status      = $p->stock_status;
                            $statusColor = $p->stock_status_color;
                            $statusKey   = $status === 'safe' ? 'in' : $status;
                        @endphp
                        <tr class="report-stock-row report-category-none hidden hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group"
                            data-category-id="none"
                            data-name="{{ strtolower($p->name . ' ' . $p->sku) }}"
                            data-status="{{ $statusKey }}">
                            <td class="px-5 py-4 ps-12">
                                <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-1 rounded font-mono">{{ $p->sku }}</code>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $p->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-400 text-sm">{{ $p->supplier->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-900 dark:text-white">{{ $p->stock }}</td>
                            <td class="px-5 py-4 text-center text-slate-500 dark:text-slate-400">{{ $p->min_stock }}</td>
                            <td class="px-5 py-4 text-right text-slate-700 dark:text-slate-300">{{ format_currency($p->purchase_price) }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-900 dark:text-white">{{ format_currency($p->stock * $p->purchase_price) }}</td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('reports.stock.print', $p) }}" target="_blank"
                                   title="{{ __('Print this product report') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-blue-400 transition group/print">
                                    <svg class="w-4 h-4 group-hover/print:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
            <tfoot class="bg-slate-50 dark:bg-slate-800/70 border-t-2 border-slate-200 dark:border-slate-700">
                <tr>
                    <td colspan="3" class="px-5 py-4 text-right font-semibold text-slate-700 dark:text-slate-300">{{ strtoupper(__('Total')) }}</td>
                    <td class="px-5 py-4 text-center font-bold text-slate-900 dark:text-white">{{ number_format($totalStock, 0, ',', '.') }}</td>
                    <td class="px-5 py-4"></td>
                    <td class="px-5 py-4"></td>
                    <td class="px-5 py-4 text-right font-bold text-slate-900 dark:text-white">{{ format_currency($totalValue) }}</td>
                    <td class="px-5 py-4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- ============================================================
     INLINE SCRIPTS
============================================================ --}}
<script>
function toggleCategoryGroup(categoryId) {
    const selector = '.report-category-' + categoryId;
    const rows     = document.querySelectorAll(selector);
    const chevron  = document.getElementById('category-chevron-' + categoryId);
    if (!rows.length) return;

    const isHidden = rows[0].classList.contains('hidden');
    rows.forEach(r => r.classList.toggle('hidden', !isHidden));
    if (chevron) chevron.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
}

function toggleAllReportGroups(expand) {
    document.querySelectorAll('.report-stock-row').forEach(r => r.classList.toggle('hidden', !expand));
    document.querySelectorAll('[id^="category-chevron-"]').forEach(el => {
        el.style.transform = expand ? 'rotate(90deg)' : 'rotate(0deg)';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const search       = document.getElementById('reportStockSearch');
    const statusFilter = document.getElementById('reportStockStatus');
    const rows         = document.querySelectorAll('.report-stock-row');
    const groupHeaders = document.querySelectorAll('.report-group-row');

    function apply() {
        const term   = (search?.value || '').toLowerCase().trim();
        const status = statusFilter?.value || '';

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const stat = row.dataset.status || '';
            const show = (!term || name.includes(term))
                      && (!status || stat === status);
            row.dataset.matchesFilter = show ? '1' : '0';
            if (show) {
                row.style.display = '';
                if (term || status) row.classList.remove('hidden');
            } else {
                row.style.display = 'none';
            }
        });

        groupHeaders.forEach(header => {
            const categoryId = header.dataset.categoryId;
            if (!categoryId) return;
            const visibleProducts = document.querySelectorAll(
                `.report-stock-row[data-category-id="${categoryId}"][data-matches-filter="1"]`
            );
            header.style.display = visibleProducts.length > 0 ? '' : 'none';
        });
    }

    search?.addEventListener('input', apply);
    statusFilter?.addEventListener('change', apply);

    const firstRow = document.querySelector('.report-stock-row');
    if (firstRow) {
        const firstCat = firstRow.dataset.categoryId;
        if (firstCat) toggleCategoryGroup(isNaN(firstCat) ? firstCat : parseInt(firstCat));
    }
});
</script>

@endsection