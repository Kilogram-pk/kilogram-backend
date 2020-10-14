<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    /**
     * Register using email
     * @param Request $request
     * @return Response
     */
    public function register(Request $request) {
        $request->validate([
            'email' => 'required_without:phone|email|max:255|unique:users',
            'phone' => 'required_without:email|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:255|unique:users',
        ]);
        $user = new User;
        $user->email = $request->input('email') ?? null;
        $user->phone = $request->input('phone') ?? null;
        $user->save();
        return response([
            'saved' => true,
            'message' => $user->email ? "A key has been sent to your email to verify" : "User created successfully"
        ]);
    }

    /**
     * Register a user's additional info
     *
     * @param Request $request
     * @return Response
     */
    public function registerInfo(Request $request)
    {
        $request->validate([
            'user' => 'required|min:6|max:255',
            'password' => 'required|min:6|max:255',
            'name' => 'required|min:6|max:255',
            'username' => 'required|min:6|max:255|unique:users,username',
        ]);
        $user = User::where(['email' => $request->input('user')])->orWhere(['phone' => $request->input('user')])->first();
        // if user not found
        if (!$user) {
            return response([
                'error' => true,
                'message' => "User not found"
            ], 401);
        }

        $user->password = $request->password;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->save();
        return response([
            'saved' => true,
            'message' => "Data added successfully"
        ]);
    }

    /**
     * Login user by username, email or phone
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) {
        $request->validate([
            'user' => 'required|max:255',
            'password' => 'required|max:255',
        ]);
        $user = User::where(['password' => Hash::make($request->input('password'))])
                    ->orWhere(['username' => $request->input('user')])
                    ->orWhere(['email' => $request->input('user')])
                    ->orWhere(['phone' => $request->input('user')])
                    ->first();
        // if user not found
        if (!$user) {
            return response([
                'error' => true,
                'message' => "User not found"
            ], 401);
        }

        // if user is not verified yet
        if(!$user->verified_at) {
            return response([
                'error' => true,
                'message' => "User not verified yet"
            ], 403);
        }

        $accessToken = $user->createToken('authToken');
        $token = $accessToken->token;
        return response([
            'access_token' => $accessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->expires_in
            )->toDateTimeString()
        ]);
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @param string $provider
     * @param Request $request
     * @return JsonResponse
     */
    public function handleProviderCallback(string $provider, Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        try {
            $user_facebook = Socialite::driver($provider)->userFromToken($request->input('token'));
        } catch (\Throwable $t ) {
            return response()->json([
                'success' => false,
                'message' => "User not found for this token"
            ], 401);
        }
        $user = User::createOrGetSocialUser("facebook", $user_facebook);
        $accessToken = $user->createToken('authToken');
        $token = $accessToken->token;
        return response()->json([
            'access_token' => $accessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->expires_in
            )->toDateTimeString()
        ]);
    }

    /**
     * Verify Code
     * @param Request $request
     * @return Response
     */
    public function verifyCode(Request $request) {
        $request->validate([
            'verification_key' => 'required:phone|size:6',
            'email' => 'required|email'
        ]);
        $user = User::where([
            'email' => $request->input('email'),
            'verification_key' => $request->input('verification_key')
        ])->first();

        // if code not found
        if (!$user) {
            return response([
                'success' => false,
                'message' => 'Verification code not found'
            ], 401);
        }

        // if code has expired (24 hours have passed)
        if ($user->verification_created && (Carbon::now() > $user->verification_created->addHours(24))) {
            return response([
                'success' => false,
                'message' => 'Verification code has expired'
            ], 401);
        }

        $user->verified_at = Carbon::now();
        $user->verification_key = null;
        $user->verification_created = null;
        $user->save();
        return response([
            'success' => true,
            'message' => 'Verification successful'
        ]);
    }

    /**
     * Renew code if 2 minutes have not passed
     * @param Request $request
     * @return Response
     */
    public function renewCode(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where([
            'email' => $request->input('email'),
        ])->first();

        // if user not found
        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        // if user has already been verified
        if ($user->verified_at) {
            return response([
                'success' => false,
                'message' => 'User has already been verified'
            ], 409);
        }

        // if code has passed 2 minutes
        if ($user->verification_created && (Carbon::now() < $user->verification_created->addMinutes(2))) {
            return response([
                'success' => false,
                'message' => 'Previous verification code was created under 2 minutes ago'
            ], 401);
        }
        $user->makeKey();
        return response([
            'success' => true,
            'message' => 'Verification code renewed'
        ], 201);
    }

    /**
     * Forgot password request
     * @param Request $request
     * @return Response
     */
    public function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = User::where(['email' => $request->input('email')])->first();
        // if user not found
        if (!$user) {
            return response([
                'error' => true,
                'message' => "User not found"
            ], 401);
        }

        // if user is not verified yet
        if(!$user->verified_at) {
            return response([
                'error' => true,
                'message' => "User not verified yet"
            ], 403);
        }

        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert([
            'email' => $user->getEmailForPasswordReset()
        ],
        [
            'email' => $user->getEmailForPasswordReset(),
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        try {
            Mail::to($user->getEmailForPasswordReset())->queue(new ResetPasswordMail($user->getEmailForPasswordReset(), $token, $user->username));
        } catch (\Throwable $t) {
            return response([
                'error' => true,
                'message' => "Something went wrong"
            ], 500);
        }
        return response([
            'success' => true,
            'message' => 'An reset email link has been sent'
        ], 201);
    }

    /**
     * Validate the reset password token
     * @param string $token
     * @return Response
     */
    public function validateToken(string $token) {
        $token = DB::table('password_resets')->where(['token' => $token])->first();
        if ($token) {
            return response([
                'success' => true,
                'message' => 'Valid token'
            ], 200);
        }
        return response([
            'success' => false,
            'message' => 'Token not found'
        ], 404);
    }

    /**
     * Reset password
     * @param Request $request
     * @return Response
     */
    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|between:6,255|confirmed',
        ]);
        $token = $this->validDateToken($request);
        if ($token) {
            $user = User::where(['email' => $request->input('email')])->first();
            if ($user) {
                $user->password = $request->input('password');
                $user->save();
                //delete old reset password data
                DB::table('users')->where('email', '=', $user->email)->delete();
                return response([
                    'success' => true,
                    'message' => 'Password updated'
                ], 201);
            }
            else {
                return response([
                    'error' => true,
                    'message' => "User not found"
                ], 401);
            }
        }
        return response([
            'success' => false,
            'message' => 'Token not found'
        ], 404);
    }

    /**
     * Check availability of value
     * @param string $key
     * @param string $value
     * @return Response
     */
    public function checkAvailability(string $key, string $value) {
        if ($key == "phone" || $key == "email" || $key == "username") {
            $user = User::where([$key => $value])->first();
            if ($user) {
                return response([
                    'success' => false,
                    'message' => 'Already in use'
                ], 401);
            }
            else {
                return response([
                    'success' => false,
                    'message' => 'Available to use'
                ], 202);
            }
        } else {
            return response([
                'success' => false,
                'message' => 'Key can only be phone, email and username'
            ], 422);
        }
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Database\Query\Builder
     */
    private function validDateToken(Request $request) {
        $token = DB::table('password_resets')->where([
            'email' => $request->input('email'),
            'token' => $request->input('token')
        ])->first();
        if ($token) {
            return $token;
        }
        return false;
    }
}
