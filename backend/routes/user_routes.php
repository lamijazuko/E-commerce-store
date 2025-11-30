<?php
// User Routes

// GET /api/users - Get all users (Admin only)
Flight::route('GET /api/users', function() {
    try {
        // Require admin role
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        if (!AuthorizationMiddleware::requireAdmin()) {
            return; // Stop execution if not authorized
        }
        
        $service = new UserService();
        $users = $service->getAllUsers();
        Flight::json(['data' => $users], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/users/:id - Get user by ID (Admin or own profile)
Flight::route('GET /api/users/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $currentUser = AuthMiddleware::authenticate();
        
        if (!$currentUser) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Admin can view anyone, users can only view themselves
        if ($currentUser['role'] !== 'admin' && $currentUser['user_id'] != $id) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $service = new UserService();
        $user = $service->getUserById($id);
        Flight::json(['data' => $user], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// POST /api/users - Create new user (Admin only - regular users should use /api/auth/register)
Flight::route('POST /api/users', function() {
    try {
        // Require admin role
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        $data = ValidationMiddleware::validateJson(
            ['name', 'email', 'password'],
            [
                'email' => ['email' => true],
                'password' => ['min' => 6]
            ]
        );
        
        if ($data === null) {
            return;
        }
        
        $service = new UserService();
        $user = $service->createUser($data);
        Flight::json(['data' => $user, 'message' => 'User created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/users/:id - Update user (Admin or own profile)
Flight::route('PUT /api/users/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Admin can update anyone, users can only update themselves
        if ($user['role'] !== 'admin' && $user['user_id'] != $id) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        Flight::set('user', $user);
        
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new UserService();
        
        // Non-admin users cannot change their role
        if ($user['role'] !== 'admin' && isset($data['role'])) {
            unset($data['role']);
        }
        
        $user = $service->updateUser($id, $data);
        Flight::json(['data' => $user, 'message' => 'User updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/users/:id - Delete user (Admin only)
Flight::route('DELETE /api/users/@id', function($id) {
    try {
        // Require admin role
        require_once __DIR__ . '/../middleware/AuthorizationMiddleware.php';
        AuthorizationMiddleware::requireAdmin();
        
        $service = new UserService();
        $service->deleteUser($id);
        Flight::json(['message' => 'User deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/users/login - Authenticate user
Flight::route('POST /api/users/login', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new UserService();
        $user = $service->authenticate($data['email'] ?? '', $data['password'] ?? '');
        Flight::json(['data' => $user, 'message' => 'Login successful'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 401);
    }
});

