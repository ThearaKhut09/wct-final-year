<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard statistics
     */
    public function getStats()
    {
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        // Recent stats (last 30 days)
        $recentOrders = Order::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $recentRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('total_amount');

        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();

        // Monthly revenue for chart
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'month')
            ->get();

        // Top selling products
        $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_customers' => $totalCustomers,
                    'total_products' => $totalProducts,
                    'total_orders' => $totalOrders,
                    'total_revenue' => $totalRevenue,
                    'recent_orders' => $recentOrders,
                    'recent_revenue' => $recentRevenue,
                    'low_stock_products' => $lowStockProducts,
                    'out_of_stock_products' => $outOfStockProducts
                ],
                'monthly_revenue' => $monthlyRevenue,
                'top_products' => $topProducts
            ]
        ]);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities()
    {
        $recentOrders = Order::with(['customer', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentCustomers = Customer::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'recent_orders' => $recentOrders,
                'recent_customers' => $recentCustomers
            ]
        ]);
    }

    /**
     * Get system health status
     */
    public function getSystemHealth()
    {
        $diskSpace = disk_free_space('/') / disk_total_space('/') * 100;
        $databaseConnection = true;

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $databaseConnection = false;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'disk_space_available' => round($diskSpace, 2),
                'database_connection' => $databaseConnection,
                'last_backup' => null, // You can implement backup functionality
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version()
            ]
        ]);
    }
}
