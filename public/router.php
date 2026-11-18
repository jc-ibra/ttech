<?php
// Built-in PHP server front controller for CI4
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}
require_once __DIR__ . '/index.php';
