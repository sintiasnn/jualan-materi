<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActiveSession;
use Illuminate\Support\Facades\DB;

class CheckDeviceLimit
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->id();
            
            // Clean up old sessions first
            ActiveSession::where('last_active_at', '<', now()->subHours(24))->delete();
            
            $currentToken = session()->getId();
            $hasExistingSession = ActiveSession::where('token', $currentToken)->exists();
            
            // Count active sessions
            $activeSessions = ActiveSession::where('user_id', $user)->count();
            
            if (!$hasExistingSession && $activeSessions >= 3) {
                // Log out the user from this device
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
                
                // Redirect to sessions page
                return redirect()->route('sessions.index')
                    ->with('error', 'Maksimal perangkat tercapai. Silakan logout dari perangkat lain terlebih dahulu.');
            }

            // Update or create session record
            ActiveSession::updateOrCreate(
                ['token' => $currentToken],
                [
                    'user_id' => $user,
                    'device_name' => $request->userAgent(),
                    'last_active_at' => now()
                ]
            );
        }
        
        return $next($request);
    }
}