@echo off
echo Fixing Laravel cache issues...

echo Clearing application cache...
php artisan cache:clear

echo Clearing configuration cache...
php artisan config:clear

echo Clearing route cache...
php artisan route:clear

echo Clearing view cache...
php artisan view:clear

echo Removing any temporary files...
if exist "bootstrap\cache\*.tmp" del /f /q "bootstrap\cache\*.tmp"

echo Regenerating caches...
php artisan config:cache
php artisan route:cache

echo Dumping autoload...
composer dump-autoload

echo Cache fix completed!
pause
