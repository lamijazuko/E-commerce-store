<?php
// User Routes

// GET /api/users - Get all users
Flight::route('GET /api/users', function() {
    try {
        $service = new UserService();
        $users = $service->getAllUsers();
        Flight::json(['data' => $users], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/users/:id - Get user by ID
Flight::route('GET /api/users/@id', function($id) {
    try {
        $service = new UserService();
        $user = $service->getUserById($id);
        Flight::json(['data' => $user], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// POST /api/users - Create new user
Flight::route('POST /api/users', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new UserService();
        $user = $service->createUser($data);
        Flight::json(['data' => $user, 'message' => 'User created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/users/:id - Update user
Flight::route('PUT /api/users/@id', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new UserService();
        $user = $service->updateUser($id, $data);
        Flight::json(['data' => $user, 'message' => 'User updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/users/:id - Delete user
Flight::route('DELETE /api/users/@id', function($id) {
    try {
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

