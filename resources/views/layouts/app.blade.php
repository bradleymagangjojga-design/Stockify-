<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ✅ CHANGED: Uses $appName instead of hardcoded "Stockify" --}}
    <title>@yield('title', 'Dashboard') — {{ $appName }}</title>

    {{-- ============================================================
         GLOBAL SETTINGS LOADER (Dark Mode + Primary Color + Density)
    ============================================================ --}}
    @php
        if (auth()->check()) {
            $appDarkMode      = \App\Models\Setting::get('dark_mode', 'false') === 'true';
            $appLayoutDensity = \App\Models\Setting::get('layout_density', 'default');

            $userColor        = auth()->user()->primary_color ?? null;
            $appPrimaryColor  = $userColor ?: \App\Models\Setting::get('primary_color', 'blue');
        } else {
            $appDarkMode      = false;
            $appPrimaryColor  = 'blue';
            $appLayoutDensity = 'default';
        }

        $colorMap = [
            'blue' => [
                '50' => '#eff6ff', '100' => '#dbeafe', '200' => '#bfdbfe', '400' => '#60a5fa',
                '500' => '#3b82f6', '600' => '#2563eb', '700' => '#1d4ed8',
                '800' => '#1e40af', '900' => '#1e3a8a',
            ],
            'green' => [
                '50' => '#ecfdf5', '100' => '#d1fae5', '200' => '#a7f3d0', '400' => '#34d399',
                '500' => '#10b981', '600' => '#059669', '700' => '#047857',
                '800' => '#065f46', '900' => '#064e3b',
            ],
            'purple' => [
                '50' => '#faf5ff', '100' => '#f3e8ff', '200' => '#e9d5ff', '400' => '#c084fc',
                '500' => '#a855f7', '600' => '#9333ea', '700' => '#7e22ce',
                '800' => '#6b21a8', '900' => '#581c87',
            ],
            'orange' => [
                '50' => '#fff7ed', '100' => '#ffedd5', '200' => '#fed7aa', '400' => '#fb923c',
                '500' => '#f97316', '600' => '#ea580c', '700' => '#c2410c',
                '800' => '#9a3412', '900' => '#7c2d12',
            ],
            'pink' => [
                '50' => '#fdf2f8', '100' => '#fce7f3', '200' => '#fbcfe8', '400' => '#f472b6',
                '500' => '#ec4899', '600' => '#db2777', '700' => '#be185d',
                '800' => '#9d174d', '900' => '#831843',
            ],
            'slate' => [
                '50' => '#f8fafc', '100' => '#f1f5f9', '200' => '#e2e8f0', '400' => '#94a3b8',
                '500' => '#64748b', '600' => '#475569', '700' => '#334155',
                '800' => '#1e293b', '900' => '#0f172a',
            ],
        ];

        $selectedColor = $colorMap[$appPrimaryColor] ?? $colorMap['blue'];
    @endphp

    {{-- ⚡ Apply theme IMMEDIATELY (before render) to prevent FOUC --}}
    <script>
        (function() {
            const storedTheme = localStorage.getItem('color-theme');
            const dbDarkMode = @json($appDarkMode);

            if (storedTheme === 'dark' || (!storedTheme && dbDarkMode)) {
                document.documentElement.classList.add('dark');
            } else if (storedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }

            const root = document.documentElement;
            root.style.setProperty('--color-primary-50',  @json($selectedColor['50']));
            root.style.setProperty('--color-primary-100', @json($selectedColor['100']));
            root.style.setProperty('--color-primary-200', @json($selectedColor['200']));
            root.style.setProperty('--color-primary-400', @json($selectedColor['400']));
            root.style.setProperty('--color-primary-500', @json($selectedColor['500']));
            root.style.setProperty('--color-primary-600', @json($selectedColor['600']));
            root.style.setProperty('--color-primary-700', @json($selectedColor['700']));
            root.style.setProperty('--color-primary-800', @json($selectedColor['800']));
            root.style.setProperty('--color-primary-900', @json($selectedColor['900']));
        })();
    </script>

    {{-- ============================================================
         PRIMARY COLOR CSS VARIABLES + TAILWIND BLUE OVERRIDES
    ============================================================ --}}
    <style>
        :root {
            --color-primary-50:  {{ $selectedColor['50'] }};
            --color-primary-100: {{ $selectedColor['100'] }};
            --color-primary-200: {{ $selectedColor['200'] }};
            --color-primary-400: {{ $selectedColor['400'] }};
            --color-primary-500: {{ $selectedColor['500'] }};
            --color-primary-600: {{ $selectedColor['600'] }};
            --color-primary-700: {{ $selectedColor['700'] }};
            --color-primary-800: {{ $selectedColor['800'] }};
            --color-primary-900: {{ $selectedColor['900'] }};
        }

        /* ---------- Backgrounds ---------- */
        .bg-blue-50   { background-color: var(--color-primary-50)  !important; }
        .bg-blue-100  { background-color: var(--color-primary-100) !important; }
        .bg-blue-200  { background-color: var(--color-primary-200) !important; }
        .bg-blue-400  { background-color: var(--color-primary-400) !important; }
        .bg-blue-500  { background-color: var(--color-primary-500) !important; }
        .bg-blue-600  { background-color: var(--color-primary-600) !important; }
        .bg-blue-700  { background-color: var(--color-primary-700) !important; }
        .bg-blue-800  { background-color: var(--color-primary-800) !important; }
        .bg-blue-900  { background-color: var(--color-primary-900) !important; }

        .hover\:bg-blue-50:hover  { background-color: var(--color-primary-50)  !important; }
        .hover\:bg-blue-100:hover { background-color: var(--color-primary-100) !important; }
        .hover\:bg-blue-500:hover { background-color: var(--color-primary-500) !important; }
        .hover\:bg-blue-600:hover { background-color: var(--color-primary-600) !important; }
        .hover\:bg-blue-700:hover { background-color: var(--color-primary-700) !important; }

        /* ---------- Text ---------- */
        .text-blue-400 { color: var(--color-primary-400) !important; }
        .text-blue-500 { color: var(--color-primary-500) !important; }
        .text-blue-600 { color: var(--color-primary-600) !important; }
        .text-blue-700 { color: var(--color-primary-700) !important; }
        .text-blue-800 { color: var(--color-primary-800) !important; }
        .text-blue-900 { color: var(--color-primary-900) !important; }

        .hover\:text-blue-600:hover { color: var(--color-primary-600) !important; }
        .hover\:text-blue-700:hover { color: var(--color-primary-700) !important; }

        /* ---------- Borders ---------- */
        .border-blue-200 { border-color: var(--color-primary-200) !important; }
        .border-blue-500 { border-color: var(--color-primary-500) !important; }
        .border-blue-600 { border-color: var(--color-primary-600) !important; }

        .hover\:border-blue-500:hover { border-color: var(--color-primary-500) !important; }

        /* ---------- Rings ---------- */
        .ring-blue-500                  { --tw-ring-color: var(--color-primary-500) !important; }
        .focus\:ring-blue-300:focus     { --tw-ring-color: var(--color-primary-200) !important; }
        .focus\:ring-blue-500:focus     { --tw-ring-color: var(--color-primary-500) !important; }
        .focus\:ring-blue-500\/50:focus { --tw-ring-color: color-mix(in srgb, var(--color-primary-500) 50%, transparent) !important; }
        .focus\:border-blue-500:focus   { border-color: var(--color-primary-500) !important; }

        /* ---------- Opacity variants ---------- */
        .bg-blue-500\/10 { background-color: color-mix(in srgb, var(--color-primary-500) 10%, transparent) !important; }
        .bg-blue-500\/20 { background-color: color-mix(in srgb, var(--color-primary-500) 20%, transparent) !important; }
        .bg-blue-600\/10 { background-color: color-mix(in srgb, var(--color-primary-600) 10%, transparent) !important; }
        .bg-blue-600\/20 { background-color: color-mix(in srgb, var(--color-primary-600) 20%, transparent) !important; }
        .bg-blue-600\/30 { background-color: color-mix(in srgb, var(--color-primary-600) 30%, transparent) !important; }
        .bg-blue-950\/50 { background-color: color-mix(in srgb, var(--color-primary-900) 50%, transparent) !important; }

        /* ---------- Gradients ---------- */
        .from-blue-500 { --tw-gradient-from: var(--color-primary-500) !important; --tw-gradient-to: rgb(59 130 246 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-blue-600 { --tw-gradient-from: var(--color-primary-600) !important; --tw-gradient-to: rgb(37 99 235 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .to-blue-500   { --tw-gradient-to:   var(--color-primary-500) !important; }
        .to-blue-600   { --tw-gradient-to:   var(--color-primary-600) !important; }
        .to-blue-700   { --tw-gradient-to:   var(--color-primary-700) !important; }
        .via-blue-600  { --tw-gradient-via:  var(--color-primary-600) !important; }

        /* ---------- Shadows ---------- */
        .shadow-blue-500\/20 { --tw-shadow-color: color-mix(in srgb, var(--color-primary-500) 20%, transparent) !important; }
        .shadow-blue-500\/30 { --tw-shadow-color: color-mix(in srgb, var(--color-primary-500) 30%, transparent) !important; }
        .shadow-blue-500\/50 { --tw-shadow-color: color-mix(in srgb, var(--color-primary-500) 50%, transparent) !important; }

        /* ---------- Divider ---------- */
        .divide-blue-500 > :not([hidden]) ~ :not([hidden]) {
            border-color: var(--color-primary-500) !important;
        }

        /* ---------- Helper classes ---------- */
        .btn-primary        { background-color: var(--color-primary-600) !important; }
        .btn-primary:hover  { background-color: var(--color-primary-700) !important; }
        .text-primary       { color: var(--color-primary-600) !important; }
        .border-primary     { border-color: var(--color-primary-600) !important; }
        .bg-primary         { background-color: var(--color-primary-600) !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Global Search URL for JavaScript --}}
    <script>
        window.stockifySearchUrl = "{{ route('api.search') }}";
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-sans antialiased">

<div class="min-h-screen">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Main Content --}}
    <main class="md:ml-64 pt-16 min-h-screen">

        {{-- Page Header --}}
        @hasSection('page-header')
            <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    @yield('page-header')
                </div>
            </div>
        @endif

        {{-- Flash Alerts --}}
        <div class="px-4 sm:px-6 lg:px-8 {{ session('success') || session('error') || $errors->any() ? 'pt-6' : '' }}">
            @if (session('success'))
                <div class="alert-auto-dismiss flex items-start gap-3 p-4 mb-4 text-sm rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-800 dark:text-green-300">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold">{{ __('Success') }}</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-auto-dismiss flex items-start gap-3 p-4 mb-4 text-sm rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-800 dark:text-red-300">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold">{{ __('An Error Occurred') }}</p>
                        <p class="text-xs mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-auto-dismiss flex items-start gap-3 p-4 mb-4 text-sm rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-800 dark:text-red-300">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold">{{ __('Validation Failed') }}</p>
                        <p class="text-xs mt-0.5">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Body --}}
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="px-4 sm:px-6 lg:px-8 py-6 mt-12 border-t border-slate-200 dark:border-slate-800">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-500">
                {{-- ✅ CHANGED: Uses $appName instead of hardcoded "Stockify" --}}
                <p>© {{ date('Y') }} {{ $appName }} — {{ __('Item Stock Management System') }}</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-slate-300 transition">{{ __('Privacy') }}</a>
                    <a href="#" class="hover:text-slate-300 transition">{{ __('Terms') }}</a>
                    <a href="#" class="hover:text-slate-300 transition">{{ __('Support') }}</a>
                    <span class="text-slate-700">·</span>
                    <span>v1.0.0</span>
                </div>
            </div>
        </footer>

    </main>

</div>

{{-- Toast container --}}
<div id="toastContainer" class="fixed top-20 right-4 z-50 space-y-2"></div>

{{-- ⚡ Render pushed scripts from @push('scripts') in any child view --}}
@stack('scripts')

{{-- Global helpers --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-dismiss').forEach(el => el.remove());
    }, 4000);
});

window.showToast = function(message, type = 'success') {
    const colors = {
        success: 'bg-green-600',
        error:   'bg-red-600',
        info:    'bg-blue-600',
    };
    const toast = document.createElement('div');
    toast.className = `fixed top-20 right-4 z-[9999] flex items-center gap-3 ${colors[type] || colors.success} text-white px-5 py-3 rounded-xl shadow-2xl transition-all duration-300 opacity-0 translate-y-[-10px]`;
    toast.innerHTML = `
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('opacity-0', 'translate-y-[-10px]'));
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-[-10px]');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};
</script>

</body>
</html>
