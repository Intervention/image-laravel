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
            __DIR__ . '/../config/image.php' => config_path('image.php'),
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
            'image',
        );

        $this->app->singleton(Facades\Image::BINDING, function () {
            return $this->imageManager();
        });

        $this->app->singleton(ImageManager::class, function () {
            return $this->imageManager();
        });

        $this->app->singleton(ImageManagerInterface::class, function () {
            return $this->imageManager();
        });
    }

    private function imageManager(): ImageManagerInterface
    {
        return new ImageManager(
            driver: config('image.driver'),
            autoOrientation: config('image.options.autoOrientation', true),
            decodeAnimation: config('image.options.decodeAnimation', true),
            backgroundColor: config('image.options.backgroundColor', 'ffffff'),
            strip: config('image.options.strip', false),
        );
    }
}
