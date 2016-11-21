<?php namespace App\RAD\Middlewares;



use App\Http\Controllers\API\Controller as APIController;
use Illuminate\Support\Facades\Auth;

class APIAuthenticate extends Auth {

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (!($user = APIController::user()))
        {
            return APIController::unauthorized();
        }
        return $next($request);
    }
}
