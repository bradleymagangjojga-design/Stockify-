<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Stock Opname Report') }} — {{ $data['opname_id'] }}</title>
    @vite(['resources/css/app.css'])
    <style>
        /* ─────────────────────────────────────────────
           PRINT RULES
        ───────────────────────────────────────────── */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; padding: 0 !important; }
            .doc-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
            @page { margin: 14mm 16mm; size: A4; }
            .company-logo,
            .avatar-badge,
            .summary-box,
            .dark-divider,
            .status-pill {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* ─────────────────────────────────────────────
           BASE
        ───────────────────────────────────────────── */
        * { box-sizing: border-box; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #eef2f7;
            color: #0f172a;
            margin: 0;
            padding: 32px 24px;
            -webkit-font-smoothing: antialiased;
        }
        .doc-wrapper { max-width: 820px; margin: 0 auto; }

        .doc-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 36px 40px 32px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, 0.06);
        }

        /* ─────────────────────────────────────────────
           TOOLBAR
        ───────────────────────────────────────────── */
        .toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 18px;
        }
        .btn {
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
            transition: all 0.15s ease;
            line-height: 1;
        }
        .btn svg {
            flex-shrink: 0;
            width: 15px;
            height: 15px;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-ghost {
            background: #fff;
            color: #334155;
            border-color: #cbd5e1;
        }
        .btn-ghost:hover { background: #f8fafc; }

        /* ─────────────────────────────────────────────
           HEADER — company left, meta right
        ───────────────────────────────────────────── */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 14px;
        }

        .company-block {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            min-width: 0;
        }
        .company-logo {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }
        .company-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }
        .company-logo-fallback {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-weight: 800;
            font-size: 24px;
        }
        .company-info { min-width: 0; }
        .company-name {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.015em;
            margin: 0 0 2px;
            line-height: 1.15;
        }
        .company-name .accent { color: #dc2626; }
        .company-tagline {
            font-size: 12.5px;
            color: #475569;
            margin: 0 0 4px;
            font-weight: 500;
        }
        .company-meta {
            font-size: 11px;
            color: #94a3b8;
            margin: 0;
            line-height: 1.5;
        }
        .company-meta strong { color: #64748b; font-weight: 600; }

        .title-block {
            text-align: right;
            flex-shrink: 0;
        }
        .title-block h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }
        .title-block .subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 10px;
            font-weight: 500;
        }
        .title-block .doc-meta {
            display: inline-block;
            text-align: left;
            font-size: 11.5px;
            color: #334155;
            line-height: 1.7;
        }
        .title-block .doc-meta strong {
            display: inline-block;
            min-width: 44px;
            color: #64748b;
            font-weight: 600;
        }

        /* ─────────────────────────────────────────────
           CONTACT LINE + DARK DIVIDER
        ───────────────────────────────────────────── */
        .contact-line {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .dark-divider {
            border: 0;
            height: 3px;
            background: #0f172a;
            border-radius: 2px;
            margin: 0 0 26px;
        }

        /* ─────────────────────────────────────────────
           SECTION LABELS
        ───────────────────────────────────────────── */
        .section-label {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #64748b;
            margin: 0 0 12px;
        }

        /* ─────────────────────────────────────────────
           PRODUCT CARD (Section 1)
        ───────────────────────────────────────────── */
        .product-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            margin-bottom: 26px;
        }
        .avatar-badge {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 20px;
            flex-shrink: 0;
        }
        .product-card .product-info { min-width: 0; flex: 1; }
        .product-card .product-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 3px;
            line-height: 1.2;
        }
        .product-card .product-sku {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 11.5px;
            color: #64748b;
            letter-spacing: 0.02em;
            margin: 0 0 3px;
        }
        .product-card .product-desc {
            font-size: 11.5px;
            color: #94a3b8;
            margin: 0;
        }

        /* ─────────────────────────────────────────────
           DETAILS TABLE (Section 2)
        ───────────────────────────────────────────── */
        .details-table {
            margin-bottom: 26px;
        }
        .details-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        .details-row:last-child { border-bottom: 0; }
        .details-label { color: #64748b; font-weight: 500; }
        .details-value { color: #0f172a; font-weight: 600; }
        .details-value.mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: 0.02em;
        }

        /* ─────────────────────────────────────────────
           SUMMARY BOX (Section 3)
        ───────────────────────────────────────────── */
        .summary-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 24px;
        }
        .summary-box .section-label {
            color: #1d4ed8;
            margin-bottom: 14px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 9px 0;
            font-size: 13px;
            border-bottom: 1px solid #dbeafe;
        }
        .summary-row:last-child {
            border-bottom: 0;
            padding-top: 14px;
            margin-top: 6px;
            border-top: 2px solid #93c5fd;
        }
        .summary-row .label { color: #475569; font-weight: 500; }
        .summary-row .value { color: #0f172a; font-weight: 600; }
        .summary-row.total .label {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }
        .summary-row.total .value {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        }
        .summary-row.total .value.red   { color: #dc2626; }
        .summary-row.total .value.green { color: #15803d; }

        /* ─────────────────────────────────────────────
           STATUS PILL
        ───────────────────────────────────────────── */
        .status-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }
        .status-pill.under { background: #fee2e2; color: #b91c1c; }
        .status-pill.over  { background: #dcfce7; color: #15803d; }
        .status-pill.match { background: #f1f5f9; color: #475569; }

        /* ─────────────────────────────────────────────
           NOTE
        ───────────────────────────────────────────── */
        .note-block {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #2563eb;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 26px;
        }
        .note-block .section-label { margin-bottom: 8px; color: #334155; }
        .note-block p {
            margin: 0;
            font-size: 13px;
            color: #334155;
            line-height: 1.55;
            white-space: pre-wrap;
        }

        /* ─────────────────────────────────────────────
           SIGNATURES
        ───────────────────────────────────────────── */
        .signature {
            margin-top: 52px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }
        .sig-line {
            border-top: 1px solid #94a3b8;
            padding-top: 10px;
            font-size: 11px;
            color: #64748b;
            text-align: center;
            font-weight: 500;
        }

        /* ─────────────────────────────────────────────
           FOOTER
        ───────────────────────────────────────────── */
        .doc-footer {
            margin-top: 34px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            color: #94a3b8;
            flex-wrap: wrap;
            gap: 6px;
        }
        .doc-footer strong { color: #64748b; font-weight: 600; }

        /* ─────────────────────────────────────────────
           RESPONSIVE (screen only)
        ───────────────────────────────────────────── */
        @media (max-width: 640px) {
            body { padding: 16px 12px; }
            .doc-card { padding: 24px 20px; }
            .doc-header { flex-direction: column; align-items: stretch; }
            .title-block { text-align: left; }
            .title-block h1 { font-size: 18px; }
            .details-row { grid-template-columns: 1fr; gap: 2px; padding: 10px 0; }
            .details-label { font-size: 11px; }
            .signature { grid-template-columns: 1fr; gap: 40px; }
        }
    </style>
</head>
<body>

@php
    /**
     * Logo detection — checks common paths, in priority order.
     * The first file that exists wins.
     *
     * Your file is currently:  public/image/Seven_Inc_logo.jpg
     */
    $logoSources = [
        'image/Seven_Inc_logo.jpg',   // ← your current file
        'image/Seven_Inc_logo.png',   // ← future-proof if you re-export
        'image/Seven_Inc_logo.svg',   // ← best quality if you add SVG later
        'images/seven-logo.png',
        'images/seven-logo.svg',
        'images/logo.png',
        'images/logo.svg',
    ];

    $logoUrl = null;
    foreach ($logoSources as $candidate) {
        if (file_exists(public_path($candidate))) {
            $logoUrl = asset($candidate);
            break;
        }
    }

    $statusKey   = strtolower($data['status']);   // under / over / match
    $diffValue   = (int) $data['difference'];
    $diffDisplay = ($diffValue > 0 ? '+' : '') . number_format($diffValue);
    $diffClass   = $diffValue < 0 ? 'red' : ($diffValue > 0 ? 'green' : '');

    $productInitial = strtoupper(substr($data['product'], 0, 1));
@endphp

<div class="doc-wrapper">

    {{-- ─────────────────────────────────────────
         TOOLBAR
    ───────────────────────────────────────── --}}
    <div class="toolbar no-print">
        <button type="button" onclick="window.print()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            {{ __('Print Now') }}
        </button>
        <a href="{{ route('opname.index') }}" class="btn btn-ghost">
            {{ __('Close') }}
        </a>
    </div>

    <div class="doc-card">

        {{-- ─────────────────────────────────────────
             HEADER — company left, title right
        ───────────────────────────────────────── --}}
        <div class="doc-header">
            <div class="company-block">
                @if ($logoUrl)
                    <div class="company-logo">
                        <img src="{{ $logoUrl }}" alt="Seven Inc. logo">
                    </div>
                @else
                    <div class="company-logo-fallback">S</div>
                @endif
                <div class="company-info">
                    <h2 class="company-name">Seven Inc<span class="accent">.</span></h2>
                    <p class="company-tagline">{{ __('Multi-Sector Digital Enterprise') }}</p>
                    <p class="company-meta">
                        <strong>CV. Sanjaya 57</strong> · Est. 2012 · Yogyakarta, Indonesia
                    </p>
                </div>
            </div>

            <div class="title-block">
                <h1>{{ __('Stock Opname Report') }}</h1>
                <p class="subtitle">{{ __('Inventory Reconciliation') }}</p>
                <div class="doc-meta">
                    <strong>{{ __('Date') }}</strong> {{ $data['opname_date'] }}<br>
                    <strong>{{ __('No') }}</strong> {{ $data['opname_id'] }}
                </div>
            </div>
        </div>

        {{-- Contact line --}}
        <div class="contact-line">
            <span>{{ __('Yogyakarta, Indonesia') }}</span>
            <span>{{ __('Telp') }}: 021-1234567 · {{ __('Email') }}: info@seveninc.id</span>
        </div>

        {{-- Dark divider --}}
        <hr class="dark-divider">

        {{-- ─────────────────────────────────────────
             SECTION 1 — PRODUCT INFORMATION
        ───────────────────────────────────────── --}}
        <p class="section-label">{{ __('Product Information') }}</p>

        <div class="product-card">
            <div class="avatar-badge">{{ $productInitial }}</div>
            <div class="product-info">
                <p class="product-name">{{ $data['product'] }}</p>
                <p class="product-sku">{{ $data['sku'] }}</p>
                <p class="product-desc">
                    {{ $data['product'] }} — {{ $data['category'] }}
                </p>
            </div>
        </div>

        {{-- ─────────────────────────────────────────
             SECTION 2 — DETAILS
        ───────────────────────────────────────── --}}
        <p class="section-label">{{ __('Details') }}</p>

        <div class="details-table">
            <div class="details-row">
                <div class="details-label">{{ __('Opname Date') }}</div>
                <div class="details-value">{{ $data['opname_date'] }}</div>
            </div>
            <div class="details-row">
                <div class="details-label">{{ __('Recorded By') }}</div>
                <div class="details-value">{{ $data['user'] }}</div>
            </div>
            <div class="details-row">
                <div class="details-label">{{ __('Category') }}</div>
                <div class="details-value">{{ $data['category'] }}</div>
            </div>
            <div class="details-row">
                <div class="details-label">{{ __('SKU') }}</div>
                <div class="details-value mono">{{ $data['sku'] }}</div>
            </div>
            <div class="details-row">
                <div class="details-label">{{ __('Printed At') }}</div>
                <div class="details-value">{{ $data['printed_at'] }}</div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────
             SECTION 3 — STOCK SUMMARY (blue box)
        ───────────────────────────────────────── --}}
        <div class="summary-box">
            <p class="section-label">{{ __('Stock Summary') }}</p>

            <div class="summary-row">
                <span class="label">{{ __('System Stock') }}</span>
                <span class="value">{{ number_format($data['system_stock']) }} {{ __('unit') }}</span>
            </div>

            <div class="summary-row">
                <span class="label">{{ __('Physical Stock') }}</span>
                <span class="value">{{ number_format($data['physical_stock']) }} {{ __('unit') }}</span>
            </div>

            <div class="summary-row">
                <span class="label">{{ __('Status') }}</span>
                <span class="value">
                    <span class="status-pill {{ $statusKey }}">{{ $data['status_label'] }}</span>
                </span>
            </div>

            <div class="summary-row total">
                <span class="label">
                    @if ($diffValue < 0)
                        {{ __('Shrinkage') }}
                    @elseif ($diffValue > 0)
                        {{ __('Surplus') }}
                    @else
                        {{ __('Difference') }}
                    @endif
                </span>
                <span class="value {{ $diffClass }}">
                    {{ $diffDisplay }} {{ __('unit') }}
                </span>
            </div>
        </div>

        {{-- ─────────────────────────────────────────
             NOTES (optional)
        ───────────────────────────────────────── --}}
        @if (!empty($data['note']))
            <div class="note-block">
                <p class="section-label">{{ __('Notes') }}</p>
                <p>{{ $data['note'] }}</p>
            </div>
        @endif

        {{-- ─────────────────────────────────────────
             SIGNATURES
        ───────────────────────────────────────── --}}
        <div class="signature">
            <div class="sig-line">{{ __('Counted by') }}</div>
            <div class="sig-line">{{ __('Approved by') }}</div>
        </div>

        {{-- ─────────────────────────────────────────
             FOOTER
        ───────────────────────────────────────── --}}
        <div class="doc-footer">
            <span><strong>Seven Inc.</strong> · stockify.id · Yogyakarta</span>
            <span>{{ $data['opname_id'] }} · {{ $data['printed_at'] }}</span>
        </div>

    </div>
</div>

</body>
</html>