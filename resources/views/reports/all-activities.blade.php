@extends('layouts.app')
@section('title', __('All Activities'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('All Activities') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('All Activities') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ $user->role === 'Staff Gudang'
                ? __('Your personal activity history')
                : __('Complete system activity history') }}
        </p>
    </div>
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 px-4 py-2 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back to Dashboard') }}
    </a>
</div>
@endsection

@section('content')

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
    <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ $user->role === 'Staff Gudang' ? __('My Activity Log') : __('System Activity Log') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                {{ __('Total:') }} {{ $activities->total() }} {{ __('entries') }}
            </p>
        </div>
    </div>

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

            <div class="p-5 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                <div class="flex items-start gap-4">
                    {{-- Action Icon --}}
                    <div class="w-10 h-10 rounded-xl {{ $actionConfig['bg'] }} flex items-center justify-center flex-shrink-0 text-lg">
                        {{ $actionConfig['icon'] }}
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $activity->user->name ?? __('Unknown User') }}
                            </span>

                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $actionConfig['bg'] }} {{ $actionConfig['text'] }}">
                                {{ $actionConfig['label'] }}
                            </span>

                            @if ($user->role !== 'Staff Gudang' && isset($activity->user->role))
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                    {{ $activity->user->role }}
                                </span>
                            @endif

                            <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            {{ $activity->description }}
                        </p>

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
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('There are no activity logs to display right now.') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    @if ($activities->hasPages())
        <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
            {{ $activities->links() }}
        </div>
    @endif
</div>

@endsection