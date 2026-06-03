<?php

namespace App\View;

use App\DataProvider\DataProviderFactory;

abstract class AbstractView implements \App\View\ViewInterface, \App\View\Cache\CacheInterface
{
    protected string $_extendsBlockName = '';
    protected ?\App\View\ViewInterface $_extends = null;
    protected ?string $_cacheTag = null;
    protected ?string $_cacheId = null;
    protected ?string $_compileTag = null;
    protected ?string $_compileId = null;
    protected \App\DataProvider\DataProviderInterface $_data;
    protected \App\DataProvider\DataProviderInterface $_inputData;
    protected ?\App\View\ViewInterface $_context;

    public function __construct(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context
    ) {
        $this->_inputData = $inputDataProvider;
        $this->_data = (new DataProviderFactory())->create();
        $this->_context = $context;
    }

    public function getInputData(string $name = null): mixed
    {
        if (is_null($name)) {
            return $this->_inputData->getData();
        }
        return $this->_inputData->{$name};
    }

    public function setInputData(string $name, $value): ViewInterface
    {
        $this->_inputData->{$name} = $value;
        return $this;
    }

    public function getContextData(string $name = null): mixed
    {
        return $this->_context?->getData($name);
    }

    public function getData(string $name = null): mixed
    {
        if (is_null($name)) {
            return $this->_data->getData();
        }
        return $this->_data->getData($name);
    }

    public function setData(string $name, $value): ViewInterface
    {
        $this->_data->{$name} = $value;
        return $this;
    }

    public function initData(\App\DataProvider\DataProviderInterface $data): ViewInterface
    {
        $this->_data = $data;
        return $this;
    }

    public function setCacheId(string $cacheId): self
    {
        $this->_cacheTag = $cacheId;
        if ($this->_context?->getCacheId()) {
            $this->_cacheId = $cacheId . '|' . $this->_context->getCacheId();
        } else {
            $this->_cacheId = $cacheId;
        }

        return $this;
    }

    public function setCompileId(string $compileId): self
    {
        $this->_compileTag = $compileId;
        if ($this->_context?->getCompileId()) {
            $this->_compileId = $compileId . '|' . $this->_context->getCompileId();
        } else {
            $this->_compileId = $compileId;
        }
        return $this;
    }

    public function getCacheId(): ?string
    {
        return $this->_cacheId;
    }

    public function getCompileId(): ?string
    {
        return $this->_compileId;
    }

    public function extends(\App\View\ViewInterface $view, $blockName): self
    {
        $this->_extends = $view;
        $this->_extendsBlockName = $blockName;
        return $this;
    }
}
