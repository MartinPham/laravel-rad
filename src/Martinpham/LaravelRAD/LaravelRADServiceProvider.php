<?php namespace Martinpham\LaravelRAD;

use Illuminate\Support\ServiceProvider;

class LaravelRADServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        dd('boot');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        dd('register');
    }
}
