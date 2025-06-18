<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('categories')->active()->inStock()->take(8)->get();
        $categories = Category::withCount('products')->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }

    public function products(Request $request)
    {
        $query = Product::with('categories')->active();
        
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('id', $request->category);
            });
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('products', compact('products', 'categories'));
    }

    public function product($id)
    {
        $product = Product::with('categories')->findOrFail($id);
        $relatedProducts = Product::with('categories')
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();
            
        return view('product-detail', compact('product', 'relatedProducts'));
    }
    
    public function about()
    {
        return view('about');
    }
    
    public function contact()
    {
        return view('contact');
    }
    
    public function cart()
    {
        return view('cart');
    }
}
