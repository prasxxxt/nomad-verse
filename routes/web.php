<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])
    ->name('comments.store');

Route::post('/likes/{type}/{id}', [App\Http\Controllers\LikeController::class, 'toggle'])
    ->name('likes.toggle');

Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])
    ->name('notifications.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

require __DIR__.'/auth.php';
