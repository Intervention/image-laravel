# Intervention Image Laravel
## Laravel Integration for Intervention Image

[![Latest Version](https://img.shields.io/packagist/v/intervention/image-laravel.svg)](https://packagist.org/packages/intervention/image-laravel)
[![Tests](https://github.com/Intervention/image-laravel/actions/workflows/build.yml/badge.svg)](https://github.com/Intervention/image-laravel/actions/workflows/build.yml)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/image-laravel.svg)](https://packagist.org/packages/intervention/image-laravel/stats)

This package provides an integration to setup [Intervention
Image](https://image.intervention.io) easily to your Laravel application.
Included are a Laravel service provider, facade and a publishable configuration
file.

## Installation

In your existing Laravel application you can install this package using [Composer](https://getcomposer.org).

```bash
composer require intervention/image-laravel
```

Next, add the configuration files to your application using the `vendor:publish` command:

```bash
php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"
```

This command will publish the configuration file `image.php` for the image
integration to your `app/config` directory. In this file you can set the
desired driver for Intervention Image. By default the library is configured 
to use GD library for image processing.

## Getting started

The integration is now complete and it is possible to access the [ImageManager](https://image.intervention.io/v3/basics/instantiation)
via Laravel's facade system.

```php
use Intervention\Image\Laravel\Facades\Image;

Route::get('/', function () {
    $image = Image::read('images/example.jpg');
});
```

Read the [official documentation of Intervention Image](https://image.intervention.io) for more information.

## License

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2024 [Oliver Vogel](http://intervention.io/)
