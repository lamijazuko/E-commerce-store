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
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= htmlspecialchars($title) ?></h1>
            <a href="/products" class="btn btn-primary">View All Products</a>
        </div>

        <?php if (!empty($categories) && is_array($categories) && count($categories) > 0): ?>
            <div class="row">
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                                <?php if (!empty($category['description'])): ?>
                                    <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($category['description']) ?></p>
                                <?php endif; ?>
                                <a href="/categories/<?= $category['category_id'] ?>/products" class="btn btn-outline-primary mt-auto">
                                    View Products →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">No Categories Found</h4>
                <p>There are no categories available at the moment.</p>
                <hr>
                <a href="/" class="btn btn-primary">Go Home</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

