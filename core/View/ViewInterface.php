<?php

namespace App\View;

interface ViewInterface
{
    function __construct(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context
    );

    public function render(): ?string;
    public function getInputData();
    public function setInputData(string $name, $value): self;
    public function getContextData(): mixed;
    public function getData(): mixed;
    public function setData(string $name, $value): self;
}
