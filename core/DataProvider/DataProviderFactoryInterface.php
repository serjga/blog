<?php

namespace App\DataProvider;

interface DataProviderFactoryInterface
{
    public function create(): \App\DataProvider\DataProviderInterface;
}
