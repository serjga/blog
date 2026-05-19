<?php

namespace App\Resource;

interface ResourceFactoryInterface {
    public function create(): \App\Resource\ResourceInterface;
}
