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
                <li class="breadcrumb-item"><a href="/categories">Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($category['name']) ?></li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><?= htmlspecialchars($category['name']) ?></h1>
                <?php if (!empty($category['description'])): ?>
                    <p class="text-muted"><?= htmlspecialchars($category['description']) ?></p>
                <?php endif; ?>
            </div>
            <div>
                <a href="/categories" class="btn btn-outline-secondary">Back to Categories</a>
                <a href="/products" class="btn btn-primary ms-2">View All Products</a>
            </div>
        </div>

        <?php if (!empty($products) && is_array($products) && count($products) > 0): ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <p class="text-muted mb-0">No image</p>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <?php if (!empty($product['description'])): ?>
                                    <p class="card-text text-muted flex-grow-1">
                                        <?= htmlspecialchars(mb_substr($product['description'], 0, 100)) ?>
                                        <?= mb_strlen($product['description']) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>
                                <div class="mt-auto">
                                    <p class="card-text"><strong class="text-primary fs-4">$<?= number_format($product['price'], 2) ?></strong></p>
                                    <a href="/products/<?= $product['product_id'] ?>" class="btn btn-primary w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">No Products Found</h4>
                <p>There are no products in this category at the moment.</p>
                <hr>
                <a href="/categories" class="btn btn-primary">Back to Categories</a>
                <a href="/products" class="btn btn-outline-primary ms-2">View All Products</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

