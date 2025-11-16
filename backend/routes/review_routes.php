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

// POST /api/reviews - Create new review
Flight::route('POST /api/reviews', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new ReviewService();
        $review = $service->createReview($data);
        Flight::json(['data' => $review, 'message' => 'Review created successfully'], 201);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// PUT /api/reviews/:id - Update review
Flight::route('PUT /api/reviews/@id', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        $service = new ReviewService();
        $review = $service->updateReview($id, $data);
        Flight::json(['data' => $review, 'message' => 'Review updated successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

// DELETE /api/reviews/:id - Delete review
Flight::route('DELETE /api/reviews/@id', function($id) {
    try {
        $service = new ReviewService();
        $service->deleteReview($id);
        Flight::json(['message' => 'Review deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
    }
});

