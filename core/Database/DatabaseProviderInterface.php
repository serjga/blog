<?php

namespace App\Database;

interface DatabaseProviderInterface {
    public function getDatabaseDriver(): DatabaseDriverInterface;
    public function getQueryBuilder(): QueryBuilderInterface;
}
