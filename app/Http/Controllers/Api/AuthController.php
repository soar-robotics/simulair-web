<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\MongoDB\User\RefreshToken;
use App\Models\MongoDB\User\User;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    const PLATFORM_WEB = "web";
    const REFRESH_TOKEN_COOKIE_NAME = "RefreshToken";

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login', 'refresh', 'register', 'verify', 'redirectToProvider', 'handleProviderCallback'
        ]]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (!auth()->user()->email_verified_at) {
            return response()->json(['error' => 'Email address is not verified.'], 403);
        }

        $platform = $request->input('platform');

        $refreshToken = $this->createRefreshToken($platform, auth()->user());

        // pass refresh token to response with other data
        return $this->respondWithTokens($token, $refreshToken, $platform);
    }

    /**
     * Create refresh token for the user.
     *
     * @param string|null $platform
     * @param $user
     * @return RefreshToken
     */
    private function createRefreshToken(?string $platform, $user) {
        // create refresh token values
        $value = hash('sha512', microtime() . $user->email);
        $expiresIn = 24*60*30; // in minutes, 30 days
        $expiration = Carbon::now()->addMinutes($expiresIn);

        // instantiate new RefreshToken object and set property values
        $refreshToken = new RefreshToken();
        $refreshToken->value = $value;
        $refreshToken->expires_in = $expiresIn;
        $refreshToken->expiration = $expiration;
        $refreshToken->req_platform = $platform;

        // store refresh token in the database for the user
        $user->refreshTokens()->save($refreshToken);

        return $refreshToken;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function invalidate()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully invalidated the token.']);
    }

    /**
     * Refresh tokens and return them in response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request)
    {
        $platform = $request->input('platform');

        // get refresh_token from the request if set
        if (!$reqRefreshToken = $request->input('refresh_token')) {
            // try to find refresh_token in HttpOnly Cookie if not previously set
            $reqRefreshToken = request()->cookie(self::REFRESH_TOKEN_COOKIE_NAME);
        }

        // find valid RefreshToken based on the input
        $refreshToken = RefreshToken::byValue($reqRefreshToken)->unused()->first();

        // handle invalid RefreshToken
        if (!$refreshToken) {
            return response()->json(['error' => 'Refresh token is invalid.'], 401);
        }

        // find User assciated with the RefreshToken
        $user = $refreshToken->user()->first();

        // set new tokens for the user and store refresh token in the database
        $token = auth()->login($user);
        $newRefreshToken = $this->createRefreshToken($platform, $user);

        // invalidate refresh token that was used to generate new tokens
        $refreshToken->used_at = Carbon::now();
        $refreshToken->save();

        return $this->respondWithTokens($token, $newRefreshToken, $platform);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @param RefreshToken $refreshToken
     * @param string|null $platform
     * @return JsonResponse
     */
    protected function respondWithTokens(string $token, RefreshToken $refreshToken, ?string $platform)
    {
	    $expiresIn = auth()->factory()->getTTL();

        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn * 60, // seconds
	        'expires_at' => Carbon::now()->addMinutes($expiresIn),
            'refresh_token' => $refreshToken->value,
            'refresh_token_exp' => $refreshToken->expiration
        ];

        $response = response()->json($data);

        if ($platform == self::PLATFORM_WEB) {
            $response = $response->cookie(
                self::REFRESH_TOKEN_COOKIE_NAME,
                $refreshToken->value,
                $refreshToken->expires_in,
                null,
                null,
                null,
                true
            );
        }

        return $response;
    }

    public function register(RegisterUserRequest $request) {
        $input = $request->except(['confirm_password']);
        $user = new User();
        $user->fill($input);

        if (!$user->username) $user->username = Str::uuid()->toString();
        $user->password = Hash::make($request->password);
        $user->save();

        $user->sendEmailVerificationNotification();

        return (new UserResource($user))->additional([
            'meta' => [
                'message' => 'User created.'
            ]
        ]);
    }

    /**
     * Verify the User based on the valid verification URL.
     *
     * @param Request $request
     * @param $userId
     * @return mixed
     */
    public function verify(Request $request, $userId) {
        if (!$request->hasValidSignature()) {
            return response()->json([
                "message" => "Invalid/expired/invalid URL provided."
            ], 401);
        }

        $user = User::find(($userId));

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        /*
        return response()->json([
            "message" => "Successfully verified email address."
        ]);
        */

        /*
         * Temporary, for long-term better to send verification URL pointing to
         * client-side app in email with params, which would invoke
         * this API endpoint instead of doing redirect
         */
        $queryString = "?verify=1&email={$user->email}";
        return redirect(config('app.web_client.email_verification_url') . $queryString);
    }

    /**
     * Resent the verification URL if the User is not already verified.
     *
     * @return JsonResponse
     */
    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json([
                "message" => "Email already verified."
            ], 400);
        }

        auth()->user()->sendEmailVerificationNotification();


        return response()->json([
            "message" => "Email verification link sent."
        ]);
    }


    public function redirectToProvider()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleProviderCallback()
    {
        $providerUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $providerUser->email)->first();

        if ($user) {
            $user->google_id = $providerUser->id;
            $user->google_avatar = $providerUser->avatar;
            $user->google_token = $providerUser->token;
            $user->google_auth_at = Carbon::now();

            if (!$user->email_verified_at) $user->email_verified_at = Carbon::now();

            $user->timestamps = false;
            $user->save();
        }
        else {
            $user = new User();
            $user->first_name = $providerUser->user['given_name'];
            $user->last_name = $providerUser->user['family_name'];
            $user->company = null;
            $user->email = $providerUser->email;
            $user->username = Str::uuid()->toString();
            $user->password = Hash::make(Str::random(40));
            $user->profile_image = null;
            $user->email_verified_at = Carbon::now();
            $user->google_id = $providerUser->id;
            $user->google_avatar = $providerUser->avatar;
            $user->google_token = $providerUser->token;
            $user->google_auth_at = Carbon::now();

            $user->timestamps = false;
            $user->save();
        }

        $token = auth()->login($user);
        //$platform = $request->input('platform');
        $platform = self::PLATFORM_WEB;

        $refreshToken = $this->createRefreshToken($platform, $user);

        return $this->respondWithTokens($token, $refreshToken, $platform);
    }
}
