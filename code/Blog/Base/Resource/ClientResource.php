<?php

namespace Blog\Base\Resource;

class ClientResource extends \App\Resource\Resource
{
    protected string $_table = 'client_fingerprint';

    public function hasHash(string $hash): bool
    {
        $this->_query->select()
            ->from($this->_table)
            ->where(['client_fingerprint.hash = :hash'], ['hash' => $hash])
            ->limit(1);
        return (bool) $this->query()->one();
    }

    public function createHash(string $hash): void
    {
        $this->_query->insert($this->_table, ['hash' => $hash]);
        $this->query();
    }
}
