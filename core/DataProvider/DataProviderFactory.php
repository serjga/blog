<?php

namespace App\DataProvider;

use \App\DataProvider\DataProvider;

class DataProviderFactory implements DataProviderFactoryInterface
{
    public function create(array $data = []): \App\DataProvider\DataProvider
    {
        return new DataProvider($data);
    }
}
