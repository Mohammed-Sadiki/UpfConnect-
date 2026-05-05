<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'linkedin_url', 'github_url', 'cv_path', 'skills', 'interests', 'year_of_study'
    ];

    protected $casts = [
        'skills' => 'array',
        'interests' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
