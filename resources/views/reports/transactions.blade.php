@extends('layouts.app')
@section('title', __('Transaction Report'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('reports.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Report') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('transactions') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Transaction Report') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Detail incoming and outgoing goods by period, product, or user') }}</p>
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
    $allTxns    = \App\Models\Transaction::all();
    $inCount    = $allTxns->where('type', 'in')->count();
    $outCount   = $allTxns->where('type', 'out')->count();
    $inQty      = (int) $allTxns->where('type', 'in')->sum('quantity');
    $outQty     = (int) $allTxns->where('type', 'out')->sum('quantity');
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Transactions --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                🔄
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Transactions') }}</p>
            <p class="text-3xl font-bold">{{ number_format($allTxns->count()) }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('All-time records') }}</p>
        </div>
    </div>

    {{-- Incoming Goods --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📥</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Incoming Goods') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($inCount) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ $inQty }} {{ __('units total') }}</p>
    </div>

    {{-- Outgoing Goods --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-xl mb-3">📤</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Outgoing Goods') }}</p>
        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($outCount) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ $outQty }} {{ __('units total') }}</p>
    </div>

    {{-- Active Days --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">📅</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Active Days') }}</p>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $allTxns->groupBy(fn($t) => $t->transaction_date->toDateString())->count() }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Days with activity') }}</p>
    </div>
</div>

{{-- ============================================================
     FILTERS
============================================================ --}}
<form method="GET" action="{{ route('reports.transactions') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 mb-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">
                {{ __('Type') }}
            </label>
            <select name="type"
                    class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                <option value="">{{ __('All') }} {{ __('Type') }}</option>
                <option value="in"  @selected(request('type') === 'in')>⬇️ {{ __('Incoming Goods') }}</option>
                <option value="out" @selected(request('type') === 'out')>⬆️ {{ __('Outgoing Goods') }}</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">
                {{ __('From Date') }}
            </label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
        </div>

        <div>
            <label class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">
                {{ __('To Date') }}
            </label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('Filter') }}
            </button>
            @if (request()->hasAny(['type', 'date_from', 'date_to']))
                <a href="{{ route('reports.transactions') }}"
                   class="inline-flex items-center justify-center w-10 h-10 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-xl transition"
                   title="{{ __('Reset') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</form>

{{-- ============================================================
     TRANSACTIONS — GROUPED BY DATE
============================================================ --}}
@php
    $transactions = \App\Models\Transaction::with(['product', 'supplier', 'user'])
        ->when(request('type'),      fn($q, $v) => $q->where('type', $v))
        ->when(request('date_from'), fn($q, $v) => $q->whereDate('transaction_date', '>=', $v))
        ->when(request('date_to'),   fn($q, $v) => $q->whereDate('transaction_date', '<=', $v))
        ->latest('transaction_date')
        ->latest('id')
        ->get();

    $transactionsByDate = $transactions->groupBy(fn($t) => $t->transaction_date->toDateString());
@endphp

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ __('Transaction Report') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                {{ $transactions->count() }} {{ __('transactions') }}
                @if (request()->hasAny(['type', 'date_from', 'date_to']))
                    · <span class="text-blue-600 dark:text-blue-400">{{ __('filtered') }}</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-1 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
            <button type="button" onclick="toggleAllDateGroups(true)"
                    title="{{ __('Expand all') }}"
                    class="px-2.5 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
            <button type="button" onclick="toggleAllDateGroups(false)"
                    title="{{ __('Collapse all') }}"
                    class="px-2.5 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="divide-y divide-slate-200 dark:divide-slate-800">

        @forelse ($transactionsByDate as $date => $txns)
            @php
                $dateObj     = \Carbon\Carbon::parse($date);
                $totalIn     = $txns->where('type', 'in')->sum('quantity');
                $totalOut    = $txns->where('type', 'out')->sum('quantity');
                $dateKey     = str_replace('-', '', $date);
                $isToday     = $dateObj->isToday();
                $isYesterday = $dateObj->isYesterday();
                $dateLabel   = $isToday ? __('Today') : ($isYesterday ? __('Yesterday') : $dateObj->translatedFormat('d M Y'));
            @endphp

            <div class="txn-date-group">

                {{-- Date header --}}
                <button type="button"
                        onclick="toggleTxnDate('{{ $dateKey }}')"
                        class="w-full flex items-center gap-4 px-5 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition text-left">

                    <span id="txn-date-chevron-{{ $dateKey }}"
                          class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 transition-transform duration-200">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $dateLabel }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $dateObj->translatedFormat('d M Y') }}</span>
                            @if ($isToday)
                                <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                    <span class="w-1 h-1 rounded-full bg-blue-500 animate-pulse"></span>
                                    {{ __('Live') }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $txns->count() }} {{ __('transactions') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0">
                        @if ($totalIn > 0)
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400">
                                ⬇️ +{{ $totalIn }}
                            </span>
                        @endif
                        @if ($totalOut > 0)
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400">
                                ⬆️ -{{ $totalOut }}
                            </span>
                        @endif
                    </div>
                </button>

                {{-- Transactions table --}}
                <div id="txn-date-content-{{ $dateKey }}" class="hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-2 text-left font-medium">{{ __('ID') }}</th>
                                <th class="px-5 py-2 text-left font-medium">{{ __('Type') }}</th>
                                <th class="px-5 py-2 text-left font-medium">{{ __('Product') }}</th>
                                <th class="px-5 py-2 text-left font-medium">{{ __('Supplier') }}</th>
                                <th class="px-5 py-2 text-right font-medium">{{ __('Quantity') }}</th>
                                <th class="px-5 py-2 text-left font-medium">{{ __('User') }}</th>
                                <th class="px-5 py-2 text-left font-medium">{{ __('Notes') }}</th>
                                <th class="px-5 py-2 text-center font-medium w-16">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @foreach ($txns as $t)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group">
                                    <td class="px-5 py-3 font-mono text-xs text-slate-500 ps-12">
                                        #{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-5 py-3">
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
                                    <td class="px-5 py-3 text-slate-900 dark:text-white font-medium">{{ $t->product->name ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs">{{ $t->supplier->name ?? '—' }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <span class="font-semibold {{ $t->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $t->type === 'in' ? '+' : '-' }}{{ $t->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 dark:text-slate-400">{{ $t->user->name ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs max-w-xs truncate">{{ $t->note ?: '—' }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <a href="{{ route('reports.transactions.print', $t) }}" target="_blank"
                                           title="{{ __('Print this transaction') }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-blue-400 transition group/print">
                                            <svg class="w-4 h-4 group-hover/print:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                        📋
                    </div>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No transactions yet') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        @if (request()->hasAny(['type', 'date_from', 'date_to']))
                            {{ __('No transactions match your filters') }}
                        @else
                            {{ __('Transactions will appear here after activity') }}
                        @endif
                    </p>
                    @if (request()->hasAny(['type', 'date_from', 'date_to']))
                        <a href="{{ route('reports.transactions') }}"
                           class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                            {{ __('Reset Filters') }}
                        </a>
                    @endif
                </div>
            </div>
        @endforelse

    </div>
</div>

{{-- ============================================================
     INLINE SCRIPTS
============================================================ --}}
<script>
function toggleTxnDate(dateKey) {
    const content = document.getElementById('txn-date-content-' + dateKey);
    const chevron = document.getElementById('txn-date-chevron-' + dateKey);
    if (!content) return;

    content.classList.toggle('hidden');
    if (chevron) chevron.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(90deg)';
}

function toggleAllDateGroups(expand) {
    document.querySelectorAll('[id^="txn-date-content-"]').forEach(el => {
        el.classList.toggle('hidden', !expand);
    });
    document.querySelectorAll('[id^="txn-date-chevron-"]').forEach(el => {
        el.style.transform = expand ? 'rotate(90deg)' : 'rotate(0deg)';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const firstGroup = document.querySelector('[id^="txn-date-content-"]');
    if (firstGroup) {
        const dateKey = firstGroup.id.replace('txn-date-content-', '');
        toggleTxnDate(dateKey);
    }
});
</script>

@endsection