<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Post routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my-posts');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Comment routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Like routes
    Route::post('/posts/{post}/like', [LikeController::class, 'togglePost'])->name('posts.like');
    Route::post('/comments/{comment}/like', [LikeController::class, 'toggleComment'])->name('comments.like');
    
    // Follow routes (Phase 6)
    Route::post('/follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::get('/discover', [FollowController::class, 'discover'])->name('follow.discover');
    Route::get('/feed', [FollowController::class, 'feed'])->name('follow.feed');
});

// Public post routes (accessible to everyone) - these must come after auth routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Public follow routes (accessible to everyone)
Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('follow.followers');
Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('follow.following');

// Dashboard route (provided by Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (provided by Breeze + our extensions)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    Route::patch('/profile/additional', [ProfileController::class, 'updateAdditional'])->name('profile.additional.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public profile routes (accessible to everyone)
Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');

// Include authentication routes (provided by Breeze)
require __DIR__.'/auth.php';