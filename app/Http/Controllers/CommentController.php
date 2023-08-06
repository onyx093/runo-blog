<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Response;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Comment::class, options: ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::query()
                    ->with(['author', 'article'])
                    ->when(
                        request('article_id'),
                        function(Builder $query, string $authorId) {
                            $query->where('article_id', '=', $authorId);
                        }
                    )
                    ->when(
                        request('created_after'),
                        function (Builder $query, $createdAfter) {
                            $query->where('created_at', '>', $createdAfter);
                        }
                    )
                    ->when(
                        request('author_email'),
                        function (Builder $query, string $authorEmail) {
                            $query->whereHas('author', fn ($a) => $a->where('email', $authorEmail));
                        }
                    )
                    ->when(
                        request('search'),
                        fn (Builder $query, string $searchTerm) => $query
                            ->where('content', 'ILIKE', "%$searchTerm%"),
                    )
                    ->paginate(8);
        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = new Comment($request->validated());
        $comment->author_id = Auth::id() ?? null;
        $comment->save();

        return response($comment, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load(['author', 'article']);
        return $comment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
