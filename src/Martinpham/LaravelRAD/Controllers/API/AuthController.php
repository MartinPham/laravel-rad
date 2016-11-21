<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:36
 */

namespace Martinpham\LaravelRAD\Controllers\API;


use App\RAD\Exceptions\InvalidActivateToken;
use App\RAD\Exceptions\InvalidResetToken;

trait AuthController
{
    public function register()
    {
        $rules = array(
            'name' => 'required|min:1',
            'surname' => 'required|min:1',
            'username' => 'required|min:1',
            'fig' => 'required|min:1',
            'email' => 'required|min:1',
//			'email' => 'required|email|unique:users',
            'password' => 'required|min:1',
        );

        $input = Input::only(
            'name',
            'surname',
            'username',
            'fig',
            'email',
            'password',
            'avatar'
        );

        $errors = self::validateData($input, $rules);
//		dd($errors);

        if(count($errors) > 0)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_DATA',
                'error'	=>	$errors,
            ));
        }

        $input['email'] = strtolower($input['email']);
        $input['username'] = strtolower($input['username']);
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $user->updateAvatar(@$input['avatar']);

        $user->sendActivationEmail();

        return $this->respond();
    }


    public function activate($user_id, $token)
    {
        try {
            $user = User::find($user_id);
            $user->activateWithToken($token);
        }catch(\Exception $e){
            throw new InvalidActivateToken('Activate Token is invalid');
        }

        return view('soft_redirect', ['url' => \Config::get('app.app_url') . 'auth?token=' . $user->getAPIAuthToken()]);
    }
    public function forceActivate($email)
    {
        $user = User::findByEmail($email);
        if($user)
        {
            $user->activateWithToken($user->getActivateToken());
        }

        return $user;
    }

    public function tokenLogin()
    {
        $user = self::user();

        if(!$user)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_AUTH_TOKEN'
            ));

        }


//		$this->data = $user;
//		$this->auth = self::token($user);

        return $this->respond();
    }

    public function login()
    {
        $rules = array(
            'email' => 'required|min:1',
            'password' => 'required|min:1'
        );

        $input = Input::only(
            'email',
            'password'
        );

        $errors = self::validateData($input, $rules);
        if(count($errors) > 0)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_DATA',
                'error'	=>	$errors,
            ));
        }

//        return $this->respond(['data' => $input]);
        $user = User::login($input['email'], $input['password']);
        if(!$user)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_CREDENTIAL',
            ));
        }



//		$this->data = $user;
        self::updateToken(self::generateToken($user));

//		dd($user);
        return $this->respond();
    }


    public function requestPassword()
    {
        $rules = array(
            'email' => 'required|email|exists:users,email'
        );

        $input = Input::only(
            'email'
        );

        $errors = self::validateData($input, $rules);
        if(count($errors) > 0)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_DATA',
                'error'	=>	$errors,
            ));
        }

        $user = User::findByEmail($input['email']);
        if(!$user)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'EMAIL_NOT_EXISTS',
            ));
        }

        $user->sendResetPasswordEmail();

        return $this->respond();
    }
    public function reactivate()
    {
        $rules = array(
            'email' => 'required|email|exists:users,email'
        );

        $input = Input::only(
            'email'
        );

        $errors = self::validateData($input, $rules);
        if(count($errors) > 0)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_DATA',
                'error'	=>	$errors,
            ));
        }

        $user = User::findByEmail($input['email']);
        if(!$user)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'EMAIL_NOT_EXISTS',
            ));
        }

        $this->data['mail'] = $user->sendActivationEmail();

        return $this->respond();
    }

    public function resetPassword($user_id, $token)
    {
        $user = User::find($user_id);

        if (! $userFromToken = \JWTAuth::setToken($token)->authenticate()) {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_TOKEN'
            ));
        }

        if(!$user || $user->id !== $userFromToken->id)
        {
            throw new InvalidResetToken('Reset token is invalid');
        }

        return view('soft_redirect', ['url' => \Config::get('app.app_url') . 'reset_password?token=' . $user->getAPIAuthToken()]);
    }

    public function updatePassword()
    {
        $rules = array(
            'token' => 'required',
            'password' => 'required',
        );

        $input = Input::only(
            'token',
            'password'
        );

        $errors = self::validateData($input, $rules);
        if(count($errors) > 0)
        {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_DATA',
                'error'	=>	$errors,
            ));
        }


        if (! $user = \JWTAuth::setToken($input['token'])->authenticate()) {
            return $this->respond(array(
                'ok'	=>	false,
                'msg'	=>	'INVALID_TOKEN'
            ));
        }



        $user->password = Hash::make($input['password']);
        $user->save();


//		$this->data = $user;
//		$this->auth = self::generateToken($user);
        self::updateToken(self::generateToken($user));

        return $this->respond();

    }


    public function logout()
    {
//        $this->auth = "-1";
        self::updateToken(false);

        return $this->respond();
    }
}