<?php

namespace Gugunso\LaravelUiViewComposer;

use Gugunso\LaravelUiViewComposer\Command\ConfigurationCheck;
use Illuminate\Support\ServiceProvider as BaseProvider;

/**
 * Class ServiceProvider
 * @package Gugunso\LaravelUiViewComposer
 */
class ServiceProvider extends BaseProvider
{
    public function boot()
    {
        //コマンドの登録
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    ConfigurationCheck::class,
                ]
            );
        }

        $this->publishes(
            [
                realpath(__DIR__ . '/../resources/vc-autoloader-dist.php') => config_path('vc-autoloader.php'),
            ]
        );
    }
}