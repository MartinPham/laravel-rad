<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:17
 */

namespace Martinpham\LaravelRAD\Controllers\ACP;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

trait LoginController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/acp';

//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('guest', ['except' => 'logout']);
//    }

    public function showLoginForm()
    {
        return $this->view();
    }


    protected function validateLogin($request)
    {
        $rules = [
            $this->username() => 'required',
            'password' => 'required',

        ];
        /** @noinspection IfConditionalsWithoutCurvyBracketsInspection */
        if($this->config->get('app.env') === 'production')
        {
//            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }
        $this->validate($request, $rules);
    }

    public function logout()
    {
        $this->guard()->logout();

        $this->request->session()->flush();

        $this->request->session()->regenerate();

        return redirect('/acp');
    }
}