<?php

namespace App\Request;

interface RequestFactoryInterface {
    public function create(): \App\Request\Request;
}
