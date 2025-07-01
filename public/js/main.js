// Main JavaScript for E-smooth Online

// Utility functions (backup if not available from api-client.js)
if (!window.utils) {
    window.utils = {
        formatPrice: function(price) {
            return '$' + parseFloat(price).toFixed(2);
        },

        showMessage: function(message, type = 'info') {
            if (typeof showNotification === 'function') {
                showNotification(message, type);
            } else {
                console.log(`[${type.toUpperCase()}] ${message}`);
            }
        },

        handleApiError: function(error) {
            console.error('API Error:', error);
            const message = error.message || 'An error occurred';
            this.showMessage(message, 'error');
        }
    };
}

// Use the global utils object
const utils = window.utils;

// Simple auth utility (backup if not available from api-client.js)
if (!window.auth) {
    window.auth = {
        isLoggedIn: function() {
            return localStorage.getItem('auth_token') !== null;
        }
    };
}

// Use the global auth object
const auth = window.auth;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme
    initializeTheme();

    // Initialize authentication
    initializeAuth();

    // Initialize product functionality
    initializeProducts();    // Initialize cart functionality
    initializeCart();

    // Initialize search functionality
    initializeSearch();
});

// Theme Management (Enhanced compatibility)
function initializeTheme() {
    console.log('Main.js: initializeTheme called');

    // Check if theme is already handled by main layout
    if (typeof window.toggleTheme === 'function' || document.querySelector('[data-theme]')) {
        console.log('Main.js: Theme already initialized by layout, skipping...');
        return;
    }

    const themeToggle = document.querySelector('.theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';

    // Set initial theme
    document.documentElement.setAttribute('data-theme', currentTheme);

    // Update toggle button icon
    updateThemeToggleIcon(currentTheme);

    // Add event listener
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleThemeLocal);
    }
}

function toggleThemeLocal() {
    console.log('Main.js: toggleThemeLocal called');
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeToggleIcon(newTheme);
}

function updateThemeToggleIcon(theme) {
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.innerHTML = theme === 'dark'
            ? '<i class="fas fa-sun"></i>'
            : '<i class="fas fa-moon"></i>';
    }
}

// Authentication Management
function initializeAuth() {
    updateAuthUIElements();

    // Login form
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    // Register form
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }

    // Logout buttons
    const logoutButtons = document.querySelectorAll('.logout-btn');
    logoutButtons.forEach(btn => {
        btn.addEventListener('click', handleLogout);
    });
}

async function handleLogin(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const email = formData.get('email');
    const password = formData.get('password');

    try {
        showLoading(e.target);
        const response = await api.login(email, password);

        if (response.success) {
            utils.showMessage('Login successful!', 'success');
            updateAuthUIElements();

            // Redirect to intended page or home
            const redirectUrl = new URLSearchParams(window.location.search).get('redirect') || '/';
            window.location.href = redirectUrl;
        } else {
            utils.showMessage(response.message || 'Login failed', 'error');
        }
    } catch (error) {
        utils.handleApiError(error);
    } finally {
        hideLoading(e.target);
    }
}

async function handleRegister(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const userData = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        password_confirmation: formData.get('password_confirmation'),
        phone: formData.get('phone'),
        address: formData.get('address')
    };

    try {
        showLoading(e.target);
        const response = await api.register(userData);

        if (response.success) {
            utils.showMessage('Registration successful!', 'success');
            updateAuthUIElements();
            window.location.href = '/';
        } else {
            utils.showMessage(response.message || 'Registration failed', 'error');
        }
    } catch (error) {
        utils.handleApiError(error);
    } finally {
        hideLoading(e.target);
    }
}

async function handleLogout() {
    try {
        await api.logout();
        utils.showMessage('Logged out successfully', 'success');
        updateAuthUIElements();
        window.location.href = '/';
    } catch (error) {
        utils.handleApiError(error);
    }
}

