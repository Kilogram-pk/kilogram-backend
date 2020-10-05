<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($redirect_url = null)
    {
        return Socialite::driver('facebook')->stateless()->redirect($redirect_url);
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @param string $provider
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(string $provider, Request $request)
    {
        $user_facebook = Socialite::driver($provider)->userFromToken($request->input('token'));
        $user = User::createOrGetSocialUser("facebook", $user_facebook);
        return response([
            $user
        ]);
    }
}
