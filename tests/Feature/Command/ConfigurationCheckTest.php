<?php

namespace Gugunso\LaravelUiViewComposer\Test\Feature\Command;

use Gugunso\LaravelUiViewComposer\Command\ConfigurationCheck;


/**
 * @coversDefaultClass \Gugunso\LaravelUiViewComposer\Command\ConfigurationCheck
 * Gugunso\LaravelUiViewComposer\Test\Feature\Command\ConfigurationCheckTest
 */
class ConfigurationCheckTest extends Base
{
    /** @var $testClassName as test target class name */
    protected $testClassName = ConfigurationCheck::class;

    /**
     * @covers ::
     */
    public function test_()
    {
        $this->artisan('laravel-ui-view-composer:config-check');
    }
}
