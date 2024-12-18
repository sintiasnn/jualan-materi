<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActiveSession;

class CheckDeviceLimit
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->id();
            
            // Clean up old sessions
            ActiveSession::where('last_active_at', '<', now()->subHours(24))->delete();
            
            // Get current session token
            $currentToken = session()->getId();
            $hasExistingSession = ActiveSession::where('token', $currentToken)->exists();
            
            // Count active sessions
            $activeSessions = ActiveSession::where('user_id', $user)->count();
            
            if (!$hasExistingSession && $activeSessions >= 3) {
                // For AJAX/JSON requests (like login)
                if ($request->expectsJson() || $request->is('login')) {
                    return response()->json([
                        'maxDevicesReached' => true,
                        'message' => 'Maximum device limit reached. Would you like to logout from another device?'
                    ], 429);
                }
                
                // For regular requests
                return redirect()->route('sessions.index')
                    ->with('warning', 'Maximum device limit reached. Please logout from another device to continue.');
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