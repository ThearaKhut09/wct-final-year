<?php
// Simple API test script
echo "Testing E-smooth Online Admin API\n";
echo "=====================================\n\n";

// Test database connection
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Boot the application
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Test database connection
    $pdo = new PDO('mysql:host=localhost;dbname=wct12', 'root', '');
    echo "✓ Database connection: SUCCESS\n";

    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@esmooth.com']);
    $admin = $stmt->fetch();

    if ($admin) {
        echo "✓ Admin user found: " . $admin['name'] . "\n";
        echo "  - Email: " . $admin['email'] . "\n";
        echo "  - Role: " . $admin['role'] . "\n";
        echo "  - Active: " . ($admin['is_active'] ? 'Yes' : 'No') . "\n";
    } else {
        echo "✗ Admin user not found\n";
    }

    // Check tables
    $tables = ['users', 'products', 'categories', 'orders', 'customers'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
        $result = $stmt->fetch();
        echo "✓ Table {$table}: {$result['count']} records\n";
    }

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Test API endpoint directly
echo "\nTesting API Login Endpoint:\n";
echo "==========================\n";

// Simulate API request
$data = json_encode(['email' => 'admin@esmooth.com', 'password' => 'admin123']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Response: {$response}\n";

if ($response) {
    $decoded = json_decode($response, true);
    if ($decoded && isset($decoded['success'])) {
        echo "API Status: " . ($decoded['success'] ? 'SUCCESS' : 'FAILED') . "\n";
        if (isset($decoded['message'])) {
            echo "Message: " . $decoded['message'] . "\n";
        }
    }
}
?>
