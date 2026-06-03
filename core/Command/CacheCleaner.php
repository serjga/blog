<?php
namespace App\Command;

use App\View\Cache\CacheCleaner as ViewCacheCleaner;

class CacheCleaner implements CommandInterface
{
    protected ViewCacheCleaner $_cacheCleaner;

    function __construct ()
    {
        $this->_cacheCleaner = new ViewCacheCleaner();
    }

    public function execute(?string $args = null): int
    {
        if ($args) {
            $args = explode(',', $args);
            $this->_cacheCleaner->clean($args);
        } else {
            $this->_cacheCleaner->cleanAll();
        }

        return 1;
    }
}
