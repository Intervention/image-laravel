<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class FacadeTest extends TestCase
{
    public function testFacade()
    {
        $facade = new ReflectionClass('Illuminate\Support\Facades\Facade');
        $reflection = new ReflectionClass('Intervention\Image\Laravel\Facades\Image');
        $this->assertTrue($reflection->isSubclassOf($facade));
    }

    public function testFacadeAccessor()
    {
        $reflection = new ReflectionClass('Intervention\Image\Laravel\Facades\Image');
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);
        $this->assertSame('image', $method->invoke(null));
    }
}
