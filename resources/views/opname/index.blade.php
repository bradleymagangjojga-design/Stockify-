@extends('layouts.app')
@section('title', __('Stock Opname'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Stock Opname') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Stock Opname') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Verify and reconcile physical stock with the system') }}
        </p>
    </div>
    <button type="button" onclick="document.getElementById('opnameForm').scrollIntoView({behavior:'smooth'})"
            class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        {{ __('Create New Opname') }}
    </button>
</div>
@endsection

@section('content')

@php
    $totalOpnames = $opnames->total();
    $thisMonth    = $opnames->where('opname_date', '>=', now()->startOfMonth())->count();
    $withDiff     = $opnames->where('difference', '!=', 0)->count();
    $perfectCount = $opnames->where('difference', 0)->count();
@endphp

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Opname --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                📋
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Opname') }}</p>
            <p class="text-3xl font-bold">{{ $totalOpnames }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('All-time records') }}</p>
        </div>
    </div>

    {{-- This Month --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">📅</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('This Month') }}</p>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $thisMonth }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ now()->translatedFormat('F Y') }}</p>
    </div>

    {{-- Match System --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">✅</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Match System') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $perfectCount }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('No difference found') }}</p>
    </div>

    {{-- Has Difference --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">⚠️</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Has Difference') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $withDiff }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Needs attention') }}</p>
    </div>
</div>

{{-- ============================================================
     CREATE NEW OPNAME FORM
============================================================ --}}
<div id="opnameForm" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-6 shadow-sm">

    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-blue-500/20">
            📋
        </div>
        <div>
            <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Create New Opname') }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                {{ __('Record physical stock count to compare with system data') }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('opname.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="product_id" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    {{ __('Product') }} <span class="text-red-500">*</span>
                </label>
                <select id="product_id" name="product_id" required
                        class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition
                               {{ $errors->has('product_id') ? 'border-red-500 dark:border-red-500' : '' }}">
                    <option value="">{{ __('— Select Product —') }}</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>
                            {{ $p->name }} — {{ $p->stock }} {{ __('unit') }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="physical_stock" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    {{ __('Physical Stock') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" id="physical_stock" name="physical_stock"
                           value="{{ old('physical_stock') }}"
                           min="0" required placeholder="0"
                           class="w-full px-4 py-3 pr-16 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition
                                  {{ $errors->has('physical_stock') ? 'border-red-500 dark:border-red-500' : '' }}">
                    <span class="absolute inset-y-0 right-4 flex items-center text-xs text-slate-500 dark:text-slate-400">
                        {{ __('unit') }}
                    </span>
                </div>
                @error('physical_stock')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="opname_date" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    {{ __('Date') }} <span class="text-red-500">*</span>
                </label>
                <input type="date" id="opname_date" name="opname_date"
                       value="{{ old('opname_date', now()->toDateString()) }}" required
                       class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition
                              {{ $errors->has('opname_date') ? 'border-red-500 dark:border-red-500' : '' }}">
                @error('opname_date')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-4">
            <label for="note" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                {{ __('Notes') }}
            </label>
            <textarea id="note" name="note" rows="2"
                      placeholder="{{ __('Example: 2 damaged units, over-count, etc.') }}"
                      class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition resize-none">{{ old('note') }}</textarea>
        </div>

        <div class="mt-5 flex items-start gap-3 p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30">
            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-lg">
                ℹ️
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">{{ __('Information') }}</p>
                <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5 leading-relaxed">
                    {{ __('After saving, the product stock will be adjusted to match the physical count and recorded in the transaction history.') }}
                </p>
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Opname') }}
            </button>
        </div>
    </form>
</div>

{{-- ============================================================
     OPNAME HISTORY
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    {{-- Toolbar --}}
    <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row gap-3 items-center">
        <div class="flex-1">
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ __('Opname History') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Click a row to see details') }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <select id="opnameStatusFilter"
                    class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                <option value="">{{ __('All Status') }}</option>
                <option value="match">✓ {{ __('Match') }}</option>
                <option value="over">↑ {{ __('Over') }}</option>
                <option value="under">↓ {{ __('Under') }}</option>
            </select>

            <input type="date" id="opnameDateFilter"
                   class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
        </div>
    </div>

    {{-- Opname List --}}
    <div class="divide-y divide-slate-200 dark:divide-slate-800">
        @forelse ($opnames as $o)
            @php
                $diffClass = $o->difference < 0 ? 'under' : ($o->difference > 0 ? 'over' : 'match');
                $diffColor = $o->difference < 0 ? 'red' : ($o->difference > 0 ? 'green' : 'slate');
                $diffIcon  = $o->difference < 0 ? '↓' : ($o->difference > 0 ? '↑' : '✓');
                $diffLabel = $o->difference < 0 ? __('Under') : ($o->difference > 0 ? __('Over') : __('Match'));
            @endphp

            <div class="opname-row group" data-status="{{ $diffClass }}" data-date="{{ $o->opname_date->toDateString() }}">

                {{-- Header Row --}}
                <div class="flex items-center justify-between gap-4 p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <button type="button" onclick="toggleOpname({{ $o->id }})"
                            class="flex items-center gap-4 flex-1 min-w-0 text-left">

                        <div id="chevron-{{ $o->id }}"
                             class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 transition">
                            <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-mono text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">
                                    #SO-{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="font-semibold text-slate-900 dark:text-white truncate">{{ $o->product->name ?? __('Product deleted') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ $o->opname_date->translatedFormat('d M Y') }} · {{ __('by') }} {{ $o->user->name ?? __('Unknown') }}
                            </p>
                        </div>
                    </button>

                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                                     bg-{{ $diffColor }}-100 dark:bg-{{ $diffColor }}-500/20
                                     text-{{ $diffColor }}-700 dark:text-{{ $diffColor }}-400">
                            <span>{{ $diffIcon }}</span>
                            {{ $diffLabel }}
                        </span>
                        <span class="font-mono font-bold text-sm {{ $o->difference < 0 ? 'text-red-600 dark:text-red-400' : ($o->difference > 0 ? 'text-green-600 dark:text-green-400' : 'text-slate-500') }}">
                            {{ $o->difference > 0 ? '+' : '' }}{{ $o->difference }}
                        </span>

                        {{-- Print button --}}
                        <a href="{{ route('opname.print', $o) }}" target="_blank"
                           title="{{ __('Print this opname report') }}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-blue-400 transition group/print">
                            <svg class="w-4 h-4 group-hover/print:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Expanded Detail Body --}}
                <div id="opname-body-{{ $o->id }}" class="hidden px-4 pb-4">
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">
                                    {{ __('System Stock') }}
                                </p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">
                                    {{ $o->system_stock }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Database value') }}</p>
                            </div>

                            <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">
                                    {{ __('Physical Stock') }}
                                </p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">
                                    {{ $o->physical_stock }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Counted value') }}</p>
                            </div>

                            <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border-2 border-{{ $diffColor }}-200 dark:border-{{ $diffColor }}-700/50">
                                <p class="text-xs font-semibold text-{{ $diffColor }}-600 dark:text-{{ $diffColor }}-400 uppercase tracking-widest mb-1">
                                    {{ __('Difference') }}
                                </p>
                                <p class="text-3xl font-bold text-{{ $diffColor }}-600 dark:text-{{ $diffColor }}-400">
                                    {{ $o->difference > 0 ? '+' : '' }}{{ $o->difference }}
                                </p>
                                <p class="text-xs text-{{ $diffColor }}-600 dark:text-{{ $diffColor }}-400 mt-1 font-medium">
                                    {{ $diffIcon }} {{ $diffLabel }}
                                </p>
                            </div>
                        </div>

                        @if ($o->note)
                            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-lg">
                                    💬
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-blue-800 dark:text-blue-300">{{ __('Notes') }}</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-0.5 leading-relaxed">{{ $o->note }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-slate-500 dark:text-slate-400 italic text-center py-2">{{ __('No notes') }}</p>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                        📋
                    </div>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No opname yet') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Create the first opname to verify physical stock') }}</p>
                    <button type="button" onclick="document.getElementById('opnameForm').scrollIntoView({behavior:'smooth'})"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Create First Opname') }}
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    @if ($opnames->hasPages())
        <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
            {{ $opnames->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleOpname(id) {
    const body = document.getElementById('opname-body-' + id);
    const chevron = document.getElementById('chevron-' + id).querySelector('svg');
    if (!body) return;
    body.classList.toggle('hidden');
    chevron.style.transform = body.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(90deg)';
}

document.addEventListener('DOMContentLoaded', () => {
    const statusFilter = document.getElementById('opnameStatusFilter');
    const dateFilter   = document.getElementById('opnameDateFilter');
    const rows         = document.querySelectorAll('.opname-row');

    function applyFilters() {
        const status = statusFilter?.value || '';
        const date   = dateFilter?.value || '';

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowDate   = row.dataset.date;
            const show = (!status || rowStatus === status) && (!date || rowDate === date);
            row.style.display = show ? '' : 'none';
        });
    }

    statusFilter?.addEventListener('change', applyFilters);
    dateFilter?.addEventListener('change', applyFilters);
});
</script>
@endpush

@endsection
