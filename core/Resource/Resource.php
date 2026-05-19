<?php

namespace App\Resource;

use App\Database\DatabaseProvider;

class Resource implements ResourceInterface
{
    protected \App\Database\DatabaseDriverInterface $_driver;
    protected \App\Database\QueryBuilderInterface $_query;

    function __construct() {
        $db = (new DatabaseProvider('mysql'))->getDatabase();
        $this->_driver = $db->getDatabaseDriver();
        $this->_query = $db->getQueryBuilder();
    }

    public function query(): \App\Database\DatabaseDriverInterface
    {
        return $this->_driver->query($this->_query);
    }

    public function page(int $page, int $limit): self
    {
        $offset = ($page - 1) * $limit;
        $this->_query->limit($limit);
        $this->_query->offset($offset);
        return $this;
    }
}
