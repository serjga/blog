<?php
namespace Router;
use App\Router\Route;
use App\Router\RouterInterface;

class Blog implements RouterInterface
{
    public function compilate(): void
    {
        $route = new Route();
        $route->get("/", [ "Blog\Category\Controllers\HomeController" ]);
        $route->get("/categories", [ "Blog\Category\Controllers\CategoryController", "categories", [] ]);
        $route->get("/article", [ "Blog\Article\Controllers\ArticleController", "article", ['id'] ]);
    }
}