function updateAuthUIElements() {
    const authElements = document.querySelectorAll('[data-auth]');
    const guestElements = document.querySelectorAll('[data-guest]');
    const isLoggedIn = auth.isLoggedIn();

    authElements.forEach(el => {
        el.style.display = isLoggedIn ? 'block' : 'none';
    });

    guestElements.forEach(el => {
        el.style.display = isLoggedIn ? 'none' : 'block';
    });
}

// Product Management
function initializeProducts() {
    // Load products on products page
    if (window.location.pathname === '/products' || window.location.pathname === '/') {
        loadProducts();
    }

    // Add to cart buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn')) {
            const productData = JSON.parse(e.target.getAttribute('data-product'));
            cart.add(productData);
        }
    });

    // Product filters
    const categoryFilter = document.getElementById('category-filter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterProducts);
    }

    const sortFilter = document.getElementById('sort-filter');
    if (sortFilter) {
        sortFilter.addEventListener('change', filterProducts);
    }
}

async function loadProducts(params = {}) {
    try {
        const productsContainer = document.getElementById('products-container');
        if (!productsContainer) return;

        showLoading(productsContainer);

        const response = await api.getProducts(params);

        if (response.success) {
            displayProducts(response.data, productsContainer);
        } else {
            utils.showMessage('Failed to load products', 'error');
        }
    } catch (error) {
        utils.handleApiError(error);
    } finally {
        const productsContainer = document.getElementById('products-container');
        if (productsContainer) {
            hideLoading(productsContainer);
        }
    }
}

function displayProducts(products, container) {
    if (!products || products.length === 0) {
        container.innerHTML = '<p class="text-center">No products found</p>';
        return;
    }

    const productsHTML = products.map(product => `
        <div class="product-card">
            <img src="${product.image || '/images/placeholder.jpg'}"
                 alt="${product.name}"
                 class="product-image">
            <div class="product-info">
                <h3 class="product-title">${product.name}</h3>
                <p class="product-description">${product.description || ''}</p>
                <div class="product-price">${utils.formatPrice(product.price)}</div>
                <div class="product-actions">
                    <button class="btn btn-primary add-to-cart-btn"
                            data-product='${JSON.stringify(product)}'>
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <a href="/product/${product.id}" class="btn btn-outline">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    `).join('');

    container.innerHTML = productsHTML;
}

async function filterProducts() {
    const category = document.getElementById('category-filter')?.value;
    const sort = document.getElementById('sort-filter')?.value;
    const search = document.getElementById('search-input')?.value;

    const params = {};
    if (category) params.category = category;
    if (sort) params.sort = sort;
    if (search) params.search = search;

    await loadProducts(params);
}

// Cart Management
function initializeCart() {
    // Use the global cart object from api-client.js
    // Update cart count on page load
    updateCartCount();

    // Cart page functionality
    if (window.location.pathname === '/cart') {
        displayCartItems();
    }

    // Checkout button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', handleCheckout);
    }
}

