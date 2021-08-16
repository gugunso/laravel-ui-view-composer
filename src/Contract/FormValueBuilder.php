<?php

namespace Gugunso\LaravelUiViewComposer\Contract;

use Illuminate\Support\Collection;

/**
 * Interface FormValueBuilder
 * FormValuesBuilder が実装するべきメソッド
 * @package Gugunso\LaravelUiViewComposer\Contract
 */
interface FormValueBuilder
{
    /**
     * @return Collection
     */
    public function build(): Collection;
}
