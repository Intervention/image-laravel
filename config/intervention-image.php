<?php

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
    |   - \Intervention\Image\Drivers\Vips\Driver::class
    */

    'driver' => env('IMAGE_DRIVER', \Intervention\Image\Drivers\Gd\Driver::class),

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    |
    | - "autoOrientation" controls whether imported images should be
    |    automatically rotated according to any existing Exif data.
    |
    | - "decodeAnimation" determines whether animated images are decoded
    |    with their animation intact or if the animation is discarded.
    |
    | - "backgroundColor" defines the default background and blending color.
    |
    | - "strip" controls whether metadata like Exif tags should be removed
    |    automatically when encoding images.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'backgroundColor' => 'ffffff',
        'strip' => false,
    ],
];
