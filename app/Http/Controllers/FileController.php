<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{


    public function upload_file(Request $request){
        {
            $request->validate([
                'image' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            ]);

            // Handle the file upload
            if ($request->hasFile('image')) {
                $notion_image = $request->file('image');
                $filename = $notion_image->getClientOriginalName();

                Storage::put('/public' .'/notion-image/'. $filename, file_get_contents($notion_image->getRealPath()));
                $path = $request->file('image')->storeAs('/public/notion-image', $filename, 'local');
                return response()->json([
                    'success' => 'File uploaded successfully',
                    'path' => $path,
                    'url' => url('/storage/notion-image/'.$filename),
                ]);
            }

            // dd($request->all());
            return response()->json(['error' => 'File upload failed'], 422);
        }
    }
}
