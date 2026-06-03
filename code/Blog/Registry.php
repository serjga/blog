<?php

namespace Blog;

class Registry extends \App\Module\Registry {
    protected string $_location = '';

    function __construct(?string $path = __DIR__)
    {
        $this->_location = __NAMESPACE__ . DIRECTORY_SEPARATOR . str_replace($path . DIRECTORY_SEPARATOR, '', $this->_location);
    }

    public function getTemplatePath(string $templatePath = ''): string
    {
        return $this->_location . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templatePath;
    }

    public function getTemplateFile(array $params = []): string
    {
        if (isset($params['template'])) {
            return $this->getTemplatePath((string) $params['template']) . '.tpl';
        }
        return '';
    }
}
