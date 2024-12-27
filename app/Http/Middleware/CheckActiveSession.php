<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActiveSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckActiveSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $currentToken = session()->getId();
            $userId = Auth::id();
            
            // First check if this device was forcefully logged out
            $wasForceLoggedOut = cache()->has("forced_logout_{$userId}_{$currentToken}");
            
            // Check if session exists in active_sessions
            $sessionExists = ActiveSession::where('token', $currentToken)
                ->where('user_id', $userId)
                ->exists();

            if ($wasForceLoggedOut || !$sessionExists) {
                // Clean up any existing sessions
                ActiveSession::where('token', $currentToken)->delete();
                
                // Clear auth and session
                Auth::guard('web')->logout();
                session()->invalidate();
                session()->regenerateToken();
                
                // Clear the cache flag
                cache()->forget("forced_logout_{$userId}_{$currentToken}");

                return redirect()->route('login')
                    ->with('error', 'Sesi Anda telah berakhir karena login di perangkat lain.');
            }

                $path = $request->path();
                // Update last URL for the session
                ActiveSession::where('token', $currentToken)
                    ->where('user_id', Auth::id())
                    ->update([
                        'last_url' => '/'.$path, // Adding slash at the beginning for consistency
                        'last_active_at' => now()
                    ]);
            }

    return $next($request);
    }
}