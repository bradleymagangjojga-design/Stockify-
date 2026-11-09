<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    /**
     * Session key used to store notification read states.
     */
    private const SESSION_KEY = 'stockify_notifications_read';

    /**
     * How many notifications to show on the page.
     */
    private const LIMIT = 50;

    /**
     * Display notifications list.
     */
    public function index()
    {
        $notifications = $this->getNotifications();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read (AJAX).
     * Route: POST /notifications/{id}/read
     */
    public function markAsRead(Request $request, int $id)
    {
        $readIds = $this->getReadIds();
        if (!in_array($id, $readIds, true)) {
            $readIds[] = $id;
            session([self::SESSION_KEY => $readIds]);
        }

        return response()->json([
            'success' => true,
            'id'      => $id,
            'unread'  => $this->getUnreadCount(),
        ]);
    }

    /**
     * Mark all notifications as read.
     * Route: POST /notifications/mark-all-read
     */
    public function markAllRead()
    {
        $allIds = $this->allNotificationIds();
        session([self::SESSION_KEY => $allIds]);

        return response()->json([
            'success' => true,
            'unread'  => 0,
        ]);
    }

    /**
     * Reset read state — useful for testing.
     * Route: POST /notifications/reset
     */
    public function reset()
    {
        session()->forget(self::SESSION_KEY);

        return response()->json(['success' => true]);
    }

    // ============================================================
    // Helpers
    // ============================================================

    /**
     * Build the notification collection from ActivityLog rows.
     */
    private function getNotifications()
    {
        $readIds = $this->getReadIds();

        if (! $this->tableExists()) {
            return collect();
        }

        return ActivityLog::query()
            ->latest()
            ->limit(self::LIMIT)
            ->get()
            ->map(function (ActivityLog $log) use ($readIds) {
                return [
                    'id'          => (int) $log->id,
                    'type'        => $this->mapType($log),
                    'title'       => $this->mapTitle($log),
                    'description' => $this->extractDescription($log),
                    'time'        => $log->created_at ?? now(),
                    'read'        => in_array((int) $log->id, $readIds, true),
                ];
            });
    }

    /**
     * Check if the activity_logs table exists (avoids errors on fresh installs).
     */
    private function tableExists(): bool
    {
        try {
            return Schema::hasTable('activity_logs') || Schema::hasTable('activity_log');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Pick the actual table name that exists.
     */
    private function tableName(): string
    {
        return Schema::hasTable('activity_logs') ? 'activity_logs' : 'activity_log';
    }

    /**
     * Read the first non-null value from a list of candidate columns.
     */
    private function firstValue(ActivityLog $log, array $candidates, $default = null)
    {
        foreach ($candidates as $col) {
            if (!empty($log->{$col})) {
                return $log->{$col};
            }
        }
        return $default;
    }

    /**
     * Extract a description from whatever column holds it.
     */
    private function extractDescription(ActivityLog $log): string
    {
        return (string) $this->firstValue($log, [
            'description',
            'message',
            'details',
            'note',
            'subject',
        ], '');
    }

    /**
     * Extract the action/type string.
     */
    private function extractAction(ActivityLog $log): string
    {
        return strtolower((string) $this->firstValue($log, [
            'type',
            'action',
            'event',
            'category',
            'log_name',
        ], ''));
    }

    /**
     * Map the action string to one of: warning, success, info, error.
     * Adjust the keywords below to match your app's action values.
     */
    private function mapType(ActivityLog $log): string
    {
        $action = $this->extractAction($log);
        $text   = strtolower($action . ' ' . $this->extractDescription($log));

        return match (true) {
            str_contains($text, 'delete')   => 'error',
            str_contains($text, 'error')    => 'error',
            str_contains($text, 'fail')     => 'error',

            str_contains($text, 'low')      => 'warning',
            str_contains($text, 'menipis')  => 'warning',
            str_contains($text, 'warning')  => 'warning',
            str_contains($text, 'out')      => 'warning',
            str_contains($text, 'keluar')   => 'warning',

            str_contains($text, 'in')       => 'success',
            str_contains($text, 'masuk')    => 'success',
            str_contains($text, 'create')   => 'success',
            str_contains($text, 'success')  => 'success',
            str_contains($text, 'berhasil') => 'success',

            default                          => 'info',
        };
    }

    /**
     * Map the action string to a human title.
     */
    private function mapTitle(ActivityLog $log): string
    {
        $action = $this->extractAction($log);
        $text   = strtolower($action . ' ' . $this->extractDescription($log));

        return match (true) {
            str_contains($text, 'low')      => __('Low Stock'),
            str_contains($text, 'menipis')  => __('Low Stock'),
            str_contains($text, 'out')      => __('Goods Out'),
            str_contains($text, 'keluar')   => __('Goods Out'),
            str_contains($text, 'in')       => __('Goods In'),
            str_contains($text, 'masuk')    => __('Goods In'),
            str_contains($text, 'opname')   => __('Stock Opname'),
            str_contains($text, 'create')   => __('Created'),
            str_contains($text, 'update')   => __('Updated'),
            str_contains($text, 'delete')   => __('Deleted'),
            default                          => $action ? ucfirst($action) : __('Activity'),
        };
    }

    /**
     * Notification IDs that the user has marked as read (session-backed).
     */
    private function getReadIds(): array
    {
        $ids = session(self::SESSION_KEY, []);
        return array_map('intval', is_array($ids) ? $ids : []);
    }

    /**
     * All notification IDs currently stored.
     */
    private function allNotificationIds(): array
    {
        if (! $this->tableExists()) {
            return [];
        }

        return ActivityLog::query()
            ->latest()
            ->limit(self::LIMIT)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Count of unread notifications.
     */
    private function getUnreadCount(): int
    {
        $readIds = $this->getReadIds();

        if (! $this->tableExists()) {
            return 0;
        }

        return ActivityLog::whereNotIn('id', $readIds)->count();
    }
}
