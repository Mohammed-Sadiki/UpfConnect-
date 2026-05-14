<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Envoie l’e-mail de vérification hors de la requête HTTP (évite timeout sur /register et renvoi de lien).
 * Avec QUEUE_CONNECTION=database : lancez `php artisan queue:work`.
 */
class SendEmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $userId) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        if ($user === null || $user->hasVerifiedEmail()) {
            return;
        }

        $user->notify(new VerifyEmail);
    }
}
