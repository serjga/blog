<?php
namespace App\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ScssPhp\ScssPhp\Compiler as ScssPhpCompiler;
use ScssPhp\ScssPhp\OutputStyle;

class Compiler implements CommandInterface
{
    protected string $_destPath = __DIR__ . "/../../public/assets";
    protected string $_srcStaticPath = __DIR__ . "/../../static/assets";
    protected string $_vendorSrcPath = __DIR__ . "/../../vendor";

    public function execute(): int
    {
        $this->_clearAssets();
        $this->_compileImages();
        $this->_compileStyles();
        $this->_compileFonts();
        $this->_compileScripts();
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
        // bootstrap
        $bootstrapSrcPath = $this->_vendorSrcPath . "/twbs/bootstrap/dist/css/bootstrap.css";
        $bootstrapDist = $this->_destPath . "/plugins/bootstrap/css";
        if (!is_dir($bootstrapDist)) {
            mkdir($bootstrapDist, 0755, true);
        }
        copy($bootstrapSrcPath, "$bootstrapDist/bootstrap.min.css");

        // scss
        $src = "$this->_srcStaticPath/scss/style.scss";
        $dst = "$this->_destPath/css";

        $scssCompiler = new ScssPhpCompiler();
        $scssCompiler->setOutputStyle(OutputStyle::COMPRESSED);
        $scssCompiler->setImportPaths([
            "$this->_srcStaticPath/scss",
            "$this->_vendorSrcPath/components/font-awesome/scss",
            "$this->_vendorSrcPath/components/font-awesome/webfonts",
            "$this->_vendorSrcPath/npm-asset/jquery-nice-select/scss"
        ]);

        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        $scssFile = file_get_contents($src);
        $cssOut = $scssCompiler->compileString($scssFile)->getCss();

        file_put_contents("$dst/style.css", $cssOut);

        echo "Styles successfully compiled.\n";
    }

    private function _compileImages(): void
    {
        $src = "$this->_srcStaticPath/images";
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
        $src = "$this->_vendorSrcPath/components/font-awesome/webfonts";
        $dst = "$this->_destPath/fonts";

        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        copy("$src/fa-solid-900.ttf", "$dst/fa-solid-900.ttf");
        copy("$src/fa-solid-900.woff2", "$dst/fa-solid-900.woff2");

        echo "Fonts successfully compiled.\n";
    }

    private function _compileScripts(): void
    {
        // bootstrap
        $srcBootstrap = "$this->_vendorSrcPath/twbs/bootstrap/dist/js/bootstrap.min.js";
        $dstBootstrap = "$this->_destPath/plugins/bootstrap/js";
        if (!is_dir($dstBootstrap)) {
            mkdir($dstBootstrap, 0755, true);
        }
        copy($srcBootstrap, "$dstBootstrap/bootstrap.js");

        // jquery
        $srcJquery = "$this->_vendorSrcPath/npm-asset/jquery/dist/jquery.min.js";
        $dstJquery = "$this->_destPath/plugins/jquery";
        if (!is_dir($dstJquery)) {
            mkdir($dstJquery, 0755, true);
        }
        copy($srcJquery, "$dstJquery/jquery.min.js");        
        
        // jquery nice select
        $srcJqueryNiceSelect = "$this->_vendorSrcPath/npm-asset/jquery-nice-select/js/jquery.nice-select.min.js";
        $dstJqueryNiceSelect = "$this->_destPath/plugins/jquery-nice-select/js";
        
        if (!is_dir($dstJqueryNiceSelect)) {
            mkdir($dstJqueryNiceSelect, 0755, true);
        }
        copy($srcJqueryNiceSelect, "$dstJqueryNiceSelect/jquery.nice-select.min.js");
        
        // scripts
        $srcScripts = "$this->_srcStaticPath/js/scripts.js";
        $dstScripts = "$this->_destPath/js";

        if (!is_dir($dstScripts)) {
            mkdir($dstScripts, 0755, true);
        }

        copy($srcScripts, "$dstScripts/scripts.js");

        echo "Scripts successfully compiled.\n";
    }
}
