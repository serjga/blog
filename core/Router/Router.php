<?php

namespace App\Router;

use App\Config\Config;

class Router implements RouterInterface {
    public function compilate(): void
    {
        $routesConfig = (new Config('routes'))->get();
        if (is_array($routesConfig)) {
            foreach ($routesConfig as $routerClass) {
                /** @var  \App\Router\RouterInterface $routerClass */
                $newClass = new $routerClass();
                $newClass->compilate();
            }
        }
    }
}
