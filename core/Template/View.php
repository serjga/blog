<?php
namespace App\Template;

use App\Request\Request;
use Smarty\Smarty;

class View
{
    protected \Smarty\Smarty $_smarty;
    protected string $_templateDir;
    protected string $_compileDir;
    protected string $_cacheDir;
    protected string $_configDir;

    function __construct() {
        $this->_smarty = new Smarty();
        $this->_templateDir = __DIR__ . '/../../static/templates';
        $this->_compileDir = __DIR__ . '/../../static/templates_c';
        $this->_cacheDir = __DIR__ . '/../../static/cache';
        $this->_configDir = __DIR__ . '/../../static/configs';

        $request = new Request();
        $assetsUrl = $request->createUrl('/assets');
        define('ASSETS_URL', $assetsUrl);
    }

    public function render(string $templatePath): void
    {
        $this->_smarty->setTemplateDir($this->_templateDir);
        $this->_smarty->setCompileDir($this->_compileDir);
        $this->_smarty->setCacheDir($this->_cacheDir);
        $this->_smarty->setConfigDir($this->_configDir);
        $this->_smarty->display($templatePath);
    }

    public function setTemplateDir (string $path): void
    {
        $this->_templateDir = $path;
    }

    public function setCompileDir (string $path): void
    {
        $this->_compileDir = $path;
    }

    public function setCacheDir (string $path): void
    {
        $this->_cacheDir = $path;
    }

    public function setConfigsDir (string $path): void
    {
        $this->_configDir = $path;
    }
}
