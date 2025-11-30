-- Milestone 4: Add role-based access control
-- Run this SQL script to update the Users table with role field

USE ECommerce;

-- Add role column to Users table
ALTER TABLE Users 
ADD COLUMN role VARCHAR(20) DEFAULT 'user' AFTER password,
ADD INDEX idx_role (role);

-- Update existing users to have 'user' role (if any exist)
UPDATE Users SET role = 'user' WHERE role IS NULL OR role = '';

-- Create an admin user (optional - password is 'admin123')
-- Uncomment and modify as needed:
-- INSERT INTO Users (name, email, password, role, address) 
-- VALUES ('Admin User', 'admin@evercart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin Address');
-- Note: The password hash above is for 'admin123'

