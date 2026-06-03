<?php

$command = $argv[1] ?? ''; // command name
$args = $argv[2] ?? ''; // arguments

require 'vendor/autoload.php';

use App\Command;

$app = new Command();
$app->run($command ?? 'build', $args);
