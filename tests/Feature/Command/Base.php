<?php


namespace Gugunso\LaravelUiViewComposer\Test\Feature\Command;

use Orchestra\Testbench\TestCase;

class Base extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\Gugunso\LaravelUiViewComposer\ServiceProvider::class];
    }

}