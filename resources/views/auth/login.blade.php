<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stockify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-700 to-purple-700 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-6">
            <div class="text-5xl mb-2">📦</div>
            <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-400">Stockify</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Aplikasi Manajemen Stok Barang</p>
        </div>

        @if ($errors->any())
            <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400">
                <span class="font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="mb-4">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" name="password" id="password" required
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600">
                Masuk
            </button>
        </form>

        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-blue-500">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Demo Accounts:</p>
            <ul class="text-xs space-y-1 text-gray-600 dark:text-gray-400">
                <li><code class="bg-gray-200 dark:bg-gray-800 px-1.5 py-0.5 rounded">admin / admin123</code> → Admin</li>
                <li><code class="bg-gray-200 dark:bg-gray-800 px-1.5 py-0.5 rounded">manager / manager123</code> → Manajer Gudang</li>
                <li><code class="bg-gray-200 dark:bg-gray-800 px-1.5 py-0.5 rounded">staff / staff123</code> → Staff Gudang</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>