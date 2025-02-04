<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use finfo;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\ImageResponseFactory;
use Intervention\Image\MediaType;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestBenchTestCase;

class ImageResponseFactoryTest extends TestBenchTestCase
{
    use WithWorkbench;

    protected Image $image;

    protected function setUp(): void
    {
        $this->image = ImageManager::gd()->create(3, 2)->fill('f50');
    }

    public function testDefaultFormat(): void
    {
        $response = ImageResponseFactory::make($this->image);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());

        $response = ImageResponseFactory::make(ImageManager::gd()->read($this->image->toGif()));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());
    }

    public function testNonDefaultFormat(): void
    {
        $response = ImageResponseFactory::make(
            $this->image,
            Format::GIF,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            Format::JPEG,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());
    }

    public function testStringFormat(): void
    {
        $response = ImageResponseFactory::make(
            $this->image,
            'gif',
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            'jpg',
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            'image/jpeg',
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());
    }

    public function testMediaTypeFormat(): void
    {
        $response = ImageResponseFactory::make(
            $this->image,
            MediaType::IMAGE_GIF,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            MediaType::IMAGE_JPEG,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            MediaType::IMAGE_JPEG,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());
    }

    public function testFileExtensionFormat(): void
    {
        $response = ImageResponseFactory::make(
            $this->image,
            FileExtension::GIF
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            FileExtension::JPG
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());

        $response = ImageResponseFactory::make(
            ImageManager::gd()->read($this->image->toGif()),
            FileExtension::JPEG
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());
    }

    public function testUnknownFormat(): void
    {
        $this->expectException(NotSupportedException::class);
        ImageResponseFactory::make($this->image, 'unknown');
    }

    public function testWithEncoderOptions(): void
    {
        $response = ImageResponseFactory::make($this->image, Format::JPEG, quality: 10);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());
    }

    private function assertMimeType(string $mimeType, string $contents): void
    {
        $detected = (new finfo(FILEINFO_MIME))->buffer($contents);
        $this->assertTrue(
            str_starts_with($detected, $mimeType),
            'The detected type ' . $detected . ' does not correspond to ' . $mimeType . '.'
        );
    }
}
