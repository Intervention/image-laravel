# Intervention Image Laravel
## Laravel Integration for Intervention Image

This package provides an integration to setup [Intervention
Image](https://image.intervention.io) easily to your Laravel application.
Included are a Laravel service provider, facade and a publishable configuration
file.

## Installation

You can easily install this package using [Composer](https://getcomposer.org).

```bash
composer require intervention/image-laravel
```

Next, add the configuration files to your application using the `vendor:publish` command:

```bash
php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"
```

This command will publish the configuration file `image.php` for the image
integration to your `app/config` directory. In this file you can set the
desired driver for Intervention Image.

## Getting started

The integration is now complete and it is possible to access the [ImageManager](https://image.intervention.io/v3/basics/instantiation)
via Laravel's facade.

```php
use Intervention\Image\Laravel\Facades\Image;

Route::get('/', function () {
    $image = Image::create(300, 200);
});
```

Read the [official documentation of Intervention Image](https://image.intervention.io) for more information.

## License

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2024 [Oliver Vogel](http://intervention.io/)
