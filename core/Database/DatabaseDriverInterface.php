<?php

namespace App\Database;

interface DatabaseDriverInterface {
    public function all(bool $assoc = false): array;
    public function one(bool $assoc = false): array|object|null;
    public function columnValues(): array;
    public function count(): ?int;
    public function query(QueryBuilderInterface $queryBuilder): DatabaseDriverInterface;
}
