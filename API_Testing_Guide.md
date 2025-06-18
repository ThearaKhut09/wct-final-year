# E-smooth Online API Testing Guide with Postman

## Overview
This guide will help you test all the APIs of the E-smooth Online e-commerce platform using Postman. The collection includes comprehensive tests for Authentication, Products, Categories, and Orders.

## Setup Instructions

### 1. Import Collection and Environment

1. **Import the Collection:**
   - Open Postman
   - Click "Import" button
   - Select `E-smooth_Online_API_Collection.postman_collection.json`
   - Click "Import"

2. **Import the Environment:**
   - Click "Import" button again
   - Select `E-smooth_Online_Environment.postman_environment.json`
   - Click "Import"

3. **Select Environment:**
   - In the top-right corner of Postman, select "E-smooth Online Environment"

### 2. Configure Base URL

The default base URL is set to `http://localhost/wct-12/public`. If your Laravel app runs on a different URL, update the `base_url` variable in your environment:

- Common alternatives:
  - `http://localhost:8000` (if using `php artisan serve`)
  - `http://127.0.0.1/wct-12/public`
  - `http://your-domain.com/public`

## Testing Workflow

### Step 1: Authentication Testing

#### 1.1 Register Users
1. **Register Regular User:**
   - Run "Register User" request
   - Creates a customer account with email: `john@example.com`

2. **Register Admin User:**
   - Run "Register Admin" request
   - Creates an admin account with email: `admin@example.com`

#### 1.2 Login and Get Tokens
1. **Login User:**
   - Run "Login User" request
   - Automatically saves `access_token` to environment variables
   - Token is used for authenticated user requests

2. **Login Admin:**
   - Run "Login Admin" request
   - Automatically saves `admin_token` to environment variables
   - Token is used for admin-only requests

#### 1.3 Test Profile Operations
1. **Get Profile:**
   - Run "Get Profile" request (requires user login)
   - Returns current user information

2. **Update Profile:**
   - Run "Update Profile" request
   - Updates user name and email

3. **Logout:**
   - Run "Logout" request
   - Invalidates the current token

### Step 2: Categories Testing

#### 2.1 Public Category Operations
1. **Get All Categories:**
   - Run "Get All Categories" request
   - No authentication required
   - Returns list of all categories

2. **Get Category by ID:**
   - Run "Get Category by ID" request
   - Replace `1` with actual category ID
   - Returns specific category details

#### 2.2 Admin Category Operations (Requires Admin Token)
1. **Create Category:**
   - Run "Create Category (Admin)" request
   - Automatically saves `category_id` to environment
   - Creates new category with sample data

2. **Update Category:**
   - Run "Update Category (Admin)" request
   - Uses saved `category_id` from previous step
   - Updates category information

3. **Delete Category:**
   - Run "Delete Category (Admin)" request
   - Deletes the created category

### Step 3: Products Testing

#### 3.1 Public Product Operations
1. **Get All Products:**
   - Run "Get All Products" request
   - No authentication required
   - Returns all products

2. **Get Products with Pagination:**
   - Run "Get Products with Pagination" request
   - Demonstrates pagination functionality
   - Modify `page` and `per_page` parameters as needed

3. **Get Product by ID:**
   - Run "Get Product by ID" request
   - Replace `1` with actual product ID
   - Returns specific product details

#### 3.2 Admin Product Operations (Requires Admin Token)
1. **Create Product:**
   - Run "Create Product (Admin)" request
   - Automatically saves `product_id` to environment
   - Creates iPhone 15 Pro sample product

2. **Update Product:**
   - Run "Update Product (Admin)" request
   - Uses saved `product_id` from previous step
   - Updates product information

3. **Delete Product:**
   - Run "Delete Product (Admin)" request
   - Deletes the created product

### Step 4: Orders Testing (Requires User Authentication)

#### 4.1 Order Operations
1. **Create Order:**
   - Run "Create Order" request
   - Automatically saves `order_id` to environment
   - Creates order with multiple items
   - Includes shipping and billing addresses

2. **Get All Orders:**
   - Run "Get All Orders" request
   - Returns user's orders

3. **Get Order by ID:**
   - Run "Get Order by ID" request
   - Uses saved `order_id` from create step
   - Returns specific order details

4. **Update Order:**
   - Run "Update Order" request
   - Updates order status and notes

5. **Delete Order:**
   - Run "Delete Order" request
   - Deletes the created order

## Expected API Responses

### Authentication Responses
```json
// Login Success
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJI...",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "customer"
    }
}
```

### Product Response
```json
{
    "data": {
        "id": 1,
        "name": "iPhone 15 Pro",
        "description": "Latest iPhone with A17 Pro chip",
        "price": "999.99",
        "stock": 50,
        "sku": "IPH15PRO001",
        "image": "iphone15pro.jpg",
        "images": ["iphone15pro_1.jpg", "iphone15pro_2.jpg"],
        "is_active": true,
        "created_at": "2025-06-18T10:00:00.000000Z",
        "updated_at": "2025-06-18T10:00:00.000000Z"
    }
}
```

### Order Response
```json
{
    "data": {
        "id": 1,
        "order_number": "ORD-2025-001",
        "customer_id": 1,
        "total_amount": "2499.97",
        "tax_amount": "199.98",
        "shipping_amount": "9.99",
        "payment_status": "pending",
        "order_status": "pending",
        "shipping_address": {...},
        "billing_address": {...},
        "items": [...]
    }
}
```

## Error Handling Testing

### Common Error Scenarios to Test:

1. **Authentication Errors:**
   - Try accessing protected routes without token
   - Use expired/invalid tokens
   - Access admin routes with regular user token

2. **Validation Errors:**
   - Send incomplete data in POST requests
   - Send invalid data types
   - Test required field validations

3. **Authorization Errors:**
   - Regular user trying to access admin endpoints
   - User trying to access other user's orders

4. **Not Found Errors:**
   - Request non-existent product/category/order IDs

## Testing Tips

1. **Sequential Testing:**
   - Follow the order: Auth → Categories → Products → Orders
   - Some tests depend on previous test results

2. **Environment Variables:**
   - The collection automatically saves important IDs and tokens
   - Check environment variables if requests fail

3. **Data Cleanup:**
   - Delete created test data after testing
   - Or reset your database between test runs

4. **Monitoring:**
   - Check Laravel logs for detailed error information
   - Monitor database changes during testing

## API Endpoints Summary

### Public Endpoints (No Authentication Required)
- `GET /api/products` - Get all products
- `GET /api/products/{id}` - Get product by ID
- `GET /api/categories` - Get all categories
- `GET /api/categories/{id}` - Get category by ID
- `POST /api/register` - Register user
- `POST /api/login` - Login user

### Authenticated Endpoints (Requires Token)
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile
- `POST /api/logout` - Logout user
- `GET /api/orders` - Get user orders
- `POST /api/orders` - Create order
- `GET /api/orders/{id}` - Get order by ID
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order

### Admin Only Endpoints (Requires Admin Token)
- `POST /api/products` - Create product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product
- `POST /api/categories` - Create category
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category

## Troubleshooting

### Common Issues:

1. **CORS Errors:**
   - Ensure CORS is properly configured in Laravel
   - Check `config/cors.php` settings

2. **Token Issues:**
   - Make sure Laravel Sanctum is properly installed
   - Check token expiration settings

3. **Database Connection:**
   - Verify database connection in Laravel
   - Run migrations if needed

4. **Base URL Issues:**
   - Update the `base_url` environment variable
   - Ensure Laravel app is running

For any issues, check the Laravel logs in `storage/logs/laravel.log` for detailed error information.
