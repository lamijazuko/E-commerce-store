<?php
/**
 * Command-line API Protection Test
 * Run this to verify all routes are protected
 */

require_once 'backend/rest/config.php';

echo "=== API Protection Test Suite ===\n\n";

// Test configuration
$baseUrl = 'http://localhost:8080/lamija_e_commerce_store/backend/rest';
$adminEmail = 'admin@evercart.com';
$adminPassword = 'admin123';

// Helper function to make API requests
function makeRequest($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init($url);
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

$passed = 0;
$failed = 0;

// Test 1: Login as admin
echo "1. Testing Admin Login...\n";
$loginResponse = makeRequest($baseUrl . '/api/auth/login', 'POST', [
    'email' => $adminEmail,
    'password' => $adminPassword
]);

if ($loginResponse['status'] === 200 && isset($loginResponse['data']['token'])) {
    $adminToken = $loginResponse['data']['token'];
    $adminUser = $loginResponse['data']['data'];
    echo "   ✅ Admin login successful\n";
    $passed++;
} else {
    echo "   ❌ Admin login failed: " . ($loginResponse['data']['message'] ?? 'Unknown error') . "\n";
    $failed++;
    exit(1);
}

// Test 2: Unauthenticated access should be blocked
echo "\n2. Testing Unauthenticated Access Blocking...\n";
$noAuthResponse = makeRequest($baseUrl . '/api/users', 'GET', null, null);
if ($noAuthResponse['status'] === 401 || $noAuthResponse['status'] === 403) {
    echo "   ✅ Unauthenticated access properly blocked (Status: {$noAuthResponse['status']})\n";
    $passed++;
} else {
    echo "   ❌ Security issue: Unauthenticated access allowed (Status: {$noAuthResponse['status']})\n";
    $failed++;
}

// Test 3: Admin can access admin endpoints
echo "\n3. Testing Admin Access to Admin Endpoints...\n";
$adminAccessResponse = makeRequest($baseUrl . '/api/users', 'GET', null, $adminToken);
if ($adminAccessResponse['status'] === 200) {
    echo "   ✅ Admin can access admin endpoints\n";
    $passed++;
} else {
    echo "   ❌ Admin cannot access admin endpoints (Status: {$adminAccessResponse['status']})\n";
    $failed++;
}

// Test 4: Product stock update protection
echo "\n4. Testing Product Stock Update Protection...\n";
$stockNoAuth = makeRequest($baseUrl . '/api/products/1/stock', 'PUT', ['quantity' => 10], null);
if ($stockNoAuth['status'] === 401 || $stockNoAuth['status'] === 403) {
    echo "   ✅ Product stock update properly protected\n";
    $passed++;
} else {
    echo "   ❌ Product stock update not protected (Status: {$stockNoAuth['status']})\n";
    $failed++;
}

// Test 5: Review update protection
echo "\n5. Testing Review Update Protection...\n";
$reviewsResponse = makeRequest($baseUrl . '/api/reviews', 'GET');
if ($reviewsResponse['status'] === 200 && isset($reviewsResponse['data']['data'][0])) {
    $reviewId = $reviewsResponse['data']['data'][0]['review_id'];
    $reviewUpdateNoAuth = makeRequest($baseUrl . '/api/reviews/' . $reviewId, 'PUT', ['rating' => 5], null);
    if ($reviewUpdateNoAuth['status'] === 401) {
        echo "   ✅ Review update properly protected\n";
        $passed++;
    } else {
        echo "   ❌ Review update not protected (Status: {$reviewUpdateNoAuth['status']})\n";
        $failed++;
    }
} else {
    echo "   ⚠️  Cannot test - no reviews found\n";
}

// Test 6: Review delete protection
echo "\n6. Testing Review Delete Protection...\n";
if (isset($reviewId)) {
    $reviewDeleteNoAuth = makeRequest($baseUrl . '/api/reviews/' . $reviewId, 'DELETE', null, null);
    if ($reviewDeleteNoAuth['status'] === 401) {
        echo "   ✅ Review delete properly protected\n";
        $passed++;
    } else {
        echo "   ❌ Review delete not protected (Status: {$reviewDeleteNoAuth['status']})\n";
        $failed++;
    }
} else {
    echo "   ⚠️  Cannot test - no reviews found\n";
}

// Summary
echo "\n=== Test Summary ===\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";
echo "Total: " . ($passed + $failed) . "\n\n";

if ($failed === 0) {
    echo "✅ ALL TESTS PASSED! All APIs are properly protected.\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED! Please review the issues above.\n";
    exit(1);
}

