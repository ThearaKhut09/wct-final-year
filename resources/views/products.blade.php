@extends('layouts.app')

@section('title', 'Products - E-smooth Online')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 3rem 2rem;
        text-align: center;
    }

    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .page-header p {
        font-size: 1.125rem;
        opacity: 0.9;
    }

    .products-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }    .filters-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        align-items: center;
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: var(--box-shadow);
    }

    [data-theme="dark"] .filters-bar {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .advanced-filters {
        display: none;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .advanced-filters.active {
        display: grid;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .filter-select {
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 0.375rem;
        background: white;
        color: var(--text-primary);
    }

    [data-theme="dark"] .filter-select {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .price-range-container {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .price-input {
        width: 80px;
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 0.375rem;
        text-align: center;
    }

    .toggle-filters {
        background: none;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .toggle-filters:hover {
        background: var(--primary-color);
        color: white;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .results-count {
        color: var(--text-secondary);
    }    .sort-options {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .view-options {
        display: flex;
        gap: 0.25rem;
        border: 1px solid var(--border-color);
        border-radius: 0.375rem;
        overflow: hidden;
    }

    .view-btn {
        background: white;
        border: none;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        color: var(--text-secondary);
        transition: var(--transition);
    }

    [data-theme="dark"] .view-btn {
        background: var(--dark-color);
    }

    .view-btn:hover,
    .view-btn.active {
        background: var(--primary-color);
        color: white;
    }

    .products-grid.list-view {
        display: block;
    }

    .products-grid.list-view .product-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
    }

    .products-grid.list-view .product-image {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
    }

    .products-grid.list-view .product-content {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .filter-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .filter-tag {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tag button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-tag button:hover {
        background: rgba(255,255,255,0.2);
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 1rem;
        background: white;
        color: var(--text-primary);
        transition: var(--transition);
    }

    [data-theme="dark"] .search-input {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .category-filter {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        background: white;
        color: var(--text-primary);
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
    }

    [data-theme="dark"] .category-filter {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .category-filter:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .results-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: var(--box-shadow);
    }

    [data-theme="dark"] .results-info {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
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
        flex-wrap: wrap;
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
        line-height: 1.4;
    }

    .product-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 1rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .product-stock {
        font-size: 0.875rem;
        color: var(--success-color);
        margin-bottom: 1rem;
    }

    .product-stock.low {
        color: var(--warning-color);
    }

    .product-stock.out {
        color: var(--danger-color);
    }

    .product-actions {
        display: flex;
        gap: 1rem;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
    }

    .pagination a,
    .pagination span {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        text-decoration: none;
        color: var(--text-primary);
        transition: var(--transition);
    }

    .pagination a:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .pagination .active span {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .no-products {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .no-products i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }

        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            min-width: auto;
        }

        .results-info {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .products-section {
            padding: 1rem;
        }
    }
</style>

<!-- Page Header -->
<section class="page-header">
    <h1>Our Products</h1>
    <p>Discover our extensive collection of premium products</p>
</section>

<!-- Products Section -->
<section class="products-section">    <!-- Filters -->
    <div class="filters-bar">
        <form method="GET" action="{{ route('products') }}" class="search-box">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <i class="fas fa-search search-icon"></i>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Search products..." 
                value="{{ request('search') }}"
                id="searchInput"
            >
        </form>
        
        <form method="GET" action="{{ route('products') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <select name="category" class="category-filter" onchange="this.form.submit()" id="categoryFilter">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <button class="toggle-filters" onclick="toggleAdvancedFilters()">
            <i class="fas fa-filter"></i> Advanced Filters
        </button>

        <!-- Advanced Filters -->
        <div class="advanced-filters" id="advancedFilters">
            <div class="filter-group">
                <label>Price Range</label>
                <div class="price-range-container">
                    <input type="number" class="price-input" placeholder="Min" id="minPrice">
                    <span>-</span>
                    <input type="number" class="price-input" placeholder="Max" id="maxPrice">
                </div>
            </div>

            <div class="filter-group">
                <label>Sort By</label>
                <select class="filter-select" id="sortBy" onchange="applySorting()">
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="rating_desc">Highest Rated</option>
                    <option value="newest">Newest First</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Availability</label>
                <select class="filter-select" id="availability">
                    <option value="all">All Products</option>
                    <option value="in_stock">In Stock Only</option>
                    <option value="on_sale">On Sale</option>
                    <option value="free_shipping">Free Shipping</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Rating</label>
                <select class="filter-select" id="ratingFilter">
                    <option value="all">All Ratings</option>
                    <option value="4">4+ Stars</option>
                    <option value="3">3+ Stars</option>
                    <option value="2">2+ Stars</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Brand</label>
                <select class="filter-select" id="brandFilter">
                    <option value="all">All Brands</option>
                    <option value="apple">Apple</option>
                    <option value="samsung">Samsung</option>
                    <option value="nike">Nike</option>
                    <option value="adidas">Adidas</option>
                </select>
            </div>

            <div class="filter-group" style="align-self: end;">
                <button class="btn btn-primary" onclick="applyAdvancedFilters()">Apply Filters</button>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="results-header">
        <div class="results-count" id="resultsCount">
            Showing {{ $products->count() }} of {{ $products->total() }} products
        </div>
        <div class="view-options">
            <button class="view-btn active" onclick="changeView('grid')" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" onclick="changeView('list')" title="List View">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
        <div>
            <strong>{{ $products->total() }}</strong> products found
            @if(request('search'))
                for "<strong>{{ request('search') }}</strong>"
            @endif
            @if(request('category'))
                @php
                    $categoryName = $categories->find(request('category'))->name ?? '';
                @endphp
                in <strong>{{ $categoryName }}</strong>
            @endif
        </div>
        <div>
            Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
        </div>
    </div>

    @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-info">
                    <div class="product-categories">
                        @foreach($product->categories as $category)
                            <span class="product-category">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-description">{{ $product->description }}</p>
                    <div class="product-price">${{ number_format($product->price, 2) }}</div>
                    <div class="product-stock {{ $product->stock <= 5 ? ($product->stock == 0 ? 'out' : 'low') : '' }}">
                        @if($product->stock > 0)
                            <i class="fas fa-check-circle"></i> 
                            {{ $product->stock }} in stock
                            @if($product->stock <= 5)
                                (Low stock!)
                            @endif
                        @else
                            <i class="fas fa-times-circle"></i> Out of stock
                        @endif
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if($product->stock > 0)
                            <button class="btn btn-primary" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-times"></i> Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No Products Found -->
        <div class="no-products">
            <i class="fas fa-search"></i>
            <h3>No products found</h3>
            <p>Try adjusting your search criteria or browse our categories.</p>
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> View All Products
            </a>
        </div>
    @endif
</section>

<script>
// Advanced filtering functionality
let activeFilters = {
    search: '',
    category: '',
    minPrice: '',
    maxPrice: '',
    sortBy: 'name_asc',
    availability: 'all',
    rating: 'all',
    brand: 'all'
};

let originalProducts = [];
let filteredProducts = [];

// Auto-submit search form on input
document.querySelector('.search-input').addEventListener('input', function(e) {
    clearTimeout(this.searchTimeout);
    activeFilters.search = this.value;
    this.searchTimeout = setTimeout(() => {
        applyAllFilters();
    }, 500);
});

function toggleAdvancedFilters() {
    const filters = document.getElementById('advancedFilters');
    const button = document.querySelector('.toggle-filters');
    
    if (filters.classList.contains('active')) {
        filters.classList.remove('active');
        button.innerHTML = '<i class="fas fa-filter"></i> Advanced Filters';
    } else {
        filters.classList.add('active');
        button.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
    }
}

function applyAdvancedFilters() {
    // Get all filter values
    activeFilters.minPrice = document.getElementById('minPrice').value;
    activeFilters.maxPrice = document.getElementById('maxPrice').value;
    activeFilters.sortBy = document.getElementById('sortBy').value;
    activeFilters.availability = document.getElementById('availability').value;
    activeFilters.rating = document.getElementById('ratingFilter').value;
    activeFilters.brand = document.getElementById('brandFilter').value;
    
    applyAllFilters();
    showFilterTags();
}

function applyAllFilters() {
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
        let visible = true;
        
        // Search filter
        if (activeFilters.search) {
            const productName = card.querySelector('.product-title').textContent.toLowerCase();
            const productDesc = card.querySelector('.product-description')?.textContent.toLowerCase() || '';
            const searchTerm = activeFilters.search.toLowerCase();
            
            if (!productName.includes(searchTerm) && !productDesc.includes(searchTerm)) {
                visible = false;
            }
        }
        
        // Price filter
        if (activeFilters.minPrice || activeFilters.maxPrice) {
            const priceText = card.querySelector('.product-price').textContent;
            const price = parseFloat(priceText.replace(/[^0-9.]/g, ''));
            
            if (activeFilters.minPrice && price < parseFloat(activeFilters.minPrice)) {
                visible = false;
            }
            if (activeFilters.maxPrice && price > parseFloat(activeFilters.maxPrice)) {
                visible = false;
            }
        }
        
        // Show/hide card
        if (visible) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update results count
    updateResultsCount(visibleCount);
    
    // Apply sorting
    if (activeFilters.sortBy !== 'name_asc') {
        applySorting();
    }
}

function applySorting() {
    const grid = document.querySelector('.products-grid');
    const cards = Array.from(grid.querySelectorAll('.product-card:not([style*="display: none"])'));
    
    cards.sort((a, b) => {
        const aTitle = a.querySelector('.product-title').textContent;
        const bTitle = b.querySelector('.product-title').textContent;
        const aPrice = parseFloat(a.querySelector('.product-price').textContent.replace(/[^0-9.]/g, ''));
        const bPrice = parseFloat(b.querySelector('.product-price').textContent.replace(/[^0-9.]/g, ''));
        
        switch (activeFilters.sortBy) {
            case 'name_asc':
                return aTitle.localeCompare(bTitle);
            case 'name_desc':
                return bTitle.localeCompare(aTitle);
            case 'price_asc':
                return aPrice - bPrice;
            case 'price_desc':
                return bPrice - aPrice;
            case 'rating_desc':
                // Would implement with actual rating data
                return Math.random() - 0.5;
            case 'newest':
                // Would implement with actual date data
                return Math.random() - 0.5;
            default:
                return 0;
        }
    });
    
    // Reorder cards in DOM
    cards.forEach(card => grid.appendChild(card));
}

function showFilterTags() {
    const existingTags = document.querySelector('.filter-tags');
    if (existingTags) {
        existingTags.remove();
    }
    
    const tags = [];
    
    if (activeFilters.search) {
        tags.push(`Search: "${activeFilters.search}"`);
    }
    if (activeFilters.minPrice) {
        tags.push(`Min Price: $${activeFilters.minPrice}`);
    }
    if (activeFilters.maxPrice) {
        tags.push(`Max Price: $${activeFilters.maxPrice}`);
    }
    if (activeFilters.availability !== 'all') {
        tags.push(`Availability: ${activeFilters.availability.replace('_', ' ')}`);
    }
    if (activeFilters.rating !== 'all') {
        tags.push(`Rating: ${activeFilters.rating}+ stars`);
    }
    if (activeFilters.brand !== 'all') {
        tags.push(`Brand: ${activeFilters.brand}`);
    }
    
    if (tags.length > 0) {
        const tagsContainer = document.createElement('div');
        tagsContainer.className = 'filter-tags';
        
        tags.forEach((tag, index) => {
            const tagElement = document.createElement('span');
            tagElement.className = 'filter-tag';
            tagElement.innerHTML = `
                ${tag}
                <button onclick="removeFilterTag(${index})" title="Remove filter">
                    <i class="fas fa-times"></i>
                </button>
            `;
            tagsContainer.appendChild(tagElement);
        });
        
        // Add clear all button
        const clearAllTag = document.createElement('span');
        clearAllTag.className = 'filter-tag';
        clearAllTag.style.background = 'var(--danger-color)';
        clearAllTag.innerHTML = `
            Clear All
            <button onclick="clearAllFilters()" title="Clear all filters">
                <i class="fas fa-times"></i>
            </button>
        `;
        tagsContainer.appendChild(clearAllTag);
        
        const resultsHeader = document.querySelector('.results-header');
        resultsHeader.after(tagsContainer);
    }
}

function removeFilterTag(index) {
    // Implementation to remove specific filter
    clearAllFilters(); // For now, just clear all
}

function clearAllFilters() {
    // Reset all filters
    activeFilters = {
        search: '',
        category: '',
        minPrice: '',
        maxPrice: '',
        sortBy: 'name_asc',
        availability: 'all',
        rating: 'all',
        brand: 'all'
    };
    
    // Reset form inputs
    document.getElementById('searchInput').value = '';
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('sortBy').value = 'name_asc';
    document.getElementById('availability').value = 'all';
    document.getElementById('ratingFilter').value = 'all';
    document.getElementById('brandFilter').value = 'all';
    
    // Remove filter tags
    const existingTags = document.querySelector('.filter-tags');
    if (existingTags) {
        existingTags.remove();
    }
    
    // Show all products
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.style.display = 'block';
    });
    
    updateResultsCount(productCards.length);
}

function changeView(viewType) {
    const grid = document.querySelector('.products-grid');
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.closest('.view-btn').classList.add('active');
    
    if (viewType === 'list') {
        grid.classList.add('list-view');
    } else {
        grid.classList.remove('list-view');
    }
}

function updateResultsCount(count) {
    const resultsCount = document.getElementById('resultsCount');
    const total = document.querySelectorAll('.product-card').length;
    resultsCount.textContent = `Showing ${count} of ${total} products`;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateResultsCount(document.querySelectorAll('.product-card:not([style*="display: none"])').length);
});
</script>
@endsection
