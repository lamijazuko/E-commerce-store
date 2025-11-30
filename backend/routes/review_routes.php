<?php
// Review Routes

// GET /api/reviews - Get all reviews
Flight::route('GET /api/reviews', function() {
    try {
        $service = new ReviewService();
        $reviews = $service->getAllReviews();
        Flight::json(['data' => $reviews], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/reviews/:id - Get review by ID
Flight::route('GET /api/reviews/@id', function($id) {
    try {
        $service = new ReviewService();
        $review = $service->getReviewById($id);
        Flight::json(['data' => $review], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 404);
    }
});

// GET /api/reviews/product/:productId - Get reviews by product ID
Flight::route('GET /api/reviews/product/@productId', function($productId) {
    try {
        $service = new ReviewService();
        $reviews = $service->getReviewsByProductId($productId);
        Flight::json(['data' => $reviews], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/reviews/user/:userId - Get reviews by user ID
Flight::route('GET /api/reviews/user/@userId', function($userId) {
    try {
        $service = new ReviewService();
        $reviews = $service->getReviewsByUserId($userId);
        Flight::json(['data' => $reviews], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// GET /api/reviews/product/:productId/rating - Get average rating for product
Flight::route('GET /api/reviews/product/@productId/rating', function($productId) {
    try {
        $service = new ReviewService();
        $rating = $service->getAverageRating($productId);
        Flight::json(['data' => $rating], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// POST /api/reviews - Create new review (Authenticated users only)
Flight::route('POST /api/reviews', function() {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $user = AuthMiddleware::authenticate();
        if (!$user) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        Flight::set('user', $user);
        
        require_once __DIR__ . '/../middleware/ValidationMiddleware.php';
        $data = ValidationMiddleware::validateJson(
            ['product_id', 'rating'],
            [
                'product_id' => ['numeric' => true],
                'rating' => ['numeric' => true]
            ]
        );
        
        if ($data === null) {
            return;
        }
        
        // Set user_id from authenticated user
        $data['user_id'] = $user['user_id'];
        
        $service = new ReviewService();
        $review = $service->createReview($data);
        Flight::json(['data' => $review, 'message' => 'Review created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/reviews/:id - Update review (Owner or Admin)
Flight::route('PUT /api/reviews/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $currentUser = AuthMiddleware::authenticate();
        
        if (!$currentUser) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Get review to check ownership
        $service = new ReviewService();
        $review = $service->getReviewById($id);
        
        if (!$review) {
            Flight::json(['error' => true, 'message' => 'Review not found'], 404);
            return;
        }
        
        // Admin can update any review, users can only update their own
        if ($currentUser['role'] !== 'admin' && $review['user_id'] != $currentUser['user_id']) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $data = json_decode(Flight::request()->getBody(), true);
        $review = $service->updateReview($id, $data);
        Flight::json(['data' => $review, 'message' => 'Review updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/reviews/:id - Delete review (Owner or Admin)
Flight::route('DELETE /api/reviews/@id', function($id) {
    try {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $currentUser = AuthMiddleware::authenticate();
        
        if (!$currentUser) {
            Flight::json(['error' => true, 'message' => 'Authentication required'], 401);
            return;
        }
        
        // Get review to check ownership
        $service = new ReviewService();
        $review = $service->getReviewById($id);
        
        if (!$review) {
            Flight::json(['error' => true, 'message' => 'Review not found'], 404);
            return;
        }
        
        // Admin can delete any review, users can only delete their own
        if ($currentUser['role'] !== 'admin' && $review['user_id'] != $currentUser['user_id']) {
            Flight::json(['error' => true, 'message' => 'Permission denied'], 403);
            return;
        }
        
        $service->deleteReview($id);
        Flight::json(['message' => 'Review deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

