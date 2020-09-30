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
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user_facebook = Socialite::driver('facebook')->stateless()->user();
        $user = User::createOrGetSocialUser("facebook", $user_facebook);
        return response([
            $user
        ]);
    }
}
