<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
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
});

// Public post routes (accessible to everyone) - these must come after auth routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

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