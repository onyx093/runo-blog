<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleCommentController extends Controller
{

    public function store(Request $request, Article $article)
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment = new Comment($data);
        $comment->author_id = Auth::id();
        $comment->article_id = $article->id;
        $comment->save();

        return response($comment, 201);
    }

    public function show(Article $article)
    {
        return $article
            ->comments()
            ->paginate();
    }
}
