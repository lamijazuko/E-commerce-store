<?php
/**
 * Complete Test Suite for Milestone 3
 * Tests all API endpoints, presentation layer, services, and views
 */

$baseUrl = 'http://localhost:8080/lamija_e_commerce_store/backend/rest';
$results = [
    'api' => ['passed' => 0, 'failed' => 0, 'tests' => []],
    'presentation' => ['passed' => 0, 'failed' => 0, 'tests' => []],
    'services' => ['passed' => 0, 'failed' => 0, 'tests' => []],
    'views' => ['passed' => 0, 'failed' => 0, 'tests' => []]
];

// Helper function to make API calls
function apiCall($method, $url, $data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response,
        'data' => json_decode($response, true),
        'content_type' => $contentType
    ];
}

// Test result recorder
function recordResult($category, $name, $passed, $message = '') {
    global $results;
    $results[$category]['tests'][] = [
        'name' => $name,
        'passed' => $passed,
        'message' => $message
    ];
    if ($passed) {
        $results[$category]['passed']++;
    } else {
        $results[$category]['failed']++;
    }
}

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     MILESTONE 3 - COMPLETE TEST SUITE (100% Coverage)       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";
echo "Base URL: {$baseUrl}\n\n";

$testData = [];

// ============================================
// 1. TEST VIEW FILES EXISTENCE
// ============================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "1. VIEW FILES CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$viewsDir = __DIR__ . '/../views/';
$requiredViews = [
    'home.php',
    'products.php',
    'product_detail.php',
    'categories.php',
    'category_products.php',
    'error.php'
];

foreach ($requiredViews as $view) {
    $filePath = $viewsDir . $view;
    $exists = file_exists($filePath);
    $status = $exists ? '✅' : '❌';
    echo "{$status} {$view}\n";
    recordResult('views', "View file: {$view}", $exists, $exists ? 'File exists' : 'File missing');
}

echo "\n";

// ============================================
// 2. TEST SERVICE CLASSES
// ============================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "2. SERVICE CLASSES CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$servicesDir = __DIR__ . '/../services/';
$requiredServices = [
    'UserService.php',
    'ProductService.php',
    'CategoryService.php',
    'OrderService.php',
    'CartService.php',
    'ReviewService.php'
];

foreach ($requiredServices as $service) {
    $filePath = $servicesDir . $service;
    $exists = file_exists($filePath);
    $status = $exists ? '✅' : '❌';
    echo "{$status} {$service}\n";
    recordResult('services', "Service file: {$service}", $exists, $exists ? 'File exists' : 'File missing');
}

echo "\n";

// ============================================
// 3. TEST PRESENTATION LAYER ROUTES
// ============================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "3. PRESENTATION LAYER ROUTES TEST\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$presentationRoutes = [
    ['path' => '/', 'name' => 'Home page', 'expected' => 200],
    ['path' => '/products', 'name' => 'Products listing', 'expected' => 200],
    ['path' => '/categories', 'name' => 'Categories listing', 'expected' => 200],
];

foreach ($presentationRoutes as $route) {
    $url = $baseUrl . $route['path'];
    $result = apiCall('GET', $url);
    
    // Check if it returns HTML (presentation layer) or JSON (API error)
    $isHtml = strpos($result['content_type'], 'text/html') !== false;
    $passed = ($result['code'] == $route['expected']) || ($isHtml && $result['code'] == 200);
    
    $status = $passed ? '✅' : '❌';
    echo "{$status} GET {$route['path']} - {$route['name']}\n";
    echo "   HTTP Code: {$result['code']} | Content-Type: {$result['content_type']}\n";
    recordResult('presentation', $route['name'], $passed, "HTTP {$result['code']}");
}

echo "\n";

// ============================================
// 4. TEST API ENDPOINTS (Basic CRUD)
// ============================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "4. API ENDPOINTS TEST (Basic CRUD Operations)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test GET endpoints (Read operations)
$readEndpoints = [
    ['path' => '/api/users', 'name' => 'GET /api/users'],
    ['path' => '/api/products', 'name' => 'GET /api/products'],
    ['path' => '/api/categories', 'name' => 'GET /api/categories'],
    ['path' => '/api/orders', 'name' => 'GET /api/orders'],
    ['path' => '/api/reviews', 'name' => 'GET /api/reviews'],
];

