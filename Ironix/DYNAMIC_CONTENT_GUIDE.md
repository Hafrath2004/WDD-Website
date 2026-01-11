# Dynamic Content Guide - Ironix Hardware Shop

## Overview
All product and blog content is dynamically loaded from the database. When you update URLs or product information in the database, the changes automatically appear on all related pages without needing to modify code.

## How It Works

### Products Table
The `products` table stores:
- `id` - Unique product identifier
- `name` - Product name
- `description` - Product description
- `price` - Product price in LKR
- `image_url` - Product image URL (supports full website URLs)

### Blog Table
The `blog` table stores:
- `id` - Unique blog post identifier
- `title` - Blog post title
- `content` - Blog post content
- `name` - Product name (for featured product)
- `description` - Product description
- `price` - Product price in LKR
- `image_url` - Product image URL (supports full website URLs)
- `author` - Blog post author
- `published_at` - Publication date

## Pages That Automatically Update

### 1. Home Page (`index.php`)
- **Data Source**: `get_products.php` API endpoint
- **Updates**: Automatically fetches all products from database
- **Image URLs**: Pulled directly from `products.image_url` column
- **How to Update**: Change `image_url` in `products` table → Refresh home page → Changes appear immediately

### 2. Products Page (`products.php`)
- **Data Source**: Direct database query from `products` table
- **Updates**: Automatically displays all products from database
- **Image URLs**: Pulled directly from `products.image_url` column
- **How to Update**: Change `image_url` in `products` table → Refresh products page → Changes appear immediately

### 3. Blog Page (`blog.php`)
- **Data Source**: Direct database query from `blog` table
- **Updates**: Automatically displays all blog posts with product information
- **Image URLs**: Pulled directly from `blog.image_url` column
- **How to Update**: Change `image_url` in `blog` table → Refresh blog page → Changes appear immediately

### 4. Search Results (`search_results.php`)
- **Data Source**: Direct database query from `products` table
- **Updates**: Automatically searches products by name/description
- **Image URLs**: Pulled directly from `products.image_url` column

### 5. Cart Page (`cart.php`)
- **Data Source**: Direct database query joining `cart` and `products` tables
- **Updates**: Automatically displays cart items with product images
- **Image URLs**: Pulled from `products.image_url` via JOIN

## How to Update Image URLs

### Method 1: Using phpMyAdmin
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select the `ironix` database
3. Click on the table you want to update (`products` or `blog`)
4. Click "Browse" to see all records
5. Click "Edit" on the record you want to change
6. Update the `image_url` field with the new URL
7. Click "Go" to save
8. Refresh the website page to see the changes

### Method 2: Using SQL
```sql
-- Update a product image URL
UPDATE products 
SET image_url = 'https://new-image-url.com/image.jpg' 
WHERE id = 1;

-- Update a blog post image URL
UPDATE blog 
SET image_url = 'https://new-image-url.com/image.jpg' 
WHERE id = 1;
```

### Method 3: Using Admin Panel
1. Log in to the admin panel
2. Navigate to Products or Blog section
3. Edit the product/blog post
4. Update the image URL field
5. Save changes

## Image URL Format

The `image_url` column supports:
- **Full website URLs**: `https://example.com/image.jpg`
- **Relative paths**: `/images/product.jpg` (if images are on your server)
- **External URLs**: Any valid image URL from the internet

### Examples:
```
https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500
https://example.com/products/hammer.jpg
/images/products/drill.jpg
```

## Automatic Updates Flow

```
Database Change (image_url updated)
    ↓
PHP Query Executes (SELECT * FROM products/blog)
    ↓
Image URL Retrieved from Database
    ↓
Displayed on Page (Home/Blog/Products)
    ↓
User Sees Updated Image (No code changes needed!)
```

## Testing Automatic Updates

1. **Test Product Image Update**:
   - Go to phpMyAdmin
   - Find a product in the `products` table
   - Change its `image_url` to a different URL
   - Refresh `index.php` or `products.php`
   - The new image should appear immediately

2. **Test Blog Image Update**:
   - Go to phpMyAdmin
   - Find a blog post in the `blog` table
   - Change its `image_url` to a different URL
   - Refresh `blog.php`
   - The new image should appear immediately

## Important Notes

- **No Caching**: Pages fetch fresh data from the database on each load
- **Real-time Updates**: Changes appear immediately after database update
- **No Code Changes Required**: All image URLs are stored in the database
- **Consistent Across Pages**: Same database = same images everywhere
- **URL Validation**: Make sure image URLs are accessible and valid

## Troubleshooting

### Images Not Updating?
1. Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
2. Verify database connection is working
3. Check that the URL in the database is correct
4. Ensure the image URL is accessible (test in browser)

### Images Not Displaying?
1. Check if the image URL is valid and accessible
2. Verify the URL doesn't have broken links
3. Check browser console for 404 errors
4. Ensure the `image_url` column has data

### Database Connection Issues?
1. Verify MySQL is running in XAMPP
2. Check database credentials in PHP files
3. Ensure the `ironix` database exists
4. Run `setup_database.php` if tables are missing

## Summary

✅ **All product images** are pulled from `products.image_url`  
✅ **All blog images** are pulled from `blog.image_url`  
✅ **Changes in database** automatically reflect on all pages  
✅ **No code modification** needed to update images  
✅ **Real-time updates** - just refresh the page after database change

