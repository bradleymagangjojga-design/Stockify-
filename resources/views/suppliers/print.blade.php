<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Supplier') }} - {{ $supplier->name }}</title>
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

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl print-shadow p-10 print:p-0 print:shadow-none print:rounded-none">

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
                <h2 class="text-xl font-bold text-slate-900 uppercase tracking-wide">{{ __('Supplier Report') }}</h2>
                <p class="text-xs text-slate-500 mt-1">{{ __('Supplier Data Report') }}</p>
                <div class="text-xs text-slate-500 mt-3">
                    <p><span class="font-semibold text-slate-700">{{ __('Date') }}:</span> {{ now()->translatedFormat('d M Y') }}</p>
                    <p><span class="font-semibold text-slate-700">No:</span> SUP-{{ str_pad($supplier->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- SUPPLIER INFO --}}
        <div class="mb-8">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ __('Supplier Information') }}</h3>
            <div class="p-5 rounded-xl bg-slate-50 border border-slate-200">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-lg text-slate-900">{{ $supplier->name }}</h4>
                        <div class="grid grid-cols-2 gap-3 mt-2 text-sm text-slate-600">
                            @if ($supplier->contact)
                                <p><span class="text-slate-500">{{ __('Phone') }}:</span> {{ $supplier->contact }}</p>
                            @endif
                            @if ($supplier->email)
                                <p><span class="text-slate-500">Email:</span> {{ $supplier->email }}</p>
                            @endif
                            @if ($supplier->address)
                                <p class="col-span-2"><span class="text-slate-500">{{ __('Address') }}:</span> {{ $supplier->address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-5 pt-5 border-t border-slate-200">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('Total Products') }}</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_products'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('Total Units') }}</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_stock'], 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('Total Value') }}</p>
                        <p class="text-xl font-bold text-slate-900 mt-1">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRODUCTS --}}
        <div class="mb-8">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ __('Supplied Products') }}</h3>
            <table class="w-full text-sm border border-slate-200">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-slate-700 text-xs uppercase tracking-wider">SKU</th>
                        <th class="px-3 py-2 text-left font-semibold text-slate-700 text-xs uppercase tracking-wider">{{ __('Product Name') }}</th>
                        <th class="px-3 py-2 text-left font-semibold text-slate-700 text-xs uppercase tracking-wider">{{ __('Category') }}</th>
                        <th class="px-3 py-2 text-center font-semibold text-slate-700 text-xs uppercase tracking-wider">{{ __('Stock') }}</th>
                        <th class="px-3 py-2 text-right font-semibold text-slate-700 text-xs uppercase tracking-wider">{{ __('Purchase Price') }}</th>
                        <th class="px-3 py-2 text-right font-semibold text-slate-700 text-xs uppercase tracking-wider">{{ __('Value') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr class="border-b border-slate-100">
                            <td class="px-3 py-2 font-mono text-xs text-slate-600">{{ $p->sku }}</td>
                            <td class="px-3 py-2 text-slate-900">{{ $p->name }}</td>
                            <td class="px-3 py-2 text-slate-600 text-xs">{{ $p->category->name ?? '—' }}</td>
                            <td class="px-3 py-2 text-center font-semibold text-slate-900">{{ $p->stock }}</td>
                            <td class="px-3 py-2 text-right text-slate-600">Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right font-semibold text-slate-900">Rp {{ number_format($p->stock * $p->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-slate-500 text-xs">
                                {{ __('This supplier has no products yet') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($products->isNotEmpty())
                    <tfoot class="bg-slate-100 border-t-2 border-slate-300">
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-right font-bold text-slate-700 text-xs uppercase">{{ __('Total') }}</td>
                            <td class="px-3 py-2 text-center font-bold text-slate-900">{{ $stats['total_stock'] }}</td>
                            <td></td>
                            <td class="px-3 py-2 text-right font-bold text-slate-900">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
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
                    <p class="text-xs text-slate-500">{{ __('Manager / Recipient') }}</p>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-8 pt-4 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400">
                {{ __('This document was generated by') }} {{ $appName }} {{ __('on') }} {{ now()->translatedFormat('d M Y, H:i') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">
                {{ __('Thank you for your trust. For inquiries, contact info@seveninc.id') }}
            </p>
        </div>

    </div>
</body>
</html>
