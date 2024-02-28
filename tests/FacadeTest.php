<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use Error;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use ReflectionClass;
use TypeError;
use ValueError;

final class FacadeTest extends TestCase
{
    public function testImageFacadeIsASubclassOfFacade(): void
    {
        $facade = new ReflectionClass('Illuminate\Support\Facades\Facade');
        $reflection = new ReflectionClass('Intervention\Image\Laravel\Facades\Image');
        $this->assertTrue($reflection->isSubclassOf($facade));
    }

    public function testFacadeAccessorReturnsImage(): void
    {
        $reflection = new ReflectionClass('Intervention\Image\Laravel\Facades\Image');
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);
        $this->assertSame('image', $method->invoke(null));
    }

    public function testReadAnImage(): void
    {
        Storage::fake('images');
        $input = UploadedFile::fake()->image('image.jpg');
        $result = Image::read($input);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testThrowsExceptionWhenReadingNonExistentImage(): void
    {
        $this->expectException(DecoderException::class);
        $input = 'path/to/non_existent_image.jpg';
        Image::read($input);
    }

    public function testCreateAnImage(): void
    {
        $width = 200;
        $height = 200;
        $result = Image::create($width, $height);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testThrowsExceptionWhenCreatingImageWithInvalidDimensions(): void
    {
        $this->expectException(ValueError::class);
        $width = -200;
        $height = 200;
        Image::create($width, $height);
    }

    public function testAnimateAnImage(): void
    {
        $callback = function () {};
        $result = Image::animate($callback);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testThrowsExceptionWhenAnimatingImageWithInvalidCallback(): void
    {
        $this->expectException(TypeError::class);
        $callback = 'not_callable';
        Image::animate($callback);
    }
}
