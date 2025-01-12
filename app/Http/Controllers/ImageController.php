<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store();

        Image::create([
            'path' => $path,
            'ip_address' => $request->ip(),
            'upvotes' => 0,
            'downvotes' => 0,
        ]);

        // Ensure only the last 50 images are stored
        if(Image::count() > 50) {
            $excessImages = Image::orderBy('created_at')->skip(50)->get();

            foreach ($excessImages as $oldImage) {
                Storage::delete($oldImage->path);
            }
        }

        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }

    public function delete(Request $request, String $password)
    {
        if($password === DB::table('options')->first()->password) {
            if(Image::orderBy('created_at', 'desc')->exists()) {
                Image::orderBy('created_at', 'desc')->first()->delete();

                return redirect('/')->with('success', 'Image Deleted!');
            }
            return "No Image To Delete";
        }
        return 'Invalid Password';
    }
}
