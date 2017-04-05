# Laravel RAD components

- MongoDB/MySQL
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

```
npm install -g gulp gulp-concat gulp-uglify gulp-notify gulp-util gulp-minify-css gulp-connect gulp-autoprefixer
```

```
npm link gulp gulp-concat gulp-uglify gulp-notify gulp-util gulp-minify-css gulp-connect gulp-autoprefixer
```