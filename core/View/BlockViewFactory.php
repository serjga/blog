<?php

namespace App\View;

class BlockViewFactory implements ViewFactoryInterface
{
    public function create(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context = null
    ): \App\View\ViewInterface {
        return new BlockView($inputDataProvider, $context);
    }
}
