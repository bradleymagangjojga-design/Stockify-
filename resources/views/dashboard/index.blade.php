@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Selamat datang, {{ auth()->user()->name }}!
    </p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Total Produk</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalProducts }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-2xl">📦</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Total Supplier</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalSuppliers }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center text-2xl">🏭</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Stok Menipis</p>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $lowStock->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900 flex items-center justify-center text-2xl">⚠️</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Total Pengguna</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-2xl">👥</div>
        </div>
    </div>
</div>

{{-- Today's Transactions + Recent Activity --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Transaksi Hari Ini</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4">
                <p class="text-xs text-green-700 dark:text-green-300 font-medium">⬇️ Barang Masuk</p>
                <p class="text-2xl font-bold text-green-800 dark:text-green-200 mt-1">{{ $inToday }} unit</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4">
                <p class="text-xs text-red-700 dark:text-red-300 font-medium">⬆️ Barang Keluar</p>
                <p class="text-2xl font-bold text-red-800 dark:text-red-200 mt-1">{{ $outToday }} unit</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
        <ul class="space-y-3">
            @forelse ($recentActivities as $a)
                <li class="flex items-start gap-3">
                    <span class="mt-1.5 w-2.5 h-2.5 rounded-full {{ $a->type === 'in' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900 dark:text-white">
                            <strong>{{ $a->product->name ?? 'Produk' }}</strong>
                            <span class="{{ $a->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                ({{ $a->type === 'in' ? '+' : '-' }}{{ $a->quantity }})
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $a->transaction_date->format('d M Y') }} • {{ $a->user->name ?? 'Unknown' }}
                        </p>
                    </div>
                </li>
            @empty
                <li class="text-sm text-gray-500">Belum ada aktivitas.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- Low Stock Warning --}}
@if ($lowStock->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">⚠️ Peringatan Stok Menipis</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Stok</th>
                    <th class="px-6 py-3">Min. Stok</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lowStock as $p)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $p->name }}</td>
                        <td class="px-6 py-4">{{ $p->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400">{{ $p->stock }}</td>
                        <td class="px-6 py-4">{{ $p->min_stock }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                Perlu Restock
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection