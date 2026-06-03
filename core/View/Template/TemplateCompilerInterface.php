<?php

namespace App\View\Template;

interface TemplateCompilerInterface
{
    public function display(string $templatePath): void;
    public function fetch(string $templatePath): string;
    public function setTemplatesDir(string $dir): self;
    public function getCacheDirectory(): string;
    public function addTemplateVariable(string $variableName, $value): self;
    public function registerObject(string $key, object $obj): self;
    public function needCache(?int $expiration = null): self;
    public function setCacheId(?string $cacheId): self;
    public function setCompileId(?string $compileId): self;
    public function hasCache(?string $templatePath = null, ?string $cacheId = null, ?string $compileId = null): bool;
    public function cleanCache(string $cacheId): void;
    public function cleanAllCache(): void;
}
