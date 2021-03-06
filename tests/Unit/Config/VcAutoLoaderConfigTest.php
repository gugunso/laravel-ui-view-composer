<?php

namespace Gugunso\LaravelUiViewComposer\Tests\Unit\Config;

use Gugunso\LaravelUiViewComposer\Config\SettingItem;
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

    /**
     * @covers ::getValidDirectory
     * @covers ::isValidSettings
     */
    public function test_getValidDirectory_NoValidSettings()
    {
        $targetClass = new $this->testClassName();
        $settings = [
            [
                'composer-path' => '',
                'composer-namespace' => '',
                'view-path' => ''
            ],
            [
                'composer-path' => 'aaaa',
                'composer-namespace' => '',
                'view-path' => ''
            ],
            [
                'composer-path' => 'aaaa',
                'composer-namespace' => '',
                'view-path' => 'bbbb'
            ],

        ];
        Config::set('vc-autoloader.settings', $settings);
        $actual = $targetClass->getValidDirectory();
        $this->assertIsIterable($actual);
        $this->assertSame([], iterator_to_array($actual));
    }

    /**
     * @covers ::getValidDirectory
     * @covers ::isValidSettings
     */
    public function test_getValidDirectory_HasValidSettings()
    {
        $targetClass = new $this->testClassName();
        $settings = [
            [
                'composer-path' => __DIR__,
                'composer-namespace' => '',
                'view-path' => 'viewPath'
            ]
        ];
        Config::set('vc-autoloader.settings', $settings);
        $actual = $targetClass->getValidDirectory();
        $this->assertIsIterable($actual);

        $asArray = iterator_to_array($actual);
        $this->assertInstanceOf(SettingItem::class, $asArray[0]);
    }

    /**
     * @covers ::interfaceExists
     */
    public function test_interfaceExists_ReturnsTrue1()
    {
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();
        //?????????????????????????????????????????????????????????
        $targetClass->shouldReceive('getInterface')->andReturn(\ArrayAccess::class);
        $actual = $targetClass->interfaceExists();
        $this->assertTrue($actual);
    }

    /**
     * @covers ::interfaceExists
     */
    public function test_interfaceExists_ReturnsTrue2()
    {
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();
        //??????????????????????????????????????????
        $targetClass->shouldReceive('getInterface')->andReturn(\stdClass::class);
        $actual = $targetClass->interfaceExists();
        $this->assertTrue($actual);
    }

    /**
     * @covers ::interfaceExists
     */
    public function test_interfaceExists_ReturnsFalse()
    {
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();
        //???????????????????????????????????????????????????????????????
        $targetClass->shouldReceive('getInterface')->andReturn('invalid class name');
        $actual = $targetClass->interfaceExists();
        $this->assertFalse($actual);
    }

    /**
     * config???interface??????????????????????????????????????????interface?????????/?????? ??????????????????true
     * @covers ::namespaceImplementsInterface
     */
    public function test_namespaceImplementsInterface_ReturnsTrue1(){
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();

        //??????or????????????????????????????????????stdClass???????????????????????????
        $targetClass->shouldReceive('getInterface')->andReturn(\stdClass::class);
        //stdClass???????????????????????????????????????
        $object=new class() extends \stdClass{};

        $actual = $targetClass->namespaceImplementsInterface(get_class($object));

        //assertions
        $this->assertTrue($actual);
    }

    /**
     * config???interface??????????????????true??????????????????
     * @covers ::namespaceImplementsInterface
     */
    public function test_namespaceImplementsInterface_ReturnsTrue2(){
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();

        //??????or???????????????????????????????????? ???????????? ???????????????????????????
        $targetClass->shouldReceive('getInterface')->andReturn('');
        //????????????????????????
        $object=new class() {};

        $actual = $targetClass->namespaceImplementsInterface(get_class($object));

        //assertions
        $this->assertTrue($actual);
    }

    /**
     * @covers ::namespaceImplementsInterface
     */
    public function test_namespaceImplementsInterface_ReturnsFalse(){
        $targetClass = \Mockery::mock($this->testClassName)->makePartial();

        //??????or????????????????????????????????????stdClass???????????????????????????
        $targetClass->shouldReceive('getInterface')->andReturn(\stdClass::class);
        //????????????????????????
        $object=new class() {};

        $actual = $targetClass->namespaceImplementsInterface(get_class($object));

        //assertions
        $this->assertFalse($actual);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }


}
