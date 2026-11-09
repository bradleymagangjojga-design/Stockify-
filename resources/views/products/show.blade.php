@extends('layouts.app')
@section('title', $product->name)

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('products.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Product') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Detail') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Product Details') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Complete information about the product') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('products.print', $product) }}" target="_blank"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            {{ __('Print') }}
        </a>
        @if (in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
            <a href="{{ route('products.edit', $product) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition shadow-lg shadow-blue-500/20">
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
            {{ strtoupper(substr($product->name, 0, 1)) }}
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-start gap-2 mb-2">
                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                    {{ $product->category->name ?? __('No Category') }}
                </span>
                @php
                    $statusColor = $product->stock_status_color;
                    $statusLabel = $product->stock_status_label;
                @endphp
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500 animate-pulse"></span>
                    {{ $statusLabel }}
                </span>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $product->name }}</h2>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-slate-500 dark:text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    SKU: <code class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-700 dark:text-slate-300 font-mono text-xs">{{ $product->sku }}</code>
                </span>
                @if ($product->supplier)
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $product->supplier->name }}
                    </span>
                @endif
            </div>

            @if ($product->description)
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-3 leading-relaxed">{{ $product->description }}</p>
            @endif
        </div>
    </div>
</div>

{{-- ============================================================
     STATS
============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-xl">📦</div>
        </div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Current Stock') }}</p>
        <p class="text-2xl font-bold {{ $product->is_low_stock ? 'text-red-600 dark:text-red-400' : 'text-slate-900 dark:text-white' }}">
            {{ $product->stock }}
        </p>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Min. {{ $product->min_stock }} {{ __('unit') }}</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl">📥</div>
        </div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Entry') }}</p>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['total_in'] }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('all-time units') }}</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-xl">📤</div>
        </div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Out') }}</p>
        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['total_out'] }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('all-time units') }}</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl">💰</div>
        </div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Stock Value') }}</p>
        <p class="text-xl font-bold text-slate-900 dark:text-white">{{ format_currency($stats['stock_value']) }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('stock × purchase price') }}</p>
    </div>
</div>

{{-- ============================================================
     DETAIL GRID
============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Price Card --}}
    <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
        <h3 class="font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ __('Price') }}
        </h3>
        <dl class="space-y-3">
            <div class="flex justify-between items-baseline">
                <dt class="text-sm text-slate-500 dark:text-slate-400">{{ __('Purchase Price') }}</dt>
                <dd class="font-semibold text-slate-900 dark:text-white">{{ format_currency($product->purchase_price) }}</dd>
            </div>
            <div class="flex justify-between items-baseline">
                <dt class="text-sm text-slate-500 dark:text-slate-400">{{ __('Selling Price') }}</dt>
                <dd class="font-semibold text-blue-600 dark:text-blue-400">{{ format_currency($product->selling_price) }}</dd>
            </div>
            <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-between items-baseline">
                <dt class="text-sm text-slate-500 dark:text-slate-400">{{ __('Margin') }}</dt>
                <dd class="font-bold text-green-600 dark:text-green-400">
                    {{ format_currency($stats['profit_margin']) }}
                </dd>
            </div>
            @if ($product->purchase_price > 0)
                <div class="flex justify-between items-baseline">
                    <dt class="text-sm text-slate-500 dark:text-slate-400">{{ __('Profit %') }}</dt>
                    <dd class="font-semibold text-slate-900 dark:text-white">
                        {{ number_format(($stats['profit_margin'] / $product->purchase_price) * 100, 1) }}%
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- Information Card --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
        <h3 class="font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ __('Information') }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SKU</dt>
                <dd class="font-mono text-sm text-slate-900 dark:text-white mt-1">{{ $product->sku }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Category') }}</dt>
                <dd class="text-sm text-slate-900 dark:text-white mt-1">{{ $product->category->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Supplier') }}</dt>
                <dd class="text-sm text-slate-900 dark:text-white mt-1">{{ $product->supplier->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Created') }}</dt>
                <dd class="text-sm text-slate-900 dark:text-white mt-1">{{ $product->created_at->format('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Last Modified') }}</dt>
                <dd class="text-sm text-slate-900 dark:text-white mt-1">{{ $product->updated_at->format('d M Y, H:i') }}</dd>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     RECENT TRANSACTIONS
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Recent Transactions') }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('10 latest transactions for this product') }}</p>
        </div>
        <a href="{{ route('stock.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">{{ __('View all') }} →</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-medium">ID</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Type') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Quantity') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Date') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('User') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Notes') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($transactions as $t)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-5 py-4 font-mono text-xs text-slate-500">#{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-4">
                            @if ($t->type === 'in')
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    {{ __('In') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    {{ __('Out') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right font-semibold {{ $t->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $t->type === 'in' ? '+' : '-' }}{{ $t->quantity }}
                        </td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $t->transaction_date->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $t->user->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400 text-xs max-w-xs truncate">{{ $t->note ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl mb-2">📋</div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ __('No transactions yet') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Transactions for this product will appear here') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
