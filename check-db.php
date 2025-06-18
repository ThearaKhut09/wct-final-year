<?php
// Simple database check script
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Check database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Check if users table exists and has data
    $users = DB::table('users')->count();
    echo "✅ Users table exists with {$users} records\n";
    
    // Check if products table exists and has data
    $products = DB::table('products')->count();
    echo "✅ Products table exists with {$products} records\n";
    
    // Check if categories table exists and has data
    $categories = DB::table('categories')->count();
    echo "✅ Categories table exists with {$categories} records\n";
    
    // Check admin user
    $admin = DB::table('users')->where('email', 'admin@esmooth.com')->first();
    if ($admin) {
        echo "✅ Admin user exists: {$admin->name} ({$admin->email})\n";
    } else {
        echo "❌ Admin user not found\n";
    }
    
    // Check sample customer
    $customer = DB::table('users')->where('email', 'customer@example.com')->first();
    if ($customer) {
        echo "✅ Customer user exists: {$customer->name} ({$customer->email})\n";
    } else {
        echo "❌ Customer user not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
