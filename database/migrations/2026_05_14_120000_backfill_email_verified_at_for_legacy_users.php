<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Legacy accounts (created before email verification was enforced) often have
     * a null email_verified_at. The "verified" middleware then blocks access after login.
     * Treat existing unverified rows as verified using their account creation time.
     */
    public function up(): void
    {
        User::query()
            ->whereNull('email_verified_at')
            ->chunkById(500, function ($users): void {
                foreach ($users as $user) {
                    $user->timestamps = false;
                    $user->forceFill([
                        'email_verified_at' => $user->created_at ?? now(),
                    ])->saveQuietly();
                }
            });
    }

    /**
     * Reverse migrations cannot know which addresses were truly unverified.
     */
    public function down(): void
    {
        //
    }
};
