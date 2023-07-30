<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

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
        $articles = Article::query()
                    ->with(['comments', 'author', 'tags'])
                    ->when(
                        request()->input('author_id'),
                        function(Builder $query, string $authorId) {
                            $query->where('author_id', '=', $authorId);
                        }
                    )
                    ->when(
                        request()->input('tag_ids'),
                        static::filterByRelationshipIds('tags', 'tags.id'),
                    )
                    ->when(
                        request()->input('author_ids'),
                        static::filterByRelationshipIds('author', 'users.id'),
                    )
                    ->when(
                        $orderingColumns = request()->input('order_by'),
                        fn (Builder $query) => $query->orderBy(...$orderingColumns)
                    )
                    ->when(
                        request('search'),
                        fn (Builder $query, string $searchTerm) => $query
                            ->where('title', 'ILIKE', "%$searchTerm%")
                            ->orWhere('content', 'ILIKE', "%$searchTerm%"),
                    )
                    ->paginate(8);
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

    private static function filterByRelationshipIds(string $relationship, string $idColumn)
    {
        return function (Builder $articleQuery, $ids) use ($relationship, $idColumn) {
            if (!is_array($ids)) {
                // support queries both like foo[]=1&foo[]=2 and foo=[1,2]
                $parsedIds = json_decode($ids);

                if (is_null($parsedIds)) {
                    // also try to support foo=1,2
                    $ids = explode(',', $ids);
                } else {
                    $ids = $parsedIds;
                }
            }

            $articleQuery
                ->whereHas(
                    $relationship,
                    fn (Builder $relationshipQuery) => $relationshipQuery
                        ->whereIn(
                            $idColumn,
                            $ids
                        )
                );
        };
    }
}
