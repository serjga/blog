<?php
namespace Router;

use App\Router\Route;
use App\Router\RouterInterface;

class Blog implements RouterInterface
{
    public function compilate(): void
    {
        $route = new Route();
        $route->get("/", [ "Blog\Base\Controller\HomeController" ]);
        $route->get("/categories", [ "Blog\Category\Controller\CategoryController", "categories", [] ]);
        $route->get("/category", [ "Blog\Category\Controller\CategoryController", "category", ['id'] ]);
        $route->get("/article", [ "Blog\Article\Controller\ArticleController", "article", ['id'] ]);
        $route->post("/update-article-views", [ "Blog\Article\Controller\ArticleController", "updateViews"]);
        $route->pageNotFound([ "Blog\Base\Controller\NotFoundPageController"]);
    }
}
