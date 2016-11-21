<?php namespace Martinpham\LaravelRAD;

use Illuminate\Support\ServiceProvider;

class LaravelRADServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
//        dd('boot');

        $this->publishes([
            __DIR__.'/../../../config/acp.php' => config_path('acp.php'),
            __DIR__.'/../../../config/app.php' => config_path('app.php'),
            __DIR__.'/../../../config/database.php' => config_path('database.php'),
            __DIR__.'/../../../config/filesystems.php' => config_path('filesystems.php'),
            __DIR__.'/../../../config/services.php' => config_path('services.php'),
            __DIR__.'/../../../config/jwt.php' => config_path('jwt.php'),
            __DIR__.'/../../../config/lfm.php' => config_path('lfm.php'),
            __DIR__.'/../../../config/recaptcha.php' => config_path('recaptcha.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../../views/ACP' => resource_path('views/ACP'),
            __DIR__.'/../../../views/emails/activateUser.blade.php' => resource_path('views/emails/activateUser.blade.php'),
            __DIR__.'/../../../views/emails/resetPassword.blade.php' => resource_path('views/emails/resetPassword.blade.php'),
            __DIR__.'/../../../views/includes/ACP' => resource_path('views/includes/ACP'),
            __DIR__.'/../../../views/layouts/ACP' => resource_path('views/layouts/ACP'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../../public/assets' => public_path('public/assets'),
            __DIR__.'/../../../public/build' => public_path('public/build'),
            __DIR__.'/../../../public/vendor' => public_path('public/vendor'),
            __DIR__.'/../../../public/home.html' => public_path('public/home.html'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../../app/Providers/RouteServiceProvider.php' => app_path('Providers/RouteServiceProvider.php'),
            __DIR__.'/../../../app/Model.php' => app_path('Model.php'),
            __DIR__.'/../../../app/Oauth.php' => app_path('Oauth.php'),
            __DIR__.'/../../../app/User.php' => app_path('User.php'),
            __DIR__.'/../../../app/Http/Kernel.php' => app_path('Http/Kernel.php'),
            __DIR__.'/../../../app/Http/Controllers' => app_path('Http/Controllers'),
        ], 'app');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
//        dd('register');
    }
}
