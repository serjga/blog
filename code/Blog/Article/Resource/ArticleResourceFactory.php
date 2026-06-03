<?php

namespace Blog\Article\Resource;

use App\Resource\AbstractResourceFactory;
use App\Resource\ResourceFactoryInterface;

class ArticleResourceFactory extends AbstractResourceFactory implements ResourceFactoryInterface
{
    function __construct(\Blog\Article\Resource\ArticleResource $resource) {
        parent::__construct($resource);
    }
}
