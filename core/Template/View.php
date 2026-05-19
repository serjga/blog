<?php
namespace App\Template;

use App\Request\Url;
use Smarty\Smarty;

class View
{
    protected \Smarty\Smarty $_smarty;
    protected string $_templateDir;
    protected string $_compileDir;
    protected string $_cacheDir;
    protected string $_configDir;

    public function create(): self
    {
        $this->_smarty = new Smarty();
        $this->_templateDir = __DIR__ . '/../../static/templates';
        $this->_compileDir = __DIR__ . '/../../static/templates_c';
        $this->_cacheDir = __DIR__ . '/../../static/cache';
        $this->_configDir = __DIR__ . '/../../static/configs';

        $config['date'] = '%I:%M %p';
        $this->_smarty->assign('config', $config);
        return $this;
    }

    public function render(string $templatePath): void
    {
        $url = new Url();
        $this->_smarty->registerObject('url', $url);
        $this->_smarty->setTemplateDir($this->_templateDir);
        $this->_smarty->setCompileDir($this->_compileDir);
        $this->_smarty->setCacheDir($this->_cacheDir);
        $this->_smarty->setConfigDir($this->_configDir);
        $this->_smarty->display($templatePath);
    }

    public function setTemplateDir (string $path): self
    {
        $this->_templateDir = $path;
        return $this;
    }

    public function setCompileDir (string $path): self
    {
        $this->_compileDir = $path;
        return $this;
    }

    public function setCacheDir (string $path): self
    {
        $this->_cacheDir = $path;
        return $this;
    }

    public function setConfigsDir (string $path): self
    {
        $this->_configDir = $path;
        return $this;
    }

    public function addTemplateVariable (string $variableName, $value): self
    {
        $this->_smarty->assign($variableName, $value);
        return $this;
    }
}
