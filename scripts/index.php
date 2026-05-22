<?php

$command = $argv[1] ?? ''; // command name

require 'vendor/autoload.php';

use App\Command;

$app = new Command();
$app->run($command ?? 'build');
