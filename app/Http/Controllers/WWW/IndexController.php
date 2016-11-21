<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 12:32
 */

namespace App\Http\Controllers\WWW;


class IndexController extends Controller
{
    public function index()
    {
        $this->data['test'] = 'Hello world';
        return $this->view();
    }
}