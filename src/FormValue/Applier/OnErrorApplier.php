<?php

namespace Gugunso\LaravelUiViewComposer\FormValue\Applier;

use Gugunso\LaravelUiViewComposer\Contract\FormValueApplier;
use Gugunso\LaravelUiViewComposer\Contract\FormValueBuilder;
use Gugunso\LaravelUiViewComposer\FormValue\Builder\ErrorFormValueBuilder;
use Illuminate\Http\Request;

/**
 * Class OnErrorApplier
 * バリデーションエラー発生時用のApplier
 * Request内容からビルダを生成して返却する。
 * @package Gugunso\LaravelUiViewComposer\FormValue\Applier
 */
class OnErrorApplier implements FormValueApplier
{
    /** @var Request $request */
    private $request;

    /**
     * OnErrorApplier constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * shouldApply
     *
     * @return bool
     */
    public function shouldApply(): bool
    {
        return (bool)$this->request->old();
    }


    /**
     * getBuilder
     *
     * @return FormValueBuilder
     */
    public function getBuilder(): FormValueBuilder
    {
        return new ErrorFormValueBuilder($this->request);
    }
}
