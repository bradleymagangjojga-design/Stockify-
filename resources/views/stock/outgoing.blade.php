@extends('layouts.app')
@section('title', __('Outgoing Goods'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('stock.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Stock') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Outgoing Goods') }}</span>
@endsection

@section('page-header')
@php
    $backUrl = request('redirect_to', route('stock.index'));
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Record Outgoing Goods') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Record goods leaving the warehouse') }}</p>
    </div>
    <a href="{{ $backUrl }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back') }}
    </a>
</div>
@endsection

@section('content')

@php
    $selectedProductId = old('product_id', request('product_id'));
@endphp

<div class="max-w-4xl mx-auto">

    {{-- Quick Tip Banner --}}
    <div class="mb-6 flex items-start gap-3 p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30">
        <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-red-900 dark:text-red-300">{{ __('Recording Outgoing Goods') }}</p>
            <p class="text-xs text-red-700 dark:text-red-400 mt-0.5 leading-relaxed">
                {{ __('Stock will be deducted automatically. Make sure the quantity does not exceed available stock.') }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('stock.outgoing.store') }}">
        @csrf

        {{-- Preserve return path --}}
        <input type="hidden" name="redirect_to" value="{{ request('redirect_to', route('stock.index')) }}">

        {{-- Dispatch Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-6 shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-red-500/20">
                    📤
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Dispatch Information') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Select the product to be dispatched') }}</p>
                </div>
            </div>

            <div class="p-6">
                <label for="product_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                    {{ __('Product') }} <span class="text-red-500">*</span>
                </label>
                <select id="product_id" name="product_id" required
                        class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500/50 focus:border-red-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    <option value="">{{ __('— Select Product —') }}</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" @selected($selectedProductId == $p->id)>
                            {{ $p->name }} — {{ $p->stock }} {{ __('unit') }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-6 shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-blue-500/20">
                    📋
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Transaction Details') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Quantity and dispatch date') }}</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label for="quantity" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Quantity') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="quantity" name="quantity"
                               value="{{ old('quantity') }}" min="1" required
                               placeholder="0"
                               class="w-full px-4 py-3 pr-16 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-red-500/50 focus:border-red-500 focus:bg-white dark:focus:bg-slate-800 transition">
                        <span class="absolute inset-y-0 right-4 flex items-center text-xs text-slate-500 dark:text-slate-400">
                            {{ __('unit') }}
                        </span>
                    </div>
                    @error('quantity')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transaction_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Date') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="transaction_date" name="transaction_date"
                           value="{{ old('transaction_date', now()->toDateString()) }}" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500/50 focus:border-red-500 focus:bg-white dark:focus:bg-slate-800 transition">
                </div>

                <div class="md:col-span-2">
                    <label for="note" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Notes') }}
                    </label>
                    <textarea id="note" name="note" rows="3"
                              placeholder="{{ __('Additional notes (optional)...') }}"
                              class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-red-500/50 focus:border-red-500 focus:bg-white dark:focus:bg-slate-800 transition resize-none">{{ old('note') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Warning Banner --}}
        <div class="flex items-start gap-3 p-4 mb-6 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30">
            <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 text-lg">
                ⚠️
            </div>
            <div>
                <p class="text-sm font-semibold text-red-800 dark:text-red-300">{{ __('Attention') }}</p>
                <p class="text-xs text-red-700 dark:text-red-400 mt-0.5 leading-relaxed">
                    {{ __('Make sure the outgoing quantity does not exceed the available stock.') }}
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ request('redirect_to', route('stock.index')) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-5 py-3 rounded-xl transition">
                {{ __('Cancel') }}
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 px-5 py-3 rounded-xl transition shadow-lg shadow-red-500/30 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Outgoing Goods') }}
            </button>
        </div>
    </form>
</div>

@endsection