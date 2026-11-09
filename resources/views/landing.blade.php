<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appName }} — {{ __('Multi-Sector Digital Enterprise') }}</title>

    <script>
        (function() {
            const storedTheme = localStorage.getItem('color-theme');
            if (storedTheme === 'dark' || !storedTheme) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased">

{{-- ============================================================
     NAVBAR
============================================================ --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-lg border-b border-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center overflow-hidden border border-slate-700 shadow-lg shadow-blue-500/20 group-hover:scale-105 transition">
                    <img src="{{ asset('image/Seven_Inc_logo.jpg') }}" alt="{{ $appName }} Logo" class="w-full h-full object-contain p-0.5">
                </div>
                <span class="text-white font-bold text-lg tracking-tight">{{ $appName }}</span>
                <span class="hidden sm:inline-block text-[10px] font-semibold text-blue-400 bg-blue-500/10 border border-blue-500/30 px-1.5 py-0.5 rounded ml-1">v1.0</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="#features" class="px-3 py-2 text-sm text-slate-400 hover:text-white transition rounded-lg hover:bg-white/5">{{ __('Features') }}</a>
                <a href="#business" class="px-3 py-2 text-sm text-slate-400 hover:text-white transition rounded-lg hover:bg-white/5">{{ __('Business Units') }}</a>
                <a href="#pricing"  class="px-3 py-2 text-sm text-slate-400 hover:text-white transition rounded-lg hover:bg-white/5">{{ __('Pricing') }}</a>
                <a href="#how"      class="px-3 py-2 text-sm text-slate-400 hover:text-white transition rounded-lg hover:bg-white/5">{{ __('How It Works') }}</a>
                <a href="#benefits" class="px-3 py-2 text-sm text-slate-400 hover:text-white transition rounded-lg hover:bg-white/5">{{ __('Benefits') }}</a>
            </div>

            <div class="flex items-center gap-2">

                {{-- LANGUAGE SWITCHER --}}
                <div class="relative" id="langSwitcher">
                    <button type="button" id="langToggle"
                            class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-white px-2.5 py-2 rounded-lg hover:bg-white/5 transition">
                        <span class="text-base leading-none">{{ app()->getLocale() === 'id' ? '🇮🇩' : '🇺🇸' }}</span>
                        <span class="hidden sm:inline font-medium">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div id="langMenu"
                         class="hidden absolute right-0 mt-2 w-52 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl py-1.5 z-50">

                        <form method="POST" action="{{ route('locale.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="id">
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-left {{ app()->getLocale() === 'id' ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">
                                <span class="text-base">🇮🇩</span>
                                <span>{{ __('Bahasa Indonesia') }}</span>
                                @if (app()->getLocale() === 'id')
                                    <svg class="w-4 h-4 ml-auto text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <form method="POST" action="{{ route('locale.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-left {{ app()->getLocale() === 'en' ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">
                                <span class="text-base">🇺🇸</span>
                                <span>{{ __('English') }}</span>
                                @if (app()->getLocale() === 'en')
                                    <svg class="w-4 h-4 ml-auto text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition shadow-lg shadow-blue-500/20">
                        <span>{{ __('Enter System') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    {{-- ✅ Login button --}}
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-flex items-center text-sm font-medium text-slate-300 hover:text-white px-4 py-2 rounded-lg hover:bg-white/5 transition">
                        {{ __('Login') }}
                    </a>

                    {{-- ✅ Register button (renamed from "Get Started") --}}
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition shadow-lg shadow-blue-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>{{ __('Register') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ============================================================
     HERO SECTION
============================================================ --}}
<section class="relative pt-32 pb-16 lg:pt-40 lg:pb-24 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 32px 32px;"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600 rounded-full blur-3xl opacity-20"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">

            <span class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-500/30 text-blue-300 text-xs font-medium px-3 py-1 rounded-full mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                {{ __('Trusted by 500+ businesses across Indonesia') }}
            </span>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight tracking-tight mb-6">
                {{ __('Manage your business') }}
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400">
                    {{ __('smarter, together.') }}
                </span>
            </h1>

            <p class="text-lg text-slate-400 leading-relaxed mb-10 mx-auto max-w-2xl">
                <span class="font-semibold text-slate-200">{{ $appName }}</span>
                {{ __('is a multi-sector digital enterprise delivering integrated solutions across fashion, production, IT, logistics, and storage — powered by modern technology.') }}
            </p>

            {{-- Hero buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-12">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>{{ __('Enter System') }}</span>
                    </a>
                @else
                    {{-- ✅ View Plans & Pricing → scrolls to #pricing --}}
                    <a href="#pricing"
                       class="inline-flex items-center justify-center gap-2 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>{{ __('View Plans & Pricing') }}</span>
                    </a>

                    {{-- ✅ Register button (renamed from "Enter System") --}}
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 text-base font-semibold text-white bg-white/5 hover:bg-white/10 border border-slate-700 hover:border-slate-600 px-8 py-4 rounded-xl transition backdrop-blur">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>{{ __('Register') }}</span>
                    </a>
                @endauth
            </div>

            <div class="flex items-center justify-center flex-wrap gap-x-8 gap-y-3 text-xs text-slate-500">
                @foreach ([
                    ['icon' => 'M5 13l4 4L19 7', 'text' => __('Free 14-day trial')],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'text' => __('No credit card required')],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'text' => __('5 minute setup')],
                ] as $item)
                    <div class="inline-flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span class="leading-none">{{ $item['text'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FEATURE CARDS
============================================================ --}}
<section id="features" class="scroll-mt-20 py-20 lg:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">{{ __('Main Features') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">
                {{ __('Four pillars of modern business management') }}
            </h2>
            <p class="text-lg text-slate-400">
                {{ __('Everything you need to manage your multi-sector operations in one integrated platform.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="group relative bg-slate-900/50 hover:bg-slate-800/70 border border-slate-800 hover:border-blue-500/50 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-3xl shadow-lg shadow-blue-500/30 mb-5 group-hover:scale-110 transition">📈</div>
                    <h3 class="text-2xl font-bold text-white mb-3">{{ __('Inventory Control') }}</h3>
                    <p class="text-slate-400 leading-relaxed mb-5">
                        {{ __('Monitor incoming and outgoing goods in real-time. System automatically updates stock and alerts when stock is low.') }}
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach ([__('Real-time stock tracking'), __('Automatic minimum stock alerts'), __('Complete transaction history'), __('Fast filter & search')] as $feat)
                            <li class="flex items-start gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feat }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="group relative bg-slate-900/50 hover:bg-slate-800/70 border border-slate-800 hover:border-green-500/50 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-green-500 rounded-full blur-3xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-3xl shadow-lg shadow-green-500/30 mb-5 group-hover:scale-110 transition">🏭</div>
                    <h3 class="text-2xl font-bold text-white mb-3">{{ __('Suppliers & Brands') }}</h3>
                    <p class="text-slate-400 leading-relaxed mb-5">
                        {{ __('Manage supplier data, in-house brands, purchase history, and business relationships in one centralized database.') }}
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach ([__('Centralized supplier database'), __('Purchase history per supplier'), __('Complete contact & address'), __('Print supplier quotation')] as $feat)
                            <li class="flex items-start gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feat }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="group relative bg-slate-900/50 hover:bg-slate-800/70 border border-slate-800 hover:border-purple-500/50 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-3xl shadow-lg shadow-purple-500/30 mb-5 group-hover:scale-110 transition">📋</div>
                    <h3 class="text-2xl font-bold text-white mb-3">{{ __('Stock Opname') }}</h3>
                    <p class="text-slate-400 leading-relaxed mb-5">
                        {{ __('Reconcile physical stock with system data. Record differences, trace causes, and save audit trail for full accountability.') }}
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach ([__('Physical vs system stock comparison'), __('Automatic difference recording'), __('Complete audit trail'), __('Opname report per period')] as $feat)
                            <li class="flex items-start gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-purple-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feat }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="group relative bg-slate-900/50 hover:bg-slate-800/70 border border-slate-800 hover:border-orange-500/50 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-orange-500 rounded-full blur-3xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-3xl shadow-lg shadow-orange-500/30 mb-5 group-hover:scale-110 transition">📄</div>
                    <h3 class="text-2xl font-bold text-white mb-3">{{ __('Reports & Analytics') }}</h3>
                    <p class="text-slate-400 leading-relaxed mb-5">
                        {{ __('Access complete analytical reports: stock, transactions, and user activity. Export to Excel or print professional documents.') }}
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach ([__('Stock report per category'), __('Incoming/outgoing transaction reports'), __('User activity reports'), __('Export to Excel & PDF')] as $feat)
                            <li class="flex items-start gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-orange-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feat }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     BUSINESS UNITS
============================================================ --}}
<section id="business" class="scroll-mt-20 py-20 lg:py-24 bg-slate-900/30 border-y border-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">{{ __('Our Business') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">
                {{ __('Six sectors, one platform') }}
            </h2>
            <p class="text-lg text-slate-400">
                {{ $appName }} {{ __('operates across six business units, all managed through one integrated system.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @foreach ([
                ['icon' => '👕', 'color' => 'blue',   'title' => __('Fashion & Apparel'),      'brands'=> 'Crows Denim · Alphawear · Grenade Clothing · Indonesia Banget', 'desc'  => __('Modern men\'s fashion e-commerce with four in-house brands.')],
                ['icon' => '🏭', 'color' => 'green',  'title' => __('Production Services'),    'brands'=> 'Rumah Konveksi', 'desc'  => __('Garment manufacturing and distribution for custom orders.')],
                ['icon' => '💻', 'color' => 'purple', 'title' => __('IT & Digital Solutions'), 'brands'=> 'Seven Tech · Republikweb.net', 'desc'  => __('Web development, backend engineering, and system integration.')],
                ['icon' => '📦', 'color' => 'orange', 'title' => __('Logistics & Courier'),    'brands'=> 'Ambilpaket · Lion Parcel · POS · JNE · Wahana', 'desc'  => __('Local shipping agency partner and last-mile delivery.')],
                ['icon' => '🔐', 'color' => 'indigo', 'title' => __('Safe Storage'),           'brands'=> 'Titipsini', 'desc'  => __('Specialized temporary storage for goods, houses, and vehicles.')],
                ['icon' => '🔧', 'color' => 'red',    'title' => __('Local Services'),         'brands'=> 'Tukang Las Jogja', 'desc'  => __('Welding and exterior design services for homes and offices.')],
            ] as $unit)
                <div class="group bg-slate-900/50 rounded-2xl border border-slate-800 p-6 hover:border-{{ $unit['color'] }}-500/50 hover:bg-slate-800/70 transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-{{ $unit['color'] }}-500/10 border border-{{ $unit['color'] }}-500/20 flex items-center justify-center text-2xl group-hover:scale-110 transition">
                            {{ $unit['icon'] }}
                        </div>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-{{ $unit['color'] }}-400 bg-{{ $unit['color'] }}-500/10 px-2 py-0.5 rounded">
                            {{ __('Unit') }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1">{{ $unit['title'] }}</h3>
                    <p class="text-xs font-medium text-{{ $unit['color'] }}-400 mb-3">{{ $unit['brands'] }}</p>
                    <p class="text-sm text-slate-400 leading-relaxed">{{ $unit['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     PRICING / SUBSCRIPTION
============================================================ --}}
<section id="pricing" class="scroll-mt-20 py-20 lg:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">{{ __('Pricing') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">
                {{ __('Simple, transparent pricing') }}
            </h2>
            <p class="text-lg text-slate-400">
                {{ __('Choose the plan that fits your business. No hidden fees. Cancel anytime.') }}
            </p>
        </div>

        {{-- Billing Cycle Toggle --}}
        <div class="flex items-center justify-center gap-3 mb-12">
            <span id="label-monthly" class="text-sm font-semibold text-white transition">{{ __('Monthly') }}</span>
            <button type="button" id="billingToggle"
                    class="relative inline-flex items-center h-7 w-14 rounded-full bg-slate-700 hover:bg-slate-600 transition focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                    role="switch" aria-checked="false">
                <span id="billingToggleKnob"
                      class="inline-block w-5 h-5 bg-white rounded-full shadow-lg transform translate-x-1 transition-transform duration-200"></span>
            </button>
            <span id="label-yearly" class="text-sm font-semibold text-slate-400 transition">
                {{ __('Yearly') }}
                <span class="ml-1 inline-flex items-center text-[10px] font-bold text-green-400 bg-green-500/10 border border-green-500/30 px-1.5 py-0.5 rounded">{{ __('Save 20%') }}</span>
            </span>
        </div>

        {{-- Pricing Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

            {{-- STARTER --}}
            <div class="relative bg-slate-900/50 rounded-2xl border border-slate-800 p-8 hover:border-slate-700 transition-all duration-300 hover:-translate-y-1">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('Starter') }}</h3>
                    <p class="text-sm text-slate-400">{{ __('Perfect for small teams getting started.') }}</p>
                </div>

                <div class="mb-6">
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-bold text-slate-400">Rp</span>
                        <span class="text-5xl font-bold text-white pricing-price" data-monthly="149" data-yearly="119">149</span>
                        <span class="text-sm text-slate-400 pricing-period">K / {{ __('month') }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 pricing-note" data-monthly="{{ __('Billed monthly') }}" data-yearly="{{ __('Billed annually — save Rp 360K/year') }}">
                        {{ __('Billed monthly') }}
                    </p>
                </div>

                <a href="{{ route('register') }}"
                   class="block w-full text-center text-sm font-semibold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 px-5 py-3 rounded-xl transition mb-6">
                    {{ __('Register') }}
                </a>

                <ul class="space-y-3">
                    @foreach ([
                        __('Up to 500 products'),
                        __('3 user accounts'),
                        __('Real-time stock tracking'),
                        __('Basic reports'),
                        __('Email support'),
                    ] as $feat)
                        <li class="flex items-start gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- PROFESSIONAL --}}
            <div class="relative bg-gradient-to-b from-blue-950/60 to-slate-900/50 rounded-2xl border-2 border-blue-500/50 p-8 shadow-2xl shadow-blue-500/20 md:scale-105 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-white bg-gradient-to-r from-blue-500 to-indigo-600 px-3 py-1 rounded-full shadow-lg shadow-blue-500/30 uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ __('Most Popular') }}
                    </span>
                </div>

                <div class="mb-6 pt-2">
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('Professional') }}</h3>
                    <p class="text-sm text-slate-300">{{ __('For growing businesses with advanced needs.') }}</p>
                </div>

                <div class="mb-6">
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-bold text-blue-300">Rp</span>
                        <span class="text-5xl font-bold text-white pricing-price" data-monthly="399" data-yearly="319">399</span>
                        <span class="text-sm text-blue-300 pricing-period">K / {{ __('month') }}</span>
                    </div>
                    <p class="text-xs text-blue-300/80 mt-2 pricing-note" data-monthly="{{ __('Billed monthly') }}" data-yearly="{{ __('Billed annually — save Rp 960K/year') }}">
                        {{ __('Billed monthly') }}
                    </p>
                </div>

                <a href="{{ route('register') }}"
                   class="block w-full text-center text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30 mb-6">
                    {{ __('Register') }}
                </a>

                <ul class="space-y-3">
                    @foreach ([
                        __('Unlimited products'),
                        __('10 user accounts'),
                        __('Advanced analytics & reports'),
                        __('Stock opname module'),
                        __('Multi-warehouse support'),
                        __('CSV & PDF export'),
                        __('Priority email support'),
                    ] as $feat)
                        <li class="flex items-start gap-2.5 text-sm text-white">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- ENTERPRISE --}}
            <div class="relative bg-slate-900/50 rounded-2xl border border-slate-800 p-8 hover:border-slate-700 transition-all duration-300 hover:-translate-y-1">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('Enterprise') }}</h3>
                    <p class="text-sm text-slate-400">{{ __('Custom solutions for large organizations.') }}</p>
                </div>

                <div class="mb-6">
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-white">{{ __('Custom') }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">{{ __('Contact us for a quote') }}</p>
                </div>

                <a href="mailto:info@seveninc.id"
                   class="block w-full text-center text-sm font-semibold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 px-5 py-3 rounded-xl transition mb-6">
                    {{ __('Contact Sales') }}
                </a>

                <ul class="space-y-3">
                    @foreach ([
                        __('Everything in Professional'),
                        __('Unlimited user accounts'),
                        __('Custom integrations & API'),
                        __('Dedicated account manager'),
                        __('On-premise deployment option'),
                        __('SLA & uptime guarantee'),
                        __('24/7 phone support'),
                    ] as $feat)
                        <li class="flex items-start gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-12 text-center">
            <p class="text-xs text-slate-500 inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ __('14-day free trial on all plans. No credit card required. Cancel anytime.') }}
            </p>
        </div>
    </div>
</section>

{{-- ============================================================
     STATISTICS
============================================================ --}}
<section class="py-20 border-b border-slate-800/50 bg-gradient-to-r from-blue-950/50 via-indigo-950/50 to-purple-950/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">{{ __('Statistics') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">{{ __('Growing with our community') }}</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="group">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition">🏢</div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 tracking-tight counter" data-target="12">0</div>
                <div class="text-sm text-slate-400">{{ __('Years of Operation') }}</div>
            </div>
            <div class="group">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition">📦</div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 tracking-tight counter" data-target="40" data-suffix="+">0</div>
                <div class="text-sm text-slate-400">{{ __('Products & Services') }}</div>
            </div>
            <div class="group">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition">🏷️</div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 tracking-tight counter" data-target="10" data-suffix="+">0</div>
                <div class="text-sm text-slate-400">{{ __('In-House Brands') }}</div>
            </div>
            <div class="group">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition">📈</div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 tracking-tight counter" data-target="6">0</div>
                <div class="text-sm text-slate-400">{{ __('Business Sectors') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     HOW IT WORKS
============================================================ --}}
<section id="how" class="scroll-mt-20 py-20 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">{{ __('How It Works') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">{{ __('Start in 3 easy steps') }}</h2>
            <p class="text-lg text-slate-400">{{ __('No complicated setup. Get started in minutes.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <div class="hidden md:block absolute top-8 left-1/4 right-1/4 h-0.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-30"></div>

            @foreach ([
                ['num' => '01', 'icon' => '👤', 'title' => __('Create Account'), 'desc' => __('Create a free account in 30 seconds. No credit card required.')],
                ['num' => '02', 'icon' => '📥', 'title' => __('Add Products'),   'desc' => __('Add products manually or bulk import from Excel.')],
                ['num' => '03', 'icon' => '🚀', 'title' => __('Start Managing'), 'desc' => __('Record transactions, monitor stock, and print reports immediately.')],
            ] as $step)
                <div class="relative text-center">
                    <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 items-center justify-center text-2xl shadow-lg shadow-blue-500/30 mb-4">
                        {{ $step['icon'] }}
                    </div>
                    <div class="text-xs font-bold text-blue-400 mb-2 uppercase tracking-wider">{{ __('Step') }} {{ $step['num'] }}</div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ $step['title'] }}</h3>
                    <p class="text-slate-400 text-sm">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     BENEFITS
============================================================ --}}
<section id="benefits" class="scroll-mt-20 py-20 lg:py-24 bg-slate-900/30 border-y border-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <div>
                <span class="inline-block text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">{{ __('Benefits') }}</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight leading-tight">
                    {{ __('Why choose') }} {{ $appName }}?
                </h2>
                <p class="text-lg text-slate-400 mb-8">
                    {{ __('Designed for multi-sector businesses with focus on speed, accuracy, and ease of use.') }}
                </p>

                <div class="space-y-5">
                    @foreach ([
                        ['icon' => '⚡', 'color' => 'blue',   'title' => __('Fast & Lightweight'),   'desc' => __('Application runs fast even with thousands of product data. Loading under 1 second.')],
                        ['icon' => '🔒', 'color' => 'green',  'title' => __('Secure & Controlled'),  'desc' => __('Multi-role authentication, CSRF protection, bcrypt hashing, and complete audit trail.')],
                        ['icon' => '📱', 'color' => 'purple', 'title' => __('Fully Responsive'),     'desc' => __('Access from desktop, tablet, or smartphone. Interface adapts automatically.')],
                        ['icon' => '🎨', 'color' => 'orange', 'title' => __('Modern UI'),            'desc' => __('Professional design with dark mode, smooth animations, and best user experience.')],
                    ] as $benefit)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-{{ $benefit['color'] }}-500/10 border border-{{ $benefit['color'] }}-500/20 flex items-center justify-center text-2xl flex-shrink-0">
                                {{ $benefit['icon'] }}
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg mb-1">{{ $benefit['title'] }}</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <div class="grid grid-cols-2 gap-4">
                    @foreach ([
                        ['label' => __('Efficiency'),         'value' => '+40%',   'color' => 'blue',   'icon' => '📈'],
                        ['label' => __('Accuracy'),           'value' => '99.9%',  'color' => 'green',  'icon' => '🎯'],
                        ['label' => __('Setup Time'),         'value' => '5 min',  'color' => 'purple', 'icon' => '⚡'],
                        ['label' => __('User Satisfaction'),  'value' => '4.9/5',  'color' => 'orange', 'icon' => '⭐'],
                    ] as $i => $stat)
                        <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-6 {{ $i % 2 === 1 ? 'translate-y-6' : '' }} hover:border-slate-700 transition">
                            <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-500/10 flex items-center justify-center text-2xl mb-4">{{ $stat['icon'] }}</div>
                            <div class="text-3xl font-bold text-white mb-1">{{ $stat['value'] }}</div>
                            <div class="text-sm text-slate-400">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FINAL CTA
============================================================ --}}
<section class="py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-10 md:p-16 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle, white 2px, transparent 2px); background-size: 32px 32px;"></div>
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-white rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white rounded-full blur-3xl opacity-20"></div>

        <div class="relative">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl mb-6">🚀</div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 tracking-tight">{{ __('Ready to get started?') }}</h2>
            <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
                {{ __('Join businesses already using') }} {{ $appName }} {{ __('to manage their multi-sector operations. Free for 14 days, no credit card required.') }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                @guest
                    {{-- ✅ Register button (renamed from "Get Started — Free") --}}
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 text-base font-semibold text-blue-700 bg-white hover:bg-blue-50 px-8 py-4 rounded-xl transition shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>{{ __('Register — Free') }}</span>
                    </a>

                    {{-- ✅ Login button (renamed from "Enter System") --}}
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 text-base font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 px-8 py-4 rounded-xl transition backdrop-blur">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>{{ __('Login') }}</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 text-base font-semibold text-blue-700 bg-white hover:bg-blue-50 px-8 py-4 rounded-xl transition shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                        <span>{{ __('Go to Dashboard') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FOOTER
============================================================ --}}
<footer class="border-t border-slate-800/50 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">

            <div class="md:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center overflow-hidden border border-slate-700">
                        <img src="{{ asset('image/Seven_Inc_logo.jpg') }}" alt="{{ $appName }} Logo" class="w-full h-full object-contain p-0.5">
                    </div>
                    <div>
                        <div class="text-white font-bold text-lg leading-tight">{{ $appName }}</div>
                        <div class="text-[10px] text-slate-500">{{ __('Multi-Sector Digital Enterprise') }}</div>
                    </div>
                </a>

                <p class="text-sm text-slate-400 max-w-sm mb-4 leading-relaxed">
                    {{ __('A dynamic, multi-sector digital creative holding company based in Yogyakarta, Indonesia. Founded in 2012, rebranded to Seven Inc. in 2017.') }}
                </p>
                <p class="text-xs text-slate-500 mb-6">
                    {{ __('Legally operating as') }} <span class="text-slate-400 font-medium">CV. Sanjaya 57</span>
                    · Est. 2012 · Yogyakarta, Indonesia
                </p>

                <div class="flex items-center gap-3">
                    @foreach (['github', 'twitter', 'linkedin'] as $social)
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition">
                            @if ($social === 'github')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                            @elseif ($social === 'twitter')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">{{ __('Business') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#features" class="text-slate-400 hover:text-white transition">{{ __('Features') }}</a></li>
                    <li><a href="#business" class="text-slate-400 hover:text-white transition">{{ __('Business Units') }}</a></li>
                    <li><a href="#pricing"  class="text-slate-400 hover:text-white transition">{{ __('Pricing') }}</a></li>
                    <li><a href="#how"      class="text-slate-400 hover:text-white transition">{{ __('How It Works') }}</a></li>
                    <li><a href="#benefits" class="text-slate-400 hover:text-white transition">{{ __('Benefits') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">{{ __('Contact') }}</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Yogyakarta, Indonesia</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:info@seveninc.id" class="hover:text-white transition">info@seveninc.id</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:+62211234567" class="hover:text-white transition">021-1234567</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-800/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">© {{ date('Y') }} {{ $appName }} · CV. Sanjaya 57 — {{ __('All rights reserved.') }}</p>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <a href="#" class="hover:text-slate-300 transition">{{ __('Privacy') }}</a>
                <a href="#" class="hover:text-slate-300 transition">{{ __('Terms') }}</a>
                <a href="#" class="hover:text-slate-300 transition">{{ __('Support') }}</a>
                <span class="text-slate-700">·</span>
                <span>v1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ============================================================
    // LANGUAGE SWITCHER
    // ============================================================
    const langToggle = document.getElementById('langToggle');
    const langMenu   = document.getElementById('langMenu');

    if (langToggle && langMenu) {
        langToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            langMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!langMenu.contains(e.target) && !langToggle.contains(e.target)) {
                langMenu.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') langMenu.classList.add('hidden');
        });
    }

    // ============================================================
    // PRICING TOGGLE (Monthly / Yearly)
    // ============================================================
    const billingToggle = document.getElementById('billingToggle');
    const billingKnob   = document.getElementById('billingToggleKnob');
    const labelMonthly  = document.getElementById('label-monthly');
    const labelYearly   = document.getElementById('label-yearly');

    if (billingToggle) {
        let isYearly = false;

        billingToggle.addEventListener('click', () => {
            isYearly = !isYearly;

            billingToggle.setAttribute('aria-checked', isYearly);
            billingKnob.style.transform = isYearly ? 'translateX(32px)' : 'translateX(4px)';

            labelMonthly.classList.toggle('text-white', !isYearly);
            labelMonthly.classList.toggle('text-slate-400', isYearly);
            labelYearly.classList.toggle('text-white', isYearly);
            labelYearly.classList.toggle('text-slate-400', !isYearly);

            document.querySelectorAll('.pricing-price').forEach(el => {
                el.textContent = isYearly ? el.dataset.yearly : el.dataset.monthly;
            });
            document.querySelectorAll('.pricing-note').forEach(el => {
                el.textContent = isYearly ? el.dataset.yearly : el.dataset.monthly;
            });
        });
    }

    // ============================================================
    // STAT COUNTERS
    // ============================================================
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (el) => {
        const target    = parseFloat(el.dataset.target);
        const decimals  = parseInt(el.dataset.decimal || 0);
        const suffix    = el.dataset.suffix || '';
        const duration  = 2000;
        const steps     = 60;
        const increment = target / steps;
        let current     = 0;
        let step        = 0;

        const timer = setInterval(() => {
            step++;
            current += increment;
            if (step >= steps) {
                current = target;
                clearInterval(timer);
            }
            let display = current.toLocaleString('id-ID', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
            });
            el.textContent = display + suffix;
        }, duration / steps);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
});
</script>

</body>
</html>
