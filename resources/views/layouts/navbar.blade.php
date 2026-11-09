@php $user = auth()->user(); @endphp

<header class="fixed top-0 right-0 left-0 md:left-64 z-30 h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-800">
    <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center gap-4">

        {{-- Mobile menu toggle --}}
        <button type="button" id="menuToggle"
                class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Breadcrumbs --}}
        <nav class="hidden md:flex items-center gap-2 text-sm" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
            @hasSection('breadcrumb')
                @yield('breadcrumb')
            @else
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">@yield('title', 'Dashboard')</span>
            @endif
        </nav>

        {{-- Global search --}}
        <div class="flex-1 max-w-md mx-auto hidden lg:block">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="globalSearchInput" placeholder="{{ __('Search for products, transactions, suppliers...') }}"
                       class="w-full pl-9 pr-16 py-2 text-sm bg-slate-100 dark:bg-slate-800 border-0 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500/50 focus:bg-white dark:focus:bg-slate-800 transition">
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                    <kbd class="hidden xl:inline-flex items-center gap-0.5 text-[10px] text-slate-400 bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded border border-slate-300 dark:border-slate-600 font-mono">
                        Ctrl K
                    </kbd>
                </div>
                <div id="globalSearchResults" class="hidden absolute top-full left-0 right-0 mt-2 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl overflow-hidden z-50"></div>
            </div>
        </div>

        {{-- Right side actions --}}
        <div class="flex items-center gap-1 ms-auto">

            {{-- Notifications --}}
            <div class="relative" id="navNotifWrapper">
                <button type="button" id="navNotifToggle"
                        class="relative inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span id="notifDot" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                </button>

                <div id="navNotifMenu" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden z-50">
                    <div class="p-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="font-semibold text-sm text-slate-900 dark:text-white">{{ __('Notifications') }}</h3>
                        <span class="text-xs bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 px-2 py-0.5 rounded-full font-medium">3 {{ __('new') }}</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <a href="{{ route('notifications.index') }}" class="flex items-start gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border-b border-slate-100 dark:border-slate-700/50">
                            <div class="w-9 h-9 rounded-lg bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center text-base flex-shrink-0">⚠️</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ __('Low Stock Warning') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">Beras Premium 5kg {{ __('remaining') }} 3 {{ __('unit') }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">5 {{ __('minutes ago') }}</p>
                            </div>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="flex items-start gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border-b border-slate-100 dark:border-slate-700/50">
                            <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-base flex-shrink-0">📦</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ __('Incoming Goods') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">10 {{ __('unit') }} Laptop ASUS ROG {{ __('received') }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">1 {{ __('hour ago') }}</p>
                            </div>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="flex items-start gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-base flex-shrink-0">👤</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ __('New User') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">{{ __('New staff account added') }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">3 {{ __('hours ago') }}</p>
                            </div>
                        </a>
                    </div>
                    <a href="{{ route('notifications.index') }}" class="block p-3 text-center text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border-t border-slate-200 dark:border-slate-700">
                        {{ __('View all notifications') }} →
                    </a>
                </div>
            </div>

            {{-- Help --}}
            <a href="{{ route('help.index') }}" title="{{ __('Help') }}"
               class="hidden sm:inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </a>

            {{-- Divider --}}
            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            {{-- User menu --}}
            <div class="relative" id="navUserWrapper">
                <button type="button" id="navUserToggle" class="flex items-center gap-2 p-1 pr-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-semibold text-slate-900 dark:text-white leading-tight">{{ $user->name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-tight">{{ $user->role }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <div id="navUserMenu" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl py-1.5 z-50">
                    <div class="px-3 py-2 border-b border-slate-200 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->username }} · {{ $user->role }}</p>
                    </div>

                    <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('My Profile') }}
                    </a>

                    {{-- Settings — now for Admin, Manajer Gudang, and Staff Gudang --}}
                    @if (in_array(auth()->user()->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']))
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ __('Settings') }}
                        </a>
                    @endif

                    <div class="border-t border-slate-200 dark:border-slate-700 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const notifToggle = document.getElementById('navNotifToggle');
    const notifMenu   = document.getElementById('navNotifMenu');
    const notifDot    = document.getElementById('notifDot');

    if (localStorage.getItem('notifications_read') === 'true') {
        if (notifDot) notifDot.style.display = 'none';
    }

    if (notifToggle && notifMenu) {
        notifToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            notifMenu.classList.toggle('hidden');
            const userMenu = document.getElementById('navUserMenu');
            if (userMenu) userMenu.classList.add('hidden');
            if (!notifMenu.classList.contains('hidden') && notifDot) {
                setTimeout(() => {
                    notifDot.style.display = 'none';
                    localStorage.setItem('notifications_read', 'true');
                }, 500);
            }
        });

        document.addEventListener('click', (e) => {
            if (!notifMenu.contains(e.target) && !notifToggle.contains(e.target)) {
                notifMenu.classList.add('hidden');
            }
        });
    }

    const userToggle = document.getElementById('navUserToggle');
    const userMenu   = document.getElementById('navUserMenu');

    if (userToggle && userMenu) {
        userToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            if (notifMenu) notifMenu.classList.add('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target) && !userToggle.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (notifMenu) notifMenu.classList.add('hidden');
            if (userMenu) userMenu.classList.add('hidden');
        }
    });
});
</script>