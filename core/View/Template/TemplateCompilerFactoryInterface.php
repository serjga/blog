<?php

namespace App\View\Template;

interface TemplateCompilerFactoryInterface
{
    public function create(): TemplateCompilerInterface;
}
