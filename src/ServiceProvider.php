<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Intervention\Image\ImageManager;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Binding name of the service container
     */
    protected const BINDING = 'image';

    /**
     * Bootstrap application events
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/image.php' => config_path($this::BINDING . '.php')
        ]);
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
            $this::BINDING
        );

        $this->app->singleton($this::BINDING, function ($app) {
            return new ImageManager(config('image.driver'));
        });
    }
}
