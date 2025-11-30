-- Insert sample products with real images into database
USE ecommerce;

-- First, ensure we have the categories (if not already created)
INSERT IGNORE INTO Categories (category_id, name, description) VALUES
(1, 'Electronics', 'Cutting-edge electronic devices and gadgets for modern living'),
(2, 'Clothing', 'Fashionable and comfortable clothing for all occasions'),
(3, 'Home & Kitchen', 'Essential home and kitchen products for everyday living'),
(4, 'Accessories', 'Stylish accessories to complement your lifestyle'),
(5, 'Sports', 'Sports and fitness equipment for an active lifestyle'),
(6, 'Books', 'Educational and entertaining books for all ages');

-- Insert products with real Unsplash images
INSERT INTO Products (product_id, name, description, price, category_id, image_url) VALUES
-- Electronics
(1, 'Wireless Bluetooth Headphones', 'High-quality wireless headphones with noise cancellation and long battery life.', 99.99, 1, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop'),
(2, 'Smart Fitness Watch', 'Track your fitness goals with this advanced smartwatch featuring heart rate monitoring.', 199.99, 1, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop'),
(5, 'Wireless Phone Charger', 'Fast wireless charging pad compatible with all Qi-enabled devices.', 39.99, 1, 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=500&h=500&fit=crop'),
(7, 'Gaming Laptop', 'High-performance gaming laptop with RTX graphics and fast SSD storage.', 1299.99, 1, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop'),
(8, 'Bluetooth Speaker', 'Portable Bluetooth speaker with 360-degree sound and waterproof design.', 79.99, 1, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&h=500&fit=crop'),

-- Clothing
(3, 'Organic Cotton T-Shirt', 'Comfortable organic cotton t-shirt available in multiple colors.', 24.99, 2, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop'),
(9, 'Denim Jeans', 'Classic fit denim jeans made from premium cotton with stretch comfort.', 59.99, 2, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop'),
(10, 'Running Shoes', 'Lightweight running shoes with advanced cushioning and breathable mesh.', 89.99, 2, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop'),

-- Home & Kitchen
(4, 'Stainless Steel Water Bottle', 'Insulated stainless steel water bottle that keeps drinks cold for 24 hours.', 29.99, 3, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&h=500&fit=crop'),
(11, 'Coffee Maker', 'Programmable coffee maker with built-in grinder and thermal carafe.', 149.99, 3, 'https://images.unsplash.com/photo-1517668808823-f8c0f12f6a99?w=500&h=500&fit=crop'),
(12, 'Non-Stick Cookware Set', 'Professional-grade non-stick cookware set with durable ceramic coating.', 199.99, 3, 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=500&h=500&fit=crop'),

-- Accessories
(6, 'Leather Wallet', 'Genuine leather wallet with RFID blocking technology.', 49.99, 4, 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=500&h=500&fit=crop'),
(13, 'Sunglasses', 'UV protection sunglasses with polarized lenses and lightweight frame.', 129.99, 4, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=500&h=500&fit=crop'),
(14, 'Backpack', 'Durable travel backpack with laptop compartment and multiple pockets.', 79.99, 4, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop'),

-- Sports
(15, 'Yoga Mat', 'Premium yoga mat with superior grip and cushioning for all poses.', 39.99, 5, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500&h=500&fit=crop'),
(16, 'Dumbbells Set', 'Adjustable dumbbells set with multiple weight options for home workouts.', 89.99, 5, 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=500&h=500&fit=crop'),

-- Books
(17, 'Programming Guide', 'Complete guide to modern programming with practical examples and exercises.', 29.99, 6, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=500&h=500&fit=crop'),
(18, 'Business Strategy', 'Essential business strategy guide for entrepreneurs and managers.', 24.99, 6, 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=500&h=500&fit=crop')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    description = VALUES(description),
    price = VALUES(price),
    category_id = VALUES(category_id),
    image_url = VALUES(image_url);

