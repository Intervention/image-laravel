<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Image;
use Illuminate\Http\Response;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\MediaType;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap application events
     *
     * @return void
     */
    public function boot()
    {
        // define config files for publishing
        $this->publishes([
            __DIR__ . '/../config/image.php' => config_path(Facades\Image::BINDING . '.php')
        ]);

        $this->app->afterResolving(ResponseFactory::class, function (): void {
            // register response macro "image"
            if ($this->shouldCreateResponseMacro()) {
                ResponseFacade::macro(Facades\Image::BINDING, function (
                    Image $image,
                    null|string|Format|MediaType|FileExtension $format = null,
                    mixed ...$options,
                ): Response {
                    return ImageResponseFactory::make($image, $format, ...$options);
                });
            }
        });
    }

    /**
     * Register the image service
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
                blendingColor: config('image.options.blendingColor', 'ffffff'),
                strip: config('image.options.strip', false)
            );
        });
    }

    /**
     * Determine if response macro should be created
     */
    private function shouldCreateResponseMacro(): bool
    {
        if (!$this->app->runningUnitTests() && $this->app->runningInConsole()) {
            return false;
        }

        return !ResponseFacade::hasMacro(Facades\Image::BINDING);
    }
}
