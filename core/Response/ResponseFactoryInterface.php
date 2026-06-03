<?php

namespace App\Response;

interface ResponseFactoryInterface {
    public function create(): \App\Response\Response;
}
