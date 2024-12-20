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
            
            // Get current session
            $currentToken = session()->getId();
            $hasExistingSession = ActiveSession::where('token', $currentToken)->exists();
            
            // Count active sessions
            $activeSessions = ActiveSession::where('user_id', $user)->count();
            
            if (!$hasExistingSession && $activeSessions >= 3) {
                // If this is a login attempt
                if ($request->is('login') && $request->isMethod('post')) {
                    return response()->json([
                        'maxDevicesReached' => true,
                        'message' => 'Maximum device limit reached. Would you like to logout from another device?'
                    ], 429);
                }
                
                // For other requests, redirect to sessions page
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