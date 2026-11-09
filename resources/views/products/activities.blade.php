@extends('layouts.app')
@section('title', __('Activity Report'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('reports.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('Report') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Activity') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Activity Report') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Complete user activity log, data changes, and access history') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            {{ __('Print') }}
        </button>
        <a href="{{ route('reports.export', ['period' => 30]) }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition shadow-lg shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            {{ __('Export') }}
        </a>
    </div>
</div>
@endsection

@section('content')

{{-- ============================================================
     SUMMARY STATS
============================================================ --}}
@php
    $totalActivities = $activities->total();
    $todayCount      = \App\Models\ActivityLog::whereDate('created_at', today())->count();
    $loginsToday     = \App\Models\ActivityLog::where('action', 'login')->whereDate('created_at', today())->count();
    $uniqueUsers     = \App\Models\ActivityLog::distinct('user_id')->count('user_id');
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Activities --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3">
                📊
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Activities') }}</p>
            <p class="text-3xl font-bold">{{ number_format($totalActivities) }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('All-time events') }}</p>
        </div>
    </div>

    {{-- Today --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📅</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Today') }}</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($todayCount) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Events today') }}</p>
    </div>

    {{-- Logins Today --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">🔐</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Logins Today') }}</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($loginsToday) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Successful sign-ins') }}</p>
    </div>

    {{-- Active Users --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">👥</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Active Users') }}</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($uniqueUsers) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('With activity logs') }}</p>
    </div>
</div>

{{-- ============================================================
     FILTERS
============================================================ --}}
<form method="GET" action="{{ route('reports.activities') }}"
      class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                {{ __('Action') }}
            </label>
            <select name="action"
                    class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                <option value="">{{ __('All Actions') }}</option>
                <option value="login"    @selected(request('action') === 'login')>🔐 {{ __('Login') }}</option>
                <option value="logout"   @selected(request('action') === 'logout')>🚪 {{ __('Logout') }}</option>
                <option value="created"  @selected(request('action') === 'created')>➕ {{ __('Created') }}</option>
                <option value="updated"  @selected(request('action') === 'updated')>✏️ {{ __('Updated') }}</option>
                <option value="deleted"  @selected(request('action') === 'deleted')>🗑️ {{ __('Deleted') }}</option>
                <option value="exported" @selected(request('action') === 'exported')>📤 {{ __('Exported') }}</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                {{ __('From Date') }}
            </label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
        </div>

        <div>
            <label class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                {{ __('To Date') }}
            </label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('Filter') }}
            </button>
            @if (request()->hasAny(['action', 'date_from', 'date_to']))
                <a href="{{ route('reports.activities') }}"
                   class="inline-flex items-center justify-center w-10 h-10 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-xl transition"
                   title="{{ __('Reset') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</form>

{{-- ============================================================
     ACTIVITY LOG
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ __('Activity Log') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                {{ $activities->total() }} {{ __('entries found') }}
                @if (request()->hasAny(['action', 'date_from', 'date_to']))
                    · <span class="text-blue-600 dark:text-blue-400">{{ __('filtered') }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Timeline List --}}
    <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
        @forelse ($activities as $activity)
            @php
                $actionConfig = match($activity->action) {
                    'login'    => ['icon' => '🔐', 'bg' => 'bg-green-100 dark:bg-green-500/20',  'text' => 'text-green-700 dark:text-green-400',   'label' => __('Login')],
                    'logout'   => ['icon' => '🚪', 'bg' => 'bg-slate-100 dark:bg-slate-800',     'text' => 'text-slate-700 dark:text-slate-400',   'label' => __('Logout')],
                    'created'  => ['icon' => '➕', 'bg' => 'bg-blue-100 dark:bg-blue-500/20',    'text' => 'text-blue-700 dark:text-blue-400',     'label' => __('Created')],
                    'updated'  => ['icon' => '✏️', 'bg' => 'bg-orange-100 dark:bg-orange-500/20','text' => 'text-orange-700 dark:text-orange-400', 'label' => __('Updated')],
                    'deleted'  => ['icon' => '🗑️', 'bg' => 'bg-red-100 dark:bg-red-500/20',      'text' => 'text-red-700 dark:text-red-400',       'label' => __('Deleted')],
                    'exported' => ['icon' => '📤', 'bg' => 'bg-purple-100 dark:bg-purple-500/20','text' => 'text-purple-700 dark:text-purple-400', 'label' => __('Exported')],
                    default    => ['icon' => '📋', 'bg' => 'bg-slate-100 dark:bg-slate-800',     'text' => 'text-slate-700 dark:text-slate-400',   'label' => ucfirst($activity->action ?? 'Activity')],
                };
            @endphp

            <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                <div class="flex items-start gap-4">

                    {{-- Action Icon --}}
                    <div class="w-11 h-11 rounded-xl {{ $actionConfig['bg'] }} flex items-center justify-center flex-shrink-0 text-lg group-hover:scale-110 transition-transform">
                        {{ $actionConfig['icon'] }}
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        {{-- Top Row: User + Action Badge + Time --}}
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $activity->user->name ?? __('Unknown User') }}
                            </span>

                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $actionConfig['bg'] }} {{ $actionConfig['text'] }}">
                                {{ $actionConfig['label'] }}
                            </span>

                            @if (auth()->user()->role !== 'Staff Gudang' && isset($activity->user->role))
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                    {{ $activity->user->role }}
                                </span>
                            @endif

                            <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Description --}}
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            {{ $activity->description }}
                        </p>

                        {{-- Metadata Row --}}
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-[11px] text-slate-400 dark:text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $activity->created_at->format('d M Y, H:i') }}
                            </span>

                            @if ($activity->ip_address)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    IP: {{ $activity->ip_address }}
                                </span>
                            @endif

                            @if ($activity->model_type ?? null)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    {{ class_basename($activity->model_type) }}
                                    @if ($activity->model_id ?? null)
                                        #{{ $activity->model_id }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                        📭
                    </div>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No activities found') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-sm">
                        @if (request()->hasAny(['action', 'date_from', 'date_to']))
                            {{ __('No activities match your filters. Try adjusting the date range or action type.') }}
                        @else
                            {{ __('Activity logs will appear here once users start interacting with the system.') }}
                        @endif
                    </p>
                    @if (request()->hasAny(['action', 'date_from', 'date_to']))
                        <a href="{{ route('reports.activities') }}"
                           class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('Reset Filters') }}
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($activities->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
            {{ $activities->links() }}
        </div>
    @endif
</div>

@endsection
