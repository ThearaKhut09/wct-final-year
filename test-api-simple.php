<?php
// Simple test to check if API endpoints are working
echo "Testing API endpoints directly...\n\n";

// Test login endpoint
$loginData = json_encode([
    'email' => 'admin@esmooth.com',
    'password' => 'admin123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $loginData
    ]
]);

echo "Testing login API...\n";
$response = file_get_contents('http://localhost/wct-12/public/api/login', false, $context);

if ($response === false) {
    echo "❌ Failed to connect to API\n";
} else {
    echo "✅ Response received:\n";
    echo $response . "\n\n";
}

// Test products endpoint (should work without auth)
echo "Testing products API...\n";
$productsResponse = file_get_contents('http://localhost/wct-12/public/api/products');

if ($productsResponse === false) {
    echo "❌ Failed to get products\n";
} else {
    echo "✅ Products response received:\n";
    echo substr($productsResponse, 0, 200) . "...\n\n";
}
?>
