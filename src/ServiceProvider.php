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
            __DIR__ . '/../config/image.php' => config_path(Facades\Image::BINDING . '.php')
        ]);

        // register response macro "image"
        ResponseFacade::resolved(function ($factory): void {
            if ($factory::hasMacro(Facades\Image::BINDING)) {
                return;
            }

            $factory::macro(Facades\Image::BINDING, function (
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
            __DIR__ . '/../config/image.php',
            Facades\Image::BINDING
        );

        $this->app->singleton(Facades\Image::BINDING, function () {
            return new ImageManager(
                driver: config('image.driver'),
                autoOrientation: config('image.options.autoOrientation', true),
                decodeAnimation: config('image.options.decodeAnimation', true),
                backgroundColor: config('image.options.backgroundColor', 'ffffff'),
                strip: config('image.options.strip', false)
            );
        });
    }
}
