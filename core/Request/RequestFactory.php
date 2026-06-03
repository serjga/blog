<?php
namespace App\Request;

class RequestFactory implements \App\Request\RequestFactoryInterface
{
    public function create(): Request
    {
        return Request::getInstance();
    }
}
