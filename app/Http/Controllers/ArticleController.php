<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Article::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::query()->with(['comments', 'author', 'tags'])->get();
        return $articles;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $article = new Article($request->safe()->only(['title', 'content']));
        $article->author_id = Auth::id();
        $article->save();
        if($request->has('tags'))
        {
            foreach ($request->input('tags') as $requestTag) {
                $tag = Tag::firstOrCreate([
                    'name' => $requestTag,
                ], [
                    'author_id' => $article->author_id,
                ]);
                $article->tags()->attach($tag);
            }
        }

        return response($article, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['comments.author', 'tags', 'author']);
        return $article;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->safe()->only(['title', 'content']));
        if($request->has('tags'))
        {
            $article->tags()->detach();
            foreach ($request->input('tags') as $requestTag) {
                $tag = Tag::firstOrCreate([
                    'name' => $requestTag,
                ], [
                    'author_id' => $article->author_id,
                ]);
                $article->tags()->attach($tag);
            }
        }
        return $article;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->tags()->detach();
        $article->delete();

        return response()->noContent();
    }
}
