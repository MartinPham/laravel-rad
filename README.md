# laravel rad components

```
laravel new App
```

```
cd App
```

```
composer require martinpham/laravel-rad:dev-master
```

config/app.php
```
Martinpham\LaravelRAD\LaravelRADServiceProvider::class,
```

```
php artisan vendor:publish --force && lc optimize
```

update .env.rad -> .env

```
php artisan db:seed
```