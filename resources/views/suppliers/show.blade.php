@extends('layouts.app')
@section('title', $supplier->name)

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('suppliers.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Supplier') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Detail') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Supplier Detail') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Complete information and products from this supplier') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('suppliers.print', $supplier) }}" target="_blank"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            {{ __('Print') }}
        </a>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('suppliers.edit', $supplier) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl transition shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('Edit') }}
            </a>
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- ============================================================
     HERO CARD
============================================================ --}}
<div class="relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 mb-6 overflow-hidden">
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-3xl"></div>

    <div class="relative flex flex-col md:flex-row gap-6">
        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-4xl flex-shrink-0 shadow-lg shadow-blue-500/20 ring-4 ring-white dark:ring-slate-900">
            {{ strtoupper(substr($supplier->name, 0, 2)) }}
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('Supplier') }} #{{ $supplier->id }}
                </span>
                @if ($supplier->products_count > 0)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        {{ __('Active') }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        {{ __('No Products Yet') }}
                    </span>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $supplier->name }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 mt-3 text-sm">
                @if ($supplier->contact)
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $supplier->contact }}
                    </div>
                @endif
                @if ($supplier->email)
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $supplier->email }}
                    </div>
                @endif
                @if ($supplier->address)
                    <div class="flex items-start gap-2 text-slate-600 dark:text-slate-400 sm:col-span-2">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $supplier->address }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     STATS CARDS
============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Products --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-xl mb-3">📦</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['total_products'] }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Supplied by this partner') }}</p>
    </div>

    {{-- Total Stock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📊</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Stock') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['total_stock'], 0, ',', '.') }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Units available') }}</p>
    </div>

    {{-- Stock Value --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">💰</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Stock Value') }}</p>
        <p class="text-xl font-bold text-slate-900 dark:text-white">{{ format_currency_compact($stats['total_value']) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Total inventory') }}</p>
    </div>

    {{-- Need Restock --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">⚠️</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Need Restock') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['low_stock'] }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Below minimum') }}</p>
    </div>
</div>

{{-- ============================================================
     PRODUCTS TABLE
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ __('Products from this Supplier') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $products->count() }} {{ __('products found') }}</p>
        </div>
        @if (in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
            <a href="{{ route('products.create', ['supplier_id' => $supplier->id]) }}"
               class="inline-flex items-center gap-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3.5 py-2 rounded-lg transition shadow-lg shadow-blue-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Add Product') }}
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-medium">SKU</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Product') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Category') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Stock / Min') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Status') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Price') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($products as $p)
                    @php
                        $statusColor = $p->stock_status_color;
                        $statusLabel = $p->stock_status_label;
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group">
                        <td class="px-5 py-4">
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
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400">
                                {{ $p->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-baseline gap-1">
                                <span class="font-bold {{ $statusColor === 'red' ? 'text-red-600 dark:text-red-400' : ($statusColor === 'orange' ? 'text-orange-600 dark:text-orange-400' : 'text-slate-900 dark:text-white') }}">
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
                        <td class="px-5 py-4 text-right font-semibold text-slate-900 dark:text-white">
                            {{ format_currency($p->selling_price) }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex items-center gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
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
                                @if (in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                                    <a href="{{ route('products.edit', $p) }}" title="{{ __('Edit') }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                                    📦
                                </div>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No products yet') }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('This supplier has no products yet') }}</p>
                                @if (in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                                    <a href="{{ route('products.create', ['supplier_id' => $supplier->id]) }}"
                                       class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ __('Add First Product') }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection