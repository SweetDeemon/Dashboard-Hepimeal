<?php

define('LARAVEL_START', microtime(true));

if (!file_exists(__DIR__.'/../.env')) {
    $env = "APP_KEY=".(getenv('APP_KEY') ?: 'base64:sHWjNa6Sqtj7xhGyLnTNDt4snELtZDFDTH9GcDFdMD8=')."\n"
        ."APP_ENV=".(getenv('APP_ENV') ?: 'production')."\n"
        ."APP_DEBUG=".(getenv('APP_DEBUG') ?: 'false')."\n"
        ."APP_URL=".(getenv('APP_URL') ?: 'https://dashboard-hepimeal.vercel.app')."\n"
        ."SESSION_DRIVER=cookie\n"
        ."CACHE_STORE=array\n"
        ."QUEUE_CONNECTION=sync\n"
        ."DB_CONNECTION=".(getenv('DB_CONNECTION') ?: 'sqlite')."\n"
        ."DB_DATABASE=/tmp/database.sqlite\n";
    file_put_contents(__DIR__.'/../.env', $env);
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Illuminate\Http\Request::capture());
