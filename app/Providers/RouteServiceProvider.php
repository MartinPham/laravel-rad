<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace . '\API\V1',
            'prefix' => '/api/v1',
            'as' => 'api.v1'
        ], function ($router) {
            require base_path('routes/api.v1.php');
        });


        Route::group([
            'middleware' => 'www',
            'namespace' => $this->namespace . '\WWW',
        ], function ($router) {
            require base_path('routes/www.php');
        });

        Route::group([
            'middleware' => 'acp',
            'prefix' => '/acp',
            'as' => 'acp',
            'namespace' => $this->namespace . '\ACP',
        ], function ($router) {
            require base_path('routes/acp.php');
        });
    }

}
