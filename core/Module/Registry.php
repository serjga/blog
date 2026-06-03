<?php
namespace App\Module;

class Registry {
    protected string $_location = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'code';

    public function getTemplatesDirectory(): string
    {
        return $this->_location;
    }

    public function getTemplatePath(string $templatePath = ''): string
    {
        if (!empty($templatePath)) {
            return $this->getTemplatesDirectory() . DIRECTORY_SEPARATOR . $templatePath;
        }
        return $this->getTemplatesDirectory();
    }
}
