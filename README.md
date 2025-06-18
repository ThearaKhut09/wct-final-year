# E-smooth Online - E-commerce Website

A comprehensive e-commerce website built with Laravel backend and modern frontend technologies, featuring a REST API, admin dashboard, and responsive user interface with dark mode support.

## 🚀 Features

### Frontend Features
- **Modern Responsive Design** - Works perfectly on desktop, tablet, and mobile
- **Dark Mode Support** - Toggle between light and dark themes
- **Product Catalog** - Browse products by category with search functionality
- **Shopping Cart** - Add/remove items, adjust quantities, persistent storage
- **Product Details** - Detailed product pages with image galleries
- **User-Friendly Navigation** - Intuitive menu and breadcrumb navigation

### Backend Features
- **REST API** - Complete API for all CRUD operations
- **Authentication** - Laravel Sanctum token-based authentication
- **Role-Based Access** - Admin and customer roles with appropriate permissions
- **Database Management** - Well-structured MySQL/SQLite database with relationships
- **Order Management** - Complete order processing system
- **Admin Dashboard** - Administrative interface for managing products and orders

### Product Categories
- **Electronics** - Latest gadgets and devices
- **Mobile Phones** - iPhones and smartphones
- **Clothing** - Fashion apparel for all
- **Shoes** - Footwear collection
- **Accessories** - Fashion accessories and jewelry

## 🛠️ Technology Stack

### Backend
- **Framework**: PHP Laravel 12.x
- **Database**: SQLite (configurable to MySQL)
- **Authentication**: Laravel Sanctum
- **API**: RESTful API with JSON responses

### Frontend
- **Template Engine**: Blade templates
- **Styling**: Custom CSS with CSS variables
- **JavaScript**: Vanilla JS for interactivity
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Inter)

## 📦 Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js (optional, for frontend assets)
- SQLite or MySQL

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd wct-12
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```

The application will be available at `http://localhost:8000`

## 🔧 Configuration

### Database Configuration
The application is configured to use SQLite by default. To use MySQL:

1. Update `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=esmooth_online
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

2. Create the database:
   ```sql
   CREATE DATABASE esmooth_online;
   ```

3. Run migrations:
   ```bash
   php artisan migrate:fresh --seed
   ```

### Default Admin Account
- **Email**: admin@esmooth.com
- **Password**: admin123
- **Role**: Admin

### Default Customer Account
- **Email**: customer@example.com
- **Password**: customer123
- **Role**: Customer

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
Use Bearer token authentication for protected endpoints:
```
Authorization: Bearer <token>
```

### Public Endpoints

#### Products
- `GET /products` - Get all products (with pagination, search, filtering)
- `GET /products/{id}` - Get specific product

#### Categories
- `GET /categories` - Get all categories
- `GET /categories/{id}` - Get specific category with products

#### Authentication
- `POST /register` - Register new user
- `POST /login` - User login

### Protected Endpoints (Require Authentication)

#### User Profile
- `GET /profile` - Get user profile
- `PUT /profile` - Update user profile
- `POST /logout` - Logout user

#### Orders
- `GET /orders` - Get user orders (customers see only their orders)
- `POST /orders` - Create new order
- `GET /orders/{id}` - Get specific order
- `PUT /orders/{id}` - Update order (admin only)
- `DELETE /orders/{id}` - Delete order (admin only)

### Admin Only Endpoints

#### Product Management
- `POST /products` - Create new product
- `PUT /products/{id}` - Update product
- `DELETE /products/{id}` - Delete product

#### Category Management
- `POST /categories` - Create new category
- `PUT /categories/{id}` - Update category
- `DELETE /categories/{id}` - Delete category

### API Response Format
```json
{
    "success": true,
    "data": {
        // Response data
    },
    "message": "Success message"
}
```

### Error Response Format
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## 🗄️ Database Schema

### Users Table
- id, name, email, password, role, email_verified_at, timestamps

### Customers Table
- id, name, email, phone, address, city, state, postal_code, country, user_id, timestamps

### Categories Table
- id, name, description, image, timestamps

### Products Table
- id, name, description, price, stock, image, images (JSON), is_active, sku, timestamps

### Orders Table
- id, order_number, customer_id, total_amount, tax_amount, shipping_amount, payment_status, order_status, shipping_address, billing_address, notes, order_date, timestamps

### Order Items Table
- id, order_id, product_id, quantity, price, timestamps

### Category Product Table (Pivot)
- id, category_id, product_id, timestamps

## 🎨 Frontend Features

### Responsive Design
- Mobile-first approach
- Flexible grid layouts
- Touch-friendly interface
- Optimized for all screen sizes

### Dark Mode
- System preference detection
- Manual toggle
- Persistent user preference
- Smooth transitions

### Shopping Cart
- Local storage persistence
- Real-time updates
- Quantity adjustments
- Price calculations

### Search & Filtering
- Real-time search
- Category filtering
- Price range filtering
- Pagination support

## 🔐 Security Features

- **CSRF Protection** - Laravel's built-in CSRF protection
- **SQL Injection Prevention** - Eloquent ORM with parameter binding
- **Authentication** - Secure token-based authentication
- **Authorization** - Role-based access control
- **Input Validation** - Server-side validation for all inputs
- **XSS Protection** - Blade template escaping

## 🚀 Deployment

### Production Environment

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Database Optimization**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Web Server Configuration**
   - Point document root to `public/` directory
   - Enable URL rewriting
   - Configure HTTPS

## 🧪 Testing

### API Testing Examples

#### Register User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'
```

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'
```

#### Get Products
```bash
curl -X GET http://localhost:8000/api/products
```

#### Create Order (Authenticated)
```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"items":[{"product_id":1,"quantity":2}],"shipping_address":"123 Main St, City, State"}'
```

## 📱 Frontend Pages

### Public Pages
- **Home** (`/`) - Featured products and categories
- **Products** (`/products`) - Product catalog with search and filtering
- **Product Detail** (`/product/{id}`) - Individual product information
- **About** (`/about`) - Company information and team
- **Contact** (`/contact`) - Contact form and business information
- **Cart** (`/cart`) - Shopping cart management

### Admin Pages
- **Dashboard** (`/admin`) - Admin overview and statistics

## 🎯 Future Enhancements

### Planned Features
- User authentication frontend (login/register pages)
- Wishlist functionality
- Product reviews and ratings
- Advanced search filters
- Email notifications
- Payment gateway integration
- Inventory management
- Sales analytics
- Multi-language support
- Advanced admin dashboard

### Technical Improvements
- Image upload and management
- Caching layer (Redis)
- Queue system for email notifications
- Full-text search (Elasticsearch)
- CDN integration
- Performance optimization
- Automated testing suite

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

## 👥 Team

- **Frontend Development** - Modern responsive design with dark mode
- **Backend Development** - Laravel API with authentication
- **Database Design** - Optimized schema with relationships
- **UI/UX Design** - User-friendly interface design

## 📞 Support

For support, email support@esmooth.com or visit our contact page.

---

**E-smooth Online** - Making premium shopping accessible to everyone! 🛍️
