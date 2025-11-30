<?php
/**
 * Run Milestone 4 Database Update
 * Adds role column to Users table
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Update - Milestone 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">📊 Database Update - Milestone 4</h1>
        <p class="lead">Adding role column to Users table for role-based access control</p>
        
        <div class="card">
            <div class="card-body">
                <h3>Update Status:</h3>
                <hr>
                
                <?php
                try {
                    // Get database configuration
                    $host = Config::DB_HOST();
                    $port = Config::DB_PORT();
                    $dbname = Config::DB_NAME();
                    $user = Config::DB_USER();
                    $pass = Config::DB_PASSWORD();
                    
                    echo "<p><strong>Database:</strong> {$dbname}</p>";
                    echo "<p><strong>Host:</strong> {$host}:{$port}</p>";
                    echo "<p><strong>User:</strong> {$user}</p>";
                    echo "<hr>";
                    
                    // Connect to database
                    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
                    $pdo = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                    
                    echo "<p class='success'>✅ Connected to database successfully!</p>";
                    
                    // Check if role column already exists
                    $stmt = $pdo->query("SHOW COLUMNS FROM Users LIKE 'role'");
                    $columnExists = $stmt->rowCount() > 0;
                    
                    if ($columnExists) {
                        echo "<p class='warning'>⚠️ Role column already exists in Users table.</p>";
                        echo "<p>Checking current users...</p>";
                        
                        // Show current users with roles
                        $users = $pdo->query("SELECT user_id, name, email, role FROM Users")->fetchAll();
                        if (count($users) > 0) {
                            echo "<table class='table table-sm mt-3'>";
                            echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr></thead>";
                            echo "<tbody>";
                            foreach ($users as $u) {
                                $roleBadge = ($u['role'] === 'admin') ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-secondary">User</span>';
                                echo "<tr><td>{$u['user_id']}</td><td>{$u['name']}</td><td>{$u['email']}</td><td>{$roleBadge}</td></tr>";
                            }
                            echo "</tbody></table>";
                        }
                        
                        echo "<p class='success'>✅ Database is already up to date!</p>";
                    } else {
                        echo "<p>Role column does not exist. Adding it now...</p>";
                        
                        // Add role column
                        $pdo->exec("ALTER TABLE Users ADD COLUMN role VARCHAR(20) DEFAULT 'user' AFTER password");
                        echo "<p class='success'>✅ Added 'role' column to Users table</p>";
                        
                        // Add index on role
                        try {
                            $pdo->exec("ALTER TABLE Users ADD INDEX idx_role (role)");
                            echo "<p class='success'>✅ Added index on 'role' column</p>";
                        } catch (PDOException $e) {
                            // Index might already exist or error, but continue
                            echo "<p class='warning'>⚠️ Index creation: " . $e->getMessage() . "</p>";
                        }
                        
                        // Update existing users to have 'user' role
                        $affected = $pdo->exec("UPDATE Users SET role = 'user' WHERE role IS NULL OR role = ''");
                        echo "<p class='success'>✅ Updated {$affected} existing user(s) with 'user' role</p>";
                        
                        echo "<hr>";
                        echo "<p class='success'><strong>✅ Database update completed successfully!</strong></p>";
                    }
                    
                    // Show table structure
                    echo "<hr>";
                    echo "<h4>Users Table Structure:</h4>";
                    $columns = $pdo->query("SHOW COLUMNS FROM Users")->fetchAll();
                    echo "<table class='table table-sm'>";
                    echo "<thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr></thead>";
                    echo "<tbody>";
                    foreach ($columns as $col) {
                        echo "<tr>";
                        echo "<td>{$col['Field']}</td>";
                        echo "<td>{$col['Type']}</td>";
                        echo "<td>{$col['Null']}</td>";
                        echo "<td>{$col['Key']}</td>";
                        echo "<td>{$col['Default']}</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    
                } catch (PDOException $e) {
                    echo "<p class='error'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "<p><strong>Common Issues:</strong></p>";
                    echo "<ul>";
                    echo "<li>Make sure MySQL is running in XAMPP</li>";
                    echo "<li>Check that the database 'ECommerce' exists</li>";
                    echo "<li>Verify database credentials in backend/rest/config.php</li>";
                    echo "<li>Check if the Users table exists</li>";
                    echo "</ul>";
                } catch (Exception $e) {
                    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
                
                <hr>
                <div class="mt-4">
                    <a href="test.php" class="btn btn-secondary">Back to Test Page</a>
                    <a href="../.." class="btn btn-primary">Go to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

