<?php

namespace App\Database;

interface QueryBuilderInterface {
    public function insert(string $table, array $values = []): self;
    public function update(string $table, array $sets, array $values = []): self;
    public function select(array $columns = []): self;
    public function addColumns(array $columns): self;
    public function from (string $table): self;
    public function rand(): self;
    public function sortBy(string $column, string $order): self;
    public function groupBy(array $columns): self;
    public function having(array $having): self;
    public function where(array $conditions, array $values = []): self;
    public function whereIn(string $column , array $conditions): self;
    public function whereNotIn(string $column , array $conditions): self;
    public function limit(int $limit): self;
    public function offset(int $offset): self;
    public function count(?string $column = '*'): self;
    public function join(string $table, string $tableColumn,  string $column): self;
    public function leftJoin(string $table, string $tableColumn, string $column): self;
}
