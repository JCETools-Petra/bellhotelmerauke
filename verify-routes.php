<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Verifying API Sync Routes ===\n\n";

$routes = \Illuminate\Support\Facades\Route::getRoutes();
$apiSyncRoutes = [];

foreach ($routes as $route) {
    $uri = $route->uri();
    if (str_contains($uri, 'api-sync')) {
        $apiSyncRoutes[] = [
            'method' => implode('|', $route->methods()),
            'uri' => $uri,
            'name' => $route->getName(),
            'action' => $route->getActionName(),
        ];
    }
}

if (empty($apiSyncRoutes)) {
    echo "❌ No API Sync routes found!\n";
    echo "\nPossible issues:\n";
    echo "1. Routes need to be cached: run 'php artisan route:clear'\n";
    echo "2. Routes file not loaded properly\n";
    echo "3. Middleware blocking routes\n";
} else {
    echo "✅ Found " . count($apiSyncRoutes) . " API Sync routes:\n\n";

    foreach ($apiSyncRoutes as $route) {
        echo "Method: " . str_pad($route['method'], 15) . " ";
        echo "URI: " . str_pad($route['uri'], 35) . " ";
        echo "Name: " . ($route['name'] ?? 'N/A') . "\n";
    }
}

echo "\n=== Controller Check ===\n";
$controllerPath = __DIR__ . '/app/Http/Controllers/Admin/ApiSyncController.php';
if (file_exists($controllerPath)) {
    echo "✅ ApiSyncController exists\n";
} else {
    echo "❌ ApiSyncController NOT found at: $controllerPath\n";
}

echo "\n=== View Check ===\n";
$viewPath = __DIR__ . '/resources/views/admin/api-sync/index.blade.php';
if (file_exists($viewPath)) {
    echo "✅ View file exists\n";
} else {
    echo "❌ View file NOT found at: $viewPath\n";
}

echo "\n=== Access URL ===\n";
echo "Try: http://127.0.0.1:8000/admin/api-sync\n";
echo "(Make sure you're logged in as admin)\n\n";
