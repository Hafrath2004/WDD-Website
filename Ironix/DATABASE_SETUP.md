# Database Setup Instructions for Ironix

## Overview
This document explains how to set up the database tables for the Ironix Hardware Shop application.

## Database Structure

### Tables Created:
1. **products** - Stores product information
   - id (Primary Key, Auto Increment)
   - name
   - description
   - price
   - image_url (supports website URLs)
   - created_at, updated_at

2. **blog** - Stores blog posts with product visualization
   - id (Primary Key, Auto Increment)
   - title
   - content
   - name (product name for visualization)
   - description (product description)
   - price (product price)
   - image_url (product image URL)
   - author
   - published_at, created_at, updated_at

3. **users** - Stores customer/user information
   - id (Primary Key, Auto Increment)
   - name
   - email (Unique)
   - phone
   - address
   - created_at, updated_at

4. **cart** - Stores shopping cart items
   - id (Primary Key, Auto Increment)
   - product_id (Foreign Key to products)
   - name
   - price
   - quantity
   - created_at, updated_at

5. **admin** - Stores admin user information
   - id (Primary Key)
   - name
   - email
   - password
   - gender
   - created_at, updated_at

## Setup Methods

### Method 1: Using the Setup Script (Recommended)
1. Make sure XAMPP is running and MySQL is active
2. Open your browser and navigate to:
   ```
   http://localhost/Ironix/setup_database.php
   ```
3. The script will automatically:
   - Create the database if it doesn't exist
   - Drop existing tables (if any)
   - Create all required tables
   - Create indexes for better performance

### Method 2: Using phpMyAdmin
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select the `ironix` database (or create it if it doesn't exist)
3. Click on the "SQL" tab
4. Copy and paste the contents of `create_tables.sql`
5. Click "Go" to execute

### Method 3: Using MySQL Command Line
1. Open MySQL command line or terminal
2. Navigate to the project directory
3. Run:
   ```bash
   mysql -u root -p ironix < create_tables.sql
   ```

## Verification

After setup, verify the tables were created:
1. Go to phpMyAdmin
2. Select the `ironix` database
3. You should see 5 tables: `products`, `blog`, `users`, `cart`, `admin`

## Important Notes

- The `image_url` column in both `products` and `blog` tables supports full website URLs (e.g., `https://example.com/image.jpg`)
- The `blog` table includes product-related columns (name, price, description, image_url) so products can be visualized in blog posts
- The `cart` table has a foreign key relationship with `products` table - deleting a product will also remove it from the cart
- All tables use UTF8MB4 character set for proper Unicode support

## Next Steps

After setting up the database:
1. Add products through the admin panel (`admin_products.php`)
2. Create blog posts (you may need to add them directly via SQL or create an admin interface)
3. Test user registration and login
4. Test adding products to cart

## Troubleshooting

If you encounter errors:
1. Make sure MySQL is running in XAMPP
2. Check that the database `ironix` exists
3. Verify database credentials in PHP files match your MySQL setup
4. Check file permissions for `create_tables.sql` and `setup_database.php`

