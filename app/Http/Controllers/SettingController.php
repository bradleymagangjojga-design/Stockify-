<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog; // ✅ Import ActivityLog
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    /**
     * Display the Settings page.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // ============================================================
        // Fetch Activity Log for the Settings "Activity Log" tab
        //    - Admin & Manager: see ALL activities
        //    - Staff Gudang: see only THEIR OWN activities
        //    - Only shows Login, Logout, Stock, and Stock Opname activities
        // ============================================================
        $activityQuery = ActivityLog::with('user');

        // Restrict Staff to only see their own activity logs
        if ($user->role === 'Staff Gudang') {
            $activityQuery->where('user_id', $user->id);
        }

        // Filter: only show Login/Logout and warehouse operations
        $activityQuery->where(function ($q) {
            $q->whereIn('action', ['login', 'logout'])
              ->orWhere('description', 'LIKE', '%stock%')
              ->orWhere('description', 'LIKE', '%opname%')
              ->orWhere('description', 'LIKE', '%incoming%')
              ->orWhere('description', 'LIKE', '%outgoing%')
              ->orWhere('description', 'LIKE', '%goods%');
        });

        // Paginate with a custom page name so it doesn't conflict with other tabs
        // appends(['active_tab' => 'activities']) keeps the tab open on page 2+
        $activities = $activityQuery
            ->latest('created_at')
            ->latest('id')
            ->paginate(20, ['*'], 'activities_page')
            ->appends(['active_tab' => 'activities']);

        // Determine active tab (from request, session, or role-based default)
        $defaultTab = $user->role === 'Admin' ? 'general' : 'appearance';
        $activeTab = $request->input('active_tab', session('active_tab', $defaultTab));

        return view('settings.index', [
            'currentLocale'    => Session::get('locale', config('app.locale', 'id')),
            'availableLocales' => [
                'id' => ['flag' => '🇮🇩', 'name' => 'Bahasa Indonesia', 'native' => 'Bahasa Indonesia'],
                'en' => ['flag' => '🇺🇸', 'name' => 'English',          'native' => 'English'],
            ],
            'activities'       => $activities,
            'activeTab'        => $activeTab,
            'settings' => [
                'app_name'             => Setting::get('app_name', config('app.name', 'Stockify')),
                'timezone'             => Setting::get('timezone', 'Asia/Jakarta'),
                'currency'             => Setting::get('currency', 'IDR'),
                'dark_mode'            => Setting::get('dark_mode', 'false'),
                'primary_color'        => $user->primary_color ?: Setting::get('primary_color', 'blue'),
                'layout_density'       => Setting::get('layout_density', 'default'),
                'low_stock_threshold'  => Setting::get('low_stock_threshold', '5'),
                'auto_update_stock'    => Setting::get('auto_update_stock', 'true'),
                'allow_negative_stock' => Setting::get('allow_negative_stock', 'false'),
                'notif_low_stock'      => Setting::get('notif_low_stock', 'true'),
                'notif_transaction'    => Setting::get('notif_transaction', 'true'),
                'notif_incoming'       => Setting::get('notif_incoming', 'true'),
                'notif_outgoing'       => Setting::get('notif_outgoing', 'false'),
                'notif_daily_report'   => Setting::get('notif_daily_report', 'false'),
                'auto_logout'          => Setting::get('auto_logout', 'true'),
            ],
        ]);
    }

    /**
     * Update the current session locale.
     */
    public function updateLocale(Request $request)
    {
        $data = $request->validate([
            'locale' => 'required|in:id,en',
        ]);

        Session::put('locale', $data['locale']);
        app()->setLocale($data['locale']);

        return back()
            ->with('active_tab', $request->input('active_tab', 'language'))
            ->with('success', 'Bahasa berhasil diubah / Language updated successfully.');
    }

    /**
     * Save General settings.
     */
    public function saveGeneral(Request $request)
    {
        abort_if(auth()->user()->role !== 'Admin', 403, 'Unauthorized action.');

        $data = $request->validate([
            'app_name' => 'required|string|max:100',
            'timezone' => 'required|string|max:50',
            'currency' => 'required|string|max:10',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()
            ->with('active_tab', $request->input('active_tab', 'general'))
            ->with('success', 'Pengaturan umum berhasil disimpan / General settings saved.');
    }

    /**
     * Save Appearance settings.
     */
    public function saveAppearance(Request $request)
    {
        $data = $request->validate([
            'dark_mode'      => 'nullable|in:true,false,on,off,1,0',
            'primary_color'  => 'nullable|in:blue,green,purple,orange,pink,slate',
            'layout_density' => 'nullable|in:compact,default,wide',
        ]);

        Setting::set('dark_mode',      $request->boolean('dark_mode') ? 'true' : 'false');
        Setting::set('layout_density', $data['layout_density'] ?? 'default');

        if (!empty($data['primary_color'])) {
            auth()->user()->update([
                'primary_color' => $data['primary_color'],
            ]);
        }

        return back()
            ->with('active_tab', $request->input('active_tab', 'appearance'))
            ->with('success', 'Pengaturan tampilan berhasil disimpan / Appearance settings saved.');
    }

    /**
     * Save Inventory settings.
     */
    public function saveInventory(Request $request)
    {
        abort_if(auth()->user()->role !== 'Admin', 403, 'Unauthorized action.');

        $data = $request->validate([
            'low_stock_threshold'  => 'required|integer|min:1|max:1000',
            'auto_update_stock'    => 'nullable|boolean',
            'allow_negative_stock' => 'nullable|boolean',
        ]);

        Setting::set('low_stock_threshold',  $data['low_stock_threshold']);
        Setting::set('auto_update_stock',    $request->boolean('auto_update_stock') ? 'true' : 'false');
        Setting::set('allow_negative_stock', $request->boolean('allow_negative_stock') ? 'true' : 'false');

        return back()
            ->with('active_tab', $request->input('active_tab', 'inventory'))
            ->with('success', 'Pengaturan inventaris berhasil disimpan / Inventory settings saved.');
    }

    /**
     * Save Notification settings.
     */
    public function saveNotifications(Request $request)
    {
        $keys = [
            'notif_low_stock',
            'notif_transaction',
            'notif_incoming',
            'notif_outgoing',
            'notif_daily_report',
        ];

        foreach ($keys as $key) {
            $rawValue = (string) $request->input($key, 'false');
            $isOn = in_array(strtolower($rawValue), ['true', '1', 'on', 'yes'], true);
            Setting::set($key, $isOn ? 'true' : 'false');
        }

        return back()
            ->with('active_tab', $request->input('active_tab', 'notifications'))
            ->with('success', 'Pengaturan notifikasi berhasil disimpan / Notification settings saved.');
    }

    /**
     * Save Security settings.
     */
    public function saveSecurity(Request $request)
    {
        abort_if(auth()->user()->role !== 'Admin', 403, 'Unauthorized action.');

        Setting::set('auto_logout', $request->boolean('auto_logout') ? 'true' : 'false');

        return back()
            ->with('active_tab', $request->input('active_tab', 'security'))
            ->with('success', 'Pengaturan keamanan berhasil disimpan / Security settings saved.');
    }
}
