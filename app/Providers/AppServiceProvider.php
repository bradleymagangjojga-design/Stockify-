<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Share $appName with ALL Blade views globally
        // Reads from the settings table. Falls back to config('app.name') if DB isn't ready.
        try {
            $appName = Setting::get('app_name', config('app.name', 'Stockify'));
        } catch (\Exception $e) {
            $appName = config('app.name', 'Stockify');
        }

        View::share('appName', $appName);
    }
}