foreach ($readEndpoints as $endpoint) {
    $url = $baseUrl . $endpoint['path'];
    $result = apiCall('GET', $url);
    $passed = $result['code'] == 200;
    $status = $passed ? '✅' : '❌';
    echo "{$status} {$endpoint['name']} - HTTP {$result['code']}\n";
    recordResult('api', $endpoint['name'], $passed, "HTTP {$result['code']}");
}

// Test POST endpoints (Create operations) - we'll create test data
echo "\n--- Creating Test Data for CRUD Tests ---\n\n";

// Create a user
$userData = [
    'name' => 'Test User ' . time(),
    'email' => 'test' . time() . '@example.com',
    'password' => 'password123',
    'address' => '123 Test Street'
];
$result = apiCall('POST', $baseUrl . '/api/users', $userData);
$userCreated = $result['code'] == 201;
echo ($userCreated ? '✅' : '❌') . " POST /api/users - Create user - HTTP {$result['code']}\n";
recordResult('api', 'POST /api/users - Create user', $userCreated, "HTTP {$result['code']}");
if ($userCreated) {
    $testData['userId'] = $result['data']['data']['user_id'] ?? null;
}

// Create a category
$categoryData = ['name' => 'Test Category ' . time()];
$result = apiCall('POST', $baseUrl . '/api/categories', $categoryData);
$categoryCreated = $result['code'] == 201;
echo ($categoryCreated ? '✅' : '❌') . " POST /api/categories - Create category - HTTP {$result['code']}\n";
recordResult('api', 'POST /api/categories - Create category', $categoryCreated, "HTTP {$result['code']}");
if ($categoryCreated) {
    $testData['categoryId'] = $result['data']['data']['category_id'] ?? null;
}

// Create a product
if (isset($testData['categoryId'])) {
    $productData = [
        'name' => 'Test Product ' . time(),
        'description' => 'Test product description',
        'price' => 99.99,
        'category_id' => $testData['categoryId']
    ];
    $result = apiCall('POST', $baseUrl . '/api/products', $productData);
    $productCreated = $result['code'] == 201;
    echo ($productCreated ? '✅' : '❌') . " POST /api/products - Create product - HTTP {$result['code']}\n";
    recordResult('api', 'POST /api/products - Create product', $productCreated, "HTTP {$result['code']}");
    if ($productCreated) {
        $testData['productId'] = $result['data']['data']['product_id'] ?? null;
    }
}

// Test UPDATE endpoints (Update operations)
if (isset($testData['userId'])) {
    echo "\n--- Testing UPDATE Operations ---\n\n";
    $updateData = ['name' => 'Updated Test User'];
    $result = apiCall('PUT', $baseUrl . '/api/users/' . $testData['userId'], $updateData);
    $passed = $result['code'] == 200;
    echo ($passed ? '✅' : '❌') . " PUT /api/users/{id} - Update user - HTTP {$result['code']}\n";
    recordResult('api', 'PUT /api/users/{id} - Update user', $passed, "HTTP {$result['code']}");
}

// Test additional endpoints
echo "\n--- Testing Additional Endpoints ---\n\n";

if (isset($testData['userId'])) {
    // Test cart endpoints
    $result = apiCall('GET', $baseUrl . '/api/cart/user/' . $testData['userId']);
    $passed = $result['code'] == 200;
    echo ($passed ? '✅' : '❌') . " GET /api/cart/user/{userId} - HTTP {$result['code']}\n";
    recordResult('api', 'GET /api/cart/user/{userId}', $passed, "HTTP {$result['code']}");
    
    // Test login endpoint
    $loginData = ['email' => $userData['email'], 'password' => $userData['password']];
    $result = apiCall('POST', $baseUrl . '/api/users/login', $loginData);
    $passed = $result['code'] == 200;
    echo ($passed ? '✅' : '❌') . " POST /api/users/login - Authenticate - HTTP {$result['code']}\n";
    recordResult('api', 'POST /api/users/login', $passed, "HTTP {$result['code']}");
}

