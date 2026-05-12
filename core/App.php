<?php

namespace App;

use DateTimeZone;
use App\Router\Router;

class App {
    function __construct() {
        date_default_timezone_set(DateTimeZone::listIdentifiers(DateTimeZone::UTC)[0]);
    }

    public function run(): void
    {
        (new Router())->compilate();
    }
}
