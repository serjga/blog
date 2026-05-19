<?php
namespace App\Database\Mysql;

use App\Database\QueryBuilderInterface;

class QueryBuilder implements QueryBuilderInterface {

    const string SELECT_QUERY = "SELECT";
    const string COUNT_QUERY = "COUNT";
    const string UPDATE_QUERY = "UPDATE";
    const string SELECT = "SELECT";
    const string UPDATE = "UPDATE";
    const string SET = "SET";
    const string COUNT = "COUNT";
    const string JOIN = "JOIN";
    const string FROM = "FROM";
    const string WHERE = "WHERE";
    const string GROUP_BY = "GROUP";
    const string HAVING = "HAVING";
    const string ORDER_BY = "ORDER";
    const string LIMIT = "LIMIT";
    const string OFFSET = "OFFSET";

    private ?string $_queryType = null;
    private array $_queryData = [];
    private array $_queryValues = [];
    private array $_selectQueryStructure = [
        self::SELECT,
        self::FROM,
        self::JOIN,
        self::WHERE,
        self::GROUP_BY,
        self::HAVING,
        self::ORDER_BY,
        self::LIMIT,
        self::OFFSET
    ];

    private array $_selectCountQueryStructure = [
        self::SELECT,
        self::FROM,
        self::JOIN,
        self::WHERE,
        self::GROUP_BY,
        self::HAVING
    ];

    private array $_updateQueryStructure = [
        self::UPDATE,
        self::SET,
        self::WHERE
    ];

    public function update(string $table, array $sets, array $values = []): self
    {
        if (is_null($this->_queryType)) {
            $this->_queryType = self::UPDATE_QUERY;
            $this->_queryData[self::UPDATE] = $table;
            foreach ($sets as $set) {
                $this->_queryData[self::SET][] = $set;
            }
            foreach ($values as $key => $value) {
                $this->_queryValues[$key] = $value;
            }
        }
        return $this;
    }

    public function select(array $columns = []): self
    {
        if (is_null($this->_queryType)) {
            $this->_queryType = self::SELECT_QUERY;
            foreach ($columns as $column) {
                $this->_queryData[self::SELECT][] = $column;
            }
        }
        return $this;
    }

    public function addColumns(array $columns): self
    {
        foreach ($columns as $column) {
            $this->_queryData[self::SELECT][] = $column;
        }
        return $this;
    }

    public function from (string $table): self
    {
        $this->_queryData[self::FROM] = $table;
        return $this;
    }

    public function rand(): self
    {
        $this->_queryData[self::ORDER_BY][] = 'RAND()';
        return $this;
    }

    public function sortBy(string $column, string $order): self
    {
        $order = $order === 'ASC' ? 'ASC' : 'DESC';
        $this->_queryData[self::ORDER_BY][] = "$column $order";
        return $this;
    }

    public function groupBy(array $columns): self
    {
        foreach ($columns as $column) {
            $this->_queryData[self::GROUP_BY][] = $column;
        }
        return $this;
    }

    public function having(array $having): self
    {
        foreach ($having as $condition) {
            $this->_queryData[self::HAVING][] = $condition;
        }
        return $this;
    }

    public function where(array $conditions, array $values = []): self
    {
        foreach ($conditions as $condition) {
            $this->_queryData[self::WHERE][] = $condition;
        }
        foreach ($values as $key => $value) {
            $this->_queryValues[$key] = $value;
        }
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->_queryData[self::LIMIT] = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->_queryData[self::OFFSET] = $offset;
        return $this;
    }

    public function count(?string $column = '*'): self
    {
        if ($this->_queryType === self::SELECT_QUERY) {
            $this->_queryType = self::COUNT_QUERY;
            $this->_queryData[self::SELECT][] = "COUNT($column)";
        }
        return $this;
    }

    public function join(string $table, string $tableColumn, string $column): self
    {
        $this->_queryData[self::JOIN][] = "JOIN $table ON $column = $tableColumn";
        return $this;
    }

    public function leftJoin(string $table, string $tableColumn, string $column): self
    {
        $this->_queryData[self::JOIN][] = "LEFT JOIN $table ON $column = $tableColumn";
        return $this;
    }

    public function getQueryValues(): array
    {
        return $this->_queryValues;
    }

    public function toString(): string
    {
        $sql = $this->getSql();
        $this->_queryType = null;
        $this->_queryData = [];
        $this->_queryValues = [];
        return $sql;
    }

    public function getSql(): string
    {
        return match ($this->_queryType) {
            self::COUNT_QUERY => $this->_compileCountSql(),
            self::SELECT_QUERY => $this->_compileSelectSql(),
            self::UPDATE_QUERY => $this->_compileUpdateSql(),
            default => '',
        };
    }

    private function _compileSelectSql(): string
    {
        if (!$this->_queryData) {
            return '';
        }

        if (empty($this->_queryData[self::SELECT])) {
            $this->_queryData[self::SELECT][] = '*';
        }

        $queryData = [];
        foreach (array_replace(array_intersect_key(array_flip($this->_selectQueryStructure), $this->_queryData), $this->_queryData) as $key => $value) {
            $queryData[] = $this->_getSqlString($key);
        }

        return implode(' ', $queryData);
    }

    private function _compileCountSql(): string
    {
        if (!$this->_queryData) {
            return '';
        }

        $queryData = [];
        foreach (array_replace(array_intersect_key(array_flip($this->_selectCountQueryStructure), $this->_queryData), $this->_queryData) as $key => $value) {
            $queryData[] = $this->_getSqlString($key);
        }

        return implode(' ', $queryData);
    }

    private function _compileUpdateSql(): string
    {
        if (!$this->_queryData) {
            return '';
        }

        $queryData = [];
        foreach (array_replace(array_intersect_key(array_flip($this->_updateQueryStructure), $this->_queryData), $this->_queryData) as $key => $value) {
            $queryData[] = $this->_getSqlString($key);
        }

        return implode(' ', $queryData);
    }

    private function _getSqlString ($key): string
    {
        return match ($key) {
            self::UPDATE => "UPDATE  " . $this->_queryData[$key],
            self::SET => "SET " . implode(', ', array_unique($this->_queryData[$key])),
            self::SELECT => "SELECT " . implode(', ', array_unique($this->_queryData[$key])),
            self::FROM => "FROM " . $this->_queryData[$key],
            self::JOIN => implode(' ', array_unique($this->_queryData[$key])),
            self::WHERE => "WHERE " . implode(' AND ', array_unique($this->_queryData[$key])),
            self::GROUP_BY => "GROUP BY " . implode(', ', array_unique($this->_queryData[$key])),
            self::ORDER_BY => "ORDER BY " . implode(', ', array_unique($this->_queryData[$key])),
            self::LIMIT => "LIMIT " . $this->_queryData[$key],
            self::OFFSET => "OFFSET " . $this->_queryData[$key],
            self::HAVING => "HAVING " . implode(', ', array_unique($this->_queryData[$key])),
            default => '',
        };
    }
}
