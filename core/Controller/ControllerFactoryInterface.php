<?php

namespace App\Controller;

interface ControllerFactoryInterface {
    public static function create(string $className);
}
