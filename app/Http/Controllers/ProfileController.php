<?php


namespace App\Http\Controllers;

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

        $user = auth()->user();
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

                // Set success message to session
                session()->flash('swal:modal', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Foto profil berhasil diperbarui.',
                ]);
            }
        } catch (\Exception $e) {
            // In case of failure, set error message to session
            session()->flash('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat memperbarui foto profil.',
            ]);
        }

        return redirect()->back();
    }
}

