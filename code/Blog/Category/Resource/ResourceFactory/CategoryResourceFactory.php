<?php

namespace Blog\Category\Resource\ResourceFactory;

use App\Resource\AbstractResourceFactory;
use App\Resource\ResourceFactoryInterface;

class CategoryResourceFactory extends AbstractResourceFactory implements ResourceFactoryInterface
{
    function __construct(\Blog\Category\Resource\CategoryResource $resource) {
        parent::__construct($resource);
    }
}
