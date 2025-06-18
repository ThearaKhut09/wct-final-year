<?php
require_once 'vendor/autoload.php';

// Create a simple database connection test
try {
    $pdo = new PDO(
        'sqlite:' . __DIR__ . '/database/database.sqlite',
        null,
        null,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "Database Connection Test - Plain Text Passwords\n";
    echo "==============================================\n\n";
    
    // Check users table
    $stmt = $pdo->query("SELECT id, name, email, password, role FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Users in database:\n";
    echo "------------------\n";
    
    foreach ($users as $user) {
        echo "ID: {$user['id']}\n";
        echo "Name: {$user['name']}\n";
        echo "Email: {$user['email']}\n";
        echo "Password: {$user['password']}\n";
        echo "Role: {$user['role']}\n";
        
        // Check if password looks like bcrypt hash
        $isHashed = strlen($user['password']) === 60 && substr($user['password'], 0, 4) === '$2y$';
        echo "Password Type: " . ($isHashed ? "Hashed (Bcrypt)" : "Plain Text") . "\n";
        echo "Length: " . strlen($user['password']) . " characters\n";
        echo "\n";
    }
    
    echo "Summary:\n";
    echo "--------\n";
    $plainTextCount = 0;
    $hashedCount = 0;
    
    foreach ($users as $user) {
        $isHashed = strlen($user['password']) === 60 && substr($user['password'], 0, 4) === '$2y$';
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
