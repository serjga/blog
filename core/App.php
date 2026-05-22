<?php

namespace App;

use App\Cookie\Cookie;
use App\Session\Session;
use DateTimeZone;
use App\Router\Router;
use Redis;

class App {
    function __construct() {
        date_default_timezone_set(DateTimeZone::listIdentifiers(DateTimeZone::UTC)[0]);
        $this->_init();
    }

    public function run(): void
    {
        (new Router())->compilate();
    }

    private function _init(): void
    {
        session_name('PHPREDIS_SESSION');
        session_start();
        $redis = new Redis();
        $redis->connect('sessions', 6379);

        $this->_updateViewsCookie();

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    private function _updateViewsCookie(): void
    {
        $tmpSessionKey = 'need_update_article_views_cookie';
        if (Session::hasTemporary($tmpSessionKey)) {
            Cookie::set(Session::getTemporary($tmpSessionKey), 1);
        }
    }
}
