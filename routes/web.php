<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;


// Define the web routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/upload', [ImageController::class, 'upload'])->name('upload');

Route::post('/vote/{image}', [VoteController::class, 'record_vote'])->name('record_vote');

Route::post('/comment/{image}', [CommentController::class, 'save']);

Route::get('/delete/{password}', [ImageController::class, 'delete'])->name('delete');

Route::get('download/{filename}', function ($filename) {
    $path = "$filename";

    // Check if the file exists
    if (!Storage::disk('local')->exists($path)) {
        abort(404, 'File not found');
    }

    // Serve the file for download
    return Storage::disk('local')->download($path);
});
