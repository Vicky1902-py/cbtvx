<?php

// Tampilkan error jika ada issue environment di hosting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto prepare .env jika belum dibuat
if (!file_exists(__DIR__.'/../.env') && file_exists(__DIR__.'/../.env.example')) {
    @copy(__DIR__.'/../.env.example', __DIR__.'/../.env');
}

// Pastikan direktori storage & cache tersedia
$dirs = [
    __DIR__.'/../storage/framework/views',
    __DIR__.'/../storage/framework/cache/data',
    __DIR__.'/../storage/framework/sessions',
    __DIR__.'/../storage/logs',
    __DIR__.'/../bootstrap/cache'
];
foreach ($dirs as $d) {
    if (!is_dir($d)) {
        @mkdir($d, 0777, true);
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
