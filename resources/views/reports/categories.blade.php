@extends('layouts.app')
@section('title', __('All Categories'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('reports.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Report') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('All Categories') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('All Categories') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Stock and value breakdown for every category') }}</p>
    </div>
    <a href="{{ route('reports.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back to Reports') }}
    </a>
</div>
@endsection

@section('content')

{{-- ============================================================
     CATEGORIES LIST
============================================================ --}}
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
                <input type="text" id="categorySearch" placeholder="{{ __('Search categories...') }}"
                       class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
            </div>

            <select id="categorySort"
                    class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                <option value="stock">{{ __('Sort by stock') }}</option>
                <option value="value">{{ __('Sort by value') }}</option>
                <option value="name">{{ __('Sort by name') }}</option>
                <option value="products">{{ __('Sort by product count') }}</option>
            </select>
        </div>
    </div>

    {{-- Category rows --}}
    <div class="divide-y divide-slate-200 dark:divide-slate-800">

        @forelse ($categories as $index => $cat)
            @php
                $percent = $totalStock > 0 ? ($cat['stock'] / $totalStock) * 100 : 0;
                $colors = [
                    ['bar' => 'from-blue-500 to-indigo-600',   'bg' => 'bg-blue-500',    'text' => 'text-blue-600 dark:text-blue-400',    'light' => 'bg-blue-100 dark:bg-blue-500/20'],
                    ['bar' => 'from-emerald-500 to-green-600', 'bg' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400','light' => 'bg-emerald-100 dark:bg-emerald-500/20'],
                    ['bar' => 'from-amber-500 to-orange-600',  'bg' => 'bg-amber-500',   'text' => 'text-amber-600 dark:text-amber-400',  'light' => 'bg-amber-100 dark:bg-amber-500/20'],
                    ['bar' => 'from-violet-500 to-purple-600', 'bg' => 'bg-violet-500',  'text' => 'text-violet-600 dark:text-violet-400','light' => 'bg-violet-100 dark:bg-violet-500/20'],
                    ['bar' => 'from-rose-500 to-pink-600',     'bg' => 'bg-rose-500',    'text' => 'text-rose-600 dark:text-rose-400',    'light' => 'bg-rose-100 dark:bg-rose-500/20'],
                    ['bar' => 'from-cyan-500 to-sky-600',      'bg' => 'bg-cyan-500',    'text' => 'text-cyan-600 dark:text-cyan-400',    'light' => 'bg-cyan-100 dark:bg-cyan-500/20'],
                ];
                $c = $colors[$index % 6];
            @endphp

            <div class="category-row p-5 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition group"
                 data-name="{{ strtolower($cat['name']) }}"
                 data-stock="{{ $cat['stock'] }}"
                 data-value="{{ $cat['value'] }}"
                 data-products="{{ $cat['products_count'] }}">

                <div class="flex items-start gap-4">

                    {{-- Rank badge --}}
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="w-9 h-9 rounded-xl {{ $c['light'] }} {{ $c['text'] }} flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                    </div>

                    {{-- Info + Progress Bar --}}
                    <div class="flex-1 min-w-0">

                        {{-- Top row: name + numbers --}}
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 dark:text-white truncate">{{ $cat['name'] }}</h3>
                                @if ($cat['description'])
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">{{ $cat['description'] }}</p>
                                @endif
                            </div>

                            <div class="text-right flex-shrink-0">
                                <p class="text-lg font-bold text-slate-900 dark:text-white">
                                    {{ number_format($cat['stock']) }}
                                    <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ __('units') }}</span>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ number_format($percent, 1) }}% {{ __('of total') }}
                                </p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden mb-3">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $c['bar'] }} transition-all duration-700 ease-out"
                                 style="width: {{ max(2, $percent) }}%"></div>
                        </div>

                        {{-- Bottom meta row --}}
                        <div class="flex flex-wrap items-center gap-3 text-xs">
                            <span class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $cat['products_count'] }}</span>
                                {{ __('products') }}
                            </span>

                            <span class="text-slate-300 dark:text-slate-600">·</span>

                            <span class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Value') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ format_currency($cat['value']) }}</span>
                            </span>

                            @if ($cat['low_stock'] > 0)
                                <span class="text-slate-300 dark:text-slate-600">·</span>
                                <span class="inline-flex items-center gap-1 text-orange-600 dark:text-orange-400 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                    {{ $cat['low_stock'] }} {{ __('low stock') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                        🏷️
                    </div>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No categories yet') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Add categories to see them here') }}</p>
                </div>
            </div>
        @endforelse

        {{-- No search results --}}
        <div id="noResults" class="hidden p-16 text-center">
            <div class="inline-flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">
                    🔍
                </div>
                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No categories found') }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Try adjusting your search') }}</p>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const search   = document.getElementById('categorySearch');
    const sortBy   = document.getElementById('categorySort');
    const rows     = Array.from(document.querySelectorAll('.category-row'));
    const container = rows.length ? rows[0].parentElement : null;
    const noResults = document.getElementById('noResults');

    function applySearch() {
        const term = (search?.value || '').toLowerCase().trim();
        let visible = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const show = (!term || name.includes(term));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResults) {
            if (visible === 0 && rows.length > 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }

    function applySort() {
        if (!container) return;
        const val = sortBy?.value || 'stock';

        const sorted = [...rows].sort((a, b) => {
            if (val === 'name')     return (a.dataset.name || '').localeCompare(b.dataset.name || '');
            if (val === 'products') return parseInt(b.dataset.products) - parseInt(a.dataset.products);
            if (val === 'value')    return parseFloat(b.dataset.value) - parseFloat(a.dataset.value);
            return parseInt(b.dataset.stock) - parseInt(a.dataset.stock);
        });

        sorted.forEach(row => container.appendChild(row));
    }

    search?.addEventListener('input', applySearch);
    sortBy?.addEventListener('change', applySort);
});
</script>

@endsection
