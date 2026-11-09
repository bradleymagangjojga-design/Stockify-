/**
 * Stockify - Main JavaScript Entry Point
 *
 * This file loads all required dependencies and initializes global UI behaviors.
 */

// ============================================================
// DEPENDENCIES
// ============================================================
import './bootstrap';
import 'flowbite';
import 'flowbite-datepicker';
import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;

// ============================================================
// GLOBAL UI BEHAVIORS
// ============================================================
document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // 1. LOCAL PRODUCT SEARCH (Product page only)
    // ============================================================
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('#productTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    }

    // ============================================================
    // 2. AUTO-DISMISS ALERTS
    // ============================================================
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-dismiss').forEach(el => el.remove());
    }, 4000);

    // ============================================================
    // 3. GLOBAL SEARCH (Ctrl+K)
    // ============================================================
    const globalSearchInput = document.getElementById('globalSearchInput');
    const globalSearchResults = document.getElementById('globalSearchResults');

    if (globalSearchInput && globalSearchResults) {
        let debounceTimer;

        // 3a. Ctrl+K / Cmd+K to focus the search bar
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                globalSearchInput.focus();
            }
        });

        // 3b. Fetch results as the user types (with 300ms debounce)
        globalSearchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                globalSearchResults.classList.add('hidden');
                globalSearchResults.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                const url = window.stockifySearchUrl || '/api/search';

                fetch(`${url}?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            globalSearchResults.innerHTML = `
                                <div class="p-4 text-sm text-slate-400 text-center">
                                    Tidak ada hasil untuk "${query}"
                                </div>`;
                        } else {
                            let html = '';
                            data.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-700 transition border-b border-slate-700/50 last:border-0">
                                        <div class="w-8 h-8 rounded-lg bg-slate-700 flex items-center justify-center text-sm flex-shrink-0">${item.icon}</div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-white truncate">${item.title}</p>
                                            <p class="text-xs text-slate-400 truncate">${item.sub}</p>
                                        </div>
                                        <span class="text-[10px] font-semibold text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded-full">${item.type}</span>
                                    </a>
                                `;
                            });
                            globalSearchResults.innerHTML = html;
                        }
                        globalSearchResults.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        globalSearchResults.innerHTML = `
                            <div class="p-4 text-sm text-red-400 text-center">
                                Terjadi kesalahan saat mencari.
                            </div>`;
                        globalSearchResults.classList.remove('hidden');
                    });
            }, 300);
        });

        // 3c. Close results when clicking outside
        document.addEventListener('click', (e) => {
            if (!globalSearchInput.contains(e.target) && !globalSearchResults.contains(e.target)) {
                globalSearchResults.classList.add('hidden');
            }
        });

        // 3d. Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                globalSearchResults.classList.add('hidden');
                globalSearchInput.blur();
            }
        });
    }
});
