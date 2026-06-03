<?php

namespace App;

use App\Command\CommandInterface;
use App\Config\Config;

class Command
{
    function __construct() {
    }

    public function run($command, ?string $args = null): void
    {
        if (empty($command)) {
            echo "Command name is required. \n";
            die;
        }

        $scriptsConfig = (new Config('scripts'))->get();
        if (is_array($scriptsConfig)) {
            if (isset($scriptsConfig[$command])) {
                $scriptClassName = $scriptsConfig[$command];
                /** @var  \App\Command\CommandInterface $routerClass */
                $commandClass = new $scriptClassName();
                if ($commandClass instanceof CommandInterface) {
                    $commandClass->execute($args);
                } else {
                    // log error
                    echo "$scriptClassName must implement App\Command\CommandInterface. \n";
                }
            } else {
                echo "Command <$command> not found. \n";
            }
        } else {
            echo "Invalid commands config. \n";
        }
        die;
    }
}
