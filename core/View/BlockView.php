<?php

namespace App\View;

use App\Module\Registry;

class BlockView extends View
{
    public function __construct(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context
    ) {
        parent::__construct($inputDataProvider, $context);
        $templateDir = (new Registry())->getTemplatesDirectory();
        $this->setTemplateDirectory($templateDir);
    }
}
