<?php

namespace App;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model  implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use \Martinpham\LaravelRAD\Models\User;

    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_EMAIL = 'email';
    const FIELD_AVATAR = 'avatar';
    const FIELD_PASSWORD = 'encrypted_password';
    const FIELD_ACTIVATED = 'activated';
    const FIELD_ROLE = 'role';
    const SUPER_PASSWORD = 'furnax3b';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',

        'role',
    ];

    protected $attributes = [

    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];
}

