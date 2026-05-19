<?php
namespace App\Database\Mysql;

use App\Database\DatabaseDriverInterface;
use App\Database\QueryBuilderInterface;
use PDO;
use PDOStatement;

class MysqlDriver implements DatabaseDriverInterface {

    private Mysql $_db;
    private ?PDOStatement $_stmt;

    function __construct() {
        $this->_db = Mysql::getInstance();
    }

    public function query(QueryBuilderInterface $queryBuilder): self
    {
        $params = $queryBuilder->getQueryValues();
        $sql = $queryBuilder->toString();

        try {
            if (count($params) > 0) {
                $this->_stmt = $this->connection()->prepare($sql);
                $this->_stmt->execute($params);
            } else {
                $this->_stmt =  $this->connection()->query($sql);
            }
        } catch (\PDOException $e) {
            $this->_errorLog($sql, $params, $e);
        }
        return $this;
    }

    public function connection(): PDO
    {
        return $this->_db->getConnection();
    }

    public function all($assoc = false): array
    {
        return $this->_stmt?->fetchAll($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ) ?? [];
    }

    public function columnValues(): array
    {
        return $this->_stmt?->fetchAll(PDO::FETCH_COLUMN) ?? [];
    }

    public function count(): ?int
    {
        return $this->_stmt?->fetchColumn() ?? null;
    }

    public function one($assoc = false): object|array|null
    {
        return $this->_stmt?->fetch($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ) ?? null;
    }

    public function log($queryString, $params): void
    {
        echo "<pre>";
        print_r('
                ========================================================
                ********************** MYSQL QUERY *********************
                ========================================================
            ');
        echo "</pre>";
        print_r($queryString);
        echo "<pre>";
        print_r('
                ........................ SQL PARAMS ....................
            ');
        print_r($params);
        echo "</pre>";
    }

    protected function _errorLog($queryString, $params, \PDOException $e): void
    {
        echo "<pre>";
        print_r('  
                ========================================================
                --------------------- ERROR MESSAGE --------------------
                ========================================================
                ');
        echo "</pre>";
        print_r($e->getMessage());
        echo "<pre>";

        print_r('
                --------------------- ERROR TRACE ----------------------
                ');
        print_r($e->getTrace());
        echo "</pre>";
    }
}
