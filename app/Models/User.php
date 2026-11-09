<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'status',
        'primary_color',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [];

    // ============================================================
    // ROLE HELPERS
    // ============================================================

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'Manajer Gudang';
    }

    public function isStaff(): bool
    {
        return $this->role === 'Staff Gudang';
    }

    /**
     * Check if the user can edit products / inventory.
     * Admin + Manager = yes. Staff = no.
     */
    public function canEdit(): bool
    {
        return in_array($this->role, ['Admin', 'Manajer Gudang']);
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * ✅ Activity logs performed by this user.
     */
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ActivityLog::class);
    }
}
