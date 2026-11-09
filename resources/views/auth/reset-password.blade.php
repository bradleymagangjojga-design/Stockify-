<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Reset Password') }} - Stockify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4
             bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900">

    <div class="absolute inset-0 opacity-5 pointer-events-none"
         style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="relative w-full max-w-md">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 md:p-10">

            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-3xl shadow-lg mb-4">
                    🔓
                </div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Reset Password') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Create a new password for your account') }}
                </p>
            </div>

            @if ($errors->any())
                <div class="flex items-start gap-3 p-4 mb-5 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 dark:text-red-300">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email', $email) }}"
                               readonly
                               class="bg-slate-100 border border-slate-300 text-slate-600 text-sm rounded-xl
                                      block w-full pl-10 p-3 cursor-not-allowed
                                      dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300">
                    </div>
                </div>

                {{-- New Password with View Icon --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('New Password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required autofocus
                               placeholder="{{ __('Minimum 6 characters') }}"
                               class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl
                                      focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500
                                      block w-full pl-10 pr-12 p-3
                                      dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400">

                        <button type="button"
                                onclick="togglePassword('password', 'eyeIcon-password', 'eyeOffIcon-password')"
                                style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 14px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; z-index: 20;"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition"
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

                {{-- Confirm Password with View Icon --}}
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Confirm Password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="{{ __('Repeat new password') }}"
                               class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl
                                      focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500
                                      block w-full pl-10 pr-12 p-3
                                      dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400">

                        <button type="button"
                                onclick="togglePassword('password_confirmation', 'eyeIcon-confirm', 'eyeOffIcon-confirm')"
                                style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 14px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; z-index: 20;"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition"
                                title="{{ __('Show/Hide Password') }}">
                            <svg id="eyeIcon-confirm" class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon-confirm" class="w-5 h-5 hidden pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               text-white bg-gradient-to-r from-blue-600 to-blue-700
                               hover:from-blue-700 hover:to-blue-800
                               focus:ring-4 focus:ring-blue-300
                               font-semibold rounded-xl text-sm px-5 py-3.5
                               shadow-lg shadow-blue-500/30
                               transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    {{ __('Reset Password') }}
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-6">
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                    ← {{ __('Back to Login') }}
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-6">
            © {{ date('Y') }} Stockify — {{ __('Item Stock Management System') }}
        </p>
    </div>

    <script>
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
    </script>

</body>
</html>
