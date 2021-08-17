<?php

namespace Gugunso\LaravelUiViewComposer;

use Gugunso\LaravelUiViewComposer\Config\VcAutoLoaderConfig;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class AutoLoadServiceProvider
 * @package Gugunso\LaravelUiViewComposer
 */
class AutoLoadServiceProvider extends ServiceProvider
{
    /**
     * 全アプリケーションサービスの初期起動
     *
     * @return void
     */
    public function boot()
    {
        /** @var VcAutoLoaderConfig $config */
        $config = App::make(VcAutoLoaderConfig::class);

        //設定ファイルチェック、有効化されていない、設定ファイルが無い場合などは何もせず終了
        if (!$config->isEnable()) {
            return;
        }
        //登録処理開始
        foreach ($config->getValidDirectory() as $setting) {
            $this->registerViewComposer(
                $setting['composer-path'],
                $setting['composer-namespace'],
                $setting['view-path']
            );
        }
    }

    /**
     * registerViewComposer
     * ViewComposerの登録処理
     *
     * @param string $composerPath
     * @param string $composerNamespace
     * @param string $view
     */
    protected function registerViewComposer(string $composerPath, string $composerNamespace, string $view)
    {
        /** @var VcAutoLoaderConfig $config */
        $config = App::make(VcAutoLoaderConfig::class);
        /** @var Finder $finder */
        $finder = App::make(Finder::class);

        //指定パスを探索。デフォルト設定状態ではファイル名末尾が Composer.php であるファイルが対象となる。
        $finder->files()->in($composerPath)->name(['*' . $config->getSuffix()]);

        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            //発見されたファイル1点ごとに登録を実施
            $this->registerByFileInfo($fileInfo, $composerPath, $composerNamespace, $view);
        }
    }

    /**
     * registerByFileInfo
     *
     * @param SplFileInfo $fileInfo
     * @param string $composerPath
     * @param string $composerNamespace
     * @param string $view
     */
    protected function registerByFileInfo(
        SplFileInfo $fileInfo,
        string $composerPath,
        string $composerNamespace,
        string $view
    ) {
        /** @var VcAutoLoaderConfig $config */
        $config = App::make(VcAutoLoaderConfig::class);

        $pathAsArray = $this->pathAsArray($composerPath, $fileInfo);
        if (!$pathAsArray) {
            return;
        }

        //名前空間に変換
        $nameSpace = $this->viewComposerNameSpace($composerNamespace, $pathAsArray);
        //ドット記法のViewファイルパスに変換
        $viewPathAsDotNotation = $this->viewPathAsDotNotation($view, $pathAsArray);

        //検出されたViewComposerのインターフェースをチェック
        $interface = $config->getInterface();

        if ($interface) {
            if (!is_subclass_of($nameSpace, $interface)) {
                return;
            }
        }
        //ViewComposerを適用
        View::composer($viewPathAsDotNotation, $nameSpace);
    }

    /**
     * pathAsArray
     * ファイルパスから、設定値で指定ディレクトリ以降の階層のディレクトリを配列形式に変換する。
     * 返値の1つめの要素は必ず空（設定値で指定したディレクトリを示す）となる。
     *
     * @param string $composerPath
     * @param $fileInfo
     * @return false|string[]
     */
    protected function pathAsArray(string $composerPath, SplFileInfo $fileInfo)
    {
        /** @var VcAutoLoaderConfig $config */
        $config = App::make(VcAutoLoaderConfig::class);
        $composerDir = str_replace($composerPath, '', $fileInfo->getPath()) . '/';
        $baseName = $fileInfo->getBasename($config->getSuffix());
        return explode(DIRECTORY_SEPARATOR, $composerDir . $baseName);
    }

    /**
     * viewComposerNameSpace
     * ViewComposerの名前空間文字列を作成する。
     *
     * @param string $composerNamespace
     * @param array $pathAsArray
     * @return string
     */
    protected function viewComposerNameSpace(string $composerNamespace, array $pathAsArray)
    {
        return $composerNamespace . implode('\\', $pathAsArray) . 'Composer';
    }

    /**
     * viewPathAsDotNotation
     * viewファイルのパスをドット記法で示す文字列を作成する。
     *
     * @param string $view
     * @param array $pathAsArray
     * @return string
     */
    protected function viewPathAsDotNotation(string $view, array $pathAsArray)
    {
        return $view . implode('.', $pathAsArray);
    }

}