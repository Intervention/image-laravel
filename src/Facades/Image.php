<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Intervention\Image\ImageManager read(mixed $input, string|array|\Intervention\Image\Interfaces\DecoderInterface $decoders = [])
 * @method static \Intervention\Image\ImageManager create(int $width, int $height)
 * @method static \Intervention\Image\ImageManager animate(callable $callback)
 */
class Image extends Facade
{
    protected static function getFacadeAccessor(): string

    {
        return 'image';
    }
}
