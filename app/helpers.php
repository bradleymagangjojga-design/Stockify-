<?php

use App\Models\Setting;

/* ============================================================
 * CURRENCY HELPERS
 * ============================================================ */

if (!function_exists('app_currency')) {
    function app_currency(): string
    {
        static $cached = null;

        if ($cached === null) {
            try {
                $cached = Setting::get('currency', 'IDR');
            } catch (\Throwable $e) {
                $cached = 'IDR';
            }
        }

        return $cached;
    }
}

if (!function_exists('base_currency')) {
    function base_currency(): string
    {
        static $cached = null;

        if ($cached === null) {
            try {
                $cached = Setting::get('base_currency', 'IDR');
            } catch (\Throwable $e) {
                $cached = 'IDR';
            }
        }

        return $cached;
    }
}

if (!function_exists('currency_config')) {
    function currency_config(?string $code = null): array
    {
        $code = strtoupper($code ?? app_currency());

        return match ($code) {
            'IDR' => ['symbol' => 'Rp',  'space' => true,  'decimals' => 0, 'dec' => ',', 'thou' => '.'],
            'USD' => ['symbol' => '$',   'space' => false, 'decimals' => 2, 'dec' => '.', 'thou' => ','],
            'SGD' => ['symbol' => 'S$',  'space' => false, 'decimals' => 2, 'dec' => '.', 'thou' => ','],
            'MYR' => ['symbol' => 'RM',  'space' => true,  'decimals' => 2, 'dec' => '.', 'thou' => ','],
            'EUR' => ['symbol' => '€',   'space' => false, 'decimals' => 2, 'dec' => ',', 'thou' => '.'],
            'GBP' => ['symbol' => '£',   'space' => false, 'decimals' => 2, 'dec' => '.', 'thou' => ','],
            default => ['symbol' => $code, 'space' => true, 'decimals' => 2, 'dec' => '.', 'thou' => ','],
        };
    }
}

if (!function_exists('exchange_rate')) {
    function exchange_rate(?string $currency = null): float
    {
        $currency = strtoupper($currency ?? app_currency());

        if ($currency === base_currency()) {
            return 1.0;
        }

        $defaults = [
            'IDR' => 1,
            'USD' => 16000,
            'SGD' => 11800,
            'MYR' => 3400,
            'EUR' => 17000,
            'GBP' => 20000,
        ];

        $default = $defaults[$currency] ?? 1;

        try {
            $rate = (float) Setting::get("exchange_rate_{$currency}", $default);
            return $rate > 0 ? $rate : $default;
        } catch (\Throwable $e) {
            return (float) $default;
        }
    }
}

if (!function_exists('convert_currency')) {
    function convert_currency(float $amount, ?string $toCurrency = null): float
    {
        $toCurrency = strtoupper($toCurrency ?? app_currency());

        if ($toCurrency === base_currency()) {
            return $amount;
        }

        return $amount / exchange_rate($toCurrency);
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol(?string $code = null): string
    {
        return currency_config($code)['symbol'];
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount, ?string $code = null): string
    {
        $code = strtoupper($code ?? app_currency());
        $converted = convert_currency((float) $amount, $code);

        $cfg = currency_config($code);
        $formatted = number_format($converted, $cfg['decimals'], $cfg['dec'], $cfg['thou']);
        $separator = $cfg['space'] ? ' ' : '';

        return $cfg['symbol'] . $separator . $formatted;
    }
}

if (!function_exists('format_currency_compact')) {
    function format_currency_compact($amount, ?string $code = null): string
    {
        $code = strtoupper($code ?? app_currency());
        $converted = convert_currency((float) $amount, $code);

        $cfg = currency_config($code);
        $separator = $cfg['space'] ? ' ' : '';
        $decSep = $cfg['dec'];

        if (abs($converted) >= 1_000_000_000) {
            $val = number_format($converted / 1_000_000_000, 1, $decSep, '');
            return $cfg['symbol'] . $separator . $val . 'B';
        }
        if (abs($converted) >= 1_000_000) {
            $val = number_format($converted / 1_000_000, 1, $decSep, '');
            return $cfg['symbol'] . $separator . $val . 'M';
        }
        if (abs($converted) >= 1_000) {
            $val = number_format($converted / 1_000, 1, $decSep, '');
            return $cfg['symbol'] . $separator . $val . 'K';
        }

        return format_currency($amount, $code);
    }
}

if (!function_exists('format_number')) {
    function format_number($value, ?int $decimals = null, ?string $code = null): string
    {
        $cfg = currency_config($code);
        $decimals = $decimals ?? $cfg['decimals'];

        return number_format((float) $value, $decimals, $cfg['dec'], $cfg['thou']);
    }
}

/* ============================================================
 * ACTIVITY LOG HELPER
 * ============================================================ */

if (!function_exists('log_activity')) {
    /**
     * Log a user activity globally.
     *
     * Examples:
     *   log_activity('login', 'User logged in');
     *   log_activity('updated', 'Updated product', $product);
     *   log_activity('exported', 'Exported CSV', null, ['model' => 'Report']);
     */
    function log_activity(
        string $action,
        ?string $description = null,
        $model = null,
        array $extra = []
    ): ?\App\Models\ActivityLog {
        try {
            $modelClass = null;
            $modelId = null;

            if ($model) {
                if (is_object($model)) {
                    $modelClass = get_class($model);
                    $modelId = $model->id ?? null;
                } elseif (is_string($model)) {
                    $modelClass = $model;
                }
            }

            return \App\Models\ActivityLog::create(array_merge([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'model'       => $modelClass,
                'model_id'    => $modelId,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'user_agent'  => substr((string) request()->userAgent(), 0, 500),
                'created_at'  => now(),
            ], $extra));
        } catch (\Throwable $e) {
            // Silent fail — logging should never break the app
            \Log::warning('log_activity failed: ' . $e->getMessage());
            return null;
        }
    }
}
