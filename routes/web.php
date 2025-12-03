<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{username}', [App\Http\Controllers\UserController::class, 'show'])
    ->name('users.show');


Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

    Route::resource('posts', PostController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])
        ->name('comments.store');

    Route::post('/likes/{type}/{id}', [App\Http\Controllers\LikeController::class, 'toggle'])
        ->name('likes.toggle');

    Route::post('/users/{user}/follow', [App\Http\Controllers\FollowController::class, 'toggle'])
        ->name('users.follow');

    Route::get('/profile/customize', [App\Http\Controllers\ProfileController::class, 'editPublic'])
        ->name('profile.edit_public');
        
    Route::patch('/profile/customize', [App\Http\Controllers\ProfileController::class, 'updatePublic'])
        ->name('profile.update_public');
});

require __DIR__.'/auth.php';