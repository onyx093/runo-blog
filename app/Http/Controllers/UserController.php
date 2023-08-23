<?php

namespace App\Http\Controllers;

use App\Events\UserFollowed;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CheckUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, options: ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::query()
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        $user = User::query()->firstWhere('email', $request->safe()['email']);
        if (!is_null($user)) {
            throw ValidationException::withMessages(['email' => 'User with email already exists']);
        }

        $user = new User($request->safe()->only(['name', 'email', 'password']));
        if ($request->has('avatar')) {
            $avatar_photo = $request->file('avatar');
            $avatar_photo_path = Storage::disk('public')->put('avatars', $avatar_photo);
            $user->avatar_url = Storage::url($avatar_photo_path);
        }
        $user->save();

        $sanctumToken = $user->createToken('my sanctum blog token')->plainTextToken;
        return ['token' => $sanctumToken];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user->load('articles.tags', 'comments');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->input('name');

        if ($request->has('avatar')) {
            $avatar_photo = $request->file('avatar');
            $avatar_photo_path = Storage::disk('public')->put('avatars', $avatar_photo);
            if (!is_null($user->avatar_url)) {
                $old_avatar_photo_path = 'avatars/' . basename($user->avatar_url);
                Storage::disk('public')->delete($old_avatar_photo_path);
            }
            $user->avatar_url = Storage::url($avatar_photo_path);
        }
        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    public function register(StoreUserRequest $request)
    {
        // Separate method to avoid "store" policy being triggered.
        return $this->store($request);
    }

    public function my()
    {
        return request()->user();
    }

    public function login(CheckUserRequest $request)
    {
        $user = User::query()->firstWhere('email', $request->safe()['email']);

        if (is_null($user)) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect']);
        }

        if (!Hash::check($request->safe()['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect']);
        }

        $sanctumToken = $user->createToken('my sanctum blog token')->plainTextToken;
        return ['token' => $sanctumToken];
    }

    public function logout()
    {
        $user = request()->user();

        $user->tokens()->delete();

        return response()->noContent();
    }

    public function uploadAvatar(Request $request)
    {
        $user = $request->user();

        if ($request->has('img_avatar')) {
            $avatar_photo = $request->file('img_avatar');
            $avatar_photo_path = Storage::disk('public')->put('avatars', $avatar_photo);
            if (!is_null($user->avatar_url)) {
                $old_avatar_photo_path = 'avatars/' . basename($user->avatar_url);
                Storage::disk('public')->delete($old_avatar_photo_path);
            }
            $user->avatar_url = Storage::url($avatar_photo_path);
            $user->save();
        }
        return $user;
    }

    public function follow(User $user)
    {
        $follower = Auth::user();
        if ($follower->id == $user->id) {
            return response()->json(["errors" => ['message' => ["You can't follow yourself."]]], Response::HTTP_FORBIDDEN);
        }
        if (!$follower->isFollowing($user->id)) {
            $follower->follow($user->id);

            // sending a notification
            // $user->notify(new UserFollowed($follower, $user));
            event(new UserFollowed($follower, $user));
            return response()->json(["success" => ['message' => ["You are now friends with {$user->name}"]]], Response::HTTP_OK);
        }
        return response()->json(["success" => ['message' => ["You are already following {$user->name}"]]], Response::HTTP_ALREADY_REPORTED);
    }

    public function unfollow(User $user)
    {
        $follower = Auth::user();
        if ($follower->isFollowing($user->id)) {
            $follower->unfollow($user->id);
            return response()->json(["success" => ['message' => ["You are no longer friends with {$user->name}"]]], Response::HTTP_OK);
        }
        return response()->json(["errors" => ['message' => ["You are not following {$user->name}"]]], Response::HTTP_FORBIDDEN);
    }

    public function notifications()
    {
        // return auth()->user()->unreadNotifications()->limit(5)->get()->toArray();
    }

    public function followers()
    {
        return Auth::user()->followers;
    }

    public function follows()
    {
        return Auth::user()->follows;
    }
}
