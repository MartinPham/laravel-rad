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
            __DIR__.'/../../../views/WWW' => resource_path('views/WWW'),
            __DIR__.'/../../../views/emails/activateUser.blade.php' => resource_path('views/emails/activateUser.blade.php'),
            __DIR__.'/../../../views/emails/resetPassword.blade.php' => resource_path('views/emails/resetPassword.blade.php'),
            __DIR__.'/../../../views/includes/ACP' => resource_path('views/includes/ACP'),
            __DIR__.'/../../../views/layouts/ACP' => resource_path('views/layouts/ACP'),
            __DIR__.'/../../../views/soft_redirect.blade.php' => resource_path('views/soft_redirect.blade.php'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../../public/assets' => public_path('assets'),
            __DIR__.'/../../../public/build' => public_path('build'),
            __DIR__.'/../../../public/vendor' => public_path('vendor'),
            __DIR__.'/../../../public/home.html' => public_path('home.html'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../../routes' => base_path('routes'),
        ], 'route');

        $this->publishes([
            __DIR__.'/../../../gulp/css' => base_path('gulp/css'),
            __DIR__.'/../../../gulp/js' => base_path('gulp/js'),
            __DIR__.'/../../../gulp/gulpfile.js' => base_path('gulp/package.json'),
        ], 'gulp');

        $this->publishes([
            __DIR__.'/../../../app/Providers/RouteServiceProvider.php' => app_path('Providers/RouteServiceProvider.php'),
            __DIR__.'/../../../app/Providers/EventServiceProvider.php' => app_path('Providers/EventServiceProvider.php'),
            __DIR__.'/../../../app/Providers/AuthServiceProvider.php' => app_path('Providers/AuthServiceProvider.php'),
            __DIR__.'/../../../app/Model.php' => app_path('Model.php'),
            __DIR__.'/../../../app/Oauth.php' => app_path('Oauth.php'),
            __DIR__.'/../../../app/User.php' => app_path('User.php'),
            __DIR__.'/../../../app/Item.php' => app_path('Item.php'),
            __DIR__.'/../../../app/Http/Kernel.php' => app_path('Http/Kernel.php'),
            __DIR__.'/../../../app/Http/Controllers' => app_path('Http/Controllers'),
        ], 'app');

        $this->publishes([
            __DIR__.'/../../../database/seeds' => database_path('seeds'),
        ], 'database');
        
        $this->publishes([
            __DIR__.'/../../../.env' => base_path('.env.rad'),
        ], 'env');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
//        dd('register');
    }
}
