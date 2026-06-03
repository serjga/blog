<?php

namespace App\View\Template;

use App\View\Template\Smarty\TemplateCompiler;

class TemplateCompilerFactory implements \App\View\Template\TemplateCompilerFactoryInterface
{
    public function create(): \App\View\Template\TemplateCompilerInterface
    {
        return TemplateCompiler::getInstance();
    }
}
