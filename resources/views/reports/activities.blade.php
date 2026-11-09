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
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Activity Report') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Activity Report') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Complete user activity log, data changes, and access history') }}</p>
    </div>
    <a href="{{ route('reports.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back') }}
    </a>
</div>
@endsection

@section('content')

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
@php
    $totalLogs    = \App\Models\ActivityLog::count();
    $todayLogs    = \App\Models\ActivityLog::whereDate('created_at', today())->count();
    $loginsToday  = \App\Models\ActivityLog::whereDate('created_at', today())->where('action', 'login')->count();
    $activeUsers  = \App\Models\ActivityLog::whereDate('created_at', '>=', now()->subDays(7))
                        ->distinct('user_id')->count('user_id');
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Activities --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                📋
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Activities') }}</p>
            <p class="text-3xl font-bold">{{ number_format($totalLogs) }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('All-time events') }}</p>
        </div>
    </div>

    {{-- Today --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📅</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Today') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($todayLogs) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Events today') }}</p>
    </div>

    {{-- Logins Today --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">🔐</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Logins Today') }}</p>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($loginsToday) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Successful sign-ins') }}</p>
    </div>

    {{-- Active Users --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">👥</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Active Users (7d)') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($activeUsers) }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Users with activity') }}</p>
    </div>
</div>

{{-- ============================================================
     FILTERS
============================================================ --}}
<form method="GET" action="{{ route('reports.activities') }}"
      class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 mb-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
        <select name="action"
                class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-slate-700 dark:text-slate-300">
            <option value="">{{ __('All Actions') }}</option>
            @foreach (['login', 'logout', 'login_failed', 'created', 'updated', 'deleted', 'exported'] as $act)
                <option value="{{ $act }}" @selected(request('action') === $act)>
                    {{ ucfirst(str_replace('_', ' ', $act)) }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-slate-700 dark:text-slate-300">

        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-slate-700 dark:text-slate-300">

        <div class="flex items-center gap-2">
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
     ACTIVITY LIST
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    {{-- Toolbar --}}
    <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ __('Activity Log') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                {{ $activities->total() }} {{ __('entries') }}
                @if (request()->hasAny(['action', 'date_from', 'date_to']))
                    · <span class="text-blue-600 dark:text-blue-400">{{ __('filtered') }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Activity rows --}}
    <div class="divide-y divide-slate-200 dark:divide-slate-800">
        @forelse ($activities as $log)
            @php
                $color = $log->action_color;
                $icon  = $log->action_icon;
            @endphp
            <div class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-lg bg-{{ $color }}-100 dark:bg-{{ $color }}-500/20 flex items-center justify-center text-lg flex-shrink-0">
                    {{ $icon }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-500/20 text-{{ $color }}-700 dark:text-{{ $color }}-400">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>

                        @if ($log->user)
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $log->user->name }}</span>
                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 uppercase">
                                {{ $log->user->role }}
                            </span>
                        @else
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400 italic">{{ __('System') }}</span>
                        @endif
                    </div>

                    <p class="text-sm text-slate-700 dark:text-slate-300 break-words">{{ $log->description ?? '—' }}</p>

                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $log->created_at->diffForHumans() }}
                        </span>
                        <span class="text-slate-300 dark:text-slate-600">·</span>
                        <span>{{ $log->created_at->format('d M Y, H:i') }}</span>

                        @if ($log->ip_address)
                            <span class="text-slate-300 dark:text-slate-600">·</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                IP: {{ $log->ip_address }}
                            </span>
                        @endif

                        @if ($log->model)
                            <span class="text-slate-300 dark:text-slate-600">·</span>
                            <span>{{ class_basename($log->model) }} #{{ $log->model_id }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                        📋
                    </div>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No activity logs yet') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        @if (request()->hasAny(['action', 'date_from', 'date_to']))
                            {{ __('No activities match your filters') }}
                        @else
                            {{ __('Actions will appear here as users interact with the system') }}
                        @endif
                    </p>
                    @if (request()->hasAny(['action', 'date_from', 'date_to']))
                        <a href="{{ route('reports.activities') }}"
                           class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                            {{ __('Reset Filters') }}
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($activities->hasPages())
        <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
            {{ $activities->links() }}
        </div>
    @endif
</div>

@endsection