<?php

namespace Blog\Article\DataProvider;

use \App\DataProvider\DataProviderFactory;
use Blog\Article\DataProvider\ArticleTagsDataProvider;

class ArticleTagsDataProviderFactory extends DataProviderFactory
{
    public function create(array $data = []): ArticleTagsDataProvider
    {
        return ArticleTagsDataProvider::getInstance();
    }
}
