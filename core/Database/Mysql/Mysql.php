<?php
namespace App\Database\Mysql;

use PDO;

class Mysql {
    protected string $_user;
    protected string $_pass;
    protected string $_dbName;
    protected string $_host;
    private static ?Mysql $_instance = null;
    private \PDO $_connection;

    function __construct() {
        $this->_user = $_ENV['DB_USER'];
        $this->_pass = $_ENV['DB_PASS'];
        $this->_dbName = $_ENV['DB_NAME'];
        $this->_host = $_ENV['DB_HOST'];

        $dsn = "mysql:host=$this->_host;dbname=$this->_dbName;charset=utf8";
        $this->_connection = new PDO($dsn, $this->_user, $this->_pass);
    }

    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getConnection(): PDO
    {
        return $this->_connection;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
