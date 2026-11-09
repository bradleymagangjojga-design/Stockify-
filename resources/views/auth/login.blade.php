<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4
             bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900">

    {{-- Subtle background pattern --}}
    <div class="absolute inset-0 opacity-5 pointer-events-none"
         style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="relative w-full max-w-md">

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 md:p-10">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-lg mb-4 overflow-hidden border border-slate-200 dark:border-slate-700 p-1">
                    <img src="{{ asset('image/Seven_Inc_logo.jpg') }}" alt="{{ $appName }} Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Welcome Back') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Sign in to your') }} {{ $appName }} {{ __('account') }}</p>
            </div>

            {{-- Flash success --}}
            @if (session('success'))
                <div class="alert-auto-dismiss flex items-start gap-3 p-4 mb-5 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 dark:text-green-300">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Role Tabs --}}
            <div class="grid grid-cols-3 gap-2 p-1 bg-gray-100 dark:bg-gray-700 rounded-xl mb-6">
                <button type="button" data-role="Admin" class="role-tab flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm font-medium transition">
                    <span>🛡️</span><span class="hidden sm:inline">{{ __('Admin') }}</span>
                </button>
                <button type="button" data-role="Manajer Gudang" class="role-tab flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm font-medium transition">
                    <span>👔</span><span class="hidden sm:inline">{{ __('Manager') }}</span>
                </button>
                <button type="button" data-role="Staff Gudang" class="role-tab flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm font-medium transition">
                    <span>👷</span><span class="hidden sm:inline">{{ __('Staff') }}</span>
                </button>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="alert-auto-dismiss flex items-center p-4 mb-5 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-gray-700 dark:text-red-400 border border-red-200 dark:border-red-900">
                    <svg class="w-5 h-5 me-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'Admin') }}">

                {{-- Username --}}
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Username') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                        </div>
                        <input type="text" name="username" id="username"
                               value="{{ old('username') }}"
                               required autofocus
                               placeholder="{{ __('Enter username') }}"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      block w-full pl-10 p-3
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                      dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                {{-- Password with View Icon --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password"
                               required
                               placeholder="••••••••"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      block w-full pl-10 pr-12 p-3
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                      dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        {{-- View Password Toggle --}}
                        <button type="button"
                                onclick="togglePassword('password', 'eyeIcon-password', 'eyeOffIcon-password')"
                                style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 14px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; z-index: 20;"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition"
                                title="{{ __('Show/Hide Password') }}">
                            <svg id="eyeIcon-password" class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon-password" class="w-5 h-5 hidden pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700 dark:border-gray-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn"
                        class="w-full flex items-center justify-center gap-2 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 font-semibold rounded-xl text-sm px-5 py-3.5 shadow-lg shadow-blue-500/30 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span id="submitText">{{ __('Login as Admin') }}</span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                <span class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider whitespace-nowrap">{{ __('OR LOGIN WITH') }}</span>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
            </div>

            {{-- Google Sign-In (POPUP) --}}
            <button type="button" id="googleLoginBtn"
                    class="w-full flex items-center justify-center gap-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 font-semibold rounded-xl text-sm px-5 py-3.5 shadow-sm transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>{{ __('Continue with Google') }}</span>
            </button>

            {{-- Create Account Link --}}
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                    {{ __('Create one now') }}
                </a>
            </p>

            {{-- Demo hint --}}
            <div class="mt-6 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                    <span class="inline-block bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded text-gray-700 dark:text-gray-300 font-medium mr-1">{{ __('Default') }}:</span>
                    <code id="demoHint" class="text-gray-600 dark:text-gray-400">admin / admin123</code>
                </p>
            </div>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-6">
            © {{ date('Y') }} {{ $appName }} — {{ __('Item Stock Management System') }}
        </p>
    </div>

    {{-- ============================================================
         SCRIPT: Password toggle + role tabs + Google popup
    ============================================================ --}}
    <script>
        // ---- Password show/hide toggle ----
        function togglePassword(inputId, eyeIconId, eyeOffIconId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeIconId);
            const eyeOffIcon = document.getElementById(eyeOffIconId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon?.classList.add('hidden');
                eyeOffIcon?.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon?.classList.remove('hidden');
                eyeOffIcon?.classList.add('hidden');
            }
        }

        // ---- Role config with translations ----
        window.loginTranslations = {
            roles: {
                'Admin': {
                    label: @json(__('Login as Admin')),
                    demo: 'admin / admin123',
                    username: 'admin',
                    password: 'admin123',
                    colors: {
                        active: 'bg-blue-600 text-white shadow-md',
                        btn: 'from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-blue-300',
                        shadow: 'shadow-blue-500/30'
                    }
                },
                'Manajer Gudang': {
                    label: @json(__('Login as Manager')),
                    demo: 'manager / manager123',
                    username: 'manager',
                    password: 'manager123',
                    colors: {
                        active: 'bg-green-600 text-white shadow-md',
                        btn: 'from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:ring-green-300',
                        shadow: 'shadow-green-500/30'
                    }
                },
                'Staff Gudang': {
                    label: @json(__('Login as Staff')),
                    demo: 'staff / staff123',
                    username: 'staff',
                    password: 'staff123',
                    colors: {
                        active: 'bg-orange-600 text-white shadow-md',
                        btn: 'from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 focus:ring-orange-300',
                        shadow: 'shadow-orange-500/30'
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const tabs          = document.querySelectorAll('.role-tab');
            const roleInput     = document.getElementById('roleInput');
            const submitText    = document.getElementById('submitText');
            const submitBtn     = document.getElementById('submitBtn');
            const demoHint      = document.getElementById('demoHint');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');

            const roleConfig = window.loginTranslations.roles;
            const demoUsernames = Object.values(roleConfig).map(c => c.username);

            let passwordManuallyEdited = false;

            passwordInput.addEventListener('input', () => {
                passwordManuallyEdited = true;
            });

            function setActiveRole(role) {
                const cfg = roleConfig[role];
                if (!cfg) return;

                tabs.forEach(tab => {
                    const isActive = tab.dataset.role === role;
                    tab.classList.remove('bg-blue-600', 'bg-green-600', 'bg-orange-600', 'text-white', 'shadow-md');
                    tab.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-600');

                    if (isActive) {
                        tab.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-600');
                        cfg.colors.active.split(' ').forEach(c => tab.classList.add(c));
                    }
                });

                roleInput.value = role;
                submitText.textContent = cfg.label;
                submitBtn.className = `w-full flex items-center justify-center gap-2 text-white bg-gradient-to-r ${cfg.colors.btn} focus:ring-4 font-semibold rounded-xl text-sm px-5 py-3.5 shadow-lg ${cfg.colors.shadow} transition-all duration-200`;
                demoHint.textContent = cfg.demo;

                const currentUsername = usernameInput.value.trim();
                if (currentUsername === '' || demoUsernames.includes(currentUsername)) {
                    usernameInput.value = cfg.username;
                }

                if (!passwordManuallyEdited) {
                    passwordInput.value = cfg.password;
                }

                setTimeout(() => {
                    usernameInput.focus();
                    usernameInput.select();
                }, 50);
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => setActiveRole(tab.dataset.role));
            });

            const initialRole = roleInput.value || 'Admin';
            setActiveRole(initialRole);

            // =========================================================
            //  GOOGLE POPUP LOGIN
            // =========================================================
            const googleBtn = document.getElementById('googleLoginBtn');
            const googlePopupUrl = '{{ route('auth.google') }}?popup=1';
            const googlePlainUrl = '{{ route('auth.google') }}';
            const dashboardUrl   = '{{ route('dashboard') }}';

            googleBtn?.addEventListener('click', () => {
                const w = 520, h = 650;
                const left = Math.max(0, (window.screen.width  - w) / 2 + (window.screenX || 0));
                const top  = Math.max(0, (window.screen.height - h) / 2 + (window.screenY || 0));

                const features = [
                    'width='  + w,
                    'height=' + h,
                    'left='   + left,
                    'top='    + top,
                    'toolbar=no', 'menubar=no', 'location=no',
                    'status=no', 'resizable=yes', 'scrollbars=yes'
                ].join(',');

                const popup = window.open(googlePopupUrl, 'google_login', features);

                // Popup blocked → fall back to full-page redirect
                if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                    window.location.href = googlePlainUrl;
                    return;
                }

                popup.focus();
            });

            // Receive result from the popup
            window.addEventListener('message', (event) => {
                if (event.origin !== window.location.origin) return;
                const data = event.data || {};
                if (data.type !== 'google-auth') return;

                if (data.status === 'success') {
                    window.location.href = data.redirect || dashboardUrl;
                } else {
                    alert('{{ __('Google sign-in failed') }}: ' + (data.message || '{{ __('Unknown error') }}'));
                }
            });
        });
    </script>

</body>
</html>