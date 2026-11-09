@extends('layouts.app')
@section('title', __('Dashboard'))

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Dashboard') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Welcome back') }}, <span class="font-semibold text-blue-600 dark:text-blue-400">{{ auth()->user()->name }}</span>.
            {{ __("Here's a summary of today's warehouse operations") }}
        </p>
    </div>
    <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') }}
        </span>
    </div>
</div>
@endsection

@section('content')

{{-- ============================================================
     ROLE-SPECIFIC DASHBOARDS
============================================================ --}}
@if ($roleView === 'admin')
    {{-- ========== ADMIN DASHBOARD ========== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        {{-- Total Products (Hero) --}}
        <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        📦
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-white bg-white/20 backdrop-blur px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +12%
                    </span>
                </div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
                <p class="text-3xl font-bold mb-1">{{ number_format($totalProducts) }}</p>
                <p class="text-[11px] text-blue-100/80">{{ __('+2 this week') }}</p>
            </div>
        </div>

        {{-- Total Suppliers --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl">🏭</div>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-500/10 px-2 py-1 rounded-lg border border-green-100 dark:border-green-500/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    +2%
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Suppliers') }}</p>
            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-1">{{ number_format($totalSuppliers) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('5 Active') }}</p>
        </div>

        {{-- Total Users --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-500/10 flex items-center justify-center text-xl">👥</div>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-500/10 px-2 py-1 rounded-lg border border-green-100 dark:border-green-500/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    +5%
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Users') }}</p>
            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-1">{{ number_format($totalUsers) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('3 Active') }}</p>
        </div>

        {{-- Stock Value --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="flex items-start justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl">💰</div>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-500/10 px-2 py-1 rounded-lg border border-green-100 dark:border-green-500/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    +8%
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Stock Value') }}</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mb-1">Rp {{ number_format($totalStockValue / 1000000, 1) }}M</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('Total Inventory') }}</p>
        </div>
    </div>

    {{-- Warning banner --}}
    @if ($lowStockCount > 0)
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 mb-6 rounded-2xl bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-500/10 dark:to-amber-500/10 border border-orange-200 dark:border-orange-500/30 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-white dark:bg-orange-500/20 flex items-center justify-center text-2xl flex-shrink-0 shadow-sm">
                ⚠️
            </div>
            <div class="flex-1">
                <p class="font-semibold text-orange-900 dark:text-orange-200">
                    {{ __('Attention') }}: {{ $lowStockCount }} {{ __('products need restocking') }}
                </p>
                <p class="text-sm text-orange-700 dark:text-orange-400 mt-0.5">
                    {{ __('Some products are below minimum stock. Restock now to avoid stockouts.') }}
                </p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 px-4 py-2.5 rounded-xl transition shadow-lg shadow-orange-500/30">
                {{ __('Manage Stock') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif

@elseif ($roleView === 'manager')
    {{-- ========== MANAGER DASHBOARD ========== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        {{-- Total Products (Hero) --}}
        <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                    📦
                </div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
                <p class="text-3xl font-bold">{{ number_format($totalProducts) }}</p>
                <p class="text-[11px] text-blue-100/80 mt-1">{{ __('Active SKUs') }}</p>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">⚠️</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Low Stock') }}</p>
            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($lowStockCount) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Need Restock') }}</p>
        </div>

        {{-- Incoming Goods --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📥</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Incoming Goods') }}</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($inToday) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Today') }}</p>
        </div>

        {{-- Outgoing Goods --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-xl mb-3">📤</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Outgoing Goods') }}</p>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($outToday) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Today') }}</p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <a href="{{ route('stock.incoming') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-green-500 dark:hover:border-green-500 hover:shadow-lg transition group">
            <div class="w-14 h-14 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-3xl group-hover:scale-110 transition">📥</div>
            <div>
                <p class="font-semibold text-slate-900 dark:text-white text-sm group-hover:text-green-600 transition">{{ __('Record Incoming') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Receipt from supplier') }}</p>
            </div>
        </a>
        <a href="{{ route('stock.outgoing') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-red-500 dark:hover:border-red-500 hover:shadow-lg transition group">
            <div class="w-14 h-14 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-3xl group-hover:scale-110 transition">📤</div>
            <div>
                <p class="font-semibold text-slate-900 dark:text-white text-sm group-hover:text-red-600 transition">{{ __('Record Outgoing') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Dispatch & sales') }}</p>
            </div>
        </a>
        <a href="{{ route('opname.index') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg transition group">
            <div class="w-14 h-14 rounded-xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-3xl group-hover:scale-110 transition">📋</div>
            <div>
                <p class="font-semibold text-slate-900 dark:text-white text-sm group-hover:text-blue-600 transition">{{ __('Stock Opname') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Verify physical stock') }}</p>
            </div>
        </a>
    </div>

@else
    {{-- ========== STAFF DASHBOARD ========== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        {{-- Today's Activity (Hero) --}}
        <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                    📊
                </div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __("Today's Activity") }}</p>
                <p class="text-3xl font-bold">{{ number_format($myTransactions) }}</p>
                <p class="text-[11px] text-blue-100/80 mt-1">{{ __('transactions recorded') }}</p>
            </div>
        </div>

        {{-- Incoming Goods --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📥</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Incoming Goods') }}</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($myIncoming) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('units received') }}</p>
        </div>

        {{-- Outgoing Goods --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">📤</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Outgoing Goods') }}</p>
            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($myOutgoing) }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('units sent') }}</p>
        </div>
    </div>

    {{-- My tasks --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 mb-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('My Tasks Today') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('List of tasks to complete') }}</p>
            </div>
            <span class="text-xs font-semibold bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 px-3 py-1.5 rounded-full">
                {{ count($pendingTasks) }} {{ __('tasks') }}
            </span>
        </div>
        <div class="space-y-3">
            @foreach ($pendingTasks as $task)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:border-slate-300 dark:hover:border-slate-600 transition">
                    <div class="w-12 h-12 rounded-xl bg-{{ $task['color'] }}-100 dark:bg-{{ $task['color'] }}-500/10 flex items-center justify-center text-xl flex-shrink-0">
                        {{ $task['icon'] }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $task['label'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $task['count'] }} {{ __('items pending') }}</p>
                    </div>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-{{ $task['color'] }}-100 dark:bg-{{ $task['color'] }}-500/20 text-{{ $task['color'] }}-700 dark:text-{{ $task['color'] }}-400 text-sm font-bold">
                        {{ $task['count'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- ============================================================
     CHART + RECENT ACTIVITY
============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('Stock Activity') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Incoming and outgoing trends — last 7 days') }}</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500"></span> {{ __('Incoming') }}</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span> {{ __('Outgoing') }}</span>
            </div>
        </div>
        <div id="stock-chart" 
             class="relative h-72 w-full"
             data-labels='@json($chartLabels)'
             data-incoming='@json($chartIncoming)'
             data-outgoing='@json($chartOutgoing)'></div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                    {{ __('Recent Activity') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Latest transactions') }}</p>
            </div>
            <a href="{{ route('reports.activities.all') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">{{ __('View all') }} →</a>
        </div>
        <div class="space-y-4 overflow-y-auto flex-1 pr-2">
            @forelse (($roleView === 'staff' ? $myRecentActivity : $recentActivities) as $a)
                <div class="flex items-start gap-3 group">
                    {{-- Type Badge --}}
                    <div class="flex-shrink-0 mt-1">
                        @if($a->type === 'in')
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 text-xs font-bold">
                                IN
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 text-xs font-bold">
                                OUT
                            </span>
                        @endif
                    </div>
                    
                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                            {{ $a->product->name ?? __('Product deleted') }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-2">
                            <span class="font-medium {{ $a->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $a->type === 'in' ? '+' : '-' }}{{ $a->quantity }} {{ __('units') }}
                            </span>
                            <span class="text-slate-300 dark:text-slate-600">•</span>
                            <span>{{ $a->user->name ?? __('Unknown') }}</span>
                        </p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ $a->transaction_date->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">📭</div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('No activity yet') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Recent transactions will appear here.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ============================================================
     LOW STOCK TABLE (Admin + Manager)
============================================================ --}}
@if ($roleView !== 'staff' && $lowStock->count() > 0)
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                {{ __('Low Stock Alert') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Products that need restocking') }}</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">{{ __('Manage all') }} →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">{{ __('Product') }}</th>
                    <th class="px-6 py-3 text-left font-semibold">{{ __('Category') }}</th>
                    <th class="px-6 py-3 text-right font-semibold">{{ __('Stock') }}</th>
                    <th class="px-6 py-3 text-right font-semibold">{{ __('Min. Stock') }}</th>
                    <th class="px-6 py-3 text-center font-semibold">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($lowStock as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-slate-900 dark:text-white">{{ $p->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $p->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $p->category->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-red-600 dark:text-red-400">{{ $p->stock }}</span>
                        </td>
                        <td class="px-6 py-4 text-right text-slate-600 dark:text-slate-400">{{ $p->min_stock }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                {{ __('Need Restock') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ============================================================
     STOCK ACTIVITY CHART SCRIPT
============================================================ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.getElementById('stock-chart');
    if (!chartEl) return;

    const labels = JSON.parse(chartEl.dataset.labels || '[]');
    const incoming = JSON.parse(chartEl.dataset.incoming || '[]');
    const outgoing = JSON.parse(chartEl.dataset.outgoing || '[]');

    const ctx = document.createElement('canvas');
    chartEl.appendChild(ctx);

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(148,163,184,0.15)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Incoming',
                    data: incoming,
                    backgroundColor: 'rgba(34, 197, 94, 0.85)',
                    hoverBackgroundColor: 'rgba(34, 197, 94, 1)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8,
                },
                {
                    label: 'Outgoing',
                    data: outgoing,
                    backgroundColor: 'rgba(239, 68, 68, 0.85)',
                    hoverBackgroundColor: 'rgba(239, 68, 68, 1)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    cornerRadius: 8,
                    displayColors: true,
                    boxPadding: 4,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: { size: 11 },
                        color: textColor,
                    },
                    grid: {
                        color: gridColor,
                        drawBorder: false,
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        font: { size: 11 },
                        color: textColor,
                    }
                }
            }
        }
    });
});
</script>

@endsection