<?php

namespace App;


class Oauth extends Model
{
    use \Martinpham\LaravelRAD\Models\Oauth;

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'service', 'oaid'
    ];
}

