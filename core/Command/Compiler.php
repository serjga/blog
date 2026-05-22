<?php
namespace App\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ScssPhp\ScssPhp\Compiler as ScssPhpCompiler;
use ScssPhp\ScssPhp\OutputStyle;
use Smarty\Smarty;

class Compiler implements CommandInterface
{
    protected string $_destPath = __DIR__ . "/../../public/assets";
    protected string $_srcPath = __DIR__ . "/../../static/assets";

    public function execute(): int
    {
        $this->_clearAssets();
        $this->_compileImages();
        $this->_compileStyles();
        $this->_compileFonts();
        return 1;
    }

    private function _clearAssets(): void
    {
        if (!is_dir($this->_destPath)) {
            return;
        }

        $it = new RecursiveDirectoryIterator($this->_destPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($this->_destPath);
    }

    private function _compileStyles(): void
    {
        $src = "$this->_srcPath/scss/style.scss";
        $dst = "$this->_destPath/css";

        $scssCompiler = new ScssPhpCompiler();
        $scssCompiler->setOutputStyle(OutputStyle::COMPRESSED);
        $scssCompiler->setImportPaths([
            "$this->_srcPath/scss",
            __DIR__ . "/../../vendor/components/font-awesome/scss",
            __DIR__ . "/../../vendor/components/font-awesome/webfonts",
            __DIR__ . '/../../vendor/twbs/bootstrap/scss'
        ]);

        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        $scssFile = file_get_contents($src);
        $cssOut = $scssCompiler->compileString($scssFile)->getCss();

        file_put_contents("$dst/style.css", $cssOut);

        (new Smarty())->clearAllCache();

        echo "Styles successfully compiled.\n";
    }

    private function _compileImages(): void
    {
        $src = "$this->_srcPath/images";
        $dst = "$this->_destPath/images";
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            /** @var RecursiveDirectoryIterator $iterator */
            $targetPath = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                $dir = dirname($targetPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                copy($item->getPathname(), $targetPath);
            }
        }

        echo "Images successfully compiled.\n";
    }

    private function _compileFonts(): void
    {
        $src = __DIR__ . "/../../vendor/components/font-awesome/webfonts";
        $dst = "$this->_destPath/fonts";

        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        copy("$src/fa-solid-900.ttf", "$dst/fa-solid-900.ttf");
        copy("$src/fa-solid-900.woff2", "$dst/fa-solid-900.woff2");

        echo "Fonts successfully compiled.\n";
    }
}
