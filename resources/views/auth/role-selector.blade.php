<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Choose Login') }} - Stockify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-800 to-slate-900 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-4xl">
    <div class="text-center mb-10">
        <div class="text-6xl mb-3">📦</div>
        <h1 class="text-3xl font-bold text-white">Stockify</h1>
        <p class="text-gray-400 mt-2">{{ __('Item Stock Management App') }}</p>
        <p class="text-sm text-gray-500 mt-4">{{ __('Choose account type to continue') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('login.admin') }}"
           class="group bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transition transform hover:scale-105 hover:shadow-2xl border-t-4 border-blue-500">
            <div class="w-20 h-20 mx-auto rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-4xl mb-4 group-hover:scale-110 transition">
                🛡️
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Admin') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ __('Full access to all features') }}
            </p>
            <span class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                {{ __('Login as Admin') }}
            </span>
        </a>

        <a href="{{ route('login.manager') }}"
           class="group bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transition transform hover:scale-105 hover:shadow-2xl border-t-4 border-green-500">
            <div class="w-20 h-20 mx-auto rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-4xl mb-4 group-hover:scale-110 transition">
                👔
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Warehouse Manager') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ __('Manage products, stock, and suppliers') }}
            </p>
            <span class="inline-block bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                {{ __('Login as Manager') }}
            </span>
        </a>

        <a href="{{ route('login.staff') }}"
           class="group bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transition transform hover:scale-105 hover:shadow-2xl border-t-4 border-orange-500">
            <div class="w-20 h-20 mx-auto rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center text-4xl mb-4 group-hover:scale-110 transition">
                👷
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Warehouse Staff') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ __('Daily warehouse operations') }}
            </p>
            <span class="inline-block bg-orange-700 hover:bg-orange-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                {{ __('Login as Staff') }}
            </span>
        </a>

    </div>

    <p class="text-center text-xs text-gray-600 mt-8">
        © {{ date('Y') }} Stockify — {{ __('Item Stock Management System') }}
    </p>
</div>

</body>
</html>
