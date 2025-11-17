<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load Composer autoloader if available
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    // Fallback: Try to load FlightPHP from common locations
    $flightPaths = [
        __DIR__ . '/../../vendor/mikecao/flight/flight/Flight.php',  // Vendor directory
        __DIR__ . '/Flight.php',  // Local download
        __DIR__ . '/../../vendor/mikecao/flight/Flight.php',
        __DIR__ . '/../../vendor/flightphp/core/flight/Flight.php',
        'vendor/mikecao/flight/Flight.php'
    ];
    $loaded = false;
    foreach ($flightPaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $loaded = true;
            break;
        }
    }
    if (!$loaded) {
        die('FlightPHP not found. Please run: composer install or download FlightPHP manually.');
    }
}

// Load configuration
require_once __DIR__ . '/config.php';

// Load DAOs
require_once __DIR__ . '/dao/BaseDao.php';
require_once __DIR__ . '/dao/UserDao.php';
require_once __DIR__ . '/dao/ProductDao.php';
require_once __DIR__ . '/dao/CategoryDao.php';
require_once __DIR__ . '/dao/OrderDao.php';
require_once __DIR__ . '/dao/CartDao.php';
require_once __DIR__ . '/dao/ReviewDao.php';

// Load Services
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../services/OrderService.php';
require_once __DIR__ . '/../services/CartService.php';
require_once __DIR__ . '/../services/ReviewService.php';

// Load Routes
require_once __DIR__ . '/../routes/user_routes.php';
require_once __DIR__ . '/../routes/product_routes.php';
require_once __DIR__ . '/../routes/category_routes.php';
require_once __DIR__ . '/../routes/order_routes.php';
require_once __DIR__ . '/../routes/cart_routes.php';
require_once __DIR__ . '/../routes/review_routes.php';
require_once __DIR__ . '/../routes/presentation_routes.php';

// Error handler
Flight::map('error', function($ex) {
    Flight::json([
        'error' => true,
        'message' => $ex->getMessage()
    ], 500);
});

// 404 handler
Flight::map('notFound', function() {
    Flight::json([
        'error' => true,
        'message' => 'Endpoint not found'
    ], 404);
});

// Start Flight
Flight::start();

