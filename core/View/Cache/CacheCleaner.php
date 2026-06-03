<?php

namespace App\View\Cache;

use App\Logger\Logger;
use App\View\Template\Smarty\TemplateCompiler;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CacheCleaner
{
    use Logger;
    private TemplateCompiler $_templateCompiler;
    function __construct()
    {
        $this->_templateCompiler = new TemplateCompiler();
    }

    public function cleanAll (): void
    {
        $this->_templateCompiler->cleanAllCache();
    }

    function clean(array $cacheTags): void
    {
        if ($cacheTags) {
            $cacheDirectory = $this->_templateCompiler->getCacheDirectory();
            $this->cleanCacheProcess($cacheTags, $cacheDirectory);
        }
    }

    public function cleanCacheProcess(array $cacheTags, string $cacheDirectory): void
    {
        $cacheTags = array_flip($cacheTags);
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($cacheDirectory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $needCleanTags = [];
        foreach ($iterator as $item) {
            $fileName = str_replace($cacheDirectory . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $tags = explode('^', $fileName);
            array_pop($tags);
            if ($tags && isset($cacheTags[$tags[0]])) {
                $this->_templateCompiler->cleanCache($tags[0]);
                array_shift($tags);
                if (!empty($tags)) {
                    $needCleanTags =  array_merge($needCleanTags, $tags);
                }
            }
        }

        if ($needCleanTags) {
            foreach (array_unique($needCleanTags) as $contextCacheTag) {
                $this->_templateCompiler->cleanCache($contextCacheTag);
            }
        }
    }
}
