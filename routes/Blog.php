<?php
namespace Router;
use App\Router\Route;
use App\Router\RouterInterface;

class Blog implements RouterInterface {

    public function compilate(): void
    {
        $route = new Route();
        $route->get("/", [ "Blog\Home\Controllers\HomeController" ]);
        $route->get("/categories", [ "Blog\Category\Controllers\CategoryController", "index", [] ]);
        $route->get("/category", [ "Blog\Category\Controllers\CategoryController", "category", ['categoryId'] ]);
        $route->get("/article", [ "Blog\Article\Controllers\ArticleController", "article", ['articleId'] ]);
    }
}
