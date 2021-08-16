<?php

namespace Gugunso\LaravelUiViewComposer\Tests\Unit\FormValue\Applier;

use Gugunso\LaravelUiUtils\Http\Contract\IdentityHandler;
use Gugunso\LaravelUiViewComposer\Contract\FormValueApplier;
use Gugunso\LaravelUiViewComposer\Contract\FormValueBuilder;
use Gugunso\LaravelUiViewComposer\FormValue\Applier\OnUpdateApplier;
use Mockery;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Gugunso\LaravelUiViewComposer\FormValue\Applier\OnUpdateApplier
 * Gugunso\LaravelUiViewComposer\Tests\Unit\FormValue\Applier\OnUpdateApplierTest
 */
class OnUpdateApplierTest extends TestCase
{
    /** @var $testClassName as test target class name */
    protected $testClassName = OnUpdateApplier::class;

    /**
     * @covers ::__construct
     */
    public function test___construct()
    {
        $stubFormValueBuilder = Mockery::mock(FormValueBuilder::class);
        $stubIdentityHandler = Mockery::mock(IdentityHandler::class);
        $targetClass = new $this->testClassName($stubFormValueBuilder, $stubIdentityHandler);
        $this->assertInstanceOf(FormValueApplier::class, $targetClass);

        //テスト対象メソッドの実行
        \Closure::bind(
            function () use ($targetClass, $stubFormValueBuilder, $stubIdentityHandler) {
                //assertions of constructor
                $this->assertSame($stubFormValueBuilder, $targetClass->builder);
                $this->assertSame($stubIdentityHandler, $targetClass->identityHandler);
            },
            $this,
            $targetClass
        )->__invoke();
    }

    /**
     * @covers ::shouldApply
     */
    public function test_shouldApply()
    {
        $stubFormValueBuilder = Mockery::mock(FormValueBuilder::class);
        $stubIdentityHandler = Mockery::mock(IdentityHandler::class);
        $stubIdentityHandler->shouldReceive('retrieveIdentity')
            ->withNoArgs()
            ->twice()
            ->andReturn('1', null);

        $targetClass = new $this->testClassName($stubFormValueBuilder, $stubIdentityHandler);

        //テスト対象メソッドの実行1
        $actual1 = $targetClass->shouldApply();
        $this->assertTrue($actual1);

        //テスト対象メソッドの実行2
        $actual2 = $targetClass->shouldApply();
        $this->assertFalse($actual2);
    }

    /**
     * @covers ::getBuilder
     */
    public function test_getBuilder()
    {
        $stubFormValueBuilder = Mockery::mock(FormValueBuilder::class);
        $stubIdentityHandler = Mockery::mock(IdentityHandler::class);
        $targetClass = new $this->testClassName($stubFormValueBuilder, $stubIdentityHandler);

        //テスト対象メソッドの実行
        $actual = $targetClass->getBuilder();
        $this->assertSame($stubFormValueBuilder, $actual);
        $this->assertInstanceOf(FormValueBuilder::class, $actual);
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
