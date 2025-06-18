<?php
require_once 'vendor/autoload.php';

// Create connection using Laravel's database config
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::createFromGlobals();
$kernel->bootstrap();

echo "Database Connection Test - Plain Text Passwords (MySQL)\n";
echo "=====================================================\n\n";

try {
    // Use Laravel's DB facade
    $users = \Illuminate\Support\Facades\DB::table('users')->select('id', 'name', 'email', 'password', 'role')->orderBy('id')->get();
    
    echo "Users in database:\n";
    echo "------------------\n";
    
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Password: {$user->password}\n";
        echo "Role: {$user->role}\n";
        
        // Check if password looks like bcrypt hash
        $isHashed = strlen($user->password) === 60 && substr($user->password, 0, 4) === '$2y$';
        echo "Password Type: " . ($isHashed ? "Hashed (Bcrypt)" : "Plain Text") . "\n";
        echo "Length: " . strlen($user->password) . " characters\n";
        echo "\n";
    }
    
    echo "Summary:\n";
    echo "--------\n";
    $plainTextCount = 0;
    $hashedCount = 0;
    
    foreach ($users as $user) {
        $isHashed = strlen($user->password) === 60 && substr($user->password, 0, 4) === '$2y$';
        if ($isHashed) {
            $hashedCount++;
        } else {
            $plainTextCount++;
        }
    }
    
    echo "Total users: " . count($users) . "\n";
    echo "Plain text passwords: $plainTextCount\n";
    echo "Hashed passwords: $hashedCount\n";
    
    if ($hashedCount === 0) {
        echo "\n✅ SUCCESS: All passwords are stored as plain text!\n";
        echo "✅ Bcrypt algorithm has been successfully removed.\n";
    } else {
        echo "\n⚠️  WARNING: Some passwords are still hashed.\n";
        echo "❌ Bcrypt removal may not be complete.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
?>
