@extends('layouts.app')

@section('title', 'My Profile - E-smooth Online')

@section('content')
<style>
    .profile-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
    }

    .profile-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--border-color);
    }

    .tab-btn {
        padding: 1rem 2rem;
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: var(--transition);
    }

    .tab-btn.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .profile-section {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
    }

    [data-theme="dark"] .profile-section {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .order-status {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-completed { background: var(--success-color); color: white; }
    .status-pending { background: var(--warning-color); color: white; }
    .status-cancelled { background: var(--danger-color); color: white; }

    @media (max-width: 768px) {
        .profile-container {
            padding: 0 1rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .profile-tabs {
            flex-wrap: wrap;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <h1 id="profileName">Loading...</h1>
        <p id="profileEmail">Loading...</p>
        <p><strong>Member since:</strong> <span id="memberSince">Loading...</span></p>
    </div>    <div class="profile-tabs">
        <button class="tab-btn active" onclick="switchTab('personal')">Personal Info</button>
        <button class="tab-btn" onclick="switchTab('orders')">My Orders</button>
        <button class="tab-btn" onclick="switchTab('wishlist')">Wishlist</button>
        <button class="tab-btn" onclick="switchTab('settings')">Settings</button>
        <button class="tab-btn" onclick="window.open('http://127.0.0.1:8000/admin', '_blank')" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 0.5rem;">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </button>
    </div>

    <!-- Personal Info Tab -->
    <div id="personal" class="tab-content active">
        <div class="profile-section">
            <h3>Personal Information</h3>
            <form id="profileForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" id="firstName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" id="lastName" class="form-input" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" id="phone" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea id="address" class="form-input" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Orders Tab -->
    <div id="orders" class="tab-content">
        <div class="profile-section">
            <h3>Order History</h3>
            <div id="ordersList">
                <div class="order-item">
                    <div>
                        <strong>Order #12345</strong><br>
                        <small>June 15, 2025</small>
                    </div>
                    <div style="flex: 1;">
                        iPhone 14 Pro, Samsung Watch
                    </div>
                    <div>$1,299.98</div>
                    <div class="order-status status-completed">Completed</div>
                </div>
                <div class="order-item">
                    <div>
                        <strong>Order #12344</strong><br>
                        <small>June 10, 2025</small>
                    </div>
                    <div style="flex: 1;">
                        Nike Air Max, Wireless Headphones
                    </div>
                    <div>$349.99</div>
                    <div class="order-status status-pending">Shipping</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wishlist Tab -->
    <div id="wishlist" class="tab-content">
        <div class="profile-section">
            <h3>My Wishlist</h3>
            <div id="wishlistItems">
                <p>Your wishlist is empty. <a href="{{ route('products') }}">Browse products</a> to add items!</p>
            </div>
        </div>
    </div>

    <!-- Settings Tab -->
    <div id="settings" class="tab-content">
        <div class="profile-section">
            <h3>Account Settings</h3>
            <div class="form-group">
                <label class="form-label">Email Notifications</label>
                <label class="checkbox-label">
                    <input type="checkbox" id="emailNotifications" checked>
                    Receive order updates and promotions
                </label>            </div>
            <div class="form-group">
                <label class="form-label">Currency</label>
                <select id="currency" class="form-input">
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                </select>
            </div>
            <button class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
            <button class="btn btn-danger" onclick="changePassword()" style="margin-left: 1rem;">Change Password</button>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

function loadProfile() {
    const userData = localStorage.getItem('user_data');
    const token = localStorage.getItem('auth_token');
    
    if (userData && token) {
        try {
            const user = JSON.parse(userData);
            document.getElementById('profileName').textContent = user.name || 'User';
            document.getElementById('profileEmail').textContent = user.email || '';
            document.getElementById('memberSince').textContent = new Date(user.created_at || Date.now()).toLocaleDateString();
            
            // Fill form
            const names = user.name ? user.name.split(' ') : ['', ''];
            document.getElementById('firstName').value = names[0] || '';
            document.getElementById('lastName').value = names.slice(1).join(' ') || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('phone').value = user.phone || '';
            document.getElementById('address').value = user.address || '';
            
            // Show welcome message if just logged in
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('welcome') === 'true') {
                setTimeout(() => {
                    showNotification('Welcome to your profile! You can update your information here.', 'success');
                }, 500);
            }
        } catch (error) {
            console.error('Error parsing user data:', error);
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            window.location.href = '/login';
        }
    } else {
        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
    }
}

function saveSettings() {
    const settings = {        emailNotifications: document.getElementById('emailNotifications').checked,
        currency: document.getElementById('currency').value
    };
    
    localStorage.setItem('userSettings', JSON.stringify(settings));
    showNotification('Settings saved successfully!', 'success');
}

function changePassword() {
    const newPassword = prompt('Enter new password:');
    if (newPassword && newPassword.length >= 8) {
        // In real app, would call API
        showNotification('Password change feature coming soon!', 'info');
    } else if (newPassword) {
        showNotification('Password must be at least 8 characters long.', 'error');
    }
}

document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('firstName').value + ' ' + document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value
    };
    
    // In real app, would call API to update profile
    const userData = JSON.parse(localStorage.getItem('user_data'));
    const updatedUser = { ...userData, ...formData };
    localStorage.setItem('user_data', JSON.stringify(updatedUser));
    
    showNotification('Profile updated successfully!', 'success');
});

// Load profile on page load
document.addEventListener('DOMContentLoaded', loadProfile);
document.addEventListener('DOMContentLoaded', function() {
    // Ensure auth UI is updated after page loads
    setTimeout(function() {
        if (typeof updateAuthUI === 'function') {
            updateAuthUI();
        } else if (typeof window.updateAuthUI === 'function') {
            window.updateAuthUI();
        }
    }, 100);
});
</script>
@endsection
