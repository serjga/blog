<?php

namespace App\Controller;

class Controller implements ControllerInterface
{
    protected \App\Request\Request $_request;

    function __construct(\App\Request\RequestFactory $requestFactory)
    {
        $this->_request = $requestFactory->create();
        $this->saveHistory();
    }

    public function getRequest(): \App\Request\Request
    {
        return $this->_request;
    }

    public function index() {}

    public function saveHistory(): void
    {
        if (!($this instanceof \Blog\Base\Controller\NotFoundPageController)) {
            $this->_request->saveHistory();
        }
    }
}
