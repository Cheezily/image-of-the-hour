<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $image = Image::latest()->first();
        $remainingTime = optional($image)->created_at ? now()->diffInSeconds($image->created_at->addHour(), false) : 0;
        $comments = $image ? $image->comments()->latest()->get() : collect();

        if($image) {
            $image->view_count += 1;
            $image->save();
        }

        $options = DB::table('options')->first();
//dd($options);
        return view('home', [
            'image' => $image,
            'remainingTime' => $remainingTime,
            'comments' => $comments,
            'useMatomo' => $options->use_matomo_tracking,
            'matomoUrl' => $options->matomo_url,
            'matomoId' => $options->matomo_id,
        ]);
    }
}
