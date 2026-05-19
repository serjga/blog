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
    protected ?string $_previousUrl;

    private static ?Request $_instance = null;

    function __construct() {
        $this->_parseUrlPath();

        $this->_parseGetRequest();

        $this->_parsePostRequest();

        $this->_parseUrl = parse_url($_SERVER['REQUEST_URI']);

        $this->_path = $this->_parseUrl['path'] ?? '';

        $this->_method = $_SERVER['REQUEST_METHOD'];

        if ($this->_method === 'GET') {
            $this->_previousUrl = $_SERVER['HTTP_REFERER'] ?? null;
        }
    }

    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
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

            foreach ($queryParts as $param) {
                $item = explode('=', $param);
                $this->_getParams[htmlspecialchars($item[0])] = htmlspecialchars($item[1]);
            }
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

    public function redirect(string $url): void
    {
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '">';
        exit;
    }

    public function back(): void
    {
        if ($this->_previousUrl) {
            $this->redirect($this->_previousUrl);
        }
    }

    public function reload(): void
    {
        $this->redirect($_SERVER['REQUEST_URI']);
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
