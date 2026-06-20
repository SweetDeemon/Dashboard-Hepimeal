<?php

define('LARAVEL_START', microtime(true));

$defaults = [
    'APP_KEY' => 'base64:sHWjNa6Sqtj7xhGyLnTNDt4snELtZDFDTH9GcDFdMD8=',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'APP_URL' => 'https://dashboard-hepimeal.vercel.app',
    'SESSION_DRIVER' => 'cookie',
    'CACHE_STORE' => 'array',
    'QUEUE_CONNECTION' => 'sync',
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => '/tmp/database.sqlite',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'VIEW_COMPILED_PATH' => '/tmp/views',
];

foreach ($defaults as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Illuminate\Http\Request::capture());
