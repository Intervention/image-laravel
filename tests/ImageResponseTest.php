<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use finfo;
use Intervention\Image\Format;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\ImageResponse;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestBenchTestCase;

class ImageResponseTest extends TestBenchTestCase
{
    use WithWorkbench;

    public function testDefaultFormat(): void
    {
        $response = ImageResponse::make($this->testImage());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());

        $response = ImageResponse::make(ImageManager::gd()->read($this->testImage()->toGif()));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());
    }

    public function testNonDefaultFormat(): void
    {
        $response = ImageResponse::make($this->testImage(), Format::GIF);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/gif', $response->headers->get('content-type'));
        $this->assertMimeType('image/gif', $response->content());

        $response = ImageResponse::make(ImageManager::gd()->read($this->testImage()->toGif()), Format::JPEG);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
        $this->assertMimeType('image/jpeg', $response->content());
    }

    public function testWithEncoderOptions(): void
    {
        $response = ImageResponse::make($this->testImage(), Format::JPEG, quality: 10);
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

    private function testImage(): Image
    {
        return ImageManager::gd()->create(3, 2);
    }
}
