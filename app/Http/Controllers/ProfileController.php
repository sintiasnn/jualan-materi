<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);
    
        // Determine which user's avatar to update
        $user = $request->has('user_id') ? User::findOrFail($request->user_id) : auth()->user();
        $oldAvatar = $user->avatar; // Get the current avatar name
    
        try {
            // Check if the request has a new avatar file
            if ($request->hasFile('avatar')) {
                // Define the new avatar filename
                $avatar = $request->file('avatar');
                $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
    
                // Move the new avatar to the server
                $avatar->move(public_path('assets/img/avatar'), $avatarName);
    
                // Delete the old avatar if it's not 'default.jpg'
                if ($oldAvatar !== 'default.jpg') {
                    $oldAvatarPath = public_path('assets/img/avatar/' . $oldAvatar);
                    if (File::exists($oldAvatarPath)) {
                        File::delete($oldAvatarPath);
                    }
                }
    
                // Update the user's avatar in the database
                $user->avatar = $avatarName;
                $user->save();
    
                // Handle the response based on request type
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto profil berhasil diperbarui.'
                    ]);
                }
    
                // Set success message to session for non-ajax requests
                session()->flash('swal:modal', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Foto profil berhasil diperbarui.',
                ]);
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui foto profil.'
                ], 500);
            }
    
            // Set error message to session for non-ajax requests
            session()->flash('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat memperbarui foto profil.',
            ]);
        }
    
        // For non-ajax requests, redirect back
        return back();
    }
}

