<?php
require_once __DIR__ . '/../rest/dao/UserDao.php';

class UserService {
    private $userDao;
    
    public function __construct() {
        $this->userDao = new UserDao();
    }
    
    public function getAllUsers() {
        return $this->userDao->getAll();
    }
    
    public function getUserById($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid user ID");
        }
        $user = $this->userDao->getById($id);
        if (!$user) {
            throw new Exception("User not found");
        }
        // Remove password from response
        unset($user['password']);
        return $user;
    }
    
    public function getUserByEmail($email) {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address");
        }
        return $this->userDao->getByEmail($email);
    }
    
    public function createUser($userData) {
        // Validation
        if (empty($userData['email'])) {
            throw new Exception("Email is required");
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        if (empty($userData['password'])) {
            throw new Exception("Password is required");
        }
        if (strlen($userData['password']) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
        if (empty($userData['name'])) {
            throw new Exception("Name is required");
        }
        
        // Check if email already exists
        $existingUser = $this->userDao->getByEmail($userData['email']);
        if ($existingUser) {
            throw new Exception("Email already registered");
        }
        
        // Hash password
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Set default role if not provided (prevent role escalation)
        if (!isset($userData['role']) || empty($userData['role'])) {
            $userData['role'] = 'user'; // Default role
        } else {
            // Only allow 'user' role for self-registration
            // Admin roles must be assigned by existing admins
            if ($userData['role'] !== 'user') {
                throw new Exception("Invalid role assignment");
            }
        }
        
        // Create user
        $user = $this->userDao->add($userData);
        unset($user['password']);
        return $user;
    }
    
    public function updateUser($id, $userData) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid user ID");
        }
        
        // Check if user exists
        $existingUser = $this->userDao->getById($id);
        if (!$existingUser) {
            throw new Exception("User not found");
        }
        
        // Validate email if provided
        if (isset($userData['email'])) {
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            // Check if email is taken by another user
            $emailUser = $this->userDao->getByEmail($userData['email']);
            if ($emailUser && $emailUser['user_id'] != $id) {
                throw new Exception("Email already registered");
            }
        }
        
        // Hash password if provided
        if (isset($userData['password'])) {
            if (strlen($userData['password']) < 6) {
                throw new Exception("Password must be at least 6 characters");
            }
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        // Don't update ID
        unset($userData['user_id']);
        
        $user = $this->userDao->update($userData, $id);
        unset($user['password']);
        return $user;
    }
    
    public function deleteUser($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("Invalid user ID");
        }
        
        $user = $this->userDao->getById($id);
        if (!$user) {
            throw new Exception("User not found");
        }
        
        return $this->userDao->delete($id);
    }
    
    public function authenticate($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }
        
        $user = $this->userDao->getByEmail($email);
        if (!$user) {
            throw new Exception("Invalid credentials");
        }
        
        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid credentials");
        }
        
        // Prepare user data with role
        $userData = [
            'user_id' => $user['user_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'address' => $user['address'] ?? '',
            'role' => $user['role'] ?? 'user' // Default to 'user' if role not set
        ];
        
        return $userData;
    }
}

