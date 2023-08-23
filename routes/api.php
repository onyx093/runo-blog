<?php

use App\Http\Controllers\{
    ArticleController,
    ArticleCommentController,
    CommentController,
    TagController,
    UserController
};
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Login, register and logout
Route::post('/authenticate', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

$guestRoutes = ['index', 'show'];

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () use ($guestRoutes) {
    Route::apiResource('/articles', ArticleController::class)->except($guestRoutes);
    Route::apiResource('/comments', CommentController::class)->except($guestRoutes);
    Route::apiResource('/tags', TagController::class)->except($guestRoutes);
    Route::apiResource('/users', UserController::class)->except($guestRoutes);

    Route::post('/users/{user}/follow', [UserController::class, 'follow'])->name('user.follow');
    Route::post('/users/{user}/unfollow', [UserController::class, 'unfollow'])->name('user.unfollow');

    Route::post('/articles/{article}/comments', [ArticleCommentController::class, 'store'])->name('articles.comments.store');

    Route::get('/my', [UserController::class, 'my']);
    Route::post('/my/upload-avatar', [UserController::class, 'uploadAvatar'])->name('users.upload-avatar');
});

// Guest routes
Route::apiResource('/articles', ArticleController::class)->only($guestRoutes);
Route::apiResource('/comments', CommentController::class)->only($guestRoutes);
Route::apiResource('/tags', TagController::class)->only($guestRoutes);
Route::apiResource('/users', UserController::class)->only($guestRoutes);

Route::get('/articles/{article}/comments', [ArticleCommentController::class, 'show'])->name('articles.comments.show');

Route::group(['middleware' => ['web']], function () {
    // your routes here

    Route::get('/auth/redirect', [UserController::class, 'gitHubLogin']);

    Route::get('/auth/callback', [UserController::class, 'gitHubRedirect']);
});

Route::post('/forgot-password', [UserController::class, 'forgotPassword'])->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', [UserController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [UserController::class, 'newPassword'])->middleware('guest')->name('password.update');
