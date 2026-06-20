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

error_log('=== DIAGNOSTICS START ===');
error_log('services.php exists: ' . (file_exists(__DIR__.'/../bootstrap/cache/services.php') ? 'yes' : 'no'));
error_log('packages.php exists: ' . (file_exists(__DIR__.'/../bootstrap/cache/packages.php') ? 'yes' : 'no'));
error_log('config dir: ' . __DIR__ . '/../config');
$configFiles = glob(__DIR__ . '/../config/*.php');
error_log('config files: ' . (is_array($configFiles) ? implode(', ', $configFiles) : 'none'));
error_log('bootstrapPath: ' . realpath(__DIR__ . '/../bootstrap'));
error_log('=== DIAGNOSTICS END ===');

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

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
    echo "Previous: " . ($e->getPrevious() ? $e->getPrevious()->getMessage() : 'none') . "\n";
    echo "\n\nPrevious trace:\n" . ($e->getPrevious() ? $e->getPrevious()->getTraceAsString() : 'none') . "\n";
    exit(1);
}
