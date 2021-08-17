<?php

namespace Gugunso\LaravelUiViewComposer\Tests\Unit;

use Gugunso\LaravelUiViewComposer\AutoLoadServiceProvider;
use Gugunso\LaravelUiViewComposer\Config\VcAutoLoaderConfig;
use Illuminate\Support\Facades\App;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
     * @covers ::boot
     */
    public function test_boot_Enabled()
    {
        $targetClass = \Mockery::mock($this->testClassName)->makePartial()->shouldAllowMockingProtectedMethods();
        $targetClass->shouldReceive('registerViewComposer')->once()->with('arg1', 'arg2', 'arg3');

        $stubConfig = \Mockery::mock(VcAutoLoaderConfig::class);
        $stubConfig->shouldReceive('isEnable')->once()->andReturnTrue();
        $stubConfig->shouldReceive('getValidDirectory')->once()->andReturn(
            [
                [
                    'composer-path' => 'arg1',
                    'composer-namespace' => 'arg2',
                    'view-path' => 'arg3'
                ]
            ]
        );
        App::shouldReceive('make')->with(VcAutoLoaderConfig::class)->once()->andReturn($stubConfig);
        App::makePartial();

        $targetClass->boot();
    }

    /**
     * @covers ::registerViewComposer
     */
    public function test_registerViewComposer()
    {
        $stubSplFileInfo = \Mockery::mock(SplFileInfo::class);

        $targetClass = \Mockery::mock($this->testClassName)->makePartial()->shouldAllowMockingProtectedMethods();
        $targetClass->shouldReceive('registerByFileInfo')
            ->with($stubSplFileInfo, 'arg1', 'arg2', 'arg3')
            ->once();

        //config
        $stubConfig = \Mockery::mock(VcAutoLoaderConfig::class);
        $stubConfig->shouldReceive('getSuffix')->withNoArgs()->once()->andReturn('Suffix.ext');

        //finder
        $stubFinder = \Mockery::mock(Finder::class);
        $stubFinder->shouldReceive('files->in->name')->with([0=>'*Suffix.ext'])->once();
        $stubFinder->shouldReceive('getIterator')->andReturn(new \ArrayIterator([$stubSplFileInfo]));

        //stub返却
        App::shouldReceive('make')->with(VcAutoLoaderConfig::class)->once()->andReturn($stubConfig);
        App::shouldReceive('make')->with(Finder::class)->once()->andReturn($stubFinder);


        //テスト対象メソッドの実行
        $actual = $targetClass->registerViewComposer('arg1', 'arg2', 'arg3');
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
