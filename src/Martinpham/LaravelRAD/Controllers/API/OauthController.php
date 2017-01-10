<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:36
 */

namespace Martinpham\LaravelRAD\Controllers\API;

use App\Oauth;

trait OauthController
{




    public function oauthLogin($service)
    {
        return \Socialite::driver($service)->redirect();
    }
    public function oauthLoginCallback($service)
    {
        $oaUserData = \Socialite::driver($service)->user();

        $user = Oauth::userFromOAuthUserData($service, $oaUserData);

        return view('soft_redirect', ['url' => \Config::get('app.app_url') . 'auth?token=' . $user->getAPIAuthToken()]);
    }

    public function oauth2TokenLogin($service)
    {

        $token = $this->request->get('token');

        $oaUserData = \Socialite::driver($service)->userDataByToken($token);
//        dd($oaUserData);
        $user = Oauth::userFromOAuthUserData($service, $oaUserData);


//        $this->data = $user;
//        $this->auth = self::generateToken($user);
        self::updateToken(self::generateToken($user));

        return $this->respond();


    }
}