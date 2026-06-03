<?php

namespace App\View;

interface ViewFactoryInterface {
    public function create(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context = null
    ): \App\View\ViewInterface;
}
