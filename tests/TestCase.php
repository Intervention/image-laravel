<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestBenchTestCase;

abstract class TestCase extends TestBenchTestCase
{
    use WithWorkbench;
}
