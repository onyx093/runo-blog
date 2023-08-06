<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'author_id'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function author():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function coverUrl(): Attribute{
        return Attribute::make(
            get: fn(?string $coverUrl) => $coverUrl ? asset($coverUrl) : null,
            set: fn(string $coverUrl) => $coverUrl ? asset($coverUrl) : null,
        );
    }
}
