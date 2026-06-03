<?php
namespace App\Request;

use App\Alert\AlertTrait;

class Request
{
    use AlertTrait;
    const string CURRENT_REQUEST_URI = 'CURRENT_REQUEST_URI';
    const string REQUEST_HISTORY = 'REQUEST_HISTORY';
    protected array $_urlPaths = [];
    protected array $_getParams = [];
    protected array $_postParams = [];
    protected string $_method;
    protected string $_path;
    protected array $_parseUrl;
    private static ?Request $_instance = null;

    function __construct() {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_parsePostRequest();
        $this->_parseUrlPath();
        $this->_parseGetRequest();

        $this->_parseUrl = parse_url($_SERVER['REQUEST_URI']);
        $this->_path = $this->_parseUrl['path'] ?? '';

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
                $this->_getParams[htmlspecialchars($item[0])] = urldecode(htmlspecialchars(trim($item[1])));
            }
        }
    }

    public function isGet(): bool
    {
        return $this->_method === 'GET';
    }

    public function isPost(): bool
    {
        return $this->_method === 'POST';
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
        if (!empty($_SESSION[self::REQUEST_HISTORY]) && count($_SESSION[self::REQUEST_HISTORY]) > 1) {
            array_pop($_SESSION[self::REQUEST_HISTORY]);
            if ($prevUrl = $_SESSION[self::REQUEST_HISTORY][array_key_last($_SESSION[self::REQUEST_HISTORY])]) {
                $this->redirect($prevUrl);
            }
        } else {
            $this->redirect(((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
        }

        exit;
    }

    public function reload(): void
    {
        $this->redirect($_SERVER['REQUEST_URI']);
    }

    public function saveHistory(): void
    {
        if ($this->_method === 'GET') {
            $currentRequestUri = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $_SESSION[self::CURRENT_REQUEST_URI] = $currentRequestUri;

            $_SESSION[self::REQUEST_HISTORY][] = $currentRequestUri;
            if (count($_SESSION[self::REQUEST_HISTORY]) > 3) {
                $_SESSION[self::REQUEST_HISTORY] = array_slice($_SESSION[self::REQUEST_HISTORY], -3);
            }
        }
    }

    public function getPreviousUrl(): ?string
    {
        return $_SESSION[self::REQUEST_HISTORY][array_key_last($_SESSION[self::REQUEST_HISTORY])];
    }

    public function getCurrentUrl(): ?string
    {
        return $_SESSION[self::CURRENT_REQUEST_URI];
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
