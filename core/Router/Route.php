<?php
namespace App\Router;

use App\Request\Request;

class Route
{
    protected Request $_request;

    public function __construct()
    {
        $this->_request = new Request();
    }

    public function get(string $url, array $controllerMethod): void
    {
        if(
            $this->_request->method() !== 'GET' ||
            $url !== $this->_request->path() ||
            empty($controllerMethod)
        ) {
            return;
        }

        $this->_compilation($controllerMethod, $this->_request->get());
    }

    public function post(string $url, array $controllerMethod): void
    {
        if (
            $this->_request->method() !== 'POST' ||
            $url !== $this->_request->path() ||
            empty($controllerMethod)
        ) {
            return;
        }

        $this->_compilation($controllerMethod, $this->_request->post());
    }

    private function _compilation(array $controllerMethod, array $requestParameters): void
    {
        $className = $controllerMethod[0];
        $method = $controllerMethod[1] ?? 'index';
        $arguments = $controllerMethod[2] ?? [];

        $argumentValues = [];
        foreach($arguments as $argumentName) {
            $argumentValues[] = $requestParameters[$argumentName] ?? '';
        }

        $newClass = new $className();
        call_user_func_array([$newClass, $method], $argumentValues);

        die;
    }
}
