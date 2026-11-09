@extends('layouts.app')
@section('title', __('Add Product'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('products.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Product') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Add') }}</span>
@endsection

@section('page-header')
<div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Add Product') }}</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Add a new product to the warehouse inventory') }}</p>
</div>
@endsection

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Quick Tip Banner --}}
    <div class="mb-6 flex items-start gap-3 p-4 rounded-2xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-blue-900 dark:text-blue-300">{{ __('Quick Tip') }}</p>
            <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5 leading-relaxed">
                {{ __('Fields marked with') }} <span class="text-red-500 font-bold">*</span> {{ __('are required. You can always edit these details later from the product list.') }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        {{-- Preserve return path --}}
        <input type="hidden" name="redirect_to" value="{{ $redirect_to ?? url()->previous() }}">

        {{-- Basic Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-xl flex-shrink-0">
                    📦
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Basic Information') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Product identity') }}</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        SKU <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required autofocus
                           placeholder="{{ __('Example: PRD-001') }}"
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    @error('sku')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Product Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="{{ __('Example: Laptop ASUS') }}"
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Category') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                        <option value="">{{ __('— Select Category —') }}</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supplier_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Supplier') }}
                    </label>
                    <select id="supplier_id" name="supplier_id"
                            class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                        <option value="">{{ __('— Select Supplier —') }}</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Description') }}
                    </label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="{{ __('Product description...') }}"
                              class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing & Stock --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-xl flex-shrink-0">
                    💰
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Price & Stock') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Price and quantity details') }}</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label for="purchase_price" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Purchase Price') }} (Rp)
                    </label>
                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', 0) }}" min="0"
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                </div>

                <div>
                    <label for="selling_price" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Selling Price') }} (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price', 0) }}" min="0" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                </div>

                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Initial Stock') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                </div>

                <div>
                    <label for="min_stock" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Min. Stock') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('A warning appears when stock is below this number') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ $redirect_to ?? route('products.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-5 py-3 rounded-xl transition">
                {{ __('Cancel') }}
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Product') }}
            </button>
        </div>
    </form>
</div>

@endsection
