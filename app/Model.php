<?php

namespace App;


class Model extends \Jenssegers\Mongodb\Eloquent\Model
{
    use \Martinpham\LaravelRAD\Models\Model;

    protected $dates = ['deleted_at'];
}

