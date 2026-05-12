<?php

namespace Blog\Article\Controllers;

use App\Template\View;

class ArticleController
{
    function __construct() {

    }

    public function index() {}

    public function article($articleId): void
    {
        $view = new View();
        $view->render('Article/index.tpl');
    }
}
