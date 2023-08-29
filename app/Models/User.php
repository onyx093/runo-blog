<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = 'http://localhost:8080/reset-password?token=' . $token . '&email=' . $this->email;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class, 'author_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $avatarUrl) => $avatarUrl ? asset($avatarUrl) : null,
            set: fn (string $avatarUrl) => $avatarUrl ? asset($avatarUrl) : null,
        );
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'user_id', 'follows_id');
    }

    public function follows()
    {
        return $this->belongsToMany(self::class, 'followers', 'follows_id', 'user_id');
    }

    public function follow($userId)
    {
        $this->follows()->attach($userId);
        return $this;
    }

    public function unfollow($userId)
    {
        $this->follows()->detach($userId);
        return $this;
    }

    public function isFollowing($userId)
    {
        return (bool) $this->follows()->where('user_id', $userId)->first();
    }
}
