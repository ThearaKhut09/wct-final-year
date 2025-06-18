# E-smooth Online - E-commerce Website

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview
This is a full-featured e-commerce website called "E-smooth online" built with Laravel backend and modern frontend technologies.

## Technology Stack
- **Backend**: PHP Laravel with REST API
- **Database**: MySQL with phpMyAdmin
- **Frontend**: HTML, CSS, JavaScript
- **Features**: Authentication, Product Management, Order Processing, Admin Dashboard, Dark Mode

## Database Design
The project uses the following entities:
- Products (name, category, price, stock, description, image)
- Customers (name, email, phone, address)
- Orders (order date, total amount, payment status)
- OrderItems (quantity, price per item)
- Categories
- Users/Admin accounts

## API Endpoints
- GET /api/products - Get all products
- POST /api/products - Create new product (requires auth)
- PUT /api/products/{id} - Update product
- DELETE /api/products/{id} - Delete product
- GET /api/orders - Get customer orders
- POST /api/orders - Create new order (requires auth)

## Code Style Guidelines
- Follow Laravel coding standards
- Use proper MVC architecture
- Implement proper error handling
- Use Laravel's built-in authentication
- Create responsive and modern UI
- Implement proper API versioning
- Use meaningful variable and function names
