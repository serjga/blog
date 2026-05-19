<?php

namespace App\Resource;

interface ResourceInterface {
    public function query(): \App\Database\DatabaseDriverInterface;
}
