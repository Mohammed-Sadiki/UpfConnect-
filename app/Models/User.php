<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'bio', 'avatar', 
        'department', 'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class); // Events created by user
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    // Connections: users this user sent a connection request to
    public function connectionsSent(): HasMany
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    // Connections: users who sent a connection request to this user
    public function connectionsReceived(): HasMany
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    // Groups relations
    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMembership::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_memberships')
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    /**
     * Groupes où l'utilisateur est membre approuvé
     */
    public function approvedGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_memberships')
            ->wherePivot('status', 'approved')
            ->withPivot('role', 'joined_at');
    }
}
