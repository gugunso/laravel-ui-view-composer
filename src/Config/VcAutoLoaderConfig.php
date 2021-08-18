<?php

namespace Gugunso\LaravelUiViewComposer\Config;

use Illuminate\Support\Facades\Config;

class VcAutoLoaderConfig
{
    /**
     * isEnable
     *
     * @return bool
     */
    public function isEnable()
    {
        return (bool)Config::get('vc-autoloader.enable');
    }

    /**
     * getSuffix
     *
     * @return string
     */
    public function getSuffix()
    {
        return Config::get('vc-autoloader.suffix');
    }

    /**
     * getInterface
     *
     * @return string
     */
    public function getInterface()
    {
        return Config::get('vc-autoloader.interface');
    }

    /**
     * getValidDirectory
     *
     * @return iterable
     */
    public function getValidDirectory(): iterable
    {
        $directories = Config::get('vc-autoloader.settings');
        foreach ($directories as $setting) {
            $setting['composer-path'] = $setting['composer-path'] ?? '';
            $setting['composer-namespace'] = $setting['composer-namespace'] ?? 'App\\';
            $setting['view-path'] = $setting['view-path'] ?? '';
            if ($this->isValidSettings(
                $setting['composer-path'],
                $setting['composer-namespace'],
                $setting['view-path']
            )) {
                yield new SettingItem($setting);
            }
        }
    }

    /**
     * isValidSettings
     *
     * @param string $composerPath
     * @param string $composerNameSpace
     * @param string $view
     * @return bool
     */
    private function isValidSettings(string $composerPath, string $composerNameSpace, string $view)
    {
        if (!$composerPath) {
            return false;
        }
        if (!$view) {
            return false;
        }
        if (!is_dir($composerPath)) {
            return false;
        }
        return true;
    }

}