<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminSettingsController extends Controller
{
    /**
     * Get system settings
     */
    public function getSettings()
    {
        $settings = [
            'site_name' => config('app.name', 'E-smooth Online'),
            'site_description' => 'Your one-stop e-commerce solution',
            'site_email' => config('mail.from.address', 'admin@esmooth.com'),
            'currency' => 'USD',
            'currency_symbol' => '$',
            'timezone' => config('app.timezone', 'UTC'),
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'items_per_page' => 15,
            'max_upload_size' => '2MB',
            'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'enable_reviews' => true,
            'enable_wishlist' => true,
            'enable_compare' => true,
            'low_stock_threshold' => 5,
            'tax_rate' => 0.0,
            'shipping_cost' => 0.0,
            'free_shipping_threshold' => 100.0,
            'maintenance_mode' => false,
            'smtp_settings' => [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption')
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_email' => 'nullable|email',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'timezone' => 'nullable|string',
            'items_per_page' => 'nullable|integer|min:5|max:100',
            'enable_reviews' => 'boolean',
            'enable_wishlist' => 'boolean',
            'enable_compare' => 'boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'maintenance_mode' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Here you would typically save settings to a database table or config files
        // For now, we'll cache the settings
        Cache::put('admin_settings', $request->all(), now()->addHours(24));

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_connection' => $this->checkDatabaseConnection(),
            'storage_info' => $this->getStorageInfo(),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'app_url' => config('app.url'),
            'extensions' => $this->getPhpExtensions()
        ];

        return response()->json([
            'success' => true,
            'data' => $info
        ]);
    }

    /**
     * Create database backup
     */
    public function createBackup()
    {
        try {
            $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
            $backupPath = storage_path('app/backups/' . $filename);

            // Create backups directory if it doesn't exist
            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            // Simple backup command (you might want to use a more sophisticated backup package)
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $backupPath
            );

            exec($command, $output, $return_var);

            if ($return_var === 0) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'filename' => $filename,
                        'size' => filesize($backupPath),
                        'created_at' => now()->toDateTimeString()
                    ],
                    'message' => 'Backup created successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create backup'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of available backups
     */
    public function getBackups()
    {
        $backups = [];
        $files = Storage::disk('local')->files('backups');

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => Storage::disk('local')->size($file),
                    'created_at' => date('Y-m-d H:i:s', Storage::disk('local')->lastModified($file))
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $backups
        ]);
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found'
            ], 404);
        }

        return response()->download(storage_path('app/' . $path));
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found'
            ], 404);
        }

        Storage::disk('local')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully'
        ]);
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'Connected';
        } catch (\Exception $e) {
            return 'Failed: ' . $e->getMessage();
        }
    }

    /**
     * Get storage information
     */
    private function getStorageInfo()
    {
        $totalSpace = disk_total_space('/');
        $freeSpace = disk_free_space('/');
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total_space' => $this->formatBytes($totalSpace),
            'used_space' => $this->formatBytes($usedSpace),
            'free_space' => $this->formatBytes($freeSpace),
            'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2)
        ];
    }

    /**
     * Get PHP extensions
     */
    private function getPhpExtensions()
    {
        $required = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
        $extensions = [];

        foreach ($required as $ext) {
            $extensions[$ext] = extension_loaded($ext);
        }

        return $extensions;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
