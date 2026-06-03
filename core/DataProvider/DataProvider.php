<?php

namespace App\DataProvider;

class DataProvider implements DataProviderInterface
{
    protected array $_data = [];

    function  __construct(?array $data = null)
    {
        if (is_array($data)) {
            $this->_data = $data;
        }
    }

    public function __set(string $name, $value): void
    {
        $this->_data[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->_data[$name] ?? null;
    }

    public function getData(?string $name = null): mixed
    {
        if (!is_null($name)) {
            return $this->_data[$name] ?? null;
        }
        return $this->_data;
    }

    public function setData(string $name, $value): void
    {
        $this->__set($name, $value);
    }

    public function initData(array $data): void
    {
        $this->_data = $data;
    }

    public function isset(string $name): bool
    {
        return isset($this->_data[$name]);
    }

    public function unset(string $name): void
    {
        unset($this->_data[$name]);
    }
}
