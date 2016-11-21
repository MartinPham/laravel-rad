<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:25
 */

namespace App\RAD\Models;



use App\RAD\Exceptions\InvalidOauthUserId;

trait Oauth
{

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'service', 'oaid'
    ];

    public function user()
    {
        return $this->belongsTo(App\User::class);
    }

    public static function userFromOAuthUserData($service, $oAuthUserData)
    {
        if(!@$oAuthUserData->id){
            throw new InvalidOauthUserId('OAUTH USER ID IS MISSING');
        }

        $oaID = $oAuthUserData->id;
        $oaUserDetail = [
            'service' => $service,
            'oaid' => $oaID,
        ];

        $oaUser = Oauth::where($oaUserDetail)->first();

        if(!$oaUser)
        {
            $oaUser = Oauth::create($oaUserDetail);
        }

        $oaUser->data = $oAuthUserData;
        $oaUser->token = $oAuthUserData->token;
        $oaUser->save();

        $user = $oaUser->user;
        if(!$user)
        {
            $oaEmail = $oaID . '@' . $service . '.tld';
            if(@$oAuthUserData->email !== '')
            {
                $oaEmail = $oAuthUserData->email;
            }

            $userDetail = [
                'email' => $oaEmail
            ];
            $user = User::where($userDetail)->first();
            if(!$user)
            {
                $user = new \App\User;
                $user->email = $oaEmail;
                $user->activated = true;
                $user->password = \Hash::make($oaID . config('app.key') . $service);

            }


            $user->save();

            if(!@$oAuthUserData->first_name || !@$oAuthUserData->last_name)
            {
                $user->fetchNamesFromFullname($oAuthUserData->name);
            }else{
                $user->name = $oAuthUserData->first_name;
                $user->surname = $oAuthUserData->last_name;
            }

            $user->avatar = $oAuthUserData->avatar;

            // extra info
            $user->username = $oAuthUserData->id . '-' . $service;
            $user->fig = '';

            $user->save();

            $user->oauths()->save($oaUser);
        }




        $user->save();

        return $user;
    }

}