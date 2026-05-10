<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMembership extends Model
{
    protected $fillable = [
        'user_id', 'group_id', 'role', 'status', 'joined_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($membership) {
            if ($membership->status === 'approved' && !$membership->joined_at) {
                $membership->joined_at = now();
            }
        });

        static::updating(function ($membership) {
            if ($membership->isDirty('status') && $membership->status === 'approved') {
                $membership->joined_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Scope pour les membres approuvés
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope pour les demandes en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