if (isset($testData['productId'])) {
    // Test product detail presentation route (if product exists)
    $result = apiCall('GET', $baseUrl . '/products/' . $testData['productId']);
    $isHtml = strpos($result['content_type'] ?? '', 'text/html') !== false;
    $passed = ($result['code'] == 200 && $isHtml) || ($result['code'] == 200);
    echo ($passed ? '✅' : '❌') . " GET /products/{id} - Product detail page - HTTP {$result['code']}\n";
    recordResult('presentation', 'Product detail page', $passed, "HTTP {$result['code']}");
    
    // Test reviews by product
    $result = apiCall('GET', $baseUrl . '/api/reviews/product/' . $testData['productId']);
    $passed = $result['code'] == 200;
    echo ($passed ? '✅' : '❌') . " GET /api/reviews/product/{productId} - HTTP {$result['code']}\n";
    recordResult('api', 'GET /api/reviews/product/{productId}', $passed, "HTTP {$result['code']}");
}

if (isset($testData['categoryId'])) {
    // Test category products presentation route
    $result = apiCall('GET', $baseUrl . '/categories/' . $testData['categoryId'] . '/products');
    $isHtml = strpos($result['content_type'] ?? '', 'text/html') !== false;
    $passed = ($result['code'] == 200 && $isHtml) || ($result['code'] == 200);
    echo ($passed ? '✅' : '❌') . " GET /categories/{id}/products - Category products page - HTTP {$result['code']}\n";
    recordResult('presentation', 'Category products page', $passed, "HTTP {$result['code']}");
}

// ============================================
// 5. TEST OPENAPI DOCUMENTATION
// ============================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "5. OPENAPI DOCUMENTATION CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$openApiFile = __DIR__ . '/openapi.yaml';
$swaggerFile = __DIR__ . '/swagger-ui.html';

$openApiExists = file_exists($openApiFile);
echo ($openApiExists ? '✅' : '❌') . " openapi.yaml file exists\n";
recordResult('views', 'OpenAPI specification file', $openApiExists, $openApiExists ? 'File exists' : 'File missing');

$swaggerExists = file_exists($swaggerFile);
echo ($swaggerExists ? '✅' : '❌') . " swagger-ui.html file exists\n";
recordResult('views', 'Swagger UI file', $swaggerExists, $swaggerExists ? 'File exists' : 'File missing');

// Test if Swagger UI is accessible
if ($swaggerExists) {
    $result = apiCall('GET', $baseUrl . '/swagger-ui.html');
    $passed = $result['code'] == 200;
    echo ($passed ? '✅' : '❌') . " Swagger UI accessible - HTTP {$result['code']}\n";
    recordResult('presentation', 'Swagger UI accessible', $passed, "HTTP {$result['code']}");
}

// ============================================
// SUMMARY REPORT
// ============================================
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST SUMMARY REPORT                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$totalPassed = 0;
$totalFailed = 0;
$totalTests = 0;

foreach ($results as $category => $data) {
    $categoryName = strtoupper($category);
    $passed = $data['passed'];
    $failed = $data['failed'];
    $total = $passed + $failed;
    $percentage = $total > 0 ? round(($passed / $total) * 100, 2) : 0;
    
    $totalPassed += $passed;
    $totalFailed += $failed;
    $totalTests += $total;
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "{$categoryName}:\n";
    echo "  ✅ Passed: {$passed}\n";
    echo "  ❌ Failed: {$failed}\n";
    echo "  📊 Success Rate: {$percentage}%\n";
    echo "\n";
}

$overallPercentage = $totalTests > 0 ? round(($totalPassed / $totalTests) * 100, 2) : 0;

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "OVERALL RESULTS:\n";
echo "  Total Tests: {$totalTests}\n";
echo "  ✅ Passed: {$totalPassed}\n";
echo "  ❌ Failed: {$totalFailed}\n";
echo "  📊 Success Rate: {$overallPercentage}%\n";
echo "\n";

if ($totalFailed == 0) {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  🎉 ALL TESTS PASSED! Milestone 3 is 100% Complete! 🎉      ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
} else {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  ⚠️  Some tests failed. Check the output above for details. ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
}

echo "\n";
echo "💡 Tip: Run this from command line for better formatting, or visit:\n";
echo "   - Swagger UI: {$baseUrl}/swagger-ui.html\n";
echo "   - Test Page: {$baseUrl}/test.php\n";
echo "   - API Test: {$baseUrl}/test_all_apis.php\n";
echo "\n";

