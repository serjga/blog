<?php

namespace App\Database;

interface DatabaseDriverInterface {
    public function all(bool $assoc = false): array;
    public function one(bool $assoc = false): object|array|null;
    public function columnValues(): array;
    public function count(): ?int;
    public function query(QueryBuilderInterface $queryBuilder): DatabaseDriverInterface;
}
