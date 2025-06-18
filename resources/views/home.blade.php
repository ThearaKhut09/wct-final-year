@extends('layouts.app')

@section('title', 'Home - E-smooth Online')

@section('content')
<style>
    .hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .hero p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .category-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        text-decoration: none;
        color: var(--text-primary);
    }

    [data-theme="dark"] .category-card {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .category-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .category-card h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .category-card p {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
    }

    [data-theme="dark"] .product-card {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: var(--light-color);
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-categories {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .product-category {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .product-name {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .product-actions {
        display: flex;
        gap: 1rem;
    }

    .features {
        background: var(--light-color);
        padding: 4rem 2rem;
        margin-top: 4rem;
    }

    [data-theme="dark"] .features {
        background: var(--dark-color);
    }

    .features-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .feature {
        text-align: center;
        padding: 2rem;
    }

    .feature-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .feature h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .feature p {
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1rem;
        }

        .hero-buttons {
            flex-direction: column;
            align-items: center;
        }

        .section {
            padding: 2rem 1rem;
        }

        .section-title {
            font-size: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to E-smooth Online</h1>
        <p>Discover premium products at unbeatable prices. From cutting-edge electronics to stylish fashion, we have everything you need.</p>
        <div class="hero-buttons">
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Shop Now
            </a>
            <a href="{{ route('about') }}" class="btn btn-outline">
                <i class="fas fa-info-circle"></i> Learn More
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section">
    <h2 class="section-title">Shop by Category</h2>
    <div class="categories-grid">
        @foreach($categories as $category)
        <a href="{{ route('products', ['category' => $category->id]) }}" class="category-card">
            <div class="category-icon">
                @switch($category->name)
                    @case('Electronics')
                        <i class="fas fa-laptop"></i>
                        @break
                    @case('Mobile Phones')
                        <i class="fas fa-mobile-alt"></i>
                        @break
                    @case('Clothing')
                        <i class="fas fa-tshirt"></i>
                        @break
                    @case('Shoes')
                        <i class="fas fa-shoe-prints"></i>
                        @break
                    @case('Accessories')
                        <i class="fas fa-gem"></i>
                        @break
                    @default
                        <i class="fas fa-tag"></i>
                @endswitch
            </div>
            <h3>{{ $category->name }}</h3>
            <p>{{ $category->products_count }} products</p>
        </a>
        @endforeach
    </div>
</section>

<!-- Featured Products Section -->
<section class="section">
    <h2 class="section-title">Featured Products</h2>
    <div class="products-grid">
        @foreach($featuredProducts as $product)
        <div class="product-card">
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
            <div class="product-info">
                <div class="product-categories">
                    @foreach($product->categories as $category)
                        <span class="product-category">{{ $category->name }}</span>
                    @endforeach
                </div>
                <h3 class="product-name">{{ $product->name }}</h3>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
                <div class="product-actions">
                    <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <button class="btn btn-primary" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="text-align: center; margin-top: 3rem;">
        <a href="{{ route('products') }}" class="btn btn-primary">
            <i class="fas fa-arrow-right"></i> View All Products
        </a>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="features-grid">
        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h3>Free Shipping</h3>
            <p>Free shipping on orders over $50. Fast and reliable delivery worldwide.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Secure Payment</h3>
            <p>Your payments are secure with our encrypted payment system.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-undo"></i>
            </div>
            <h3>Easy Returns</h3>
            <p>30-day return policy. Return items hassle-free if not satisfied.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h3>24/7 Support</h3>
            <p>Our customer support team is available 24/7 to assist you.</p>
        </div>
    </div>
</section>
@endsection
