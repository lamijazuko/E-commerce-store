<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($product['image_url'])): ?>
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <p class="text-muted">No image available</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                
                <?php if (!empty($rating) && isset($rating['avg_rating'])): ?>
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark fs-6">
                            ★ <?= number_format($rating['avg_rating'], 1) ?> 
                            (<?= $rating['review_count'] ?? 0 ?> reviews)
                        </span>
                    </div>
                <?php endif; ?>
                
                <h2 class="text-primary mb-4">$<?= number_format($product['price'], 2) ?></h2>
                
                <?php if (!empty($product['description'])): ?>
                    <div class="mb-4">
                        <h4>Description</h4>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <button class="btn btn-primary btn-lg">Add to Cart</button>
                    <a href="/products" class="btn btn-outline-secondary btn-lg ms-2">Back to Products</a>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Reviews</h3>
                
                <?php if (!empty($reviews) && is_array($reviews) && count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <?php
                                            $stars = '';
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= ($review['rating'] ?? 0)) {
                                                    $stars .= '★';
                                                } else {
                                                    $stars .= '☆';
                                                }
                                            }
                                            echo $stars;
                                            ?>
                                            <span class="text-muted">(<?= $review['rating'] ?? 'N/A' ?>/5)</span>
                                        </h5>
                                        <?php if (!empty($review['created_at'])): ?>
                                            <small class="text-muted">Posted on <?= date('F j, Y', strtotime($review['created_at'])) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($review['comment'])): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                <?php else: ?>
                                    <p class="card-text text-muted"><em>No comment provided</em></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        No reviews yet. Be the first to review this product!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

