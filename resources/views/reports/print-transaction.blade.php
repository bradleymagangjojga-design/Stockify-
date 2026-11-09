<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }} — {{ __('Transaction Report') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none !important; } body { background: white !important; } .print-shadow { box-shadow: none !important; } }
        @page { margin: 1.5cm; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 print:bg-white print:py-0">
    <div class="no-print fixed top-4 right-4 z-50 flex items-center gap-2">
        <button onclick="window.print()" class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl shadow-lg shadow-blue-500/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print Now') }}
        </button>
        <button onclick="window.close()" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 px-5 py-3 rounded-xl shadow-sm transition">{{ __('Close') }}</button>
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
                <h2 class="text-xl font-bold text-slate-900 uppercase tracking-wide">{{ __('Transaction Report') }}</h2>
                <p class="text-xs text-slate-500 mt-1">{{ __('Transaction Information') }}</p>
                <div class="text-xs text-slate-500 mt-3">
                    <p><span class="font-semibold text-slate-700">{{ __('Printed') }}:</span> {{ now()->format('d M Y, H:i') }}</p>
                    <p><span class="font-semibold text-slate-700">{{ __('Transaction ID') }}:</span> TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- TYPE BADGE --}}
        <div class="mb-6">
            @if ($transaction->type === 'in')
                <span class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-full bg-green-100 text-green-700 border border-green-300">
                    ⬇️ {{ __('INCOMING GOODS') }}
                </span>
            @else
                <span class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-full bg-red-100 text-red-700 border border-red-300">
                    ⬆️ {{ __('OUTGOING GOODS') }}
                </span>
            @endif
        </div>

        {{-- DETAILS TABLE --}}
        <div class="mb-8">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ __('Transaction Details') }}</h3>
            <table class="w-full text-sm border border-slate-200">
                <tbody>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 w-1/3 text-slate-700">{{ __('Product') }}</td><td class="py-3 px-4 font-medium text-slate-900">{{ $transaction->product->name ?? '—' }}</td></tr>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700">SKU</td><td class="py-3 px-4 font-mono text-slate-900">{{ $transaction->product->sku ?? '—' }}</td></tr>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700">{{ __('Category') }}</td><td class="py-3 px-4 text-slate-900">{{ optional($transaction->product->category)->name ?? '—' }}</td></tr>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700">{{ __('Supplier') }}</td><td class="py-3 px-4 text-slate-900">{{ $transaction->supplier->name ?? '—' }}</td></tr>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700">{{ __('Quantity') }}</td><td class="py-3 px-4 font-bold text-lg {{ $transaction->type === 'in' ? 'text-green-600' : 'text-red-600' }}">{{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }} {{ __('unit') }}</td></tr>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700">{{ __('Date & Time') }}</td><td class="py-3 px-4 text-slate-900">{{ $transaction->transaction_date->translatedFormat('d M Y, H:i') }}</td></tr>
                    <tr class="border-b border-slate-200"><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700">{{ __('Recorded By') }}</td><td class="py-3 px-4 text-slate-900">{{ $transaction->user->name ?? '—' }}</td></tr>
                    <tr><td class="py-3 px-4 font-semibold bg-slate-50 text-slate-700 align-top">{{ __('Notes') }}</td><td class="py-3 px-4 text-slate-700">{{ $transaction->note ?: '—' }}</td></tr>
                </tbody>
            </table>
        </div>

        {{-- SIGNATURES --}}
        <div class="grid grid-cols-2 gap-8 pt-6 border-t-2 border-slate-900">
            <div>
                <p class="text-xs text-slate-500 mb-16">{{ __('Created by') }},</p>
                <div class="border-t border-slate-300 pt-2">
                    <p class="text-sm font-semibold text-slate-900">{{ $transaction->user->name ?? auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-slate-500">{{ $transaction->user->role ?? auth()->user()->role ?? 'Admin' }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-16">{{ __('Approved by') }},</p>
                <div class="border-t border-slate-300 pt-2">
                    <p class="text-sm font-semibold text-slate-900">______________________</p>
                    <p class="text-xs text-slate-500">{{ __('Manager / Recipient') }}</p>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-8 pt-4 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400">{{ __('This document was generated by') }} {{ $appName }} {{ __('on') }} {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 400));</script>
</body>
</html>
