<?php

namespace App\Http\Controllers;

use App\Models\ActiveSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function showActiveSessions()
    {
        // dd('Reached controller');  // Check if we hit this point
        
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

        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Perangkat berhasil di logout!');
    }

    public function forceLogout()
    {
        $oldestSession = ActiveSession::where('user_id', auth()->id())
            ->orderBy('last_active_at', 'asc')
            ->first();

        if ($oldestSession) {
            $oldestSession->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}