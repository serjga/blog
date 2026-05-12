<?php

namespace Blog\Home\Controllers;

use App\Template\View;

class HomeController
{
    function __construct() {

    }

    public function index(): void
    {
        $view = new View();
        $view->render('Home/index.tpl');
    }
}
