<?php
/**
 * Authentication Routes
 * Handles login, register, logout, and session management
 */

// POST /api/auth/register - Register new user
Flight::route('POST /api/auth/register', function() {
    try {
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        
        $data = ValidationMiddleware::validateJson(
            ['name', 'email', 'password'],
            [
                'email' => ['email' => true],
                'password' => ['min' => 6]
            ]
        );
        
        if ($data === null) {
            return; // ValidationMiddleware already sent response
        }
        
        $service = new UserService();
        $user = $service->createUser($data);
        
        // Auto-login after registration
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::login($user);
        
        // Generate API token
        $token = AuthMiddleware::generateToken($user);
        
        Flight::json([
            'data' => $user,
            'token' => $token,
            'message' => 'User registered and logged in successfully'
        ], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/auth/login - Login user
Flight::route('POST /api/auth/login', function() {
    try {
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        
        $data = ValidationMiddleware::validateJson(
            ['email', 'password'],
            ['email' => ['email' => true]]
        );
        
        if ($data === null) {
            return; // ValidationMiddleware already sent response
        }
        
        $service = new UserService();
        $user = $service->authenticate($data['email'], $data['password']);
        
        // Create session
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::login($user);
        
        // Generate API token
        $token = AuthMiddleware::generateToken($user);
        
        Flight::json([
            'data' => $user,
            'token' => $token,
            'message' => 'Login successful'
        ], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 401);
    }
});

// POST /api/auth/logout - Logout user
Flight::route('POST /api/auth/logout', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::logout();
        
        Flight::json(['message' => 'Logout successful'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/auth/me - Get current authenticated user
Flight::route('GET /api/auth/me', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json([
                'error' => true,
                'message' => 'Not authenticated'
            ], 401);
            return;
        }
        
        Flight::json(['data' => $user], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 401);
    }
});

// Legacy route for backward compatibility
Flight::route('POST /api/users/login', function() {
    try {
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        
        $data = ValidationMiddleware::validateJson(
            ['email', 'password'],
            ['email' => ['email' => true]]
        );
        
        if ($data === null) {
            return; // ValidationMiddleware already sent response
        }
        
        $service = new UserService();
        $user = $service->authenticate($data['email'], $data['password']);
        
        // Create session
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::login($user);
        
        // Generate API token
        $token = AuthMiddleware::generateToken($user);
        
        Flight::json([
            'data' => $user,
            'token' => $token,
            'message' => 'Login successful'
        ], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 401);
    }
});

