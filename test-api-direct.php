<?php

 

echo "========================================\n";

echo "Testing Hoteliermarket API Directly\n";

echo "========================================\n\n";

 

$apiKey = 'htk_NfMhypcZDiZrXtyyUL24vsi1eTcPDFVNVmSvprEvDqdbLy0F';

$propertyId = 14;

$apiUrl = "https://hoteliermarket.my.id/api/properties/{$propertyId}/room-pricing";

 

echo "Configuration:\n";

echo "API Key: " . substr($apiKey, 0, 20) . "...\n";

echo "Property ID: {$propertyId}\n";

echo "URL: {$apiUrl}\n\n";

 

echo "========================================\n";

echo "Test 1: Property ID 14 (Bell Hotel)\n";

echo "========================================\n";

 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [

    "X-API-Key: {$apiKey}",

    "Accept: application/json"

]);

 

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$error = curl_error($ch);

curl_close($ch);

 

echo "HTTP Status: {$httpCode}\n";

 

if ($error) {

    echo "❌ Curl Error: {$error}\n";

} elseif ($httpCode == 200) {

    echo "✅ SUCCESS! API connection working!\n\n";

    echo "Response:\n";

    $data = json_decode($response, true);

    echo json_encode($data, JSON_PRETTY_PRINT);

    echo "\n";

} elseif ($httpCode == 403) {

    echo "❌ FORBIDDEN (403)\n";

    echo "Response: {$response}\n\n";

    echo "This means: API key is NOT authorized for Property ID {$propertyId}\n\n";

} else {

    echo "❌ Error\n";

    echo "Response: {$response}\n\n";

}

 

echo "\n========================================\n";

echo "Test 2: Let's Try Property ID 13\n";

echo "========================================\n";

 

$propertyId13 = 13;

$apiUrl13 = "https://hoteliermarket.my.id/api/properties/{$propertyId13}/room-pricing";

 

echo "URL: {$apiUrl13}\n";

 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl13);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [

    "X-API-Key: {$apiKey}",

    "Accept: application/json"

]);

 

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$error = curl_error($ch);

curl_close($ch);

 

echo "HTTP Status: {$httpCode}\n";

 

if ($error) {

    echo "❌ Curl Error: {$error}\n";

} elseif ($httpCode == 200) {

    echo "✅ SUCCESS! This API key works with Property ID 13!\n";

    echo "BUT Property ID should be 14 for Bell Hotel.\n\n";

} elseif ($httpCode == 403) {

    echo "❌ FORBIDDEN (403)\n";

    echo "API key also not authorized for Property ID 13\n\n";

}

 

echo "\n========================================\n";

echo "DIAGNOSIS\n";

echo "========================================\n\n";

 

echo "Possible Issues:\n\n";

 

echo "1. API Key Not Authorized for Property 14\n";

echo "   - Contact Hoteliermarket support\n";

echo "   - Verify API key is linked to Property ID 14\n";

echo "   - Maybe need to re-create API key\n\n";

 

echo "2. Wrong Property ID\n";

echo "   - Check in Hoteliermarket dashboard\n";

echo "   - What is the actual Property ID for Bell Hotel?\n";

echo "   - Maybe it's not 14?\n\n";

 

echo "3. API Key Restrictions\n";

echo "   - Check allowed origins in Hoteliermarket dashboard\n";

echo "   - Check if IP address is blocked\n\n";

 

echo "4. API Key Copied Incorrectly\n";

echo "   - Re-copy API key from Hoteliermarket dashboard\n";

echo "   - Check for hidden spaces or characters\n\n";

 

echo "\n========================================\n";

echo "NEXT STEPS\n";

echo "========================================\n\n";

 

echo "1. Login to Hoteliermarket dashboard\n";

echo "2. Go to API Keys section\n";

echo "3. Find 'Bell Hotel' API key\n";

echo "4. Verify the Property ID associated with it\n";

echo "5. If Property ID ≠ 14, update .env file\n";

echo "6. If API key not working, regenerate new API key\n\n";

 

echo "========================================\n\n";