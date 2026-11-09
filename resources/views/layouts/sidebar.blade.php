@php
$user = auth()->user();

$sections = [
    [
        'label' => __('Overview'),
        'items' => [
            ['route' => 'dashboard', 'label' => __('Dashboard'), 'icon' => 'home', 'roles' => ['Admin','Manajer Gudang','Staff Gudang']],
        ],
    ],
    [
        'label' => __('Inventory'),
        'items' => [
            ['route' => 'products.index',   'label' => __('Product'),      'icon' => 'box',       'roles' => ['Admin','Manajer Gudang']],
            ['route' => 'categories.index', 'label' => __('Category'),     'icon' => 'tag',       'roles' => ['Admin']],
            ['route' => 'stock.index',      'label' => __('Stock'),        'icon' => 'chart',     'roles' => ['Admin','Manajer Gudang','Staff Gudang']],
            ['route' => 'opname.index',     'label' => __('Stock Opname'), 'icon' => 'clipboard', 'roles' => ['Admin','Manajer Gudang','Staff Gudang']],
        ],
    ],
    [
        'label' => __('Partners'),
        'items' => [
            ['route' => 'suppliers.index', 'label' => __('Supplier'), 'icon' => 'building', 'roles' => ['Admin','Manajer Gudang']],
        ],
    ],
    [
        'label' => __('Insights'),
        'items' => [
            ['route' => 'reports.index',  'label' => __('Report'),   'icon' => 'document', 'roles' => ['Admin','Manajer Gudang']],
        ],
    ],
    [
        'label' => __('System'),
        'items' => [
            ['route' => 'users.index',     'label' => __('User'),     'icon' => 'users',    'roles' => ['Admin']],
            ['route' => 'settings.index',  'label' => __('Settings'), 'icon' => 'cog',      'roles' => ['Admin', 'Manajer Gudang', 'Staff Gudang']],
        ],
    ],
];

$icons = [
    'home'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    'box'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
    'tag'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
    'chart'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
    'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
    'building'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
    'users'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
    'document'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    'cog'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
];
@endphp

{{-- Backdrop for mobile --}}
<div id="sidebarBackdrop" class="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-sm hidden md:hidden"></div>

<aside id="sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen bg-slate-900 dark:bg-slate-900 border-r border-slate-800
              transition-transform -translate-x-full md:translate-x-0 flex flex-col">

    {{-- Brand --}}
    <div class="flex-shrink-0 h-16 flex items-center px-5 border-b border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center overflow-hidden shadow-sm group-hover:scale-105 transition-transform duration-200 flex-shrink-0 border border-slate-700">
                <img src="{{ asset('image/Seven_Inc_logo.jpg') }}" alt="{{ $appName }} Logo" class="w-full h-full object-contain p-0.5">
            </div>
            {{-- ✅ CHANGED: Uses $appName instead of hardcoded "Stockify" --}}
            <span class="text-white font-bold text-base tracking-tight truncate max-w-[120px]">{{ $appName }}</span>
            <span class="text-[10px] font-semibold text-blue-400 bg-blue-500/10 border border-blue-500/30 px-1.5 py-0.5 rounded flex-shrink-0">v1.0</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">

        @foreach ($sections as $section)
            @php
                $visibleItems = array_filter($section['items'], fn($i) => in_array($user->role, $i['roles'], true));
            @endphp

            @if (!empty($visibleItems))
                <div>
                    <p class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">
                        {{ $section['label'] }}
                    </p>
                    <ul class="space-y-0.5">
                        @foreach ($visibleItems as $item)
                            @php
                                $base = explode('.', $item['route'])[0];
                                $isActive = request()->routeIs($base . '*') || request()->routeIs($item['route']);
                            @endphp
                            <li>
                                <a href="{{ route($item['route']) }}"
                                   class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                          {{ $isActive
                                             ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'
                                             : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $icons[$item['icon']] ?? '' !!}
                                    </svg>
                                    <span>{{ $item['label'] }}</span>

                                    @if ($isActive)
                                        <span class="ms-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach

    </nav>

    {{-- Logout + User Card --}}
    <div class="flex-shrink-0 border-t border-slate-800">

        {{-- Logout button --}}
        <form method="POST" action="{{ route('logout') }}" class="p-3 pb-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>{{ __('Logout') }}</span>
            </button>
        </form>

        {{-- User card with dropdown --}}
        <div class="p-3 pt-0 relative" id="sidebarUserWrapper">
            <button type="button" id="sidebarUserToggle"
                    class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 transition">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-xs font-medium text-white truncate">{{ $user->name }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ $user->role }}</p>
                </div>
                <svg id="sidebarUserChevron" class="w-4 h-4 text-slate-500 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            {{-- Dropdown menu --}}
            <div id="sidebarUserMenu" class="hidden absolute bottom-full left-3 right-3 mb-2 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl py-2 z-50">
                <div class="px-3 py-2 border-b border-slate-700">
                    <p class="text-xs font-semibold text-white truncate">{{ $user->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ $user->username }} · {{ $user->role }}</p>
                </div>

                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ __('Profile') }}
                </a>

                @if (in_array(auth()->user()->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']))
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('Settings') }}
                    </a>
                @endif

                <div class="border-t border-slate-700 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-400 hover:bg-red-500/10 hover:text-red-300 transition">
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
</aside>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Mobile sidebar toggle
    const toggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
    }
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    }

    if (toggle) toggle.addEventListener('click', openSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    // User dropdown
    const userToggle  = document.getElementById('sidebarUserToggle');
    const userMenu    = document.getElementById('sidebarUserMenu');
    const userChevron = document.getElementById('sidebarUserChevron');

    if (userToggle && userMenu) {
        userToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = userMenu.classList.contains('hidden');
            userMenu.classList.toggle('hidden', !isHidden);
            if (userChevron) {
                userChevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });

        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target) && !userToggle.contains(e.target)) {
                userMenu.classList.add('hidden');
                if (userChevron) userChevron.style.transform = 'rotate(0deg)';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                userMenu.classList.add('hidden');
                if (userChevron) userChevron.style.transform = 'rotate(0deg)';
            }
        });
    }
});
</script>
