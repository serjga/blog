<?php

namespace Blog\Article\DataProvider;

use App\DataProvider\DataProviderFactory;
use Blog\Article\DataProvider\ArticleDataProvider;

class ArticleDataProviderFactory extends DataProviderFactory
{
    public function create(array $data = []): ArticleDataProvider
    {
        return ArticleDataProvider::getInstance();
    }
}
