# E-smooth Online Admin - Troubleshooting Guide

## Common Issues and Solutions

### 1. "API not working" or "Connection refused"

**Problem**: The admin interface can't connect to the API
**Solutions**:
1. Make sure Laravel development server is running:
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
   ```
2. Check if you're accessing the correct URL:
   - Admin Dashboard: `http://127.0.0.1:8000/admin.html`
   - API Test Page: `http://127.0.0.1:8000/admin-test.html`

### 2. "Unauthorized" or "Admin access required"

**Problem**: Login fails or shows unauthorized error
**Solutions**:
1. Make sure admin user exists:
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```
2. Check admin credentials:
   - Email: `admin@esmooth.com`
   - Password: `admin123`
3. Verify user role in database:
   ```sql
   SELECT * FROM users WHERE email = 'admin@esmooth.com';
   ```

### 3. Database Connection Issues

**Problem**: "Database connection failed"
**Solutions**:
1. Check `.env` file database settings:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=wct12
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Make sure MySQL/XAMPP is running
3. Create database if it doesn't exist:
   ```sql
   CREATE DATABASE wct12;
   ```

### 4. CORS Issues

**Problem**: Browser blocks API requests
**Solutions**:
1. Make sure CORS middleware is enabled
2. Access admin panel from same domain as API
3. Use development server URL: `http://127.0.0.1:8000`

### 5. Missing Routes or Controllers

**Problem**: "Route not found" or "Controller not found"
**Solutions**:
1. Clear route cache:
   ```bash
   php artisan route:clear
   php artisan config:clear
   php artisan cache:clear
   ```
2. Check if all controllers exist in `app/Http/Controllers/Api/`

### 6. Token Issues

**Problem**: Authentication token expires or invalid
**Solutions**:
1. Login again to get new token
2. Clear browser localStorage
3. Check Sanctum configuration

## Quick Testing Steps

### 1. Basic System Check
```bash
# Check if server is running
curl http://127.0.0.1:8000

# Test login API
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@esmooth.com","password":"admin123"}'
```

### 2. Database Check
```bash
# Run migrations
php artisan migrate

# Seed admin user
php artisan db:seed --class=AdminSeeder

# Check database tables
php artisan tinker
>>> \App\Models\User::where('role', 'admin')->get();
```

### 3. Frontend Check
1. Open `http://127.0.0.1:8000/admin-test.html`
2. Click "Test Admin Login"
3. Check browser console for errors
4. Verify system status shows green checkmarks

## File Locations

- **Admin Dashboard**: `public/admin.html`
- **API Test Page**: `public/admin-test.html`
- **API Routes**: `routes/api.php`
- **Controllers**: `app/Http/Controllers/Api/`
- **Middleware**: `app/Http/Middleware/`
- **Models**: `app/Models/`

## Default Credentials

- **Admin Email**: admin@esmooth.com
- **Admin Password**: admin123
- **Customer Email**: customer@esmooth.com
- **Customer Password**: customer123

## Debug Mode

Enable debug mode for detailed error messages:
```env
APP_DEBUG=true
APP_ENV=local
```

## Still Having Issues?

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser developer tools console
3. Verify all migrations are run: `php artisan migrate:status`
4. Test API endpoints individually using API test page
