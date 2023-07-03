<?php

use App\Models\Article;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hello-world', function (Request $request) {
    return ['hello' => 'world'];
});

Route::get('/add/{input}/{another}', function (Request $request, string $asdf, string $second) {
    return $request->foo . $asdf;
    return (int) $asdf +  (int) $second;
});

Route::get('/articles', function () {
    return Article::all();
});

Route::post('/articles', function (Request $request) {
    //dd($request->toArray());

    $article = new Article($request->all());
    $article->save();

    return response('well done you posted it');
});

Route::get('/articles/{article}', function (Article $article) {
    return $article;
});

Route::patch('/articles/{article}', function (Request $request, Article $article) {
    $article->update($request->all());
    $article->save();
});

Route::delete('/articles/{article}', function (Request $request, Article $article) {
    $article->delete();
});
