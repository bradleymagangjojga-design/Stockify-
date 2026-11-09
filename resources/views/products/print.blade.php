<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $product->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-shadow { box-shadow: none !important; }
        }
        @page { margin: 1.5cm; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 print:bg-white print:py-0">

    {{-- Print Controls --}}
    <div class="no-print fixed top-4 right-4 z-50 flex items-center gap-2">
        <button onclick="window.print()"
                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl shadow-lg shadow-blue-500/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            {{ __('Print Now') }}
        </button>
        <button onclick="window.close()"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 px-5 py-3 rounded-xl shadow-sm transition">
            {{ __('Close') }}
        </button>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl print-shadow p-10 print:p-0 print:shadow-none print:rounded-none">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-6 pb-6 border-b-2 border-slate-900 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center overflow-hidden border border-slate-200 p-1">
                        <img src="{{ asset('image/Seven_Inc_logo.jpg') }}" alt="{{ $appName }} Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $appName }}</h1>
                        <p class="text-xs text-slate-500">{{ __('Multi-Sector Digital Enterprise') }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">CV. Sanjaya 57 · Est. 2012 · Yogyakarta, Indonesia</p>
                    </div>
                </div>
                <div class="text-xs text-slate-500 mt-3 leading-relaxed">
                    <p>Yogyakarta, Indonesia</p>
                    <p>Telp: 021-1234567 · Email: info@seveninc.id</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-slate-900 uppercase tracking-wide">{{ __('Product Quotation') }}</h2>
                <p class="text-xs text-slate-500 mt-1">{{ __('Product Information') }}</p>
                <div class="text-xs text-slate-500 mt-3">
                    <p><span class="font-semibold text-slate-700">{{ __('Date') }}:</span> {{ now()->format('d M Y') }}</p>
                    <p><span class="font-semibold text-slate-700">No:</span> QUO-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- PRODUCT SUMMARY --}}
        <div class="mb-8">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ __('Product Information') }}</h3>
            <div class="flex items-start gap-4 p-5 rounded-xl bg-slate-50 border border-slate-200">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                    {{ strtoupper(substr($product->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-lg text-slate-900">{{ $product->name }}</h4>
                    <p class="text-sm text-slate-500 mt-0.5 font-mono">{{ $product->sku }}</p>
                    @if ($product->description)
                        <p class="text-sm text-slate-600 mt-2">{{ $product->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- DETAILS TABLE --}}
        <div class="mb-8">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ __('Details') }}</h3>
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-slate-200">
                        <td class="py-3 text-sm text-slate-500 w-1/3">{{ __('Category') }}</td>
                        <td class="py-3 text-sm font-medium text-slate-900">{{ $product->category->name ?? '—' }}</td>
                    </tr>
                    <tr class="border-b border-slate-200">
                        <td class="py-3 text-sm text-slate-500">{{ __('Supplier') }}</td>
                        <td class="py-3 text-sm font-medium text-slate-900">{{ $product->supplier->name ?? '—' }}</td>
                    </tr>
                    <tr class="border-b border-slate-200">
                        <td class="py-3 text-sm text-slate-500">{{ __('Available Stock') }}</td>
                        <td class="py-3 text-sm font-medium text-slate-900">{{ $product->stock }} {{ __('unit') }}</td>
                    </tr>
                    <tr class="border-b border-slate-200">
                        <td class="py-3 text-sm text-slate-500">{{ __('Unit Price') }}</td>
                        <td class="py-3 text-lg font-bold text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    </tr>
                    @if ($product->min_stock > 0)
                        <tr class="border-b border-slate-200">
                            <td class="py-3 text-sm text-slate-500">{{ __('Min. Order') }}</td>
                            <td class="py-3 text-sm font-medium text-slate-900">1 {{ __('unit') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- PRICING BREAKDOWN --}}
        <div class="mb-8 p-5 rounded-xl bg-blue-50 border border-blue-200">
            <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4">{{ __('Price Summary') }}</h3>
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-blue-200">
                        <td class="py-2.5 text-sm text-blue-700">{{ __('Price per Unit') }}</td>
                        <td class="py-2.5 text-sm text-right font-medium text-blue-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b border-blue-200">
                        <td class="py-2.5 text-sm text-blue-700">{{ __('Quantity') }}</td>
                        <td class="py-2.5 text-sm text-right font-medium text-blue-900">1 {{ __('unit') }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 text-base font-bold text-blue-900">{{ __('TOTAL') }}</td>
                        <td class="py-3 text-xl font-bold text-right text-blue-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- NOTES --}}
        <div class="mb-10 p-4 rounded-xl bg-slate-50 border border-slate-200">
            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Notes & Terms') }}</h4>
            <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                <li>{{ __('Prices exclude VAT.') }}</li>
                <li>{{ __('Prices are subject to change without prior notice.') }}</li>
                <li>{{ __('Quotation is valid for 30 days from the date of issue.') }}</li>
                <li>{{ __('Payment can be made via bank transfer.') }}</li>
            </ul>
        </div>

        {{-- SIGNATURES --}}
        <div class="grid grid-cols-2 gap-8 pt-6 border-t-2 border-slate-900">
            <div>
                <p class="text-xs text-slate-500 mb-16">{{ __('Created by') }},</p>
                <div class="border-t border-slate-300 pt-2">
                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->role ?? 'Admin' }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-16">{{ __('Approved by') }},</p>
                <div class="border-t border-slate-300 pt-2">
                    <p class="text-sm font-semibold text-slate-900">______________________</p>
                    <p class="text-xs text-slate-500">{{ __('Customer / Recipient') }}</p>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-8 pt-4 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400">
                {{ __('This document was generated by') }} {{ $appName }} {{ __('on') }} {{ now()->format('d M Y, H:i') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">
                {{ __('Thank you for your trust. For inquiries, contact info@seveninc.id') }}
            </p>
        </div>

    </div>

</body>
</html>
