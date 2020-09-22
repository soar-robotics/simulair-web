<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthUserProfileImageUpdateRequest;
use App\Http\Requests\User\AuthUserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api')->except(['getProfileImage']);

        $this->userService = $userService;
    }

    public function me(Request $request)
    {
        $user = $this->userService->getUser(auth()->user()->id);

        return new UserResource($user);
    }

    /**
     * Update currently authenticated User details.
     *
     * @param AuthUserUpdateRequest $request
     * @return UserResource
     */
    public function meUpdate(AuthUserUpdateRequest $request) {
        $updateData = Arr::only($request->input(), ['first_name', 'last_name', 'username', 'company']);

        $user = $this->userService->update(auth()->user()->id, $updateData);

        return (new UserResource($user))->additional([
            'meta' => [
                'message' => 'User updated.'
            ]
        ]);
    }

    public function meProfileImageUpdate(AuthUserProfileImageUpdateRequest $request) {
        $user = auth()->user();
        $imageFile = $request->file('profile_image');

        $user = $this->userService->setProfileImage($user->id, $imageFile);

        return (new UserResource($user))->additional([
            'meta' => [
                'message' => 'Profile image updated.'
            ]
        ]);
    }

    public function meGetProfileImage(Request $request) {
        $user = auth()->user();

        $path = $this->userService->getProfileImagePath($user->id);

        if ($path) {
            return response()->file($path);
        }

        return response()->json(["message" => "File not found."], 404);
    }

    public function getProfileImage(Request $request, string $id) {
        if (! $request->hasValidSignature()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $path = $this->userService->getProfileImagePath($id);

        if ($path) {
            return response()->file($path);
        }

        return response()->json(["message" => "File not found."], 404);
    }
}
