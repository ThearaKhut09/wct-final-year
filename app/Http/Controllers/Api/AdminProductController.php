<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Display all products for admin (including inactive)
     */
    public function index(Request $request)
    {
        $query = Product::with('categories');

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->category . '%');
            });
        }

        // Search by name, description, or SKU
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<=', 5);
                    break;
                case 'in_stock':
                    $query->where('stock', '>', 5);
                    break;
            }
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sku' => 'nullable|string|unique:products,sku',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $productData = $request->only([
            'name', 'description', 'price', 'stock', 'sku',
            'is_active', 'meta_title', 'meta_description', 'weight', 'dimensions'
        ]);

        // Generate SKU if not provided
        if (!$productData['sku']) {
            $productData['sku'] = 'PRD-' . strtoupper(Str::random(8));
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $productData['image'] = $imagePath;
        }

        $product = Product::create($productData);

        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

        $product->load('categories');

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product created successfully'
        ], 201);
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sku' => 'sometimes|nullable|string|unique:products,sku,' . $id,
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $productData = $request->only([
            'name', 'description', 'price', 'stock', 'sku',
            'is_active', 'meta_title', 'meta_description', 'weight', 'dimensions'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $productData['image'] = $imagePath;
        }

        $product->update($productData);

        if ($request->has('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        $product->load('categories');

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product updated successfully'
        ]);
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Detach categories
        $product->categories()->detach();

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'is_active' => !$product->is_active
        ]);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product status updated successfully'
        ]);
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $products = Product::whereIn('id', $request->product_ids);

        switch ($request->action) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = 'Products activated successfully';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = 'Products deactivated successfully';
                break;
            case 'delete':
                // Delete images and detach categories
                foreach ($products->get() as $product) {
                    if ($product->image && Storage::disk('public')->exists($product->image)) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $product->categories()->detach();
                }
                $products->delete();
                $message = 'Products deleted successfully';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get product statistics
     */
    public function getProductStats()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $inactiveProducts = Product::where('is_active', false)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $totalValue = Product::where('is_active', true)->sum(DB::raw('price * stock'));
        $averagePrice = Product::where('is_active', true)->avg('price');

        $categoriesCount = Category::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'inactive_products' => $inactiveProducts,
                'out_of_stock_products' => $outOfStockProducts,
                'low_stock_products' => $lowStockProducts,
                'total_inventory_value' => round($totalValue, 2),
                'average_price' => round($averagePrice, 2),
                'total_categories' => $categoriesCount
            ]
        ]);
    }

    /**
     * Import products from CSV
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_shift($csvData);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $index => $row) {
            $data = array_combine($headers, $row);

            try {
                Product::create([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? '',
                    'price' => $data['price'],
                    'stock' => $data['stock'] ?? 0,
                    'sku' => $data['sku'] ?? 'PRD-' . strtoupper(Str::random(8)),
                    'is_active' => $data['is_active'] ?? true
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'imported' => $imported,
                'errors' => $errors
            ],
            'message' => "Successfully imported {$imported} products"
        ]);
    }
}
