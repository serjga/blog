<?php
namespace App\Database;

use App\Config\Config;

class DatabaseProvider
{
    protected string $_db;

    function __construct(string $db) {
        $this->_db = $db;
    }

    public function getDatabase(): DatabaseProviderInterface
    {
        $dbClassName = (new Config('db'))->get($this->_db);
        $class = new $dbClassName();
        if (!($class instanceof DatabaseProviderInterface)) {
            throw new \Exception("The class must implement the DatabaseProviderInterface.");
        }
        return $class;
    }
}
