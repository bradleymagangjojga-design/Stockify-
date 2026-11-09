@extends('layouts.app')
@section('title', __('Add Category'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('categories.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
        {{ __('Category') }}
    </a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Add') }}</span>
@endsection

@section('page-header')
<div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Add Category') }}</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
        {{ __('Create a new category to group products') }}
    </p>
</div>
@endsection

@section('content')

<div class="max-w-2xl mx-auto">

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
                {{ __('Fields marked with') }} <span class="text-red-500 font-bold">*</span> {{ __('are required. You can rename categories anytime without affecting products.') }}
            </p>
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

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

            {{-- Card Header --}}
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-blue-500/20">
                    🏷️
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Category Information') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('New category data') }}</p>
                </div>
            </div>

            {{-- Form Fields --}}
            <div class="p-6 space-y-5">

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Category Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           required maxlength="100" autofocus
                           placeholder="{{ __('Example: Electronics') }}"
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl
                                  text-slate-900 dark:text-white placeholder-slate-400
                                  focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('You can enter the name in any language you want') }}
                    </p>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Description') }}
                    </label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="{{ __('Short description about this category...') }}"
                              class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl
                                     text-slate-900 dark:text-white placeholder-slate-400
                                     focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition resize-none">{{ old('description') }}</textarea>
                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Optional. Keep it short and clear for easy recognition.') }}
                    </p>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Card Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-5 py-3 rounded-xl transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Save Category') }}
                </button>
            </div>

        </div>
    </form>
</div>

@endsection
