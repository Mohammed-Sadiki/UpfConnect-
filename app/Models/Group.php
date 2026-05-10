<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $fillable = [
        'created_by', 'name', 'description', 'image', 'visibility', 'category', 'members_count', 'posts_count'
    ];

    protected $casts = [
        'members_count' => 'integer',
        'posts_count' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(GroupMembership::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Vérifie si l'utilisateur est membre du groupe
     */
    public function isMember(User $user): bool
    {
        return $this->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur est admin du groupe
     */
    public function isAdmin(User $user): bool
    {
        return $this->memberships()
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur est modérateur ou admin
     */
    public function isModerator(User $user): bool
    {
        return $this->memberships()
            ->where('user_id', $user->id)
            ->whereIn('role', ['admin', 'moderator'])
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur a une demande en attente
     */
    public function hasPendingRequest(User $user): bool
    {
        return $this->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Scope pour les groupes publics
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope pour les groupes d'une catégorie
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
