<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Intervention\Image\ImageManager static read(mixed $input, string|array|DecoderInterface $decoders = [])
 * @method \Intervention\Image\ImageManager static create(int $width, int $height)
 * @method \Intervention\Image\ImageManager static animate(callable $callback)
 */
class Image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'image';
    }
}
