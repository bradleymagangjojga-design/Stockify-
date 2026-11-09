<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Log a user activity.
     */
    protected function logActivity(
        string $action,
        ?string $description = null,
        $model = null,
        array $extra = []
    ): ?ActivityLog {
        if (function_exists('log_activity')) {
            return log_activity($action, $description, $model, $extra);
        }

        return null;
    }
}
