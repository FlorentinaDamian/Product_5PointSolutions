<?php
$config = [
    'host'   => 'localhost',
    'dbname' => 'products_db',
    'user'   => 'root',
    'pass'   => '',
];
if (is_file(__DIR__ . '/config.local.php')) {
    $config = array_merge($config, require __DIR__ . '/config.local.php');
}
return $config;
