@extends('layouts.app')
@section('title', __('Category'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Category') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Category Management') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage categories to group products') }}</p>
    </div>
    <a href="{{ route('categories.create') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        {{ __('Add Category') }}
    </a>
</div>
@endsection

@section('content')

{{-- Flash messages --}}
@if (session('success'))
    <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30">
        <span class="text-lg">✅</span>
        <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30">
        <span class="text-lg">⚠️</span>
        <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
    </div>
@endif

{{-- ============================================================
     SUMMARY CARDS
============================================================ --}}
@php
    $emptyCount = $categories->filter(fn ($c) => $c->products_count === 0)->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Categories --}}
    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 overflow-hidden">
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">🏷️</div>
            <p class="text-xs font-semibold text-blue-100 uppercase tracking-widest mb-1">{{ __('Total Categories') }}</p>
            <p class="text-3xl font-bold">{{ $totalCategories }}</p>
            <p class="text-[11px] text-blue-100/80 mt-1">{{ __('Product groups') }}</p>
        </div>
    </div>

    {{-- Total Products --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-xl mb-3">📦</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Total Products') }}</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $totalProducts }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Across all categories') }}</p>
    </div>

    {{-- Average Product --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-xl mb-3">📊</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Average Product') }}</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $averageProduct }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('Per category') }}</p>
    </div>

    {{-- Empty Categories --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center text-xl mb-3">⏳</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">{{ __('Empty') }}</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $emptyCount }}</p>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ __('No products yet') }}</p>
    </div>
</div>

{{-- ============================================================
     TABLE + TOOLBAR
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

    {{-- Toolbar --}}
    <div class="p-4 border-b border-slate-200 dark:border-slate-800">
        <div class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="relative flex-1 max-w-md w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="categorySearch" placeholder="{{ __('Search categories...') }}"
                       class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
            </div>

            <select id="categorySort"
                    class="text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300">
                <option value="name">{{ __('Sort by name') }}</option>
                <option value="products">{{ __('Sort by product count') }}</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table id="categoryTable" class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Category') }}</th>
                    <th class="px-5 py-3 text-left font-medium">{{ __('Description') }}</th>
                    <th class="px-5 py-3 text-center font-medium">{{ __('Number of Products') }}</th>
                    <th class="px-5 py-3 text-right font-medium">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($categories as $c)
                    <tr class="category-row hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group"
                        data-name="{{ strtolower($c->name) }}"
                        data-products="{{ $c->products_count }}">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($c->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $c->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">#{{ $c->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-400 max-w-xs">
                            {{ $c->description ?: '—' }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                {{ $c->products_count }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex items-center gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">

                                {{-- View --}}
                                <a href="{{ route('categories.show', $c) }}"
                                   title="{{ __('View') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Print --}}
                                <a href="{{ route('categories.print', $c) }}" target="_blank"
                                   title="{{ __('Print') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('categories.edit', $c) }}"
                                   title="{{ __('Edit') }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Delete trigger --}}
                                <button type="button"
                                        title="{{ __('Delete') }}"
                                        data-delete-trigger
                                        data-category-id="{{ $c->id }}"
                                        data-category-name="{{ $c->name }}"
                                        data-products-count="{{ $c->products_count }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">🏷️</div>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No categories yet') }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Add the first category to start grouping products') }}</p>
                                <a href="{{ route('categories.create') }}"
                                   class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ __('Add Category') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse

                {{-- No results row for search --}}
                <tr id="noResultsRow" class="hidden">
                    <td colspan="4" class="px-5 py-16 text-center">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">🔍</div>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No categories found') }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Try adjusting your search term') }}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ============================================================
     DELETE / REASSIGN MODAL
============================================================ --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" data-modal-backdrop></div>

    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div id="modalIcon" class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center text-2xl flex-shrink-0">
                        🗑️
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 id="deleteModalTitle" class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ __('Delete Category') }}
                        </h3>
                        <p id="deleteModalMessage" class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            {{-- Populated by JS --}}
                        </p>

                        {{-- Warning block (shown when products present) --}}
                        <div id="deleteModalWarning" class="hidden mt-4 flex items-start gap-2.5 p-3 rounded-xl bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/30">
                            <span class="text-base flex-shrink-0">📦</span>
                            <p class="text-xs text-orange-800 dark:text-orange-300 leading-relaxed">
                                {{ __('These products will be moved to the destination category you select below. Nothing will be lost.') }}
                            </p>
                        </div>

                        {{-- Reassign dropdown (only shown when products present) --}}
                        <div id="reassignBlock" class="hidden mt-4">
                            <label for="reassignSelect" class="block mb-2 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                {{ __('Move products to') }}
                            </label>
                            <select name="reassign_to" id="reassignSelect"
                                    class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                                <option value="">{{ __('— Select destination category —') }}</option>
                            </select>
                            <p class="mt-2 text-[11px] text-slate-400 dark:text-slate-500">
                                {{ __('Only categories that are not being deleted are shown.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                <button type="button"
                        data-modal-cancel
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 px-5 py-2.5 rounded-xl transition">
                    {{ __('Cancel') }}
                </button>
                <button type="button"
                        id="deleteModalConfirm"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-xl transition shadow-lg shadow-red-500/30 hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span id="deleteModalConfirmLabel">{{ __('Delete') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================
     Pre-computed JSON payloads for JS
     (Blade's @json() cannot parse inline closures with [ ] —
      always compute to a variable first.)
============================================================ --}}
@php
    $allCategoriesJson = $categories
        ->map(fn ($c) => [
            'id'    => $c->id,
            'name'  => $c->name,
            'count' => $c->products_count,
        ])
        ->values();

    $deleteUrlTemplate = route('categories.destroy', ['category' => '__ID__']);
@endphp

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Search & sort ────────────────────────────────────────
    const search    = document.getElementById('categorySearch');
    const sortBy    = document.getElementById('categorySort');
    const tbody     = document.querySelector('#categoryTable tbody');
    const rows      = Array.from(document.querySelectorAll('.category-row'));
    const noResults = document.getElementById('noResultsRow');

    function applySearch() {
        const term = (search?.value || '').toLowerCase().trim();
        let visible = 0;
        rows.forEach(row => {
            const match = (!term || (row.dataset.name || '').includes(term));
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (noResults) {
            noResults.classList.toggle('hidden', visible !== 0 || rows.length === 0);
        }
    }
    search?.addEventListener('input', applySearch);

    sortBy?.addEventListener('change', () => {
        const val = sortBy.value;
        const sorted = [...rows].sort((a, b) => {
            if (val === 'products') return parseInt(b.dataset.products) - parseInt(a.dataset.products);
            return (a.dataset.name || '').localeCompare(b.dataset.name || '');
        });
        sorted.forEach(row => tbody.appendChild(row));
    });

    // ── Modal config ─────────────────────────────────────────
    const allCategories     = @json($allCategoriesJson);
    const deleteUrlTemplate = @json($deleteUrlTemplate);

    const modal          = document.getElementById('deleteModal');
    const modalForm      = document.getElementById('deleteForm');
    const modalMsg       = document.getElementById('deleteModalMessage');
    const modalWarn      = document.getElementById('deleteModalWarning');
    const modalIcon      = document.getElementById('modalIcon');
    const modalTitle     = document.getElementById('deleteModalTitle');
    const confirmBtn     = document.getElementById('deleteModalConfirm');
    const confirmLbl     = document.getElementById('deleteModalConfirmLabel');
    const cancelBtn      = modal.querySelector('[data-modal-cancel]');
    const backdrop       = modal.querySelector('[data-modal-backdrop]');
    const reassignBlock  = document.getElementById('reassignBlock');
    const reassignSelect = document.getElementById('reassignSelect');

    function setConfirmState(enabled, label) {
        confirmLbl.textContent = label;
        if (enabled) {
            confirmBtn.removeAttribute('disabled');
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            confirmBtn.setAttribute('disabled', 'disabled');
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function openModal({ id, name, productCount }) {
        // Set form action
        modalForm.action = deleteUrlTemplate.replace('__ID__', id);

        const hasProducts = productCount > 0;

        // Message
        modalMsg.textContent = hasProducts
            ? `"${name}" is currently used by ${productCount} product${productCount === 1 ? '' : 's'}. Choose where to move them before deleting.`
            : `Are you sure you want to delete "${name}"? This action cannot be undone.`;

        // Toggle blocks
        modalWarn.classList.toggle('hidden', !hasProducts);
        reassignBlock.classList.toggle('hidden', !hasProducts);

        // Icon + title
        modalIcon.classList.remove('bg-red-100', 'dark:bg-red-500/20', 'bg-orange-100', 'dark:bg-orange-500/20');
        modalIcon.classList.add(...(hasProducts
            ? ['bg-orange-100', 'dark:bg-orange-500/20']
            : ['bg-red-100', 'dark:bg-red-500/20']));
        modalIcon.textContent = hasProducts ? '📦' : '🗑️';
        modalTitle.textContent = hasProducts ? '{{ __('Reassign & Delete') }}' : '{{ __('Delete Category') }}';

        // Populate reassign dropdown
        if (hasProducts) {
            reassignSelect.innerHTML = '<option value="">{{ __('— Select destination category —') }}</option>';

            let destinationCount = 0;
            allCategories.forEach(cat => {
                if (cat.id === id) return; // exclude self
                destinationCount++;

                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.textContent = `${cat.name} (${cat.count} product${cat.count === 1 ? '' : 's'})`;
                reassignSelect.appendChild(opt);
            });

            if (destinationCount === 0) {
                setConfirmState(false, '{{ __('No destination') }}');
                modalMsg.textContent = `"${name}" has ${productCount} product${productCount === 1 ? '' : 's'}, and there is no other category to move them to. Create a destination category first.`;
            } else {
                setConfirmState(false, '{{ __('Move & Delete') }}');
            }
        } else {
            reassignSelect.innerHTML = '';
            setConfirmState(true, '{{ __('Delete') }}');
        }

        // Show
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        reassignSelect.value = '';
    }

    // Enable confirm once a destination is picked
    reassignSelect?.addEventListener('change', () => {
        if (reassignSelect.value) {
            setConfirmState(true, '{{ __('Move & Delete') }}');
        } else {
            setConfirmState(false, '{{ __('Move & Delete') }}');
        }
    });

    // Triggers
    document.querySelectorAll('[data-delete-trigger]').forEach(btn => {
        btn.addEventListener('click', () => {
            openModal({
                id:           parseInt(btn.dataset.categoryId, 10),
                name:         btn.dataset.categoryName,
                productCount: parseInt(btn.dataset.productsCount || '0', 10),
            });
        });
    });

    // Confirm
    confirmBtn?.addEventListener('click', () => {
        if (confirmBtn.hasAttribute('disabled')) return;
        modalForm.submit();
    });

    // Close paths
    cancelBtn?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });
});
</script>

@endsection