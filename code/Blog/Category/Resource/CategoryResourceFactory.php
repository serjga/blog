<?php

namespace Blog\Category\Resource;

use App\Resource\AbstractResourceFactory;

class CategoryResourceFactory extends AbstractResourceFactory
{
    function __construct(\Blog\Category\Resource\CategoryResource $resource) {
        parent::__construct($resource);
    }
}
