@extends('layouts.app')
@section('title', __('Supplier'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Supplier') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Supplier Management') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage supplier data and business partner relationships') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('suppliers.export') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            {{ __('Export') }}
        </a>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Add Supplier') }}
            </a>
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Suppliers --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                🏭
            </div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Suppliers') }}</p>
            <p class="text-3xl font-bold">{{ $suppliers->count() }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('Business partners') }}</p>
        </div>
    </div>

    {{-- Active --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">✅</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Active') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $suppliers->where('products_count', '>', 0)->count() }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('With products') }}</p>
    </div>

    {{-- New Suppliers --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">🆕</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('New Suppliers') }}</p>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $suppliers->where('created_at', '>=', now()->subDays(30))->count() }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Last 30 days') }}</p>
    </div>

    {{-- Total Products --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">📦</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $suppliers->sum('products_count') }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Across all suppliers') }}</p>
    </div>
</div>

{{-- ============================================================
     TABLE + TOOLBAR
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    {{-- Toolbar --}}
    <div class="p-4 border-b border-slate-200 dark:border-slate-800">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="supplierSearch" placeholder="{{ __('Search suppliers by name, contact, email...') }}"
                       class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <select id="statusFilter"
                        class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active (Has Products)') }}</option>
                    <option value="empty">{{ __('No Products Yet') }}</option>
                </select>

                <select id="locationFilter"
                        class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                    <option value="">{{ __('All Locations') }}</option>
                    <option value="jakarta">Jakarta</option>
                    <option value="bandung">Bandung</option>
                    <option value="surabaya">Surabaya</option>
                    <option value="yogyakarta">Yogyakarta</option>
                </select>

                <button type="button" id="resetFilters" onclick="resetSupplierFilters()"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('Reset') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table id="supplierTable" class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Supplier') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Contact') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Location') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Products') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Status') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($suppliers as $s)
                    <tr class="supplier-row hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group"
                        data-name="{{ strtolower($s->name . ' ' . ($s->contact ?? '') . ' ' . ($s->email ?? '')) }}"
                        data-status="{{ $s->products_count > 0 ? 'active' : 'empty' }}"
                        data-location="{{ strtolower($s->address ?? '') }}">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($s->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-900 dark:text-white truncate">{{ $s->name }}</p>
                                    @if ($s->email)
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $s->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-sm">{{ $s->contact ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm truncate max-w-[200px]">{{ $s->address ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                {{ $s->products_count }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if ($s->products_count > 0)
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    {{ __('No Products') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex items-center gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                {{-- View --}}
                                <a href="{{ route('suppliers.show', $s) }}" title="{{ __('View') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Print --}}
                                <a href="{{ route('suppliers.print', $s) }}" target="_blank" title="{{ __('Print') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>

                                @if (auth()->user()->isAdmin())
                                    {{-- Edit --}}
                                    <a href="{{ route('suppliers.edit', $s) }}" title="{{ __('Edit') }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('suppliers.destroy', $s) }}"
                                          onsubmit="return confirm('{{ __('Delete') }} {{ $s->name }}?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="{{ __('Delete') }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
                                    🏭
                                </div>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No suppliers yet') }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Start by adding your first supplier') }}</p>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('suppliers.create') }}"
                                       class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ __('Add Supplier') }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse

                {{-- No results row for search --}}
                <tr id="noResultsRow" class="hidden">
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">
                                🔍
                            </div>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No suppliers found') }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Try adjusting your search or filters') }}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50 dark:bg-slate-800/30">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ __('Showing') }} <span class="font-medium text-slate-900 dark:text-white" id="visibleCount">{{ $suppliers->count() }}</span>
            {{ __('of') }} <span class="font-medium text-slate-900 dark:text-white">{{ $suppliers->count() }}</span> {{ __('suppliers') }}
        </p>
    </div>
</div>

<script>
function resetSupplierFilters() {
    const search = document.getElementById('supplierSearch');
    const statusFilter = document.getElementById('statusFilter');
    const locationFilter = document.getElementById('locationFilter');
    const rows = document.querySelectorAll('.supplier-row');
    const visibleCount = document.getElementById('visibleCount');
    const noResultsRow = document.getElementById('noResultsRow');

    if (search) search.value = '';
    if (statusFilter) statusFilter.value = '';
    if (locationFilter) locationFilter.value = '';

    rows.forEach(row => { row.style.display = ''; });
    if (visibleCount) visibleCount.textContent = rows.length;
    if (noResultsRow) noResultsRow.classList.add('hidden');
    if (search) search.focus();

    if (typeof showToast === 'function') showToast('{{ __('Filters reset') }}', 'info');
}

document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('supplierSearch');
    const statusFilter = document.getElementById('statusFilter');
    const locationFilter = document.getElementById('locationFilter');
    const rows = document.querySelectorAll('.supplier-row');
    const visibleCount = document.getElementById('visibleCount');
    const noResultsRow = document.getElementById('noResultsRow');

    function applyFilters() {
        const term = (search?.value || '').toLowerCase().trim();
        const status = statusFilter?.value || '';
        const location = (locationFilter?.value || '').toLowerCase().trim();
        let visible = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const rowStatus = row.dataset.status || '';
            const rowLocation = (row.dataset.location || '').toLowerCase();

            const show = (!term || name.includes(term))
                      && (!status || rowStatus === status)
                      && (!location || rowLocation.includes(location));

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (visibleCount) visibleCount.textContent = visible;
        if (noResultsRow) {
            if (visible === 0 && rows.length > 0) {
                noResultsRow.classList.remove('hidden');
            } else {
                noResultsRow.classList.add('hidden');
            }
        }
    }

    search?.addEventListener('input', applyFilters);
    statusFilter?.addEventListener('change', applyFilters);
    locationFilter?.addEventListener('change', applyFilters);
});
</script>

@endsection