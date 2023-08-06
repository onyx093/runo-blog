<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CheckUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateUserRequest;
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
        if($request->has('avatar')) {
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
        return $user->load('articles', 'comments');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->input('name');

        if($request->has('avatar')) {
            $avatar_photo = $request->file('avatar');
            $avatar_photo_path = Storage::disk('public')->put('avatars', $avatar_photo);
            if(!is_null($user->avatar_url))
            {
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
}
