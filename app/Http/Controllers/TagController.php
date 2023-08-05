<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Tag::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::query()
                ->with(['articles'])
                ->when(
                    request('author_id'),
                    function(Builder $query, string $authorId) {
                        $query->where('author_id', '=', $authorId);
                    }
                )
                ->paginate(8);
        return $tags;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $tag = new Tag($request->validated());
        $tag->author_id = Auth::id();
        $tag->save();

        return response($tag, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $tag->load(['articles']);
        return $tag;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return $tag;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        DB::transaction(function() use ($tag) {
            $tag->articles()->detach();
            $tag->delete();
        });

        return response()->noContent();
    }
}
