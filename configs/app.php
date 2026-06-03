<?php
return [
    'db' => 'mysql',
    'save_db_logs' => true,
    'smarty_cache_directory' => __DIR__ . '/../static/smarty',
    'smarty_configs_directory' => __DIR__ . '/smarty',
    'cache' => 24 * 60 * 60, // <= 1 day (<expiration_in_sec> | <true> use default | <null> cache_off)
];
