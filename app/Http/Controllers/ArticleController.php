<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Interfaces\INotificationService;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    private $notifiers;

    public function __construct(INotificationService ...$notificationServices)
    {
        $this->authorizeResource(Article::class, options: ['except' => ['index', 'show']]);
        $this->notifiers = $notificationServices;
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

        if($request->hasFile('cover_photo')) {
            $cover_photo = $request->file('cover_photo');
            $cover_photo_path = Storage::disk('public')->put('covers', $cover_photo);
            // $cover_photo_path = $cover_photo->storePublicly('covers', ['disk' => 'public']);
            // $cover_photo_path = $cover_photo->storePublicly('public/covers');
            $article->cover_url = Storage::url($cover_photo_path);
        }
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

        foreach($this->notifiers as $notifier)
        {
            $notifier->notifyAbout($article);
        }

        return response($article, Response::HTTP_CREATED);
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
        $article->title = $request->input('title');
        $article->content = $request->input('content');

        if($request->hasFile('cover_photo')) {
            $cover_photo = $request->file('cover_photo');
            $cover_photo_path = Storage::disk('public')->put('covers', $cover_photo);
            if(!is_null($article->cover_url))
            {
                $old_cover_photo_path = 'covers/' . basename($article->cover_url);
                Storage::disk('public')->delete($old_cover_photo_path);
            }
            $article->cover_url = Storage::url($cover_photo_path);
        }
        $article->save();

        if($request->has('tags'))
        {
            DB::transaction(function() use ($article, $request) {
                $article->tags()->detach();
                foreach ($request->input('tags') as $requestTag) {
                    $tag = Tag::firstOrCreate([
                        'name' => $requestTag,
                    ], [
                        'author_id' => $article->author_id,
                    ]);
                    $article->tags()->attach($tag);
                }
            });
        }
        return $article;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        DB::transaction(function() use ($article) {
            $article->tags()->detach();
            $article->delete();
        });

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
