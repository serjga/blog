<?php

namespace Blog\Category\Controllers;

use App\Template\View;

class CategoryController
{
    function __construct() {
    }

    public function index(): void
    {
        $view = new View();
        $view->render('Category/index.tpl');
    }

    public function category($categoryId) {}
}
