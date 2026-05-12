<?php
namespace App\Request;

class Request
{
    protected array $_urlPaths = [];
    protected array $_getParams = [];
    protected array $_postParams = [];
    protected string $_method;
    protected string $_path;
    protected array $_parseUrl;

    function __construct() {
        $this->_parseUrlPath();

        $this->_parseGetRequest();

        $this->_parsePostRequest();

        $this->_parseUrl = parse_url($_SERVER['REQUEST_URI']);

        $this->_path = $this->_parseUrl['path'] ?? '';

        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    protected function _parseUrlPath(): void
    {
        $url = $_SERVER['REQUEST_URI'];
        $path = parse_url($url, PHP_URL_PATH);
        $pathParts = explode(",", $path);
        $this->_urlPaths = array_map(fn($param) => htmlspecialchars($param), $pathParts);
    }

    protected function _parseGetRequest(): void
    {
        $url = $_SERVER['REQUEST_URI'];

        $query = parse_url($url, PHP_URL_QUERY);

        $this->_getParams = [];
        if (!empty($query)) {
            $queryParts = explode("&", $query);

            $queryParams = array_map(function($p) {
                $paramData = explode('=', $p);
                return [ htmlspecialchars($paramData[0]) => htmlspecialchars($paramData[1]) ];
            }, $queryParts);

            print_r($queryParams);
            $this->_getParams = $queryParams;
        }
    }

    public function get(): array
    {
        return $this->_getParams;
    }

    protected function _parsePostRequest(): void
    {
        $this->_postParams = array_map(fn($param) => htmlspecialchars(trim($param)), $_POST);
    }

    public function post(): array
    {
        return $this->_postParams;
    }

    public function method(): string
    {
        return $this->_method;
    }

    public function path(): string
    {
        return $this->_path;
    }

    public function createUrl($url): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url";
    }
}
