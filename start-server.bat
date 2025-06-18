@echo off
echo Starting E-smooth Online Server...
echo.

echo [1/5] Clearing Laravel caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo [2/5] Running database migrations...
php artisan migrate --force

echo.
echo [3/5] Seeding database...
php artisan db:seed --force

echo.
echo [4/5] Generating application key...
php artisan key:generate --force

echo.
echo [5/5] Starting development server...
echo.
echo E-smooth Online is starting at: http://localhost:8000
echo Test connection at: http://localhost:8000/connection-test.html
echo API Test page at: http://localhost:8000/api-test.html
echo.
echo Press Ctrl+C to stop the server
echo.

php artisan serve --host=localhost --port=8000
