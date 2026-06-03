<?php
namespace App\Database\Mysql;

use PDO;
use PDOStatement;
use App\Config\Config;
use App\Logger\Logger;
use App\Logger\MysqlLogger;
use App\Database\QueryBuilderInterface;
use App\Database\DatabaseDriverInterface;

class MysqlDriver implements DatabaseDriverInterface {
    use Logger;
    use MysqlLogger;

    private Mysql $_db;
    private ?PDOStatement $_stmt;
    private bool $_dbQueryLog;

    function __construct() {
        $this->_db = Mysql::getInstance();
        $this->_dbQueryLog = (bool) (new Config('app'))->get('save_db_logs');
    }

    public function query(QueryBuilderInterface $queryBuilder): self
    {
        $params = $queryBuilder->getQueryValues();
        $sql = $queryBuilder->toString();

        try {
            if ($this->_dbQueryLog) {
                $this->queryLog($sql, $params);
            }

            if (count($params) > 0) {
                $this->_stmt = $this->connection()?->prepare($sql);
                $this->_stmt->execute($params);
            } else {
                $this->_stmt =  $this->connection()?->query($sql);
            }
        } catch (\PDOException $e) {
            $message = "DATABASE ERROR: " . $e->getMessage();
            $trace = $e->getTraceAsString();
            $this->log($message, ['sql' => $sql, 'params' => $params, 'trace' => $trace]);
        }
        return $this;
    }

    public function connection(): ?PDO
    {
        try {
            return $this->_db->getConnection();
        } catch (\Throwable $e) {
            $message = "DATABASE CONNECTION ERROR: " . $e->getMessage();
            $trace = $e->getTraceAsString();
            $this->log($message, ['trace' => $trace]);
            return null;
        }
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
        $res = $this->_stmt?->fetch($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ);
        return $res ?: null;
    }
}
