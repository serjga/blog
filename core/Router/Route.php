<?php
namespace App\Router;

use App\Controller\ControllerFactory;
use App\Request\Request;
use App\Request\RequestFactory;
use App\Token\Token;

class Route
{
    protected Request $_request;
    protected Token $_token;

    public function __construct() {
        $this->_request = (new RequestFactory())->create();
        $this->_token = new Token();
    }

    public function pageNotFound(array $controllerMethod): void
    {
        if(
            $this->_request->method() !== 'GET' ||
            empty($controllerMethod)
        ) {
            return;
        }

        $this->_compilation($controllerMethod, $this->_request->get());
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

        $headers = getallheaders();
        $token = $headers['X-Csrf-Token'] ?? $_POST['csrf_token'] ?? '';
        if (!$this->_token->verify($token)) {
            http_response_code(403);
            die("Error: CSRF token is missing.");
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


        $controller = ControllerFactory::create($className);
        call_user_func_array([$controller, $method], $argumentValues);
        die;
    }
}
