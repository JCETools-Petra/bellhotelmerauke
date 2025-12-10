<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Current Hoteliermarket Configuration (Loaded in Memory) ===\n\n";

$apiKey = config('services.hoteliermarket.api_key');
$apiUrl = config('services.hoteliermarket.api_url');
$propertyId = config('services.hoteliermarket.property_id');
$timeout = config('services.hoteliermarket.timeout');

echo "API Key:     " . ($apiKey ? substr($apiKey, 0, 20) . '...' : 'NOT SET') . "\n";
echo "API URL:     " . ($apiUrl ?? 'NOT SET') . "\n";
echo "Property ID: " . ($propertyId ?? 'NOT SET') . "\n";
echo "Timeout:     " . ($timeout ?? 'NOT SET') . " seconds\n";

echo "\n=== Expected Configuration (From Your Setup) ===\n\n";
echo "API Key:     htk_NfMhypcZDiZrX... (Bell Hotel)\n";
echo "API URL:     https://hoteliermarket.my.id\n";
echo "Property ID: 14 ✅ (MUST BE 14 for Bell Hotel)\n";
echo "Timeout:     30 seconds\n";

echo "\n=== Comparison ===\n\n";

$errors = [];

if (!$apiKey) {
    $errors[] = "❌ API Key is NOT SET in config";
} elseif (!str_starts_with($apiKey, 'htk_NfMhypcZDiZrX')) {
    $errors[] = "⚠️  API Key doesn't match expected Bell Hotel key";
}

if ($propertyId != 14) {
    $errors[] = "❌ Property ID is {$propertyId}, but SHOULD BE 14 for Bell Hotel";
}

if ($apiUrl != 'https://hoteliermarket.my.id') {
    $errors[] = "⚠️  API URL is {$apiUrl}";
}

if (empty($errors)) {
    echo "✅ All configuration is CORRECT!\n";
} else {
    echo "⚠️  Configuration Issues Found:\n\n";
    foreach ($errors as $error) {
        echo "   {$error}\n";
    }

    echo "\n=== How to Fix ===\n\n";
    echo "1. Check your .env file:\n";
    echo "   HOTELIERMARKET_API_KEY=htk_NfMhypcZDiZrXtyyUL24vsi1eTcPDFVNVmSvprEvDqdbLy0F\n";
    echo "   HOTELIERMARKET_PROPERTY_ID=14\n\n";

    echo "2. Clear config cache:\n";
    echo "   php artisan config:clear\n\n";

    echo "3. Rebuild config cache:\n";
    echo "   php artisan config:cache\n\n";

    echo "4. Run this script again to verify\n\n";
}

echo "\n=== Direct .env File Check ===\n\n";

$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);

    // Extract values from .env
    preg_match('/HOTELIERMARKET_API_KEY=(.*)/', $envContent, $envApiKey);
    preg_match('/HOTELIERMARKET_PROPERTY_ID=(.*)/', $envContent, $envPropertyId);

    echo ".env file contents:\n";
    echo "HOTELIERMARKET_API_KEY=" . ($envApiKey[1] ?? 'NOT FOUND') . "\n";
    echo "HOTELIERMARKET_PROPERTY_ID=" . ($envPropertyId[1] ?? 'NOT FOUND') . "\n";

    if (isset($envPropertyId[1]) && trim($envPropertyId[1]) != '14') {
        echo "\n❌ PROBLEM FOUND: .env has Property ID = " . trim($envPropertyId[1]) . ", but it MUST be 14\n";
        echo "   Please edit .env and change HOTELIERMARKET_PROPERTY_ID=14\n";
    }
} else {
    echo "❌ .env file not found!\n";
}

echo "\n";
