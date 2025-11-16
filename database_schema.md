# Database Schema - EverCart E-Commerce Storea

## Entity-Relationship Diagram (ERD)

```
                    ┌─────────────────┐
                    │     USERS       │
                    ├─────────────────┤
                    │ user_id (PK)    │
                    │ username        │
                    │ email           │
                    │ password_hash   │
                    │ first_name      │
                    │ last_name       │
                    │ phone           │
                    │ address         │
                    │ created_at      │
                    │ updated_at      │
                    └─────────────────┘
                            │
                            │ 1:N
                            │
                    ┌─────────────────┐
                    │     ORDERS      │
                    ├─────────────────┤
                    │ order_id (PK)   │
                    │ user_id (FK)    │
                    │ order_date      │
                    │ total_amount    │
                    │ status          │
                    │ shipping_addr   │
                    │ billing_addr    │
                    │ payment_method  │
                    │ created_at      │
                    │ updated_at      │
                    └─────────────────┘
                            │
                            │ 1:N
                            │
                    ┌─────────────────┐
                    │   ORDER_ITEMS   │
                    ├─────────────────┤
                    │ item_id (PK)    │
                    │ order_id (FK)   │
                    │ product_id (FK) │
                    │ quantity        │
                    │ unit_price      │
                    │ total_price     │
                    └─────────────────┘
                            │
                            │ N:1
                            │
                    ┌─────────────────┐
                    │    PRODUCTS     │
                    ├─────────────────┤
                    │ product_id (PK) │
                    │ name            │
                    │ description     │
                    │ price           │
                    │ stock_quantity  │
                    │ category_id (FK)│
                    │ image_url       │
                    │ status          │
                    │ created_at      │
                    │ updated_at      │
                    └─────────────────┘
                            │
                            │ N:1
                            │
                    ┌─────────────────┐
                    │   CATEGORIES    │
                    ├─────────────────┤
                    │ category_id (PK)│
                    │ name            │
                    │ description     │
                    │ parent_id (FK)  │
                    │ image_url       │
                    │ status          │
                    │ created_at      │
                    │ updated_at      │
                    └─────────────────┘
                            │
                            │ 1:N
                            │
                    ┌─────────────────┐
                    │     REVIEWS     │
                    ├─────────────────┤
                    │ review_id (PK)  │
                    │ product_id (FK) │
                    │ user_id (FK)    │
                    │ rating          │
                    │ comment         │
                    │ created_at      │
                    │ updated_at      │
                    └─────────────────┘
                            │
                            │ N:1
                            │
                    ┌─────────────────┐
                    │     USERS       │
                    │  (same as above)│
                    └─────────────────┘
```

## Entity Descriptions

### 1. USERS
**Purpose**: Store customer account information
**Key Attributes**:
- `user_id`: Primary key, unique identifier
- `email`: Unique email address for login
- `password_hash`: Encrypted password
- `first_name`, `last_name`: Customer's full name
- `phone`: Contact number
- `address`: Default shipping address

### 2. CATEGORIES
**Purpose**: Organize products into hierarchical categories
**Key Attributes**:
- `category_id`: Primary key
- `name`: Category name (e.g., "Electronics", "Clothing")
- `parent_id`: Self-referencing foreign key for subcategories
- `description`: Category description
- `status`: Active/inactive status

### 3. PRODUCTS
**Purpose**: Store product information and inventory
**Key Attributes**:
- `product_id`: Primary key
- `name`: Product name
- `description`: Detailed product description
- `price`: Product price
- `stock_quantity`: Available inventory
- `category_id`: Foreign key to categories
- `image_url`: Product image URL
- `status`: Active/discontinued status

### 4. ORDERS
**Purpose**: Track customer orders and transactions
**Key Attributes**:
- `order_id`: Primary key
- `user_id`: Foreign key to users
- `order_date`: When the order was placed
- `total_amount`: Total order value
- `status`: Order status (pending, processing, shipped, delivered, cancelled)
- `shipping_addr`: Delivery address
- `billing_addr`: Billing address
- `payment_method`: Payment method used

### 5. ORDER_ITEMS
**Purpose**: Store individual items within each order
**Key Attributes**:
- `item_id`: Primary key
- `order_id`: Foreign key to orders
- `product_id`: Foreign key to products
- `quantity`: Number of items ordered
- `unit_price`: Price per unit at time of order
- `total_price`: Total price for this line item

### 6. REVIEWS
**Purpose**: Store customer product reviews and ratings
**Key Attributes**:
- `review_id`: Primary key
- `product_id`: Foreign key to products
- `user_id`: Foreign key to users
- `rating`: Rating from 1-5 stars
- `comment`: Written review text
- `created_at`: When review was posted

## Relationships

1. **Users → Orders** (1:N): One user can have many orders
2. **Orders → Order_Items** (1:N): One order can contain many items
3. **Products → Order_Items** (1:N): One product can be in many order items
4. **Categories → Products** (1:N): One category can contain many products
5. **Products → Reviews** (1:N): One product can have many reviews
6. **Users → Reviews** (1:N): One user can write many reviews
7. **Categories → Categories** (1:N): Self-referencing for subcategories

## Additional Considerations

- All tables include `created_at` and `updated_at` timestamps for audit trails
- Password should be hashed using bcrypt or similar
- Consider adding indexes on frequently queried fields (user_id, product_id, category_id)
- Order status should be tracked through state transitions
- Product prices in order_items preserve historical pricing
- Categories can be nested for hierarchical organization
