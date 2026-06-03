<?php

namespace Blog\Base\Resource;

use App\Resource\AbstractResourceFactory;
use App\Resource\ResourceFactoryInterface;

class ClientResourceFactory extends AbstractResourceFactory implements ResourceFactoryInterface
{
    function __construct(\Blog\Base\Resource\ClientResource $resource) {
        parent::__construct($resource);
    }
}
