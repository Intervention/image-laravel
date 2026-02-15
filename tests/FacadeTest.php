<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Images\Exceptions\InvalidArgumentException;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestBenchTestCase;
use ReflectionClass;

final class FacadeTest extends TestBenchTestCase
{
    use WithWorkbench;

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
        $result = Image::decode($input);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testThrowsExceptionWhenReadingNonExistentImage(): void
    {
        $this->expectException(FileNotFoundException::class);
        $input = 'path/to/non_existent_image.jpg';
        Image::decode($input);
    }

    public function testCreateAnImage(): void
    {
        $width = 200;
        $height = 200;
        $result = Image::createImage($width, $height);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testThrowsExceptionWhenCreatingImageWithInvalidDimensions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $width = -200;
        $height = 200;
        Image::createImage($width, $height);
    }

    public function testAnimateAnImage(): void
    {
        $result = Image::createImage(30, 20, fn () => null);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }
}
