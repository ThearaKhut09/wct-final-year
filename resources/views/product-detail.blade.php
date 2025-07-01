@extends('layouts.app')

@section('title', $product->name . ' - E-smooth Online')

@section('content')
<style>
    .product-detail {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .breadcrumb {
        margin-bottom: 2rem;
        color: var(--text-secondary);
    }

    .breadcrumb a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .product-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    .product-images {
        position: relative;
    }

    .main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        background: var(--light-color);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .product-categories {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .product-category {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .product-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .product-description {
        color: var(--text-secondary);
        font-size: 1.125rem;
        line-height: 1.6;
    }

    .product-price {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .product-stock {
        font-size: 1.125rem;
        font-weight: 500;
    }

    .product-stock.in-stock {
        color: var(--success-color);
    }

    .product-stock.low-stock {
        color: var(--warning-color);
    }

    .product-stock.out-of-stock {
        color: var(--danger-color);
    }

    .product-sku {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1rem 0;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .quantity-btn {
        background: var(--light-color);
        border: none;
        padding: 0.75rem 1rem;
        cursor: pointer;
        color: var(--text-primary);
        font-size: 1.125rem;
        transition: var(--transition);
    }

    [data-theme="dark"] .quantity-btn {
        background: var(--dark-color);
    }

    .quantity-btn:hover {
        background: var(--primary-color);
        color: white;
    }

    .quantity-input {
        border: none;
        padding: 0.75rem 1rem;
        text-align: center;
        width: 4rem;
        font-size: 1.125rem;
        background: white;
        color: var(--text-primary);
    }

    [data-theme="dark"] .quantity-input {
        background: var(--dark-color);
    }

    .product-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .related-products {
        margin-top: 4rem;
    }

    .section-title {
        font-size: 2rem;
        margin-bottom: 2rem;
        color: var(--text-primary);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .related-product {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        text-decoration: none;
        color: var(--text-primary);
    }

    [data-theme="dark"] .related-product {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .related-product:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .related-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .related-info {
        padding: 1rem;
    }

    .related-name {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .related-price {
        color: var(--primary-color);
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .product-main {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .product-title {
            font-size: 2rem;
        }

        .product-price {
            font-size: 2.5rem;
        }

        .product-actions {
            flex-direction: column;
        }        .product-detail {
            padding: 0 1rem;
        }
    }

    /* Reviews Section Styles */
    .reviews-section {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        padding: 2rem;
        margin: 3rem 0;
    }

    [data-theme="dark"] .reviews-section {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .rating-summary {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .overall-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rating-stars {
        color: #ffc107;
        font-size: 1.2rem;
    }

    .rating-breakdown {
        display: grid;
        gap: 0.5rem;
        margin-bottom: 2rem;
        max-width: 400px;
    }

    .rating-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
    }

    .rating-bar span:first-child {
        width: 60px;
        color: var(--text-secondary);
    }

    .rating-bar span:last-child {
        width: 30px;
        text-align: right;
        color: var(--text-secondary);
    }

    .bar {
        flex: 1;
        height: 8px;
        background: var(--light-color);
        border-radius: 4px;
        overflow: hidden;
    }

    .bar .fill {
        height: 100%;
        background: #ffc107;
        transition: var(--transition);
    }

    .review-form {
        background: var(--light-color);
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    [data-theme="dark"] .review-form {
        background: rgba(255,255,255,0.05);
    }

    .rating-input {
        display: flex;
        gap: 0.5rem;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .rating-input i {
        cursor: pointer;
        color: #ddd;
        transition: var(--transition);
    }

    .rating-input i:hover,
    .rating-input i.active {
        color: #ffc107;
    }

    .review-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .review-filters select {
        min-width: 150px;
    }

    .review-item {
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem 0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .review-date {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .review-title {
        font-size: 1.1rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .review-text {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .review-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .review-action-btn {
        background: none;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .review-action-btn:hover {
        background: var(--light-color);
        color: var(--text-primary);
    }

    .review-action-btn.liked {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .reviews-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .rating-summary {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .review-filters {
            flex-direction: column;
        }

        .reviewer-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<div class="product-detail">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('products') }}">Products</a> /
        @foreach($product->categories as $category)
            <a href="{{ route('products', ['category' => $category->id]) }}">{{ $category->name }}</a> /
        @endforeach
        {{ $product->name }}
    </nav>

    <!-- Product Main -->
    <div class="product-main">
        <div class="product-images">
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="main-image">
        </div>

        <div class="product-info">
            <div class="product-categories">
                @foreach($product->categories as $category)
                    <span class="product-category">{{ $category->name }}</span>
                @endforeach
            </div>

            <h1 class="product-title">{{ $product->name }}</h1>

            @if($product->sku)
                <div class="product-sku">SKU: {{ $product->sku }}</div>
            @endif

            <div class="product-price">${{ number_format($product->price, 2) }}</div>

            <div class="product-stock {{ $product->stock > 10 ? 'in-stock' : ($product->stock > 0 ? 'low-stock' : 'out-of-stock') }}">
                <i class="fas {{ $product->stock > 0 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                @if($product->stock > 0)
                    {{ $product->stock }} in stock
                    @if($product->stock <= 10)
                        (Limited quantity!)
                    @endif
                @else
                    Out of stock
                @endif
            </div>

            <p class="product-description">{{ $product->description }}</p>

            @if($product->stock > 0)
                <div class="quantity-selector">
                    <label>Quantity:</label>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                        <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="{{ $product->stock }}">
                        <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                    </div>
                </div>                <div class="product-actions">
                    <button class="btn btn-primary" onclick="addToCartWithQuantity()">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline" onclick="toggleWishlist()">
                        <i class="far fa-heart" id="wishlistIcon"></i> <span id="wishlistText">Add to Wishlist</span>
                    </button>
                    <button class="btn btn-secondary">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div>
            @else
                <div class="product-actions">
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-times"></i> Out of Stock
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-bell"></i> Notify When Available
                    </button>
                </div>
            @endif        </div>
    </div>

    <!-- Product Reviews Section -->
    <div class="reviews-section">
        <div class="reviews-header">
            <h2>Customer Reviews</h2>
            <div class="rating-summary">
                <div class="overall-rating">
                    <div class="rating-stars" id="overallStars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="rating-number">4.2 out of 5</span>
                    <span class="review-count">(127 reviews)</span>
                </div>
                <button class="btn btn-primary" onclick="showReviewForm()">Write a Review</button>
            </div>
        </div>

        <div class="rating-breakdown">
            <div class="rating-bar">
                <span>5 stars</span>
                <div class="bar"><div class="fill" style="width: 60%"></div></div>
                <span>76</span>
            </div>
            <div class="rating-bar">
                <span>4 stars</span>
                <div class="bar"><div class="fill" style="width: 25%"></div></div>
                <span>32</span>
            </div>
            <div class="rating-bar">
                <span>3 stars</span>
                <div class="bar"><div class="fill" style="width: 10%"></div></div>
                <span>13</span>
            </div>
            <div class="rating-bar">
                <span>2 stars</span>
                <div class="bar"><div class="fill" style="width: 3%"></div></div>
                <span>4</span>
            </div>
            <div class="rating-bar">
                <span>1 star</span>
                <div class="bar"><div class="fill" style="width: 2%"></div></div>
                <span>2</span>
            </div>
        </div>

        <!-- Review Form (Initially Hidden) -->
        <div id="reviewForm" class="review-form" style="display: none;">
            <h3>Write Your Review</h3>
            <form id="reviewSubmitForm">
                <div class="form-group">
                    <label>Rating</label>
                    <div class="rating-input" id="ratingInput">
                        <i class="far fa-star" data-rating="1"></i>
                        <i class="far fa-star" data-rating="2"></i>
                        <i class="far fa-star" data-rating="3"></i>
                        <i class="far fa-star" data-rating="4"></i>
                        <i class="far fa-star" data-rating="5"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Review Title</label>
                    <input type="text" id="reviewTitle" class="form-input" placeholder="Summarize your experience" required>
                </div>
                <div class="form-group">
                    <label>Your Review</label>
                    <textarea id="reviewText" class="form-input" rows="4" placeholder="Tell others about your experience with this product" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                    <button type="button" class="btn btn-secondary" onclick="hideReviewForm()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Reviews List -->
        <div class="reviews-list">
            <div class="review-filters">
                <select id="sortReviews" class="form-input" onchange="sortReviews()">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="highest">Highest Rated</option>
                    <option value="lowest">Lowest Rated</option>
                </select>
                <select id="filterRating" class="form-input" onchange="filterReviews()">
                    <option value="all">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>

            <div id="reviewsList">
                <!-- Sample Reviews -->
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <strong>Sarah Johnson</strong>
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <span class="review-date">June 15, 2025</span>
                    </div>
                    <h4 class="review-title">Excellent product quality!</h4>
                    <p class="review-text">This product exceeded my expectations. The quality is outstanding and delivery was super fast. Would definitely recommend to others!</p>
                    <div class="review-actions">
                        <button class="review-action-btn" onclick="toggleReviewLike(this)">
                            <i class="far fa-thumbs-up"></i> Helpful (12)
                        </button>
                        <button class="review-action-btn">
                            <i class="fas fa-flag"></i> Report
                        </button>
                    </div>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <strong>Mike Chen</strong>
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <span class="review-date">June 12, 2025</span>
                    </div>
                    <h4 class="review-title">Good value for money</h4>
                    <p class="review-text">Solid product with great features. The price point is very competitive. Only minor issue was the packaging could be better.</p>
                    <div class="review-actions">
                        <button class="review-action-btn" onclick="toggleReviewLike(this)">
                            <i class="far fa-thumbs-up"></i> Helpful (8)
                        </button>
                        <button class="review-action-btn">
                            <i class="fas fa-flag"></i> Report
                        </button>
                    </div>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <strong>Emily Davis</strong>
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <span class="review-date">June 10, 2025</span>
                    </div>
                    <h4 class="review-title">Average experience</h4>
                    <p class="review-text">The product is okay but not exceptional. It does what it's supposed to do but there are probably better alternatives available.</p>
                    <div class="review-actions">
                        <button class="review-action-btn" onclick="toggleReviewLike(this)">
                            <i class="far fa-thumbs-up"></i> Helpful (3)
                        </button>
                        <button class="review-action-btn">
                            <i class="fas fa-flag"></i> Report
                        </button>
                    </div>
                </div>
            </div>

            <button class="btn btn-outline" id="loadMoreReviews" onclick="loadMoreReviews()">
                Load More Reviews
            </button>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products">
        <h2 class="section-title">You Might Also Like</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $related)
            <a href="{{ route('product.detail', $related->id) }}" class="related-product">
                <img src="{{ $related->image }}" alt="{{ $related->name }}" class="related-image">
                <div class="related-info">
                    <div class="related-name">{{ $related->name }}</div>
                    <div class="related-price">${{ number_format($related->price, 2) }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>    @endif
</div>

@push('scripts')
<script>
    const productData = {
        id: {{ $product->id }},
        name: '{{ $product->name }}',
        price: {{ $product->price }},
        image: '{{ $product->image }}',
        maxStock: {{ $product->stock }}
    };

    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < productData.maxStock) {
            input.value = currentValue + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    function addToCartWithQuantity() {
        const quantity = parseInt(document.getElementById('quantity').value);

        // Use global cart object from api-client.js
        if (window.cart) {
            window.cart.add(productData, quantity);
        } else {
            // Fallback if cart not ready - use the correct cart_items structure
            let cartItems = JSON.parse(localStorage.getItem('cart_items') || '[]');
            const existingItem = cartItems.find(item => item.product.id === productData.id);

            if (existingItem) {
                const newQuantity = existingItem.quantity + quantity;
                if (newQuantity <= productData.maxStock) {
                    existingItem.quantity = newQuantity;
                    showNotification(`Updated quantity to ${newQuantity}`, 'success');
                } else {
                    showNotification(`Cannot add more. Only ${productData.maxStock} available.`, 'warning');
                    return;
                }
            } else {
                cartItems.push({
                    product: {
                        id: productData.id,
                        name: productData.name,
                        price: productData.price,
                        image: productData.image
                    },
                    quantity: quantity
                });
                showNotification(`Added ${quantity} item(s) to cart!`, 'success');
            }

            localStorage.setItem('cart_items', JSON.stringify(cartItems));
            updateCartCount();
        }
    }

    // Validate quantity input
    document.getElementById('quantity').addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) {
            this.value = 1;
        } else if (value > productData.maxStock) {
            this.value = productData.maxStock;            showNotification(`Maximum available quantity is ${productData.maxStock}`, 'warning');
        }
    });    // Reviews functionality
    let selectedRating = 0;

    // Wishlist functionality
    function toggleWishlist() {
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const productIndex = wishlist.findIndex(item => item.id === productData.id);
        const icon = document.getElementById('wishlistIcon');
        const text = document.getElementById('wishlistText');

        if (productIndex === -1) {
            // Add to wishlist
            wishlist.push(productData);
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            icon.className = 'fas fa-heart';
            text.textContent = 'Remove from Wishlist';
            showNotification('Added to wishlist!', 'success');
        } else {
            // Remove from wishlist
            wishlist.splice(productIndex, 1);
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            icon.className = 'far fa-heart';
            text.textContent = 'Add to Wishlist';
            showNotification('Removed from wishlist!', 'success');
        }
    }

    // Check if product is in wishlist on page load
    function checkWishlistStatus() {
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const isInWishlist = wishlist.some(item => item.id === productData.id);
        const icon = document.getElementById('wishlistIcon');
        const text = document.getElementById('wishlistText');

        if (isInWishlist) {
            icon.className = 'fas fa-heart';
            text.textContent = 'Remove from Wishlist';
        } else {
            icon.className = 'far fa-heart';
            text.textContent = 'Add to Wishlist';
        }
    }

    // Initialize wishlist status
    document.addEventListener('DOMContentLoaded', checkWishlistStatus);

    function showReviewForm() {
        const userData = localStorage.getItem('user_data');
        if (!userData) {
            showNotification('Please login to write a review', 'warning');
            return;
        }
        document.getElementById('reviewForm').style.display = 'block';
        document.getElementById('reviewForm').scrollIntoView({ behavior: 'smooth' });
    }

    function hideReviewForm() {
        document.getElementById('reviewForm').style.display = 'none';
        document.getElementById('reviewSubmitForm').reset();
        selectedRating = 0;
        updateRatingInput();
    }

    function updateRatingInput() {
        const stars = document.querySelectorAll('.rating-input i');
        stars.forEach((star, index) => {
            if (index < selectedRating) {
                star.className = 'fas fa-star active';
            } else {
                star.className = 'far fa-star';
            }
        });
    }

    // Rating input handling
    document.querySelectorAll('.rating-input i').forEach((star, index) => {
        star.addEventListener('click', () => {
            selectedRating = index + 1;
            updateRatingInput();
        });

        star.addEventListener('mouseenter', () => {
            const stars = document.querySelectorAll('.rating-input i');
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.className = 'fas fa-star';
                } else {
                    s.className = 'far fa-star';
                }
            });
        });
    });

    document.getElementById('ratingInput').addEventListener('mouseleave', updateRatingInput);

    // Review submission
    document.getElementById('reviewSubmitForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (selectedRating === 0) {
            showNotification('Please select a rating', 'warning');
            return;
        }

        const title = document.getElementById('reviewTitle').value;
        const text = document.getElementById('reviewText').value;
        const userData = JSON.parse(localStorage.getItem('user_data'));

        // Create new review
        const newReview = {
            id: Date.now(),
            title: title,
            text: text,
            rating: selectedRating,
            author: userData.name,
            date: new Date().toLocaleDateString(),
            helpful: 0,
            productId: productData.id
        };

        // Save to local storage (in real app, would send to API)
        let reviews = JSON.parse(localStorage.getItem('productReviews') || '[]');
        reviews.unshift(newReview);
        localStorage.setItem('productReviews', JSON.stringify(reviews));

        // Add to DOM
        addReviewToList(newReview);
        hideReviewForm();
        showNotification('Review submitted successfully!', 'success');
    });

    function addReviewToList(review) {
        const reviewsList = document.getElementById('reviewsList');
        const reviewHTML = `
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <strong>${review.author}</strong>
                        <div class="rating-stars">
                            ${generateStars(review.rating)}
                        </div>
                    </div>
                    <span class="review-date">${review.date}</span>
                </div>
                <h4 class="review-title">${review.title}</h4>
                <p class="review-text">${review.text}</p>
                <div class="review-actions">
                    <button class="review-action-btn" onclick="toggleReviewLike(this)">
                        <i class="far fa-thumbs-up"></i> Helpful (${review.helpful})
                    </button>
                    <button class="review-action-btn">
                        <i class="fas fa-flag"></i> Report
                    </button>
                </div>
            </div>
        `;
        reviewsList.insertAdjacentHTML('afterbegin', reviewHTML);
    }

    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star"></i>';
            } else {
                stars += '<i class="far fa-star"></i>';
            }
        }
        return stars;
    }

    function toggleReviewLike(button) {
        const isLiked = button.classList.contains('liked');
        const countMatch = button.textContent.match(/\((\d+)\)/);
        let count = countMatch ? parseInt(countMatch[1]) : 0;

        if (isLiked) {
            button.classList.remove('liked');
            count--;
            button.innerHTML = `<i class="far fa-thumbs-up"></i> Helpful (${count})`;
        } else {
            button.classList.add('liked');
            count++;
            button.innerHTML = `<i class="fas fa-thumbs-up"></i> Helpful (${count})`;
        }
    }

    function sortReviews() {
        // Implementation for sorting reviews
        showNotification('Review sorting feature coming soon!', 'info');
    }

    function filterReviews() {
        // Implementation for filtering reviews
        showNotification('Review filtering feature coming soon!', 'info');
    }

    function loadMoreReviews() {
        // Implementation for loading more reviews
        showNotification('More reviews loaded!', 'info');
    }
</script>
@endsection
