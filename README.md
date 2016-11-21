# Laravel rad components

- MongoDB
- View with helpers
- Controller with helpers
- ACP with Auth, Recaptcha, S3, CRUD example
- API with Auth, JWT, JSON Error handle, Timezone handle
- Auth with Oauth

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