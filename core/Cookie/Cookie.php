<?php

namespace App\Cookie;

class Cookie
{
    public static function set(
        string $name,
        string $value,
        int $expire = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httponly = false
    ): bool {
        $_COOKIE[$name] = $value;
        $res = setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return $res;
    }

    public static function get(string $name): ?string
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function delete(string $name, int $expire = 0, string $path = "", string $domain = ""): bool
    {
        unset($_COOKIE[$name]);
        return setcookie($name, "", $expire, $path, $domain);
    }

    public static function has(string $name, string $value = ''): bool
    {
        if (isset($_COOKIE[$name])) {

            if (!empty($value)) {
                return $_COOKIE[$name] == $value;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
