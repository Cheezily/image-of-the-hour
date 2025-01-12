<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function record_vote(Request $request, Image $image)
    {
        $request->validate([
            'vote' => 'required|in:up,down',
        ]);

        if ($request->vote === 'up') {
            $image->increment('upvotes');
        } else {
            $image->increment('downvotes');
        }

        if ($image->upvotes + $image->downvotes >= 20 && $image->downvotes >= 2 * $image->upvotes) {
            $image->delete();
            return redirect()->route('home')->with('removed', 'The image has been removed due to negative votes.');
        }

        return back()->with('voted', 'Vote Recorded. Thanks!');
    }
}
