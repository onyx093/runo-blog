<?php

namespace App\Http\Controllers;

use App\Events\UserFollowed;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CheckUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


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
        return $user->load('articles.tags', 'comments', 'followers', 'follows');
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
        $authUser = Auth::user();
        if ($authUser->id == $user->id) {
            return response()->json(["errors" => ["You can't follow yourself."]], Response::HTTP_FORBIDDEN);
        }
        if (!$authUser->isFollowing($user->id)) {
            $authUser->follow($user->id);

            event(new UserFollowed($authUser, $user));
            return response()->json(['message' => ["You are now friends with {$user->name}"]], Response::HTTP_OK);
        }
        return response()->json(["errors" => ["You are already following {$user->name}"]], Response::HTTP_ALREADY_REPORTED);
    }

    public function unfollow(User $user)
    {
        $authUser = Auth::user();
        if ($authUser->isFollowing($user->id)) {
            $authUser->unfollow($user->id);
            return response()->json(['message' => ["You are no longer friends with {$user->name}"]], Response::HTTP_OK);
        }
        return response()->json(["errors" => ["You are not following {$user->name}"]], Response::HTTP_FORBIDDEN);
    }

    public function getNotifications(Request $request)
    {
        return $request->user()->unreadNotifications;
    }

    public function markNotifications(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->noContent();
    }

    public function followers()
    {
        return Auth::user()->followers;
    }

    public function follows()
    {
        return Auth::user()->follows;
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent to your email.']);
        }
        return response()->json(['error' => 'Failed to send reset link.'], 400);
    }

    public function resetPassword(string $token)
    {
        return ['token' => $token];
    }

    public function newPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ]);

                $user->update();

                //event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.']);
        }
        return response()->json(['error' => 'Failed to reset password.'], 400);
    }

    public function redirectToProvider(string $provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->redirect()->getTargetUrl();
    }

    public function handleProviderCallback(string $provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return Redirect::to("/api/login/$provider");
        }

        $newUser = User::updateOrCreate([
            'email' => "$provider@email.com",
        ], [
            'name' => $user->getName(),
            'password' => bcrypt(Str::random(12))
        ]);

        $sanctumToken = $newUser->createToken('my sanctum blog token')->plainTextToken;
        return ['token' => $sanctumToken];
    }

    private function validateProvider(string $provider)
    {
        if (!in_array($provider, ['github', 'facebook', 'google'])) {
            return response()->json(['error' => 'Please login using github, facebook or google'], 422);
        }
    }

    public function loginWithGithub()
    {
        $user = [
            'email' => 'username@github.com',
            'name' => 'Github User'
        ];

        $newUser = User::updateOrCreate([
            'email' => "github@email.com",
        ], [
            'name' => $user['name'],
            'password' => bcrypt(Str::random(12))
        ]);

        $sanctumToken = $newUser->createToken('my sanctum blog token')->plainTextToken;
        return ['token' => $sanctumToken];
    }
}
