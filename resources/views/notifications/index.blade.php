@extends('layouts.app')
@section('title', __('Notifications'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Notifications') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Notifications') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('All notifications and recent activity') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" id="markAllReadBtn"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ __('Mark All Read') }}
        </button>
    </div>
</div>
@endsection

@section('content')

{{-- Filter Tabs --}}
@php
    $allCount      = $notifications->count();
    $unreadCount   = $notifications->where('read', false)->count();
    $warningCount  = $notifications->where('type', 'warning')->count();
    $successCount  = $notifications->where('type', 'success')->count();
    $infoCount     = $notifications->where('type', 'info')->count();
@endphp

<div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
    @foreach ([
        ['key' => 'all',      'label' => __('All'),      'count' => $allCount],
        ['key' => 'unread',   'label' => __('Unread'),   'count' => $unreadCount],
        ['key' => 'warning',  'label' => __('Warning'),  'count' => $warningCount],
        ['key' => 'success',  'label' => __('Success'),  'count' => $successCount],
        ['key' => 'info',     'label' => __('Info'),     'count' => $infoCount],
    ] as $tab)
        <button type="button" data-filter="{{ $tab['key'] }}"
                class="notification-tab flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition
                       {{ $tab['key'] === 'all'
                          ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'
                          : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
            {{ $tab['label'] }}
            <span data-count-for="{{ $tab['key'] }}"
                  class="notification-tab-count text-xs font-semibold {{ $tab['key'] === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} px-2 py-0.5 rounded-full
                         {{ $tab['count'] === 0 ? 'hidden' : '' }}">
                {{ $tab['count'] }}
            </span>
        </button>
    @endforeach
</div>

{{-- Notifications List --}}
<div id="notificationsList" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">

    @forelse ($notifications as $notif)
        @php
            $typeStyles = [
                'success' => ['bg' => 'bg-green-100 dark:bg-green-500/20', 'text' => 'text-green-600 dark:text-green-400', 'icon' => '✅'],
                'warning' => ['bg' => 'bg-orange-100 dark:bg-orange-500/20', 'text' => 'text-orange-600 dark:text-orange-400', 'icon' => '⚠️'],
                'info'    => ['bg' => 'bg-blue-100 dark:bg-blue-500/20', 'text' => 'text-blue-600 dark:text-blue-400', 'icon' => 'ℹ️'],
                'error'   => ['bg' => 'bg-red-100 dark:bg-red-500/20', 'text' => 'text-red-600 dark:text-red-400', 'icon' => '❌'],
            ];
            $style = $typeStyles[$notif['type']] ?? $typeStyles['info'];
        @endphp

        <div class="notification-item flex items-start gap-4 p-5 border-b border-slate-100 dark:border-slate-800 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition cursor-pointer
                    {{ $notif['read'] ? 'opacity-70' : '' }}"
             data-id="{{ $notif['id'] }}"
             data-type="{{ $notif['type'] }}"
             data-read="{{ $notif['read'] ? 'true' : 'false' }}">

            {{-- Icon --}}
            <div class="w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center text-xl flex-shrink-0">
                {{ $style['icon'] }}
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white">
                            {{ $notif['title'] }}
                            <span class="unread-dot inline-block w-2 h-2 rounded-full bg-blue-500 ms-1 align-middle {{ $notif['read'] ? 'hidden' : '' }}"></span>
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $notif['description'] }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $notif['time']->diffForHumans() }}
                        </p>
                    </div>
                    <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full flex-shrink-0
                                 {{ $style['bg'] }} {{ $style['text'] }}">
                        {{ ucfirst($notif['type']) }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="p-16 text-center">
            <div class="inline-flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">🔔</div>
                <p class="font-medium text-slate-900 dark:text-white">{{ __('No notifications yet') }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Notifications will appear here when there is new activity') }}</p>
            </div>
        </div>
    @endforelse

</div>

{{-- ============================================================
     PAGE SCRIPT — inline at end of content section.
     This ALWAYS renders via @yield('content') in the layout.
     It does NOT depend on @stack('scripts') at all.
============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    console.log('[Notifications] Script loaded');

    var CSRF = document.querySelector('meta[name="csrf-token"]');
    CSRF = CSRF ? CSRF.content : '';

    function getItems() { return document.querySelectorAll('.notification-item'); }
    function getTabs()  { return document.querySelectorAll('.notification-tab'); }

    // ===== FILTER =====
    function applyFilter(filter) {
        getItems().forEach(function (item) {
            var type = item.dataset.type;
            var read = item.dataset.read === 'true';
            var visible = true;
            if (filter === 'unread') visible = !read;
            else if (filter !== 'all') visible = (type === filter);
            item.style.display = visible ? '' : 'none';
        });
    }

    // ===== TABS =====
    getTabs().forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            var filter = this.dataset.filter;
            console.log('[Notifications] Tab clicked:', filter);

            getTabs().forEach(function (t) {
                t.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/20');
                t.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300', 'border', 'border-slate-200', 'dark:border-slate-700');
            });
            this.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300', 'border', 'border-slate-200', 'dark:border-slate-700');
            this.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/20');

            getTabs().forEach(function (t) {
                var badge = t.querySelector('.notification-tab-count');
                if (!badge) return;
                if (t === tab) {
                    badge.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
                    badge.classList.add('bg-white/20', 'text-white');
                } else {
                    badge.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
                    badge.classList.remove('bg-white/20', 'text-white');
                }
            });

            applyFilter(filter);
        });
    });

    // ===== ITEM CLICKS =====
    getItems().forEach(function (item) {
        item.addEventListener('click', function () {
            var id = this.dataset.id;
            if (this.dataset.read === 'true') return;
            console.log('[Notifications] Mark as read:', id);

            this.dataset.read = 'true';
            this.classList.add('opacity-70');
            var dot = this.querySelector('.unread-dot');
            if (dot) dot.classList.add('hidden');

            fetch('/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    refreshCounts();
                    var activeTab = document.querySelector('.notification-tab.bg-blue-600');
                    if (activeTab && activeTab.dataset.filter === 'unread') applyFilter('unread');
                }
            })
            .catch(function (err) { console.error('[Notifications] Mark failed:', err); });
        });
    });

    // ===== MARK ALL READ =====
    var markBtn = document.getElementById('markAllReadBtn');
    if (markBtn) {
        markBtn.addEventListener('click', function () {
            console.log('[Notifications] Mark all read clicked');
            this.disabled = true;
            var self = this;

            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    getItems().forEach(function (item) {
                        item.dataset.read = 'true';
                        item.classList.add('opacity-70');
                        var dot = item.querySelector('.unread-dot');
                        if (dot) dot.classList.add('hidden');
                    });
                    refreshCounts();
                    var unreadBadge = document.querySelector('[data-count-for="unread"]');
                    if (unreadBadge) {
                        unreadBadge.textContent = '0';
                        unreadBadge.classList.add('hidden');
                    }
                    localStorage.setItem('notifications_read', 'true');
                    var navDot = document.querySelector('#navNotifToggle .bg-red-500');
                    if (navDot) navDot.style.display = 'none';
                    var activeTab = document.querySelector('.notification-tab.bg-blue-600');
                    if (activeTab && activeTab.dataset.filter === 'unread') applyFilter('unread');
                    if (typeof showToast === 'function') {
                        showToast('{{ __('All notifications marked as read') }}', 'success');
                    }
                }
            })
            .catch(function (err) { console.error('[Notifications] Mark all failed:', err); })
            .finally(function () { self.disabled = false; });
        });
    } else {
        console.warn('[Notifications] #markAllReadBtn not found');
    }

    // ===== COUNTS =====
    function refreshCounts() {
        var items = getItems();
        var counts = {
            all: items.length,
            unread: Array.prototype.filter.call(items, function (i) { return i.dataset.read === 'false'; }).length,
            warning: Array.prototype.filter.call(items, function (i) { return i.dataset.type === 'warning'; }).length,
            success: Array.prototype.filter.call(items, function (i) { return i.dataset.type === 'success'; }).length,
            info: Array.prototype.filter.call(items, function (i) { return i.dataset.type === 'info'; }).length
        };
        Object.keys(counts).forEach(function (key) {
            var badge = document.querySelector('[data-count-for="' + key + '"]');
            if (!badge) return;
            badge.textContent = counts[key];
            badge.classList.toggle('hidden', counts[key] === 0);
        });
    }

    // ===== BOOT =====
    console.log('[Notifications] Boot — items:', getItems().length, 'tabs:', getTabs().length);

    var anyUnread = Array.prototype.some.call(getItems(), function (i) { return i.dataset.read === 'false'; });
    localStorage.setItem('notifications_read', anyUnread ? 'false' : 'true');
    if (!anyUnread) {
        var navDot = document.querySelector('#navNotifToggle .bg-red-500');
        if (navDot) navDot.style.display = 'none';
    }
});
</script>

@endsection