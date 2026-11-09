<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Cache key used for all settings.
     */
    private const CACHE_KEY = 'stockify.settings.all';

    /**
     * Get a setting value by key, with a default fallback.
     */
    public static function get(string $key, $default = null)
    {
        $all = static::allCached();

        return $all[$key] ?? $default;
    }

    /**
     * Set a setting value by key (creates or updates) AND clears the cache.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key'   => $key],
            ['value' => (string) $value]
        );

        // ✅ Bust the cache so the next get() reads fresh data
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get all settings as key => value array (cached).
     */
    public static function all_as_array(): array
    {
        return static::allCached();
    }

    /**
     * Internal: load all settings via cache.
     */
    private static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Manually flush the settings cache.
     * Useful after bulk updates or in tinker.
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
