<?php

namespace Blog\Tag\Resource\ResourceFactory;

use App\Resource\AbstractResourceFactory;
use App\Resource\ResourceFactoryInterface;

class TagResourceFactory extends AbstractResourceFactory implements ResourceFactoryInterface
{
    function __construct(\Blog\Tag\Resource\TagResource $resource) {
        parent::__construct($resource);
    }
}
