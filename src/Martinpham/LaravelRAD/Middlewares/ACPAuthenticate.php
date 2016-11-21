<?php

namespace App\RAD\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class ACPAuthenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd($this->auth->user());
        if ($this->auth->guest()) {
            return redirect()->guest('acp/auth/login');
        }else{
            if ($this->auth->user()->role === 'user')
            {
                $this->auth->logout();
                return redirect('/');
            }
        }

        return $next($request);
    }
}
