<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:25
 */

namespace Martinpham\LaravelRAD\Models;



use Martinpham\LaravelRAD\Exceptions\InvalidOauthUserId;

trait Oauth
{
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public static function userFromOAuthUserData($service, $oAuthUserData)
    {
        if(!@$oAuthUserData->id){
            throw new InvalidOauthUserId('OAUTH USER ID IS MISSING');
        }

        $oaID = $oAuthUserData->id;
        $oaUserDetail = [
            self::FIELD_SERVICE => $service,
            self::FIELD_OAID => $oaID,
        ];

        $oaUser = self::where($oaUserDetail)->first();

        if(!$oaUser)
        {
            $oaUser = self::create($oaUserDetail);
        }

        $oaUser->{self::FIELD_DATA} = $oAuthUserData;
        $oaUser->{self::FIELD_TOKEN} = $oAuthUserData->token;
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
                \App\User::FIELD_EMAIL => $oaEmail
            ];
            $user = \App\User::where($userDetail)->first();
            if(!$user)
            {
                $user = new \App\User;
                $user->{\App\User::FIELD_EMAIL} = $oaEmail;
                $user->{\App\User::FIELD_ACTIVATED} = true;
                $user->{\App\User::FIELD_PASSWORD} = \Hash::make($oaID . config('app.key') . $service);

            }


            $user->save();

            if(!@$oAuthUserData->first_name || !@$oAuthUserData->last_name)
            {
                $user->fetchNamesFromFullname($oAuthUserData->name);
            }else{
                $user->{\App\User::FIELD_FIRST_NAME} = $oAuthUserData->first_name;
                $user->{\App\User::FIELD_LAST_NAME} = $oAuthUserData->last_name;
            }

            $user->{\App\User::FIELD_AVATAR} = $oAuthUserData->avatar;

            // extra info

            $user->save();

            $user->oauths()->save($oaUser);
        }




        $user->save();

        return $user;
    }

}