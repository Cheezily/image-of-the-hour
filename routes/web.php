<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Comment;

// Define the web routes
Route::get('/', function () {
    $image = Image::latest()->first();
    $remainingTime = optional($image)->created_at ? now()->diffInSeconds($image->created_at->addHour(), false) : 0;
    $comments = $image ? $image->comments()->latest()->get() : collect();

    return view('home', [
        'image' => $image,
        'remainingTime' => $remainingTime,
        'comments' => $comments
    ]);
})->name('home');

Route::post('/upload', [ImageController::class, 'upload'])->name('upload');

Route::post('/vote/{image}', [ImageController::class, 'vote'])->name('vote');

Route::post('/comment/{image}', function (Request $request, Image $image) {
    $request->validate([
        'comment' => 'required|string|max:500',
    ]);

    $image->comments()->create([
        'content' => $request->comment,
        'ip_address' => $request->ip()
    ]);

    return back();
});
