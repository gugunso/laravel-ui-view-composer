<?php

namespace Gugunso\LaravelUiViewComposer\Command;

use Gugunso\LaravelUiViewComposer\Config\SettingItem;
use Gugunso\LaravelUiViewComposer\Config\VcAutoLoaderConfig;
use Illuminate\Console\Command;
use Illuminate\View\Factory;

class ConfigurationCheck extends Command
{
    /** @var string $signature */
    protected $signature = 'laravel-ui-view-composer:config-check';
    /** @var string $description */
    protected $description = '設定ファイルの検証を行う';

    /** @var VcAutoLoaderConfig $config 設定ファイル内容を扱うクラスのインスタンス */
    private $config;

    /**
     *
     */
    public function handle()
    {
        $this->init();
        $this->info('==== common setting ====');
        $this->line('enable:' . ($this->config->isEnable() ? 'true' : 'false'));
        $this->line('suffix:' . $this->config->getSuffix());
        $this->line('interface:' . $this->config->getInterface());

        if (interface_exists($this->config->getInterface())) {
            $this->line('[OK] Interface has found.');
        } elseif (class_exists($this->config->getInterface())) {
            $this->line('[OK] Class has found.');
        } else {
            $this->warn('[NG] ' . $this->config->getInterface() . ' doesnt exist.');
        }

        $this->info('==== detail ====');

        $this->details();
    }

    private function init()
    {
        $this->config = new VcAutoLoaderConfig();
    }

    /**
     * @return void
     */
    private function details()
    {
        $i = 1;
        /** @var SettingItem $settingItem */
        foreach ($this->config->getValidDirectory() as $settingItem) {
            $this->info('[Setting No.' . $i . '] ' . $settingItem->getComposerPath());
            $this->detail($settingItem);
            $i++;
        }
    }

    /**
     * @param SettingItem $settingItem
     * @return void
     */
    private function detail(SettingItem $settingItem)
    {
        /** @var Factory $viewFactory */
        $viewFactory = app(Factory::class);
        $viewFinder = $viewFactory->getFinder();

        $finder = $settingItem->createFinder($this->config->getSuffix());
        $results = [];
        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            $nameSpace = $settingItem->viewComposerNamespace($fileInfo, $this->config->getSuffix());
            $viewPath = $settingItem->viewPathAsDotNotation($fileInfo, $this->config->getSuffix());

            $result = [];
            //1.
            $result[0] = $nameSpace;

            //2.
            //検出されたViewComposerのインターフェースをチェック
            $interface = $this->config->getInterface();
            if ($interface && !is_subclass_of($nameSpace, $interface)) {
                $result[1] = 'NO';
            } else {
                $result[1] = 'YES';
            }

            //3.
            $result[2] = $viewPath;

            //4.
            try {
                $viewFinder->find($viewPath);
                $result[3] = 'YES';
            } catch (\InvalidArgumentException $e) {
                $result[3] = 'NO';
            }

            //5.
            if ($result[1] === 'YES' && $result[3] === 'YES') {
                $result[4] = 'OK';
            } else {
                $result[4] = 'NG';
            }

            $results[] = $result;
        }
        $this->table(['Found ViewComposers', 'implements', 'view name', 'view exists', 'status'], $results);
    }
}