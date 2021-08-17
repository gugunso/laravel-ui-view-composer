<?php

namespace Gugunso\LaravelUiViewComposer\Tests\Unit\Config;

use Gugunso\LaravelUiViewComposer\Config\VcAutoLoaderConfig;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Gugunso\LaravelUiViewComposer\Config\VcAutoLoaderConfig
 * Gugunso\LaravelUiViewComposer\Tests\Unit\Config\VcAutoLoaderConfigTest
 */
class VcAutoLoaderConfigTest extends TestCase
{
    /** @var $testClassName as test target class name */
    protected $testClassName = VcAutoLoaderConfig::class;

    /**
     * @covers ::isEnable
     */
    public function test_isEnable()
    {
        $targetClass = new $this->testClassName();
        Config::set('vc-autoloader.enable', '1');
        $actual = $targetClass->isEnable();
        $this->assertTrue($actual);

        Config::set('vc-autoloader.enable', null);
        $actual = $targetClass->isEnable();
        $this->assertFalse($actual);
    }

    /**
     * @covers ::getSuffix
     */
    public function test_getSuffix()
    {
        $targetClass = new $this->testClassName();
        Config::set('vc-autoloader.suffix', 'suffix.ext');
        $actual = $targetClass->getSuffix();
        $this->assertSame('suffix.ext', $actual);
    }

    /**
     * @covers ::getInterface
     */
    public function test_getInterface()
    {
        $targetClass = new $this->testClassName();
        Config::set('vc-autoloader.interface', 'interface-name');
        $actual = $targetClass->getInterface();
        $this->assertSame('interface-name', $actual);
    }

}
