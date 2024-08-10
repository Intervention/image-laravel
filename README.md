# Intervention Image Laravel
## Laravel Integration for Intervention Image

[![Latest Version](https://img.shields.io/packagist/v/intervention/image-laravel.svg)](https://packagist.org/packages/intervention/image-laravel)
[![Tests](https://github.com/Intervention/image-laravel/actions/workflows/build.yml/badge.svg)](https://github.com/Intervention/image-laravel/actions/workflows/build.yml)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/image-laravel.svg)](https://packagist.org/packages/intervention/image-laravel/stats)
[![Support me on Ko-fi](https://raw.githubusercontent.com/Intervention/image-laravel/main/.github/images/support.svg)](https://ko-fi.com/interventionphp)

This package provides an integration to setup [Intervention
Image](https://image.intervention.io) easily to your Laravel application.
Included are a Laravel service provider, facade and a publishable configuration
file.

## Requirements

- Laravel >= 8

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
desired driver and its configuration options for Intervention Image. By default
the library is configured to use GD library for image processing.

The configuration files looks like this.

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports “GD Library” and “Imagick” to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    |
    | - "autoOrientation" controls whether an imported image should be
    |    automatically rotated according to any existing Exif data.
    |
    | - "decodeAnimation" decides whether a possibly animated image is
    |    decoded as such or whether the animation is discarded.
    |
    | - "blendingColor" Defines the default blending color.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
    ]
];
```

You can read more about the different options for
[driver selection](https://image.intervention.io/v3/basics/image-manager#driver-selection), setting options for 
[auto orientation](https://image.intervention.io/v3/modifying/effects#image-orientation-according-to-exif-data), 
[decoding animations](https://image.intervention.io/v3/modifying/animations) and 
[blending color](https://image.intervention.io/v3/basics/colors#transparency).

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

## Authors

This library is developed and maintained by [Oliver Vogel](https://intervention.io)

Thanks to the community of [contributors](https://github.com/Intervention/image-laravel/graphs/contributors) who have helped to improve this project.

## License

Intervention Image Laravel is licensed under the [MIT License](LICENSE).
