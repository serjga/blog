<?php

namespace Blog\Tag\Resource;

use App\Resource\AbstractResourceFactory;

class TagResourceFactory extends AbstractResourceFactory
{
    function __construct(\Blog\Tag\Resource\TagResource $resource) {
        parent::__construct($resource);
    }
}
