<?php

namespace App\View;

use App\Config\Config;
use App\View\Template\TemplateCompilerFactory;

class View extends AbstractView
{
    private \App\View\Template\Smarty\TemplateCompiler $_templateCompiler;
    protected string $_template = '';
    protected bool $_cacheEnabled = false;
    protected bool $_useCache = false;
    protected int $_cacheExpire = 0;

    public function __construct(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context
    ) {
        parent::__construct($inputDataProvider, $context);
        $this->_templateCompiler = (new TemplateCompilerFactory())->create();

        $configCache = (new Config('app'))->get('cache');

        if (is_int($configCache)) {
            $this->_cacheEnabled = true;
            $this->_cacheExpire = $configCache;
        }
    }

    public function render(bool $return = false): ?string
    {
        $this->_templateCompiler->needCache();
        if ($this->_cacheEnabled && $this->_useCache) {
            $this->applyTemplateCompilerCache();
        }

        if ($this->_extends && $this->_extendsBlockName) {
            $blockContent = $this->_templateCompiler->fetch($this->getTemplate());

            $this->_extends->addTemplateVariable($this->_extendsBlockName, $blockContent);
            return $this->_extends->render($return);
        } else {
            if ($return) {
                return $this->_templateCompiler->fetch($this->getTemplate());
            }
            $this->_templateCompiler->display($this->getTemplate());
        }
        return null;
    }

    public function applyTemplateCompilerCache(): self
    {
        $this->_templateCompiler->needCache($this->_cacheExpire);
        $this->_templateCompiler
            ->setCacheId($this->_cacheId)
            ->setCompileId($this->_compileId);
        return $this;
    }

    public function setTemplateDirectory(string $templateDirectory): self
    {
        $this->_templateCompiler->setTemplatesDir($templateDirectory);
        return $this;
    }

    public function addTemplateVariable(string $variableName, $value): self
    {
        $this->_templateCompiler->addTemplateVariable($variableName, $value);
        return $this;
    }

    public function registerObject(string $name, object $object): self
    {
        $this->_templateCompiler->registerObject($name, $object);
        return $this;
    }

    public function cacheOn(int $expiration = 0): self
    {
        if (!$this->_cacheEnabled) {
            return $this;
        }

        $this->_useCache = true;
        if ($expiration > 0) {
            $this->_cacheExpire = $expiration;
        }
        return $this;
    }

    public function cacheOff(): self
    {
        $this->_useCache = false;
        return $this;
    }

    public function hasCache(): bool
    {
        $this->_templateCompiler->needCache();
        if ($this->_cacheEnabled && $this->_useCache) {

            $this->applyTemplateCompilerCache();
        }

        return $this->_templateCompiler->hasCache($this->getTemplate(), $this->getCacheId(), $this->getCompileId());
    }

    public function getTemplate(): string
    {
        return $this->_template;
    }

    public function setTemplate(string $template): string
    {
        return $this->_template = $template;
    }
}
