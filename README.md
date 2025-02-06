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

## Features

Although Intervention Image can be used with Laravel without this extension,
this intergration package includes the following features that make image
interaction with the framework much easier.

### Application-wide configuration

The extension comes with a global configuration file that is recognized by
Laravel. It is therefore possible to store the settings for Intervention Image
once centrally and not have to define them individually each time you call the
image manager.

The configuration file can be copied to the application with the following command.

```bash
php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"
```

This command will publish the configuration file `config/image.php`. Here you
can set the desired driver and its configuration options for Intervention
Image. By default the library is configured to use GD library for image
processing.

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
    |
    | - "strip" controls if meta data like exif tags should be removed when
    |    encoding images.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
        'strip' => false,
    ]
];
```

You can read more about the different options for
[driver selection](https://image.intervention.io/v3/basics/image-manager#driver-selection), setting options for 
[auto orientation](https://image.intervention.io/v3/modifying/effects#image-orientation-according-to-exif-data), 
[decoding animations](https://image.intervention.io/v3/modifying/animations) and 
[blending color](https://image.intervention.io/v3/basics/colors#transparency).

### Static Facade Interface

This package also integrates access to Intervention Image's central entry
point, the `ImageManager::class`, via a static [facade](https://laravel.com/docs/11.x/facades). The call provides access to the
centrally configured [image manager](https://image.intervention.io/v3/basics/instantiation) via singleton pattern.

The following code example shows how to read an image from an upload request
the image facade in a Laravel route and save it on disk with a random file
name.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

Route::get('/', function (Request $request) {
    $upload = $request->file('image');
    $image = Image::read($upload)
        ->resize(300, 200);

    Storage::put(
        Str::random() . '.' . $upload->getClientOriginalExtension(),
        $image->encodeByExtension($upload->getClientOriginalExtension(), quality: 70)
    );
});
```

### Image Response Macro

Furthermore, the package includes a response macro that can be used to
elegantly encode an image resource and convert it to an HTTP response in a
single step.

The following code example shows how to read an image from disk apply
modifications and use the image response macro to encode it and send the image
back to the user in one call. Only the first parameter is required.

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

Route::get('/', function () {
    $image = Image::read(Storage::get('example.jpg'))
        ->scale(300, 200);

    return response()->image($image, Format::WEBP, quality: 65);
});
```

You can read more about intervention image in general in the [official documentation of Intervention Image](https://image.intervention.io).

## Authors

This library is developed and maintained by [Oliver Vogel](https://intervention.io)

Thanks to the community of [contributors](https://github.com/Intervention/image-laravel/graphs/contributors) who have helped to improve this project.

## License

Intervention Image Laravel is licensed under the [MIT License](LICENSE).
