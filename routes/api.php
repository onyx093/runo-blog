<?php

use App\Http\Controllers\{
    ArticleController, CommentController, TagController
};
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$guestRoutes = ['index', 'show'];

Route::middleware('auth:sanctum')->group(function() use ($guestRoutes) {
    Route::apiResource('/articles', ArticleController::class)->except($guestRoutes);
    Route::apiResource('/articles', ArticleController::class)->only($guestRoutes)->withoutMiddleware('auth:sanctum');

    Route::apiResource('/comments', CommentController::class)->except($guestRoutes)->withoutMiddleware('auth:sanctum');
    Route::apiResource('/comments', CommentController::class)->only($guestRoutes)->withoutMiddleware('auth:sanctum');

    Route::apiResource('/tags', TagController::class)->except($guestRoutes);
    Route::apiResource('/tags', TagController::class)->only($guestRoutes)->withoutMiddleware('auth:sanctum');
});


Route::post('/authenticate', function(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', Password::min(8)->letters()],
    ]);

    $user = User::query()->firstWhere('email', $credentials['email']);
    if(!Hash::check($credentials['password'], $user->password)) {
        throw new AuthenticationException();
    }
    $sanctumToken = $user->createToken('my sanctum blog token')->plainTextToken;
    return ['token' => $sanctumToken];
});

Route::post('/register', function(Request $request) {
    $credentials = $request->validate([
        'name' => ['required', 'string'],
        'email' => ['required', 'email'],
        'password' => ['required', Password::min(8)->letters()],
    ]);

    $user = User::query()->firstWhere('email', $credentials['email']);
    if(!is_null($user)) {
        throw ValidationException::withMessages(['User with email already exists']);
    }

    $user = new User($credentials);
    $user->save();

    $sanctumToken = $user->createToken('my sanctum blog token')->plainTextToken;
    return ['token' => $sanctumToken];
});
