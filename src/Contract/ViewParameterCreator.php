<?php

namespace Gugunso\LaravelUiViewComposer\Contract;

/**
 * Interface ViewParameterCreator
 * @package Gugunso\LaravelUiViewComposer\Contract
 */
interface ViewParameterCreator
{
    /**
     * ViewComposerからviewにアサインしたい内容を配列で返却する。
     * @return array
     */
    public function createParameter(): array;
}
