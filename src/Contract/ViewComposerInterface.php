<?php

namespace Gugunso\LaravelUiViewComposer\Contract;

use Illuminate\View\View;

/**
 * Interface ViewComposerInterface
 * ViewComposerが実装するべきメソッド。
 * Laravelの暗黙のルールをinterface化しただけのもの。
 * @package Gugunso\LaravelUiViewComposer\Contract
 */
interface ViewComposerInterface
{
    /**
     * @param View $view
     */
    public function compose(View $view): void;
}
