<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel;

use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Http\Response;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Intervention\Image\MediaType;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap application events.
     *
     * @return void
     */
    public function boot()
    {
        // define config files for publishing
        $this->publishes([
            __DIR__ . '/../config/intervention-image.php' => config_path('intervention-image.php'),
        ]);

        // register response macro "image"
        ResponseFacade::resolved(function ($factory): void {
            if ($factory::hasMacro('image')) {
                return;
            }

            $factory::macro('image', function (
                ImageInterface $image,
                null|string|Format|MediaType|FileExtension $format = null,
                mixed ...$options,
            ): Response {
                return ImageResponseFactory::make($image, $format, ...$options);
            });
        });
    }

    /**
     * Register the image service.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/intervention-image.php',
            'intervention-image',
        );

        $this->app->singleton(Facades\Image::BINDING, function () {
            return new ImageManager(...$this->config());
        });

        $this->app->singleton(ImageManager::class, function () {
            return new ImageManager(...$this->config());
        });

        $this->app->singleton(ImageManagerInterface::class, function () {
            return new ImageManager(...$this->config());
        });
    }

    /**
     * Get image manager config values.
     *
     * @return array{
     *      'driver': string,
     *      'autoOrientation': bool,
     *      'decodeAnimation': bool,
     *      'backgroundColor': mixed,
     *      'strip': bool}
     */
    private function config(): array
    {
        $filename = $this->hasPublishedLegacyConfig() ? 'image' : 'intervention-image';

        return [
            'driver' => config($filename . '.driver'),
            'autoOrientation' => config($filename . '.options.autoOrientation', true),
            'decodeAnimation' => config($filename . 'image.options.decodeAnimation', true),
            'backgroundColor' => config($filename . 'image.options.backgroundColor', 'ffffff'),
            'strip' => config($filename . 'image.options.strip', false),
        ];
    }

    /**
     * Determine if a legacy config file for Intervention Image Laravel is published.
     */
    private function hasPublishedLegacyConfig(): bool
    {
        return is_array(config('image.options'));
    }
}
