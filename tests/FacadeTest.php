<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;
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
        $reflection = new ReflectionClass(ImageFacade::class);
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);
        $this->assertSame('image', $method->invoke(null));
    }

    public function testReadAnImage(): void
    {
        Storage::fake('images');
        $input = UploadedFile::fake()->image('image.jpg');
        $result = ImageFacade::decode($input);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testThrowsExceptionWhenReadingNonExistentImage(): void
    {
        $this->expectException(DirectoryNotFoundException::class);
        $input = 'path/to/non_existent_image.jpg';
        ImageFacade::decode($input);
    }

    public function testCreateAnImage(): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageFacade::createImage(20, 10),
        );
    }

    public function testThrowsExceptionWhenCreatingImageWithInvalidDimensions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ImageFacade::createImage(-20, 10);
    }

    public function testAnimateAnImage(): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageFacade::createImage(30, 20, fn () => null),
        );
    }
}
