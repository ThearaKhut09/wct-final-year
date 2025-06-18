// API Configuration
const API_BASE_URL = window.location.origin + '/api';
let authToken = localStorage.getItem('auth_token');

// API Client Class
class ApiClient {
    constructor() {
        this.baseURL = API_BASE_URL;
        this.token = authToken;
    }

    // Set authentication token
    setToken(token) {
        this.token = token;
        if (token) {
            localStorage.setItem('auth_token', token);
        } else {
            localStorage.removeItem('auth_token');
        }
    }

    // Get authentication headers
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }

        // Add authorization token if available
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    // Generic API request method
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: this.getHeaders(),
            ...options
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error('API Request Error:', error);
            throw error;
        }
    }

    // GET request
    async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }

    // POST request
    async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // PUT request
    async put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // DELETE request
    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }

    // Authentication methods
    async login(email, password) {
        const response = await this.post('/login', { email, password });
        if (response.success && response.token) {
            this.setToken(response.token);
        }
        return response;
    }

    async register(userData) {
        const response = await this.post('/register', userData);
        if (response.success && response.token) {
            this.setToken(response.token);
        }
        return response;
    }

    async logout() {
        try {
            await this.post('/logout', {});
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            this.setToken(null);
        }
    }

    async getProfile() {
        return this.get('/profile');
    }

    async updateProfile(userData) {
        return this.put('/profile', userData);
    }

    // Product methods
    async getProducts(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = queryString ? `/products?${queryString}` : '/products';
        return this.get(endpoint);
    }

    async getProduct(id) {
        return this.get(`/products/${id}`);
    }

    async createProduct(productData) {
        return this.post('/products', productData);
    }

    async updateProduct(id, productData) {
        return this.put(`/products/${id}`, productData);
    }

    async deleteProduct(id) {
        return this.delete(`/products/${id}`);
    }

    // Category methods
    async getCategories() {
        return this.get('/categories');
    }

    async getCategory(id) {
        return this.get(`/categories/${id}`);
    }

    async createCategory(categoryData) {
        return this.post('/categories', categoryData);
    }

    async updateCategory(id, categoryData) {
        return this.put(`/categories/${id}`, categoryData);
    }

    async deleteCategory(id) {
        return this.delete(`/categories/${id}`);
    }

    // Order methods
    async getOrders() {
        return this.get('/orders');
    }

    async getOrder(id) {
        return this.get(`/orders/${id}`);
    }

    async createOrder(orderData) {
        return this.post('/orders', orderData);
    }

    async updateOrder(id, orderData) {
        return this.put(`/orders/${id}`, orderData);
    }

    async deleteOrder(id) {
        return this.delete(`/orders/${id}`);
    }
}

// Create global API client instance
const api = new ApiClient();

// Make it available globally
window.api = api;

// Authentication helpers
window.auth = {
    isLoggedIn() {
        return !!api.token;
    },
    
    getToken() {
        return api.token;
    },
    
    async checkAuth() {
        if (!api.token) return false;
        
        try {
            await api.getProfile();
            return true;
        } catch (error) {
            api.setToken(null);
            return false;
        }
    }
};

// Utility functions for UI
window.utils = {
    showMessage(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;
        
        // Set background color based on type
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };
        notification.style.backgroundColor = colors[type] || colors.info;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    },
    
    async handleApiError(error) {
        console.error('API Error:', error);
        this.showMessage(error.message || 'An error occurred', 'error');
    },
    
    formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(price);
    },
    
    formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
};

// Shopping cart functionality
window.cart = {
    items: JSON.parse(localStorage.getItem('cart_items') || '[]'),
    
    add(product, quantity = 1) {
        const existingItem = this.items.find(item => item.product.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.items.push({ product, quantity });
        }
        
        this.save();
        this.updateUI();
        utils.showMessage(`${product.name} added to cart`, 'success');
    },
    
    remove(productId) {
        this.items = this.items.filter(item => item.product.id !== productId);
        this.save();
        this.updateUI();
    },
    
    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.product.id === productId);
        if (item) {
            if (quantity <= 0) {
                this.remove(productId);
            } else {
                item.quantity = quantity;
                this.save();
                this.updateUI();
            }
        }
    },
    
    clear() {
        this.items = [];
        this.save();
        this.updateUI();
    },
    
    save() {
        localStorage.setItem('cart_items', JSON.stringify(this.items));
    },
    
    getTotal() {
        return this.items.reduce((total, item) => {
            return total + (item.product.price * item.quantity);
        }, 0);
    },
    
    getCount() {
        return this.items.reduce((count, item) => count + item.quantity, 0);
    },
    
    updateUI() {
        // Update cart count in navigation
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = this.getCount();
        });
        
        // Update cart total if on cart page
        const cartTotalElement = document.querySelector('.cart-total');
        if (cartTotalElement) {
            cartTotalElement.textContent = utils.formatPrice(this.getTotal());
        }
        
        // Trigger custom event for other components
        window.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { items: this.items, total: this.getTotal(), count: this.getCount() }
        }));
    }
};

// Initialize cart UI on page load
document.addEventListener('DOMContentLoaded', () => {
    cart.updateUI();
});

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { api, auth, utils, cart };
}
