<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Forgot Password') }} - Stockify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4
             bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900">

    <div class="absolute inset-0 opacity-5 pointer-events-none"
         style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="relative w-full max-w-md">

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 md:p-10">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-3xl shadow-lg mb-4">
                    🔐
                </div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Forgot Password?') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Enter your email and we will send a reset link') }}
                </p>
            </div>

            {{-- Dev-mode reset link (only shown after successful submission) --}}
            @if (session('status') && session('reset_url'))
                <div class="p-4 mb-5 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">
                                {{ session('status') }}
                            </p>
                            <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                {{ __('Email is not configured. Use the link below to reset your password.') }}
                            </p>
                            <a href="{{ session('reset_url') }}"
                               class="inline-flex items-center gap-2 mt-3 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg transition">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                {{ __('Open Reset Password Link') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="flex items-start gap-3 p-4 mb-5 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 dark:text-red-300">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

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
                        <input type="email" name="email" id="email" required autofocus
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl
                                      focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500
                                      block w-full pl-10 p-3
                                      dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400">
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Use the email registered in the Stockify system') }}
                    </p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Send Reset Link') }}
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-6">
                {{ __('Remember your password?') }}
                <a href="{{ route('login') }}"
                   class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                    {{ __('Back to Login') }}
                </a>
            </p>

        </div>

        <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-6">
            © {{ date('Y') }} Stockify — {{ __('Item Stock Management System') }}
        </p>
    </div>

</body>
</html>
