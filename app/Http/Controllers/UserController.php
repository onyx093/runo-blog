<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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
            throw ValidationException::withMessages(['User with email already exists']);
        }

        $user = new User($request->validated());
        $user->save();

        $sanctumToken = $user->createToken('my sanctum blog token')->plainTextToken;
        return ['token' => $sanctumToken];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

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
        if (!Hash::check($request->safe()['password'], $user->password)) {
            throw new AuthenticationException();
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
