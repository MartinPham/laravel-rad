<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:12
 */

namespace Martinpham\LaravelRAD\Controllers\WWW;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

trait Controller
{
    public $auth;

    public function __construct(Request $request, ResponseFactory $responseFactory, AuthGuard $authGuard)
    {
        $this->request = $request;
        $this->respond = $responseFactory;
        $this->auth = $authGuard;
    }
}