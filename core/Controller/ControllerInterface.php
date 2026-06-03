<?php

namespace App\Controller;

interface ControllerInterface {
    public function index();
    public function saveHistory(): void;
}