function displayCartItems() {
    const cartContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    if (!cartContainer) return;

    // Use the global cart object from api-client.js
    if (window.cart && window.cart.items.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center p-4">
                <i class="fas fa-shopping-cart fa-3x text-secondary mb-3"></i>
                <h3>Your cart is empty</h3>
                <p>Add some products to get started!</p>
                <a href="/products" class="btn btn-primary">Shop Now</a>
            </div>
        `;
        return;
    }

    const cartHTML = window.cart.items.map(item => `
        <div class="cart-item" data-product-id="${item.product.id}">
            <img src="${item.product.image || '/images/placeholder.jpg'}"
                 alt="${item.product.name}"
                 class="cart-item-image">
            <div class="cart-item-info">
                <h4>${item.product.name}</h4>
                <div class="cart-item-price">$${parseFloat(item.product.price).toFixed(2)}</div>
            </div>
            <div class="cart-item-controls">
                <div class="quantity-controls">
                    <button class="btn-quantity" onclick="updateQuantity(${item.product.id}, ${item.quantity - 1})">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="btn-quantity" onclick="updateQuantity(${item.product.id}, ${item.quantity + 1})">+</button>
                </div>
                <button class="btn btn-danger btn-sm" onclick="removeItem(${item.product.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');

    cartContainer.innerHTML = cartHTML;

    if (cartTotal) {
        cartTotal.textContent = '$' + window.cart.getTotal().toFixed(2);
    }
}

function updateCartQuantity(productId, quantity) {
    if (window.cart) {
        window.cart.updateQuantity(productId, quantity);
        displayCartItems();
    }
}

function removeFromCart(productId) {
    if (window.cart) {
        window.cart.remove(productId);
        displayCartItems();
    }
}

async function handleCheckout() {
    if (!auth.isLoggedIn()) {
        utils.showMessage('Please login to checkout', 'warning');
        window.location.href = `/login?redirect=${encodeURIComponent('/cart')}`;
        return;
    }

    // Use the global cart object instead of local cart array
    if (!window.cart || window.cart.items.length === 0) {
        utils.showMessage('Your cart is empty', 'warning');
        return;
    }

    try {
        const total = window.cart.getTotal();
        const orderData = {
            items: window.cart.items.map(item => ({
                product_id: item.product.id,
                quantity: item.quantity,
                price: item.product.price
            })),
            total_amount: total
        };

        const response = await api.createOrder(orderData);

        if (response.success) {
            window.cart.clear();
            utils.showMessage('Order placed successfully!', 'success');
            window.location.href = `/orders/${response.data.id}`;
        } else {
            utils.showMessage(response.message || 'Checkout failed', 'error');
        }
    } catch (error) {
        utils.handleApiError(error);
    }
}

// Search Functionality
function initializeSearch() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');

    if (searchForm) {
        searchForm.addEventListener('submit', handleSearch);
    }

    if (searchInput) {
        // Add debounced search
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    filterProducts();
                }
            }, 500);
        });
    }
}

function handleSearch(e) {
    e.preventDefault();
    const searchInput = document.getElementById('search-input');
    const query = searchInput?.value.trim();

    if (query) {
        window.location.href = `/products?search=${encodeURIComponent(query)}`;
    }
}

// Utility Functions
function showLoading(element) {
    if (element) {
        element.classList.add('loading-state');
        element.style.opacity = '0.6';
        element.style.pointerEvents = 'none';
    }
}

function hideLoading(element) {
    if (element) {
        element.classList.remove('loading-state');
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    }
}

// Global functions for onclick handlers
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.toggleTheme = toggleTheme;

// Global functions for cart management (used by onclick events)
window.updateQuantity = function(productId, quantity) {
    updateCartQuantity(productId, quantity);
};

window.removeItem = function(productId) {
    removeFromCart(productId);
};

// Make updateCartCount available globally
window.updateCartCount = function() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        let totalItems = 0;

        if (window.cart && window.cart.items) {
            totalItems = window.cart.getCount();
        } else {
            // Fallback to localStorage
            const cartItems = JSON.parse(localStorage.getItem('cart_items') || '[]');
            totalItems = cartItems.reduce((count, item) => count + item.quantity, 0);
        }

        cartCount.textContent = totalItems;

        // Update cart badge visibility
        if (totalItems > 0) {
            cartCount.style.display = 'flex';
        } else {
            cartCount.style.display = 'none';
        }
    }
};

// Ensure addToCart is available globally (backup to layout function)
if (!window.addToCart) {
    window.addToCart = function(productId, name, price, image) {
        // Create product object in the format expected by cart.add
        const productData = {
            id: productId,
            name: name,
            price: price,
            image: image
        };

        // Use the global cart object from api-client.js
        if (window.cart) {
            window.cart.add(productData, 1);
        } else {
            // Fallback if cart object not available yet
            utils.showMessage('Cart not ready, please try again', 'warning');
        }
    };
}
