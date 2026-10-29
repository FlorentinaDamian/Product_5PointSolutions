<?php
require_once __DIR__ . '/CreateTable.php';
function getConnection(): PDO
{
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }
    $cfg = require __DIR__ . '/config.php';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $db = str_replace('`', '', $cfg['dbname']);
    $server = new PDO("mysql:host={$cfg['host']};charset=utf8mb4", $cfg['user'], $cfg['pass'], $options);
    $server->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn = new PDO("mysql:host={$cfg['host']};dbname=$db;charset=utf8mb4", $cfg['user'], $cfg['pass'], $options);
    createProductsTable($conn);
    return $conn;
}
