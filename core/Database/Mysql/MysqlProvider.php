<?php
namespace App\Database\Mysql;

use App\Database\DatabaseProviderInterface;
use App\Database\QueryBuilderInterface;
use App\Database\DatabaseDriverInterface;

class MysqlProvider implements DatabaseProviderInterface {
    public function getDatabaseDriver(): DatabaseDriverInterface
    {
        return new MysqlDriver();
    }

    public function getQueryBuilder(): QueryBuilderInterface
    {
        return new QueryBuilder();
    }
}
