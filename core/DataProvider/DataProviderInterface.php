<?php

namespace App\DataProvider;

interface DataProviderInterface
{
    public function __set(string $name, $value): void;
    public function __get(string $name): mixed;
    public function getData(): mixed;
    public function setData(string $name, $value): void;
    public function isset(string $name): bool;
    public function initData(array $data): void;
}
