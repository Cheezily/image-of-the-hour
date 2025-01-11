<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function save(Request $request, Image $image)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $image->comments()->create([
            'content' => $request->comment,
            'ip_address' => $request->ip()
        ]);

        return back();
    }
}
