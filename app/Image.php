<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 17:49
 */

namespace App;


class Item extends Model
{
    protected $fillable = ['name', 'photo'];
    protected $hidden = ['created_at', 'updated_at'];

}