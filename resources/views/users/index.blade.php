@extends('layouts.app')
@section('title', __('Users'))

@section('content')

{{-- ============================================================
     HEADER — Title + Buttons
============================================================ --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-1">
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Users') }}</h1>
    <div class="flex items-center gap-2">
        <button type="button"
                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
            {{ __('Send invites') }}
        </button>
        <a href="{{ route('users.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
            {{ __('Export') }}
        </a>
    </div>
</div>

<p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
    {{ __('View and manage users, send invites, and export user data.') }}
    <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Learn more...') }}</a>
</p>

{{-- ============================================================
     TABS
============================================================ --}}
@php
    $tabs = [
        ['key' => 'active',   'label' => __('Active')],
        ['key' => 'new',      'label' => __('New')],
        ['key' => 'staff',    'label' => __('Staff')],
        ['key' => 'manager',  'label' => __('Manager')],
        ['key' => 'admin',    'label' => __('Admin')],
        ['key' => 'inactive', 'label' => __('Inactive')],
    ];
    $activeTab = collect($tabs)->firstWhere('key', $tab) ?? $tabs[0];
@endphp

<div class="border-b border-slate-200 dark:border-slate-800 mb-6">
    <nav class="flex gap-1 -mb-px overflow-x-auto">
        @foreach ($tabs as $t)
            <a href="{{ route('users.index', array_filter(['tab' => $t['key'], 'search' => request('search')])) }}"
               class="px-4 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition
                      {{ $tab === $t['key']
                         ? 'border-blue-600 text-slate-900 dark:text-white'
                         : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                {{ $t['label'] }}
            </a>
        @endforeach
    </nav>
</div>

{{-- ============================================================
     SECTION HEADING + TOGGLE
============================================================ --}}
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-900 dark:text-white">
        {{ $activeTab['label'] }} {{ __('users') }}
    </h2>
    <button type="button" id="toggleEmails"
            class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
        {{ __('Show emails') }}
    </button>
</div>

{{-- ============================================================
     SEARCH
============================================================ --}}
<form method="GET" action="{{ route('users.index') }}" class="mb-5">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="{{ __('username, email or name') }}"
           autocomplete="off"
           class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
</form>

{{-- ============================================================
     TABLE
============================================================ --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200 dark:border-slate-800">
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('User') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider email-col hidden">{{ __('Email') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Role') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Last Login') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Created') }}</th>
                <th class="w-16"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
            @forelse ($users as $u)
                @php
                    $roleLabel = match($u->role) {
                        'Manajer Gudang' => __('Manager'),
                        'Staff Gudang'   => __('Staff'),
                        'Admin'          => __('Admin'),
                        default          => $u->role,
                    };
                    $roleClass = match($u->role) {
                        'Admin'          => 'text-purple-500 dark:text-purple-400',
                        'Manajer Gudang' => 'text-blue-500 dark:text-blue-400',
                        'Staff Gudang'   => 'text-orange-500 dark:text-orange-400',
                        default          => 'text-slate-500',
                    };
                    $isActive  = ($u->status ?? 'active') === 'active';
                    $lastLogin = $u->last_login_at ?? $u->updated_at ?? null;
                    $isSelf    = $u->id === auth()->id();
                @endphp

                <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                    {{-- User (avatar + name) --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if (!empty($u->avatar) && file_exists(public_path('storage/' . $u->avatar)))
                                <img src="{{ asset('storage/' . $u->avatar) }}"
                                     alt="{{ $u->name }}"
                                     class="w-7 h-7 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center text-white font-semibold text-[11px] flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex items-center gap-2">
                                <a href="{{ route('users.edit', $u) }}"
                                   class="font-medium text-slate-900 dark:text-white hover:underline truncate">
                                    {{ $u->name }}
                                </a>
                                @if ($isSelf)
                                    <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 flex-shrink-0">
                                        {{ __('You') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Email (hidden by default) --}}
                    <td class="px-4 py-3 email-col hidden">
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $u->email ?? '—' }}</span>
                    </td>

                    {{-- Role --}}
                    <td class="px-4 py-3">
                        <span class="text-xs font-semibold {{ $roleClass }}">{{ $roleLabel }}</span>
                    </td>

                    {{-- Last Login --}}
                    <td class="px-4 py-3">
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $lastLogin ? $lastLogin->diffForHumans(short: true) : '—' }}
                        </span>
                    </td>

                    {{-- Created --}}
                    <td class="px-4 py-3">
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $u->created_at ? $u->created_at->diffForHumans(short: true) : '—' }}
                        </span>
                    </td>

                    {{-- Actions (right edge) --}}
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if (!$isActive)
                                <span title="{{ __('Account inactive') }}" class="inline-flex items-center justify-center w-5 h-5 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                            @endif

                            {{-- Hover-only actions --}}
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('users.edit', $u) }}" title="{{ __('Edit') }}"
                                   class="inline-flex items-center justify-center w-6 h-6 rounded text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                @if (!$isSelf)
                                    <form method="POST" action="{{ route('users.destroy', $u) }}"
                                          onsubmit="return confirm('{{ __('Delete user') }} {{ $u->name }}?')"
                                          class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="{{ __('Delete') }}"
                                                class="inline-flex items-center justify-center w-6 h-6 rounded text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <p class="font-semibold text-slate-900 dark:text-white">{{ __('No users found') }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ request('search') ? __('Try a different search term.') : __('Nothing in this tab yet.') }}
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ============================================================
     FOOTER / PAGINATION
============================================================ --}}
@if ($users->total() > 0)
    <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ __('Showing') }}
            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</span>
            {{ __('of') }}
            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $users->total() }}</span>
            {{ __('users') }}
        </p>
        @if ($users->hasPages())
            <div>{{ $users->links() }}</div>
        @endif
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('toggleEmails');
    if (!toggle) return;

    var hidden = true;
    toggle.addEventListener('click', function () {
        hidden = !hidden;
        document.querySelectorAll('.email-col').forEach(function (el) {
            el.classList.toggle('hidden', hidden);
        });
        toggle.textContent = hidden ? '{{ __('Show emails') }}' : '{{ __('Hide emails') }}';
    });
});
</script>

@endsection
