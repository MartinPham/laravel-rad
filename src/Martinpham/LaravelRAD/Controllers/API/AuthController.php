<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:36
 */

namespace Martinpham\LaravelRAD\Controllers\API;

use App\User;
use Martinpham\LaravelRAD\Exceptions\InvalidActivateToken;
use Martinpham\LaravelRAD\Exceptions\InvalidResetToken;

trait AuthController
{
    public function register(\Illuminate\Contracts\Hashing\Hasher $hasher)
    {
        $rules = array(
            'name' => 'required|min:1',
            'surname' => 'required|min:1',
            'email' => 'required|min:1',
//			'email' => 'required|email|unique:users',
            'password' => 'required|min:1',
        );

        $input = $this->request->only(
            'name',
            'surname',
            
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
        $input['password'] = $hasher->make($input['password']);

        $check = User::findByEmail($input['email']);
        if($check)
        {
            return $this->respond(array(
                'ok'    =>  false,
                'msg'   =>  'Email has been used',
                'error' =>  $errors,
            ));
        }

        $user = User::create($input);

        $user->updateAvatar(@$input['avatar']);

        $user->activated = false;
        $user->save();

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

        $input = $this->request->only(
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
        $token = self::generateToken($user);
        $this->user = $user;
        $this->auth = $token;
        self::updateToken($token);

		// dd($user);
        return $this->respond();
    }


    public function requestPassword()
    {
        $rules = array(
            'email' => 'required|email|exists:users,email'
        );

        $input = $this->request->only(
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

        $input = $this->request->only(
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

    public function updatePassword(\Illuminate\Contracts\Hashing\Hasher $hasher)
    {
        $rules = array(
            'token' => 'required',
            'password' => 'required',
        );

        $input = $this->request->only(
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



        $user->password = $hasher->make($input['password']);
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