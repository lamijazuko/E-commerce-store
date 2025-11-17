<?php
/**
 * Simple test script to verify FlightPHP and database connection
 */

// Test FlightPHP loading
echo "<h2>Testing FlightPHP Installation</h2>";

try {
    require_once __DIR__ . '/../../vendor/autoload.php';
    echo "✅ Composer autoload loaded successfully<br>";
    
    if (class_exists('Flight')) {
        echo "✅ FlightPHP class loaded successfully<br>";
    } else {
        echo "❌ FlightPHP class not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error loading FlightPHP: " . $e->getMessage() . "<br>";
}

// Test database connection
echo "<h2>Testing Database Connection</h2>";

try {
    require_once __DIR__ . '/config.php';
    
    $host = Config::DB_HOST();
    $port = Config::DB_PORT();
    $dbname = Config::DB_NAME();
    $user = Config::DB_USER();
    $pass = Config::DB_PASSWORD();
    
    echo "Connection details:<br>";
    echo "Host: $host<br>";
    echo "Port: $port<br>";
    echo "Database: $dbname<br>";
    echo "User: $user<br>";
    echo "Password: " . (empty($pass) ? '(empty)' : '***') . "<br><br>";
    
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ Database connection successful!<br>";
    
    // Test if tables exist
    $tables = ['users', 'products', 'categories', 'orders', 'cart', 'reviews'];
    echo "<br><h3>Checking Tables:</h3>";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "⚠️ Table '$table' does not exist (you may need to run database-schema.sql)<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    echo "<br><strong>Tips:</strong><br>";
    echo "1. Make sure MySQL is running in XAMPP<br>";
    echo "2. Make sure the database 'ECommerce' exists (run database-schema.sql)<br>";
    echo "3. Check your database credentials in backend/rest/config.php<br>";
}

// Test service loading
echo "<h2>Testing Service Classes</h2>";

$services = [
    'UserService',
    'ProductService',
    'CategoryService',
    'OrderService',
    'CartService',
    'ReviewService'
];

foreach ($services as $service) {
    $file = __DIR__ . '/../services/' . $service . '.php';
    if (file_exists($file)) {
        require_once $file;
        if (class_exists($service)) {
            echo "✅ $service loaded successfully<br>";
        } else {
            echo "❌ $service class not found<br>";
        }
    } else {
        echo "❌ $service file not found<br>";
    }
}

echo "<br><h2>✅ Setup Verification Complete!</h2>";
echo "<p>If all tests passed, your API is ready to use!</p>";
echo "<p><a href='swagger-ui.html'>View API Documentation (Swagger UI)</a></p>";
echo "<p><a href='index.php/api/products'>Test API Endpoint</a></p>";

