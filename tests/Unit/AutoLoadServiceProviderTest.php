<?php

namespace Gugunso\LaravelUiViewComposer\Tests\Unit;

use Gugunso\LaravelUiViewComposer\AutoLoadServiceProvider;
use Gugunso\LaravelUiViewComposer\Config\VcAutoLoaderConfig;
use Illuminate\Support\Facades\App;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Gugunso\LaravelUiViewComposer\AutoLoadServiceProvider
 * Gugunso\LaravelUiViewComposer\Tests\Unit\AutoLoadServiceProviderTest
 */
class AutoLoadServiceProviderTest extends TestCase
{
    /** @var $testClassName as test target class name */
    protected $testClassName = AutoLoadServiceProvider::class;

    /**
     * @covers ::boot
     */
    public function test_boot_Disabled()
    {
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();
        $stubConfig = \Mockery::mock(VcAutoLoaderConfig::class);
        $stubConfig->shouldReceive('isEnable')->once()->andReturnFalse();
        App::shouldReceive('make')->with(VcAutoLoaderConfig::class)->once()->andReturn($stubConfig);
        App::makePartial();
        $targetClass->boot();
    }


    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }


}
