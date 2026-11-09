<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    // Table has only created_at, no updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'login'    => '🔓',
            'logout'   => '🔒',
            'created'  => '➕',
            'updated'  => '✏️',
            'deleted'  => '🗑️',
            'exported' => '📥',
            'viewed'   => '👁️',
            default    => '📝',
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'login'    => 'green',
            'logout'   => 'slate',
            'created'  => 'blue',
            'updated'  => 'orange',
            'deleted'  => 'red',
            'exported' => 'purple',
            'viewed'   => 'slate',
            default    => 'slate',
        };
    }
}
