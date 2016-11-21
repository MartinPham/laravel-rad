<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:34
 */

namespace Martinpham\LaravelRAD\Controllers\ACP;


trait IndexController
{
    public function index(\Illuminate\Contracts\Hashing\Hasher $hasher, \Illuminate\Routing\Redirector $redirector)
    {
        if ($this->request->has('name')) {
            $this->auth->user()->name = $this->request->get('name');

            if ($this->request->has('password')) {
                $this->auth->user()->password = $hasher->make($this->request->get('password'));
            }
            $this->auth->user()->save();
            return $redirector->back();
        }



        return $this->view();
    }
}