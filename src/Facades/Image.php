<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DataUriInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use SplFileInfo;
use Stringable;

/**
 * @method static ImageInterface createImage(int $width, int $height, null|callable|AnimationFactoryInterface $animation = null)
 * @method static ImageInterface decode(mixed $source, null|string|array|DecoderInterface $decoders = null)
 * @method static ImageInterface decodePath(string|Stringable $path)
 * @method static ImageInterface decodeBinary(string|Stringable $binary)
 * @method static ImageInterface decodeSplFileInfo(SplFileInfo $splFileInfo)
 * @method static ImageInterface decodeBase64(string|Stringable $base64)
 * @method static ImageInterface decodeDataUri(string|Stringable|DataUriInterface $dataUri)
 * @method static ImageInterface decodeStream(mixed $stream)
 */
class Image extends Facade
{
    /**
     * Binding name of the service container
     */
    public const BINDING = 'image';

    protected static function getFacadeAccessor()
    {
        return self::BINDING;
    }
}
