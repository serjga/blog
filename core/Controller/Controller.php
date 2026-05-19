<?php

namespace App\Controller;

use App\Request\Request;

class Controller implements ControllerInterface
{
    protected \App\Request\Request $_request;

    function __construct()
    {
        $this->_request = (new Request());
    }

    public function getRequest(): Request
    {
        return $this->_request;
    }

    public function index() {}
}
