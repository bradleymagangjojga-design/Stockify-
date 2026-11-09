@extends('layouts.app')
@section('title', __('Settings'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Settings') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Settings') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Manage application settings, appearance, and system configuration') }}
        </p>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-500/20 px-3 py-1.5 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            {{ __('System Normal') }}
        </span>
    </div>
</div>
@endsection

@section('content')

@php
    $userRole = auth()->user()->role;
    $defaultTab = $userRole === 'Admin' ? 'general' : 'appearance';
    
    // Priority: Controller passes $activeTab, fallback to session or default
    $activeTab = $activeTab ?? session('active_tab', $defaultTab);

    // ✅ ADDED: 'activities' tab available for ALL roles
    $allTabs = [
        ['id' => 'general',       'label' => __('General'),       'icon' => 'cog',    'roles' => ['Admin']],
        ['id' => 'appearance',    'label' => __('Appearance'),    'icon' => 'paint',  'roles' => ['Admin', 'Manajer Gudang', 'Staff Gudang']],
        ['id' => 'language',      'label' => __('Language'),      'icon' => 'globe',  'roles' => ['Admin', 'Manajer Gudang', 'Staff Gudang']],
        ['id' => 'inventory',     'label' => __('Inventory'),     'icon' => 'box',    'roles' => ['Admin']],
        ['id' => 'notifications', 'label' => __('Notifications'), 'icon' => 'bell',   'roles' => ['Admin', 'Manajer Gudang', 'Staff Gudang']],
        ['id' => 'security',      'label' => __('Security'),      'icon' => 'shield', 'roles' => ['Admin']],
        ['id' => 'activities',    'label' => __('Activity Log'),  'icon' => 'list',   'roles' => ['Admin', 'Manajer Gudang', 'Staff Gudang']], // ✅ NEW
    ];

    $tabs = array_filter($allTabs, fn($t) => in_array($userRole, $t['roles']));

    $tabIcons = [
        'cog'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'paint'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
        'globe'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>',
        'box'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        'bell'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
        'shield' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        'list'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>', // ✅ ADDED: List icon
    ];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- LEFT: TAB NAVIGATION --}}
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-2 sticky top-24">
            <nav class="space-y-1" id="settingsTabsNav">
                @foreach ($tabs as $tab)
                    <button type="button"
                            data-tab="{{ $tab['id'] }}"
                            class="settings-tab w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition text-left
                                   {{ $activeTab === $tab['id']
                                      ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'
                                      : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $tabIcons[$tab['icon']] !!}
                        </svg>
                        <span>{{ $tab['label'] }}</span>
                    </button>
                @endforeach
            </nav>

            {{-- System Info --}}
            <div class="mt-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                    {{ __('System Info') }}
                </p>
                <div class="space-y-1.5 text-[11px]">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-500">{{ __('Version') }}</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">v1.0.0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-500">Laravel</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-500">PHP</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: TAB PANELS --}}
    <div class="lg:col-span-3">

        {{-- GENERAL (Admin Only) --}}
        @if ($userRole === 'Admin')
            <div id="tab-general" class="settings-panel {{ $activeTab !== 'general' ? 'hidden' : '' }}">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('General Settings') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Basic configuration of the Stockify application') }}</p>
                    </div>

                    <form method="POST" action="{{ route('settings.general') }}" class="p-6 space-y-6">
                        @csrf
                        <input type="hidden" name="active_tab" value="general">

                        <div>
                            <label for="app_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                                {{ __('Application Name') }}
                            </label>
                            <input type="text" id="app_name" name="app_name"
                                   value="{{ $settings['app_name'] ?? config('app.name', 'Stockify') }}"
                                   class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                                {{ __('This name will be displayed throughout the application') }}
                            </p>
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                                {{ __('Timezone') }}
                            </label>
                            <select id="timezone" name="timezone"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                                <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB — UTC+7)</option>
                                <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA — UTC+8)</option>
                                <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT — UTC+9)</option>
                                <option value="UTC" {{ ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                                {{ __('Currency') }}
                            </label>
                            <select id="currency" name="currency"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                                <option value="IDR" {{ ($settings['currency'] ?? '') === 'IDR' ? 'selected' : '' }}>IDR (Rp — Rupiah Indonesia)</option>
                                <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD ($ — US Dollar)</option>
                                <option value="SGD" {{ ($settings['currency'] ?? '') === 'SGD' ? 'selected' : '' }}>SGD (S$ — Singapore Dollar)</option>
                                <option value="MYR" {{ ($settings['currency'] ?? '') === 'MYR' ? 'selected' : '' }}>MYR (RM — Malaysian Ringgit)</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ __('Changes are saved automatically') }}
                            </p>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- APPEARANCE (Everyone) --}}
        <div id="tab-appearance" class="settings-panel {{ $activeTab !== 'appearance' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Appearance') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Customize application appearance') }}</p>
                </div>

                <form method="POST" action="{{ route('settings.appearance') }}" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="active_tab" value="appearance">

                    <div class="flex items-start justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Dark Mode') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ __('Enable dark theme for eye comfort') }}
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="hidden" name="dark_mode" value="false">
                            <input type="checkbox" name="dark_mode" id="darkModeToggle" value="true" class="sr-only peer" {{ ($settings['dark_mode'] ?? 'false') === 'true' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                            {{ __('Primary Color') }}
                        </label>

                        <input type="hidden" name="primary_color" id="primary_color_input" value="{{ $settings['primary_color'] ?? 'blue' }}">

                        <div class="grid grid-cols-6 gap-3">
                            @foreach ([
                                ['name' => 'blue',   'bg' => 'bg-blue-600'],
                                ['name' => 'green',  'bg' => 'bg-green-600'],
                                ['name' => 'purple', 'bg' => 'bg-purple-600'],
                                ['name' => 'orange', 'bg' => 'bg-orange-600'],
                                ['name' => 'pink',   'bg' => 'bg-pink-600'],
                                ['name' => 'slate',  'bg' => 'bg-slate-700'],
                            ] as $color)
                                <button type="button" data-color="{{ $color['name'] }}"
                                        class="color-swatch relative aspect-square rounded-xl {{ $color['bg'] }} hover:scale-105 transition
                                               {{ ($settings['primary_color'] ?? 'blue') === $color['name'] ? 'ring-4 ring-offset-2 dark:ring-offset-slate-900' : '' }}">
                                    @if (($settings['primary_color'] ?? 'blue') === $color['name'])
                                        <span class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Save Appearance') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- LANGUAGE (Everyone) --}}
        <div id="tab-language" class="settings-panel {{ $activeTab !== 'language' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Language Settings') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('Choose application display language') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('settings.locale') }}" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="active_tab" value="language">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($availableLocales as $code => $locale)
                            <label class="language-card relative flex items-center gap-4 p-5 rounded-xl border-2 cursor-pointer transition
                                          {{ $currentLocale === $code
                                             ? 'border-blue-500 dark:border-blue-500'
                                             : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600' }}">
                                <input type="radio" name="locale" value="{{ $code }}"
                                       class="sr-only language-radio"
                                       {{ $currentLocale === $code ? 'checked' : '' }}>

                                <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl flex-shrink-0">
                                    {{ $locale['flag'] }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $locale['name'] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $locale['native'] }}</p>
                                </div>

                                <div class="check-icon w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 {{ $currentLocale === $code ? '' : 'hidden' }}">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                <div class="empty-icon w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex-shrink-0 {{ $currentLocale === $code ? 'hidden' : '' }}"></div>
                            </label>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Selected language') }}: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ strtoupper($currentLocale) }}</span>
                        </p>
                        <button type="submit"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Save Language') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- INVENTORY (Admin Only) --}}
        @if ($userRole === 'Admin')
            <div id="tab-inventory" class="settings-panel {{ $activeTab !== 'inventory' ? 'hidden' : '' }}">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Inventory') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Stock and warehouse settings') }}</p>
                    </div>

                    <form method="POST" action="{{ route('settings.inventory') }}" class="p-6 space-y-6">
                        @csrf
                        <input type="hidden" name="active_tab" value="inventory">

                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                                {{ __('Low Stock Threshold (Default)') }}
                            </label>
                            <div class="relative">
                                <input type="number" id="low_stock_threshold" name="low_stock_threshold"
                                       value="{{ $settings['low_stock_threshold'] ?? '5' }}" min="1" max="100"
                                       class="w-full px-4 py-3 pr-16 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                                <span class="absolute inset-y-0 right-4 flex items-center text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('unit') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Auto-Update Stock') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ __('Stock is reduced automatically when outgoing transactions occur') }}
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="hidden" name="auto_update_stock" value="false">
                                <input type="checkbox" name="auto_update_stock" value="true" {{ ($settings['auto_update_stock'] ?? 'true') === 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- NOTIFICATIONS (Everyone) --}}
        <div id="tab-notifications" class="settings-panel {{ $activeTab !== 'notifications' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Notifications') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Configure which notifications you want to receive') }}</p>
                </div>

                @php
                    $isOn = fn($val) => in_array(strtolower((string) $val), ['true', '1', 'yes', 'on'], true);

                    $toggles = [
                        ['key' => 'notif_low_stock',    'title' => __('Low Stock Warning'), 'desc' => __('Notification when product reaches minimum stock'), 'default' => 'true'],
                        ['key' => 'notif_transaction',  'title' => __('New Transaction'),   'desc' => __('Notification for every incoming/outgoing transaction'), 'default' => 'true'],
                        ['key' => 'notif_incoming',     'title' => __('Incoming Goods'),    'desc' => __('Notification when goods are received from supplier'), 'default' => 'true'],
                        ['key' => 'notif_outgoing',     'title' => __('Outgoing Goods'),    'desc' => __('Notification when goods are shipped out'), 'default' => 'false'],
                        ['key' => 'notif_daily_report', 'title' => __('Daily Report'),      'desc' => __('Daily warehouse activity summary'), 'default' => 'false'],
                    ];
                @endphp

                <form method="POST" action="{{ route('settings.notifications') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="active_tab" value="notifications">

                    @foreach ($toggles as $t)
                        @php
                            $currentValue = $settings[$t['key']] ?? $t['default'];
                            $checked = $isOn($currentValue);
                        @endphp

                        <div class="flex items-start justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $t['title'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $t['desc'] }}</p>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="hidden" name="{{ $t['key'] }}" value="false">
                                <input type="checkbox"
                                       name="{{ $t['key'] }}"
                                       value="true"
                                       {{ $checked ? 'checked' : '' }}
                                       class="sr-only peer">

                                <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    @endforeach

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Save Notifications') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SECURITY (Admin Only) --}}
        @if ($userRole === 'Admin')
            <div id="tab-security" class="settings-panel {{ $activeTab !== 'security' ? 'hidden' : '' }}">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Security') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Account and system security settings') }}</p>
                    </div>

                    <form method="POST" action="{{ route('settings.security') }}" class="p-6 space-y-5">
                        @csrf
                        <input type="hidden" name="active_tab" value="security">

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                    🔐
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Change Password') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ __('Password last changed 30 days ago') }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('profile.index') }}"
                               class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                {{ __('Change Password') }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <div class="flex items-start justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Auto Logout') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ __('Auto logout after 2 hours of inactivity') }}
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="hidden" name="auto_logout" value="false">
                                <input type="checkbox" name="auto_logout" value="true" {{ ($settings['auto_logout'] ?? 'true') === 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Save Security') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ACTIVITY LOG (Everyone) --}}
        <div id="tab-activities" class="settings-panel {{ $activeTab !== 'activities' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ $userRole === 'Staff Gudang' ? __('My Activity Log') : __('Activity Log') }}
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ $userRole === 'Staff Gudang' 
                                ? __('Your personal operational history') 
                                : __('Operational history of the warehouse') }}
                        </p>
                    </div>
                    <span class="text-xs font-medium bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 px-3 py-1.5 rounded-full">
                        {{ $activities->total() }} {{ __('entries') }}
                    </span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse ($activities as $activity)
                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition flex items-start gap-4">
                            
                            {{-- Action Icon --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-lg
                                {{ $activity->action === 'login' ? 'bg-green-100 dark:bg-green-500/10' : '' }}
                                {{ $activity->action === 'logout' ? 'bg-slate-100 dark:bg-slate-800' : '' }}
                                {{ $activity->action === 'created' ? 'bg-blue-100 dark:bg-blue-500/10' : '' }}
                                {{ $activity->action === 'updated' ? 'bg-orange-100 dark:bg-orange-500/10' : '' }}
                                {{ $activity->action === 'deleted' ? 'bg-red-100 dark:bg-red-500/10' : '' }}
                                {{ $activity->action === 'exported' ? 'bg-purple-100 dark:bg-purple-500/10' : '' }}
                            ">
                                @if($activity->action === 'login') 🔐
                                @elseif($activity->action === 'logout') 🚪
                                @elseif($activity->action === 'created') ➕
                                @elseif($activity->action === 'updated') ✏️
                                @elseif($activity->action === 'deleted') 🗑️
                                @elseif($activity->action === 'exported') 📤
                                @else 📋
                                @endif
                            </div>

                            {{-- Activity Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $activity->user->name ?? __('Unknown User') }}
                                    </span>
                                    
                                    @if($userRole !== 'Staff Gudang' && isset($activity->user->role))
                                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 uppercase">
                                            {{ $activity->user->role }}
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ $activity->description }}
                                </p>

                                <div class="flex flex-wrap items-center gap-3 mt-2 text-[11px] text-slate-400 dark:text-slate-500">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $activity->created_at->format('d M Y, H:i') }}
                                    </span>
                                    @if($activity->ip_address)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                            IP: {{ $activity->ip_address }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">
                                📭
                            </div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('No activities found') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('There are no activity logs to display right now.') }}</p>
                        </div>
                    @endforelse
                </div>

                @if($activities->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

<script>
// ============================================================
// SETTINGS TABS — with URL hash persistence
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const tabs   = document.querySelectorAll('.settings-tab');
    const panels = document.querySelectorAll('.settings-panel');

    // ---------- Core: switch to a tab by id ----------
    function activateTab(tabId, updateHash = true) {
        // Guard: only activate if a tab with this id exists
        const targetPanel = document.getElementById('tab-' + tabId);
        if (!targetPanel) return false;

        // Update all tab buttons
        tabs.forEach(t => {
            if (t.dataset.tab === tabId) {
                t.classList.remove('text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-100', 'dark:hover:bg-slate-800', 'hover:text-slate-900', 'dark:hover:text-white');
                t.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/20');
            } else {
                t.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/20');
                t.classList.add('text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-100', 'dark:hover:bg-slate-800', 'hover:text-slate-900', 'dark:hover:text-white');
            }
        });

        // Show only the target panel
        panels.forEach(p => p.classList.add('hidden'));
        targetPanel.classList.remove('hidden');

        // Update URL hash (so refresh/bookmark keeps this tab)
        if (updateHash) {
            history.replaceState(null, '', '#' + tabId);
        }

        return true;
    }

    // ---------- Attach click handlers ----------
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            activateTab(tab.dataset.tab, true);
        });
    });

    // ---------- On page load: determine which tab to show ----------
    // Priority:
    //   1. URL hash (e.g. #appearance)
    //   2. Session flash (already applied server-side via $activeTab)
    //   3. Default (server-side $activeTab)
    const hash = window.location.hash.replace('#', '').trim();
    if (hash) {
        // Try to activate the hash tab; if it fails (invalid id), keep server default
        activateTab(hash, false);
    }

    // ---------- Listen for back/forward button ----------
    window.addEventListener('hashchange', () => {
        const newHash = window.location.hash.replace('#', '').trim();
        if (newHash) activateTab(newHash, false);
    });

    // ---------- Language Selection Visual Update ----------
    const languageRadios = document.querySelectorAll('.language-radio');
    languageRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.language-card').forEach(label => {
                label.classList.remove('border-blue-500', 'dark:border-blue-500');
                label.classList.add('border-slate-200', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-800/50', 'hover:border-slate-300', 'dark:hover:border-slate-600');

                const checkIcon = label.querySelector('.check-icon');
                const emptyIcon = label.querySelector('.empty-icon');
                if (checkIcon) checkIcon.classList.add('hidden');
                if (emptyIcon) emptyIcon.classList.remove('hidden');
            });

            if (this.checked) {
                const selectedLabel = this.closest('.language-card');
                selectedLabel.classList.remove('border-slate-200', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-800/50', 'hover:border-slate-300', 'dark:hover:border-slate-600');
                selectedLabel.classList.add('border-blue-500', 'dark:border-blue-500');

                const checkIcon = selectedLabel.querySelector('.check-icon');
                const emptyIcon = selectedLabel.querySelector('.empty-icon');
                if (checkIcon) checkIcon.classList.remove('hidden');
                if (emptyIcon) emptyIcon.classList.add('hidden');
            }
        });
    });

    // ---------- Appearance Color Swatches ----------
    document.querySelectorAll('.color-swatch').forEach(swatch => {
        swatch.addEventListener('click', function() {
            const color = this.dataset.color;
            const hiddenInput = document.getElementById('primary_color_input');
            if (hiddenInput) hiddenInput.value = color;

            document.querySelectorAll('.color-swatch').forEach(s => {
                s.classList.remove('ring-4', 'ring-offset-2', 'dark:ring-offset-slate-900');
                const checkmark = s.querySelector('span');
                if (checkmark) checkmark.remove();
            });

            this.classList.add('ring-4', 'ring-offset-2', 'dark:ring-offset-slate-900');

            const newCheckmark = document.createElement('span');
            newCheckmark.className = 'absolute inset-0 flex items-center justify-center';
            newCheckmark.innerHTML = `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            `;
            this.appendChild(newCheckmark);
        });
    });

    // ---------- Dark Mode Toggle ----------
    const darkToggle = document.getElementById('darkModeToggle');
    if (darkToggle) {
        darkToggle.checked = document.documentElement.classList.contains('dark');
        darkToggle.addEventListener('change', () => {
            document.documentElement.classList.toggle('dark', darkToggle.checked);
            localStorage.setItem('color-theme', darkToggle.checked ? 'dark' : 'light');
        });
    }
});
</script>

@endsection