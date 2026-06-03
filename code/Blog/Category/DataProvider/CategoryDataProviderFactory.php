<?php

namespace Blog\Category\DataProvider;

use \App\DataProvider\DataProviderFactory;

class CategoryDataProviderFactory extends DataProviderFactory
{
    public function create(array $data = []): CategoryDataProvider
    {
        return CategoryDataProvider::getInstance();
    }
}
