<?php

namespace Blog\Category\DataProvider;

use App\DataProvider\DataProviderFactory;
use Blog\Article\DataProvider\CategoryTagsDataProvider;

class CategoryTagsDataProviderFactory extends DataProviderFactory
{
    public function create(array $data = []): CategoryTagsDataProvider
    {
        return CategoryTagsDataProvider::getInstance();
    }
}
