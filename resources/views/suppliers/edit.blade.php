@extends('layouts.app')
@section('title', __('Edit Supplier'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('suppliers.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Supplier') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Edit') }}</span>
@endsection

@section('page-header')
<div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Edit Supplier') }}</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Update supplier information') }}</p>
</div>
@endsection

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Preview Card --}}
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 mb-6 flex items-center gap-4 overflow-hidden">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg shadow-blue-500/20 ring-4 ring-white dark:ring-slate-900">
            {{ strtoupper(substr($supplier->name, 0, 2)) }}
        </div>

        <div class="relative flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/20 px-2 py-0.5 rounded">
                    {{ __('Editing') }}
                </span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $supplier->name }}</h3>
            <div class="flex items-center gap-2 mt-1 flex-wrap">
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    {{ $supplier->products_count }} {{ __('related products') }}
                </span>
                <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">· #{{ $supplier->id }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-red-800 dark:text-red-300">{{ __('Validation Failed') }}</p>
                <ul class="list-disc list-inside text-xs text-red-700 dark:text-red-400 space-y-0.5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-blue-500/20">
                ✏️
            </div>
            <div>
                <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Edit Information') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Update supplier data') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                    {{ __('Supplier Name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $supplier->name) }}" required autofocus
                       class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label for="contact" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Contact Number') }}
                    </label>
                    <input type="text" id="contact" name="contact" value="{{ old('contact', $supplier->contact) }}"
                           placeholder="021-1234567"
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Email') }}
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $supplier->email) }}"
                           placeholder="sales@example.com"
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                    {{ __('Address') }}
                </label>
                <textarea id="address" name="address" rows="4"
                          placeholder="{{ __('Complete supplier address...') }}"
                          class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition resize-none">{{ old('address', $supplier->address) }}</textarea>
            </div>

            {{-- Did You Know --}}
            <div class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30">
                <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0 text-lg">
                    💡
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-amber-900 dark:text-amber-300">{{ __('Did you know?') }}</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5 leading-relaxed">
                        {{ __('Renaming this supplier will update it everywhere — including products, reports, and printed documents.') }}
                    </p>
                </div>
            </div>

        </form>

        {{-- Card Footer --}}
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 flex items-center justify-end gap-3">
            <a href="{{ route('suppliers.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-5 py-3 rounded-xl transition">
                {{ __('Cancel') }}
            </a>
            <button type="submit" form="" onclick="document.querySelector('form[action=\'{{ route('suppliers.update', $supplier) }}\']').submit();"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Update Supplier') }}
            </button>
        </div>

    </div>
</div>

@endsection