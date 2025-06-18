<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Create a request to boot the application
$request = \Illuminate\Http\Request::createFromGlobals();
$kernel->bootstrap();

// Test plain text password functionality
echo "Testing Plain Text Password Implementation\n";
echo "=========================================\n\n";

// Test 1: Check if admin user exists with plain text password
try {
    $adminUser = \App\Models\User::where('email', 'admin@esmooth.com')->first();
    if ($adminUser) {
        echo "✅ Admin user found: {$adminUser->email}\n";
        echo "   Password stored as: {$adminUser->password}\n";
        echo "   Password type: " . (strlen($adminUser->password) === 60 ? "Hashed (Bcrypt)" : "Plain Text") . "\n\n";
    } else {
        echo "❌ Admin user not found\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking admin user: " . $e->getMessage() . "\n\n";
}

// Test 2: Check if customer user exists with plain text password
try {
    $customerUser = \App\Models\User::where('email', 'customer@esmooth.com')->first();
    if ($customerUser) {
        echo "✅ Customer user found: {$customerUser->email}\n";
        echo "   Password stored as: {$customerUser->password}\n";
        echo "   Password type: " . (strlen($customerUser->password) === 60 ? "Hashed (Bcrypt)" : "Plain Text") . "\n\n";
    } else {
        echo "❌ Customer user not found\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking customer user: " . $e->getMessage() . "\n\n";
}

// Test 3: Test login API with plain text password
echo "Testing Login API with Plain Text Passwords\n";
echo "-------------------------------------------\n";

// Test admin login
try {
    $loginData = [
        'email' => 'admin@esmooth.com',
        'password' => 'admin123'
    ];
    
    $request = new \Illuminate\Http\Request();
    $request->merge($loginData);
    
    $authController = new \App\Http\Controllers\Api\AuthController();
    $response = $authController->login($request);
    $responseData = json_decode($response->getContent(), true);
    
    if ($responseData['success']) {
        echo "✅ Admin login successful!\n";
        echo "   User: {$responseData['data']['user']['name']}\n";
        echo "   Role: {$responseData['data']['user']['role']}\n\n";
    } else {
        echo "❌ Admin login failed: {$responseData['message']}\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing admin login: " . $e->getMessage() . "\n\n";
}

// Test customer login
try {
    $loginData = [
        'email' => 'customer@esmooth.com',
        'password' => 'customer123'
    ];
    
    $request = new \Illuminate\Http\Request();
    $request->merge($loginData);
    
    $authController = new \App\Http\Controllers\Api\AuthController();
    $response = $authController->login($request);
    $responseData = json_decode($response->getContent(), true);
    
    if ($responseData['success']) {
        echo "✅ Customer login successful!\n";
        echo "   User: {$responseData['data']['user']['name']}\n";
        echo "   Role: {$responseData['data']['user']['role']}\n\n";
    } else {
        echo "❌ Customer login failed: {$responseData['message']}\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing customer login: " . $e->getMessage() . "\n\n";
}

// Test 4: Test wrong password
try {
    $loginData = [
        'email' => 'admin@esmooth.com',
        'password' => 'wrongpassword'
    ];
    
    $request = new \Illuminate\Http\Request();
    $request->merge($loginData);
    
    $authController = new \App\Http\Controllers\Api\AuthController();
    $response = $authController->login($request);
    $responseData = json_decode($response->getContent(), true);
    
    if (!$responseData['success']) {
        echo "✅ Wrong password correctly rejected!\n";
        echo "   Message: {$responseData['message']}\n\n";
    } else {
        echo "❌ Wrong password was accepted - this is a security issue!\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing wrong password: " . $e->getMessage() . "\n\n";
}

echo "Test completed!\n";
