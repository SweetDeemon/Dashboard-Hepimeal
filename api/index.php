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
    'LOG_CHANNEL' => 'stderr',
    'DB_DATABASE' => '/tmp/database.sqlite',
    'VIEW_COMPILED_PATH' => '/tmp/views',
];

foreach ($defaults as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
}

@mkdir('/tmp/views', 0777, true);

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Ponytail: directly register ViewServiceProvider in case services manifest doesn't work
$app->register(\Illuminate\View\ViewServiceProvider::class);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n\n";
    $prev = $e->getPrevious();
    while ($prev) {
        echo "\nPrevious: " . $prev->getMessage() . "\n";
        echo "File: " . $prev->getFile() . ":" . $prev->getLine() . "\n";
        echo $prev->getTraceAsString() . "\n";
        $prev = $prev->getPrevious();
    }
    exit(1);
}
