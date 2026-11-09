@extends('layouts.app')
@section('title', __('Help'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Help') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Help & Support') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('User guide and support contact') }}</p>
    </div>
</div>
@endsection

@section('content')

{{-- Support Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    {{-- Documentation --}}
    <a href="#" class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-xl hover:border-blue-500 dark:hover:border-blue-500 hover:-translate-y-1 transition-all duration-200">
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-3xl shadow-lg shadow-blue-500/20 group-hover:scale-110 transition mb-4">
            📚
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Documentation') }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Complete guide to using Stockify') }}</p>
    </a>

    {{-- Live Chat --}}
    <a href="#" class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-xl hover:border-green-500 dark:hover:border-green-500 hover:-translate-y-1 transition-all duration-200">
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-3xl shadow-lg shadow-green-500/20 group-hover:scale-110 transition mb-4">
            💬
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Live Chat') }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Contact our support team') }}</p>
    </a>

    {{-- Email Support --}}
    <a href="mailto:support@stockify.app" class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-xl hover:border-pink-500 dark:hover:border-pink-500 hover:-translate-y-1 transition-all duration-200">
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-3xl shadow-lg shadow-pink-500/20 group-hover:scale-110 transition mb-4">
            📧
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Email Support') }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">support@stockify.app</p>
    </a>

</div>

{{-- FAQ Section --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Frequently Asked Questions') }}</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Answers to common questions') }}</p>
    </div>

    @php
        $faqs = [
            [
                'q' => __('How do I add a new product?'),
                'a' => __('Open the Product menu → click the "+ Add Product" button → fill in the form → save. The product will immediately appear in the list.'),
            ],
            [
                'q' => __('How do I perform a stock opname?'),
                'a' => __('Open the Stock Opname menu → select the product → enter the physical stock count → add notes if needed → save. The system will automatically reconcile the difference and log it.'),
            ],
            [
                'q' => __('What happens if stock is below minimum?'),
                'a' => __('The product will be marked with a red "Low Stock" badge. You will also receive a notification. Restock immediately to avoid running out.'),
            ],
            [
                'q' => __('Can I export reports to Excel?'),
                'a' => __('Yes. Open any report page (Stock, Transaction, or Activity) and click the "Export" button at the top right.'),
            ],
            [
                'q' => __('How do I change my password?'),
                'a' => __('Go to Profile → Change Password → enter your current password, then your new password twice → save.'),
            ],
        ];
    @endphp

    <div class="divide-y divide-slate-200 dark:divide-slate-800">
        @foreach ($faqs as $index => $faq)
            <div class="faq-item">
                <button type="button"
                        onclick="toggleFaq({{ $index }})"
                        class="w-full flex items-center justify-between gap-4 px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition text-left">
                    <span class="font-medium text-slate-900 dark:text-white">{{ $faq['q'] }}</span>
                    <span id="faq-chevron-{{ $index }}"
                          class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0 transition-transform duration-200">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </button>
                <div id="faq-answer-{{ $index }}" class="hidden px-6 pb-5">
                    <div class="pt-1 text-sm text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-4">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function toggleFaq(index) {
    const answer  = document.getElementById('faq-answer-' + index);
    const chevron = document.getElementById('faq-chevron-' + index);
    if (!answer) return;

    const isHidden = answer.classList.contains('hidden');

    // Close all
    document.querySelectorAll('[id^="faq-answer-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="faq-chevron-"]').forEach(el => el.style.transform = 'rotate(0deg)');

    // Open the clicked one (if it was closed)
    if (isHidden) {
        answer.classList.remove('hidden');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Auto-open first FAQ
    toggleFaq(0);
});
</script>
@endpush

@endsection
