<?php
namespace App\Response;

class ResponseFactory implements \App\Response\ResponseFactoryInterface
{
    public function create(): Response
    {
        return Response::getInstance();
    }
}
