@extends('layouts.app')

@section('title', 'My Wishlist - E-smooth Online')

@section('content')
<style>
    .wishlist-page {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 3rem 2rem;
        border-radius: 1rem;
        text-align: center;
        margin-bottom: 3rem;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .wishlist-item {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        position: relative;
    }

    [data-theme="dark"] .wishlist-item {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .wishlist-item:hover {
        transform: translateY(-5px);
    }

    .item-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: var(--light-color);
    }

    .item-content {
        padding: 1.5rem;
    }

    .item-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .item-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .item-actions {
        display: flex;
        gap: 0.5rem;
    }

    .remove-wishlist {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .remove-wishlist:hover {
        background: var(--danger-color);
        transform: scale(1.1);
    }

    .empty-wishlist {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-wishlist i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }

    .wishlist-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: var(--box-shadow);
        text-align: center;
    }

    [data-theme="dark"] .stat-card {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .wishlist-page {
            padding: 0 1rem;
        }

        .page-header {
            padding: 2rem 1rem;
        }

        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
    }
</style>

<div class="wishlist-page">
    <div class="page-header">
        <h1><i class="fas fa-heart"></i> My Wishlist</h1>
        <p>Keep track of your favorite products</p>
    </div>

    <div class="wishlist-stats">
        <div class="stat-card">
            <div class="stat-number" id="totalItems">0</div>
            <div class="stat-label">Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="totalValue">$0.00</div>
            <div class="stat-label">Total Value</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="avgPrice">$0.00</div>
            <div class="stat-label">Average Price</div>
        </div>
    </div>

    <div id="wishlistContent">
        <div class="empty-wishlist">
            <i class="fas fa-heart-broken"></i>
            <h3>Your wishlist is empty</h3>
            <p>Browse our products and click the heart icon to add items to your wishlist!</p>
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Browse Products
            </a>
            <button class="btn btn-outline" onclick="addSampleWishlistItems()" style="margin-left: 1rem;">
                <i class="fas fa-plus"></i> Add Sample Items
            </button>
        </div>
    </div>
</div>

<script>
let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

function renderWishlist() {
    const content = document.getElementById('wishlistContent');
    
    updateWishlistStats();
    
    if (wishlist.length === 0) {
        content.innerHTML = `
            <div class="empty-wishlist">
                <i class="fas fa-heart-broken"></i>
                <h3>Your wishlist is empty</h3>
                <p>Browse our products and click the heart icon to add items to your wishlist!</p>
                <a href="{{ route('products') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Browse Products
                </a>
                <button class="btn btn-outline" onclick="addSampleWishlistItems()" style="margin-left: 1rem;">
                    <i class="fas fa-plus"></i> Add Sample Items
                </button>
            </div>
        `;
        return;
    }

    content.innerHTML = `
        <div class="wishlist-actions" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
            <h2>Your Wishlist (${wishlist.length} items)</h2>
            <div>
                <button class="btn btn-outline" onclick="clearWishlist()">
                    <i class="fas fa-trash"></i> Clear All
                </button>
                <button class="btn btn-primary" onclick="addAllToCart()" style="margin-left: 1rem;">
                    <i class="fas fa-shopping-cart"></i> Add All to Cart
                </button>
            </div>
        </div>
        <div class="wishlist-grid">
            ${wishlist.map(item => `
                <div class="wishlist-item">
                    <button class="remove-wishlist" onclick="removeFromWishlist(${item.id})" title="Remove from wishlist">
                        <i class="fas fa-times"></i>
                    </button>
                    <img src="${item.image}" alt="${item.name}" class="item-image" 
                         onerror="this.src='https://via.placeholder.com/280x200?text=Product'">
                    <div class="item-content">
                        <h3 class="item-title">${item.name}</h3>
                        <div class="item-price">$${parseFloat(item.price).toFixed(2)}</div>
                        <div class="item-actions">
                            <button class="btn btn-primary" onclick="addToCartFromWishlist(${item.id})" style="flex: 1;">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                            <a href="/product/${item.id}" class="btn btn-outline">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function updateWishlistStats() {
    const totalItems = wishlist.length;
    const totalValue = wishlist.reduce((sum, item) => sum + parseFloat(item.price), 0);
    const avgPrice = totalItems > 0 ? totalValue / totalItems : 0;
    
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalValue').textContent = '$' + totalValue.toFixed(2);
    document.getElementById('avgPrice').textContent = '$' + avgPrice.toFixed(2);
}

function addToWishlist(productId, name, price, image) {
    const existingItem = wishlist.find(item => item.id === productId);
    
    if (existingItem) {
        showNotification('Item already in wishlist!', 'info');
        return false;
    }
    
    wishlist.push({
        id: productId,
        name: name,
        price: price,
        image: image,
        dateAdded: new Date().toISOString()
    });
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    showNotification('Added to wishlist!', 'success');
    return true;
}

function removeFromWishlist(productId) {
    wishlist = wishlist.filter(item => item.id !== productId);
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    renderWishlist();
    showNotification('Removed from wishlist!', 'success');
}

function addToCartFromWishlist(productId) {
    const item = wishlist.find(item => item.id === productId);
    if (item) {
        addToCart(item.id, item.name, item.price, item.image);
    }
}

function addAllToCart() {
    if (wishlist.length === 0) return;
    
    wishlist.forEach(item => {
        addToCart(item.id, item.name, item.price, item.image);
    });
    
    showNotification(`Added ${wishlist.length} items to cart!`, 'success');
}

function clearWishlist() {
    if (confirm('Are you sure you want to clear your entire wishlist?')) {
        wishlist = [];
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        renderWishlist();
        showNotification('Wishlist cleared!', 'success');
    }
}

function addSampleWishlistItems() {
    const sampleItems = [
        {
            id: 1,
            name: "iPhone 14 Pro",
            price: 999.99,
            image: "https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=280&h=200&fit=crop",
            dateAdded: new Date().toISOString()
        },
        {
            id: 2,
            name: "Samsung Galaxy Watch",
            price: 299.99,
            image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=280&h=200&fit=crop",
            dateAdded: new Date().toISOString()
        },
        {
            id: 3,
            name: "Nike Air Max",
            price: 129.99,
            image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=280&h=200&fit=crop",
            dateAdded: new Date().toISOString()
        }
    ];
    
    sampleItems.forEach(item => {
        const exists = wishlist.find(w => w.id === item.id);
        if (!exists) {
            wishlist.push(item);
        }
    });
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    renderWishlist();
    showNotification('Sample items added to wishlist!', 'success');
}

// Global function to check if item is in wishlist
function isInWishlist(productId) {
    return wishlist.some(item => item.id === productId);
}

// Listen for wishlist updates from other tabs
window.addEventListener('storage', function(e) {
    if (e.key === 'wishlist') {
        wishlist = JSON.parse(e.newValue) || [];
        renderWishlist();
    }
});

// Initialize wishlist on page load
document.addEventListener('DOMContentLoaded', function() {
    renderWishlist();
});
</script>
@endsection
