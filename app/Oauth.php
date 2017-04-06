<?php

namespace App;


class Oauth extends Model
{
    use \Martinpham\LaravelRAD\Models\Oauth;


    const FIELD_SERVICE = 'service';
    const FIELD_OAID = 'oaid';
    const FIELD_DATA = 'data';
    const FIELD_TOKEN = 'token';


    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'service', 'oaid'
    ];
}

