<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with(['author', 'article'])->get();
        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = new Comment($request->validated());
        $comment->author_id = Auth::id();
        $comment->save();

        return response($comment, 201);
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
