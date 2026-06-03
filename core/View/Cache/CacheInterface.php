<?php

namespace App\View\Cache;

interface CacheInterface
{
    public function setCacheId(string $cacheId): self;
    public function getCacheId(): ?string;
    public function setCompileId(string $compileId): self;
    public function getCompileId(): ?string;
}
