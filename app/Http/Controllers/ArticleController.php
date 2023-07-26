<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;

class ArticleController extends Controller
{
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
        $article = new Article($request->validated());
        $article->save();
        if($request->has('tags'))
        {
            foreach (explode(',', $request->tags) as $requestTag) {
                if(!empty(trim($requestTag))){
                    $tag = Tag::firstOrCreate([
                        'name' => $requestTag,
                        'author_id' => $request->validated('author_id'),
                    ]);
                    $article->tags()->attach($tag);
                }
            }
        }

        return response($article, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['comments', 'tags']);
        return $article;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->validated());
        return response()->noContent();
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
