@extends('layouts.app')
@section('title', __('Product'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Product') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Product Management') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage all products and warehouse inventory') }}</p>
    </div>
    <a href="{{ route('products.create', ['redirect_to' => url()->current()]) }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        {{ __('Add Product') }}
    </a>
</div>
@endsection

@section('content')

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
@php
    $allProducts = \App\Models\Product::with('category')->get();
    $inStock     = $allProducts->filter(fn($p) => $p->is_safe_stock)->count();
    $lowStock    = $allProducts->filter(fn($p) => $p->is_low_stock)->count();
    $outOfStock  = $allProducts->filter(fn($p) => $p->is_out_of_stock)->count();
    $totalValue  = $allProducts->sum(fn($p) => $p->stock * $p->purchase_price);
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Products --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                📦
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
            <p class="text-3xl font-bold">{{ $allProducts->count() }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('Active SKUs') }}</p>
        </div>
    </div>

    {{-- Safe Stock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">🟢</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Safe Stock') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $inStock }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Above minimum') }}</p>
    </div>

    {{-- Low Stock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">🟡</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Low Stock') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $lowStock }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Need restock') }}</p>
    </div>

    {{-- Out of Stock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-xl mb-3">🔴</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Out of Stock') }}</p>
        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $outOfStock }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Urgent restock') }}</p>
    </div>
</div>

{{-- ============================================================
     TOOLBAR + TABLE
============================================================ --}}
@php
    $productsBySupplier = \App\Models\Product::with(['category', 'supplier'])
        ->orderBy('name')
        ->get()
        ->groupBy(fn($p) => $p->supplier_id ?? 'none');

    $suppliersGrouped = \App\Models\Supplier::orderBy('name')->get()
        ->mapWithKeys(fn($s) => [$s->id => ['name' => $s->name, 'products' => $productsBySupplier->get($s->id, collect())]])
        ->filter(fn($g) => $g['products']->isNotEmpty())
        ->all();

    $noSupplier = $productsBySupplier->get('none', collect());
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
                <input type="text" id="productSearch" placeholder="{{ __('Search products by name or SKU...') }}"
                       class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <select id="categoryFilter"
                        class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach (\App\Models\Category::orderBy('name')->get() as $c)
                        <option value="{{ strtolower($c->name) }}">{{ $c->name }}</option>
                    @endforeach
                </select>

                <select id="statusFilter"
                        class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="in">🟢 {{ __('Safe Stock') }}</option>
                    <option value="low">🟡 {{ __('Low Stock') }}</option>
                    <option value="out">🔴 {{ __('Out of Stock') }}</option>
                </select>

                <div class="flex items-center gap-1 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                    <button type="button" onclick="toggleAllSuppliers(true)" title="{{ __('Expand all') }}"
                            class="px-2.5 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
                    <button type="button" onclick="toggleAllSuppliers(false)" title="{{ __('Collapse all') }}"
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
                    <th class="px-5 py-3 text-left font-medium">{{ __('Product') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Category') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Price') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Stock / Min') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Status') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

                @foreach ($suppliersGrouped as $supplierId => $group)
                    @php
                        $supplierProducts = $group['products'];
                        $supplierTotalStock = $supplierProducts->sum('stock');
                    @endphp

                    <tr class="supplier-group-row cursor-pointer transition"
                        data-supplier-id="{{ $supplierId }}"
                        onclick="toggleSupplierGroup({{ $supplierId }})">
                        <td colspan="6" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 border-y border-slate-200 dark:border-slate-700 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 transition">
                            <div class="flex items-center gap-3">
                                <span id="supplier-chevron-{{ $supplierId }}"
                                      class="w-6 h-6 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-transform duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $group['name'] }}</span>
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                    {{ $supplierProducts->count() }} {{ __('products') }}
                                </span>
                                <span class="ms-auto text-xs font-medium text-slate-500 dark:text-slate-400">
                                    {{ __('Total stock') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($supplierTotalStock) }} {{ __('unit') }}</span>
                                </span>
                            </div>
                        </td>
                    </tr>

                    @foreach ($supplierProducts as $p)
                        @php
                            $status      = $p->stock_status;
                            $statusColor = $p->stock_status_color;
                            $statusLabel = $p->stock_status_label;
                            $statusKey   = $status === 'safe' ? 'in' : $status;
                        @endphp
                        <tr class="product-row supplier-products-{{ $supplierId }} hidden hover:bg-slate-50 dark:hover:bg-slate-800/50 transition"
                            data-supplier-id="{{ $supplierId }}"
                            data-name="{{ strtolower($p->name . ' ' . $p->sku) }}"
                            data-category="{{ strtolower($p->category->name ?? '') }}"
                            data-status="{{ $statusKey }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 ps-6">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 shadow-sm">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $p->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $p->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400">
                                    {{ $p->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-semibold text-slate-900 dark:text-white">{{ format_currency($p->selling_price) }}</div>
                                <div class="text-[10px] text-slate-500">{{ __('Buy') }}: {{ format_currency($p->purchase_price) }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-baseline gap-1">
                                    <span class="text-base font-bold {{ $statusColor === 'red' ? 'text-red-600 dark:text-red-400' : ($statusColor === 'orange' ? 'text-orange-600 dark:text-orange-400' : 'text-slate-900 dark:text-white') }}">
                                        {{ $p->stock }}
                                    </span>
                                    <span class="text-xs text-slate-400">/ {{ $p->min_stock }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('products.show', $p) }}" title="{{ __('View') }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('products.print', $p) }}" target="_blank" title="{{ __('Print') }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-500/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('products.edit', $p) }}" title="{{ __('Edit') }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $p) }}" class="inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" title="{{ __('Delete') }}"
                                                onclick="openDeleteModal(this.closest('form'), '{{ $p->name }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                {{-- Products without supplier --}}
                @if ($noSupplier->isNotEmpty())
                    <tr class="supplier-group-row cursor-pointer transition" data-supplier-id="none" onclick="toggleSupplierGroup('none')">
                        <td colspan="6" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 border-y border-slate-200 dark:border-slate-700 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 transition">
                            <div class="flex items-center gap-3">
                                <span id="supplier-chevron-none" class="w-6 h-6 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-transform duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ __('No Supplier') }}</span>
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                    {{ $noSupplier->count() }} {{ __('products') }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    @foreach ($noSupplier as $p)
                        @php
                            $status      = $p->stock_status;
                            $statusColor = $p->stock_status_color;
                            $statusLabel = $p->stock_status_label;
                            $statusKey   = $status === 'safe' ? 'in' : $status;
                        @endphp
                        <tr class="product-row supplier-products-none hidden hover:bg-slate-50 dark:hover:bg-slate-800/50 transition"
                            data-supplier-id="none"
                            data-name="{{ strtolower($p->name . ' ' . $p->sku) }}"
                            data-category="{{ strtolower($p->category->name ?? '') }}"
                            data-status="{{ $statusKey }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 ps-6">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $p->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $p->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400">
                                    {{ $p->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-semibold text-slate-900 dark:text-white">{{ format_currency($p->selling_price) }}</div>
                                <div class="text-[10px] text-slate-500">{{ __('Buy') }}: {{ format_currency($p->purchase_price) }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-baseline gap-1">
                                    <span class="text-base font-bold {{ $statusColor === 'red' ? 'text-red-600 dark:text-red-400' : ($statusColor === 'orange' ? 'text-orange-600 dark:text-orange-400' : 'text-slate-900 dark:text-white') }}">
                                        {{ $p->stock }}
                                    </span>
                                    <span class="text-xs text-slate-400">/ {{ $p->min_stock }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('products.show', $p) }}" title="{{ __('View') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-purple-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('products.edit', $p) }}" title="{{ __('Edit') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-blue-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $p) }}" class="inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" title="{{ __('Delete') }}"
                                                onclick="openDeleteModal(this.closest('form'), '{{ $p->name }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif

                <tr id="noResultsRow" class="hidden">
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">🔍</div>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No products found') }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Try adjusting your search or filter to find what you are looking for.') }}</p>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

{{-- ============================================================
     DELETE CONFIRMATION MODAL
============================================================ --}}
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md overflow-hidden transform transition-all scale-95 opacity-0 duration-200" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Delete Confirmation') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1" id="deleteModalMessage">
                        {{ __('Are you sure you want to delete this item?') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-700">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                {{ __('Cancel') }}
            </button>
            <button type="button" id="confirmDeleteBtn" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                {{ __('Delete') }}
            </button>
        </div>
    </div>
</div>

<script>
function toggleSupplierGroup(supplierId) {
    const selector = '.supplier-products-' + supplierId;
    const rows     = document.querySelectorAll(selector);
    const chevron  = document.getElementById('supplier-chevron-' + supplierId);
    if (!rows.length) return;

    const isHidden = rows[0].classList.contains('hidden');
    rows.forEach(r => r.classList.toggle('hidden', !isHidden));
    if (chevron) chevron.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
}

function toggleAllSuppliers(expand) {
    document.querySelectorAll('.product-row').forEach(r => r.classList.toggle('hidden', !expand));
    document.querySelectorAll('[id^="supplier-chevron-"]').forEach(el => {
        el.style.transform = expand ? 'rotate(90deg)' : 'rotate(0deg)';
    });
}

let formToSubmit = null;

function openDeleteModal(form, itemName) {
    formToSubmit = form;
    document.getElementById('deleteModalMessage').textContent = `{{ __('Are you sure you want to delete') }} ${itemName}? {{ __('This action cannot be undone.') }}`;

    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    formToSubmit = null;
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (formToSubmit) {
        formToSubmit.submit();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const search         = document.getElementById('productSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter   = document.getElementById('statusFilter');
    const rows           = document.querySelectorAll('.product-row');
    const groupHeaders   = document.querySelectorAll('.supplier-group-row');
    const noResultsRow   = document.getElementById('noResultsRow');

    function apply() {
        const term     = (search?.value || '').toLowerCase().trim();
        const category = categoryFilter?.value || '';
        const status   = statusFilter?.value || '';

        let visibleCount = 0;
        const visibleSuppliers = new Set();

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const cat  = row.dataset.category || '';
            const stat = row.dataset.status || '';

            const matches = (!term || name.includes(term))
                         && (!category || cat === category)
                         && (!status || stat === status);

            if (matches) {
                visibleCount++;
                visibleSuppliers.add(row.dataset.supplierId);
                row.dataset.matchesFilter = '1';
                row.style.display = '';
            } else {
                row.dataset.matchesFilter = '0';
                row.style.display = 'none';
                row.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            noResultsRow.classList.remove('hidden');
        } else {
            noResultsRow.classList.add('hidden');
        }

        groupHeaders.forEach(header => {
            const supplierId = header.dataset.supplierId;
            if (!supplierId) return;
            if (visibleSuppliers.has(supplierId)) {
                header.style.display = '';
            } else {
                header.style.display = 'none';
            }
        });
    }

    search?.addEventListener('input', apply);
    categoryFilter?.addEventListener('change', apply);
    statusFilter?.addEventListener('change', apply);

    const firstRow = document.querySelector('.product-row');
    if (firstRow) {
        const firstSupplier = firstRow.dataset.supplierId;
        if (firstSupplier) {
            const key = isNaN(firstSupplier) ? firstSupplier : parseInt(firstSupplier);
            toggleSupplierGroup(key);
        }
    }
});
</script>

@endsection
