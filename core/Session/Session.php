<?php

namespace App\Session;

class Session
{
    public static function get(string $sessionKey = null)
    {
        if (is_null($sessionKey)) {
            return $_SESSION;
        } else {
            return $_SESSION[$sessionKey];
        }
    }

    public static function has(string $sessionKey): bool
    {
        return !(!isset($_SESSION[$sessionKey]));
    }

    public static function delete(string $sessionKey): void
    {
        unset($_SESSION[$sessionKey]);
    }

    public static function set(string $sessionKey, $sessionValue): void
    {
        $_SESSION[$sessionKey] = $sessionValue;
    }

    public static function setTemporary(string $sessionKey, $sessionValue): void
    {
        if (empty($sessionValue)) {
            return;
        }

        if (empty($_SESSION['TEMPORARY_SESSION'])) {
            $_SESSION['TEMPORARY_SESSION'] = [];
        }

        $_SESSION['TEMPORARY_SESSION'][$sessionKey] = $sessionValue;
    }

    public static function hasTemporary(string $sessionKey): bool
    {
        if (!isset($_SESSION['TEMPORARY_SESSION']) || empty($_SESSION['TEMPORARY_SESSION'][$sessionKey])) {
            return false;
        }
        return true;
    }

    public static function getTemporary(string $sessionKey)
    {
        $values = $_SESSION['TEMPORARY_SESSION'][$sessionKey] ?? null;
        self::forget($sessionKey);
        return $values;
    }

    public static function forget($sessionKey = null): void
    {
        if(!empty($sessionKey)) {
            unset($_SESSION['TEMPORARY_SESSION'][$sessionKey]);
        } else {
            unset($_SESSION['TEMPORARY_SESSION']);
        }
    }
}
