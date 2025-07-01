<?php
// Simple test to check Laravel installation
echo "<h1>Laravel E-smooth Online Test</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";
echo "<p>Laravel Bootstrap Test:</p>";

try {
    // Try to load Laravel
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    echo "<p style='color: green;'>✓ Laravel autoloader loaded successfully</p>";
    echo "<p style='color: green;'>✓ Application bootstrapped successfully</p>";

    // Test database connection
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    try {
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "<p style='color: green;'>✓ Database connection successful</p>";

        // Test if tables exist
        $tables = \Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table'");
        echo "<p style='color: green;'>✓ Found " . count($tables) . " database tables</p>";

        // Test getting products
        $productCount = \Illuminate\Support\Facades\DB::table('products')->count();
        echo "<p style='color: green;'>✓ Found {$productCount} products in database</p>";

    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Laravel failed to load: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Available Links:</h3>";
echo "<p><a href='/wct-12/public/'>Go to E-smooth Online Homepage</a></p>";
echo "<p><a href='/wct-12/public/admin'>Admin Dashboard (Laravel Blade)</a></p>";
echo "<p><a href='/wct-12/public/api/products'>Test API - View Products (JSON)</a></p>";
echo "<p><a href='/wct-12/public/connection-test.html'>Frontend Connection Test</a></p>";
echo "<hr>";
echo "<h4>Backup Admin Interfaces (archived):</h4>";
echo "<p><a href='/wct-12/public/admin-static-backup.html'>Static Admin Dashboard (Backup)</a></p>";
echo "<p><a href='/wct-12/public/demo-backup.html'>Demo Interface (Backup)</a></p>";
echo "<p><a href='/wct-12/public/admin-test-backup.html'>API Test Interface (Backup)</a></p>";
?>
