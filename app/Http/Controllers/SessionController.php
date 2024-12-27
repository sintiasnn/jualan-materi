<?php

namespace App\Http\Controllers;

use App\Models\ActiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function showActiveSessions()
    {
        $sessions = ActiveSession::where('user_id', auth()->id())
            ->orderBy('last_active_at', 'desc')
            ->get();

        return view('livewire.pages.auth.sessions', compact('sessions'));
    }

    public function destroy(ActiveSession $session)
    {
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();
            
            // Get user info before deletion
            $userId = $session->user_id;
            $deviceToken = $session->token;

            // Add this user's ID to a blacklist with a timestamp
            cache()->put("forced_logout_{$userId}_{$deviceToken}", now(), now()->addMinutes(5));

            // Delete the session
            $session->delete();

            DB::commit();
            return back()->with('success', 'Perangkat berhasil dikeluarkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengeluarkan perangkat. Silakan coba lagi.');
        }
    }

    public function forceLogout()
    {
        try {
            DB::beginTransaction();

            $oldestSession = ActiveSession::where('user_id', auth()->id())
                ->orderBy('last_active_at', 'asc')
                ->first();

            if ($oldestSession) {
                // Delete from active_sessions
                $oldestSession->delete();

                // Delete from Laravel's sessions table if using database driver
                if (config('session.driver') === 'database') {
                    DB::table('active_sessions')->where('id', $oldestSession->token)->delete();
                }

                DB::commit();
                return response()->json(['success' => true]);
            }

            DB::commit();
            return response()->json(['success' => false], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false], 500);
        }
    }
}