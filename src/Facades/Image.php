<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Intervention\Image\Interfaces\ImageInterface read(mixed $input, string|array|\Intervention\Image\Interfaces\DecoderInterface $decoders = [])
 * @method static \Intervention\Image\Interfaces\ImageInterface create(int $width, int $height)
 * @method static \Intervention\Image\Interfaces\ImageInterface animate(callable $callback)
 */
class Image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'image';
    }
}
