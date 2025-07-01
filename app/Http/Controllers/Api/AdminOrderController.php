<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of all orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by customer name or order ID
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', '%' . $search . '%')
                                  ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Show specific order
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'orderItems.product', 'payment'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->load(['customer', 'orderItems.product']),
            'message' => 'Order status updated successfully'
        ]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->update([
            'payment_status' => $request->payment_status
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->load(['customer', 'orderItems.product']),
            'message' => 'Payment status updated successfully'
        ]);
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Delete order items first
        $order->orderItems()->delete();

        // Delete the order
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    /**
     * Get order statistics
     */
    public function getOrderStats()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingPayments = Order::where('payment_status', 'pending')->count();
        $failedPayments = Order::where('payment_status', 'failed')->count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $averageOrderValue = Order::where('payment_status', 'paid')->avg('total_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'order_status' => [
                    'total' => $totalOrders,
                    'pending' => $pendingOrders,
                    'processing' => $processingOrders,
                    'shipped' => $shippedOrders,
                    'delivered' => $deliveredOrders,
                    'cancelled' => $cancelledOrders
                ],
                'payment_status' => [
                    'paid' => $paidOrders,
                    'pending' => $pendingPayments,
                    'failed' => $failedPayments
                ],
                'revenue' => [
                    'total' => $totalRevenue,
                    'average_order_value' => round($averageOrderValue, 2)
                ]
            ]
        ]);
    }

    /**
     * Export orders
     */
    public function export(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product']);

        // Apply same filters as index
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();

        $csvData = [];
        $csvData[] = ['Order ID', 'Customer Name', 'Customer Email', 'Order Date', 'Status', 'Payment Status', 'Total Amount', 'Items'];

        foreach ($orders as $order) {
            $items = $order->orderItems->map(function($item) {
                return $item->product->name . ' (Qty: ' . $item->quantity . ')';
            })->implode(', ');

            $csvData[] = [
                $order->id,
                $order->customer->name,
                $order->customer->email,
                $order->created_at->format('Y-m-d H:i:s'),
                $order->status,
                $order->payment_status,
                $order->total_amount,
                $items
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $csvData,
            'message' => 'Orders exported successfully'
        ]);
    }
}
