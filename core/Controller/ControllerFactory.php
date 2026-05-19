<?php

namespace App\Controller;

class ControllerFactory implements ControllerFactoryInterface
{
    public static function create(string $className)
    {
        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $className();
        }

        $resolvedArgs = [];
        $params = $constructor->getParameters();

        foreach ($params as $param) {
            $type = $param->getType();

            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();
                $instance = self::create($className);
                $resolvedArgs[] = $instance;
            }
        }

        return $reflection->newInstanceArgs($resolvedArgs);
    }
}
