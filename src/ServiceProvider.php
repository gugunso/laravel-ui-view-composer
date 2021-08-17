<?php

namespace Gugunso\LaravelUiViewComposer;

use Illuminate\Support\ServiceProvider as BaseProvider;

/**
 * Class ServiceProvider
 * @package Gugunso\LaravelUiViewComposer
 */
class ServiceProvider extends BaseProvider
{
    public function boot()
    {
        $this->publishes(
            [
                realpath(__DIR__ . '/../resources/vc-autoloader-dist.php') => config_path('vc-autoloader.php'),
            ]
        );
    }
}