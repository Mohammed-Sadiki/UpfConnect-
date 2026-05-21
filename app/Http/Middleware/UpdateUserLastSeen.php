<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeen
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Only update database at most once per minute to avoid query spam
            if (!$user->last_seen_at || $user->last_seen_at->lessThan(now()->subMinutes(1))) {
                $user->timestamps = false;
                $user->update(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }
}
