<?php
/**
 * MySQL Connection Checker
 * This script helps identify the correct MySQL password
 */

echo "<h2>MySQL Connection Checker</h2>";
echo "<p>Checking common XAMPP password configurations...</p>";

$passwords = ['', 'root', 'password', 'admin', '123456'];
$host = '127.0.0.1';
$port = 3310;
$user = 'root';

$connected = false;

foreach ($passwords as $password) {
    try {
        $pdo = new PDO(
            "mysql:host=$host;port=$port",
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<p style='color: green;'>✅ <strong>SUCCESS!</strong> Connected with password: '" . ($password === '' ? '(empty)' : $password) . "'</p>";
        $connected = true;
        
        // Test if ECommerce database exists
        try {
            $pdo->exec("USE ECommerce");
            echo "<p style='color: green;'>✅ Database 'ECommerce' exists!</p>";
            
            // List tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (count($tables) > 0) {
                echo "<p>Tables found: " . implode(', ', $tables) . "</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Database exists but has no tables. Run database-schema.sql to create tables.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠️ Database 'ECommerce' does not exist. You need to create it first.</p>";
            echo "<p><strong>To create the database:</strong></p>";
            echo "<ol>";
            echo "<li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
            echo "<li>Click 'New' to create a database</li>";
            echo "<li>Name it 'ECommerce'</li>";
            echo "<li>Import the file: database-schema.sql</li>";
            echo "</ol>";
        }
        
        break;
    } catch (PDOException $e) {
        echo "<p style='color: gray;'>❌ Failed with password: '" . ($password === '' ? '(empty)' : $password) . "'</p>";
    }
}

if (!$connected) {
    echo "<hr>";
    echo "<h3>⚠️ Could not connect with common passwords</h3>";
    echo "<p><strong>Your MySQL root user has a custom password.</strong></p>";
    echo "<p><strong>Options:</strong></p>";
    echo "<ol>";
    echo "<li><strong>Find your password:</strong> Check if you have it saved somewhere, or check XAMPP documentation</li>";
    echo "<li><strong>Reset the password:</strong> You can reset MySQL root password in XAMPP</li>";
    echo "<li><strong>Update config.php:</strong> Once you know the password, update <code>backend/rest/config.php</code></li>";
    echo "</ol>";
    echo "<p><strong>To reset MySQL password in XAMPP:</strong></p>";
    echo "<ol>";
    echo "<li>Stop MySQL in XAMPP Control Panel</li>";
    echo "<li>Open XAMPP Shell (from XAMPP Control Panel)</li>";
    echo "<li>Run: <code>mysqladmin -u root password \"\"</code> (to set empty password)</li>";
    echo "<li>Or: <code>mysqladmin -u root password \"yourpassword\"</code> (to set custom password)</li>";
    echo "<li>Start MySQL again</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><a href='test.php'>Run Full Test</a> | <a href='config.php'>View Config</a></p>";

