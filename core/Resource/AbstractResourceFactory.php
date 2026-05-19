<?php

namespace App\Resource;

use App\Database\DatabaseProviderInterface;

class AbstractResourceFactory implements ResourceFactoryInterface
{
    protected \App\Resource\ResourceInterface $_resource;

    function __construct(\App\Resource\ResourceInterface $resource) {
        $this->_resource = $resource;
    }

    public function create(): \App\Resource\ResourceInterface
    {
        return new $this->_resource;
    }
}
