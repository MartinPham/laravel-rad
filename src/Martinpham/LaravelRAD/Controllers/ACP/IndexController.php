<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:34
 */

namespace Martinpham\LaravelRAD\Controllers\ACP;


use App\User;

trait IndexController
{
    public function index(\Illuminate\Contracts\Hashing\Hasher $hasher, \Illuminate\Routing\Redirector $redirector)
    {
        if ($this->request->has('update_user')) {
            $this->auth->user()->{User::FIELD_FIRST_NAME} = $this->request->get('first_name');
            $this->auth->user()->{User::FIELD_LAST_NAME} = $this->request->get('last_name');

            if ($this->request->has('password')) {
                $this->auth->user()->{User::FIELD_PASSWORD} = $hasher->make($this->request->get('password'));
            }
            $this->auth->user()->save();
            return $redirector->back();
        }



        return $this->view();
    }
}