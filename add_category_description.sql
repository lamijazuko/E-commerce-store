-- Add description field to Categories table
USE ecommerce;

-- Add description column to Categories table if it doesn't exist
ALTER TABLE Categories 
ADD COLUMN description TEXT NULL AFTER name;

-- Verify the change
DESCRIBE Categories;

