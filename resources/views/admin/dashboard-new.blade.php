{{-- @extends('layouts.app')

@section('title', 'Admin Dashboard - E-smooth Online')

@section('content')
<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .admin-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .admin-nav {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .nav-tab {
        background: white;
        border: 2px solid var(--border-color);
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-primary);
        text-decoration: none;
    }

    [data-theme="dark"] .nav-tab {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .nav-tab:hover,
    .nav-tab.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    [data-theme="dark"] .stat-card {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.sales { background: var(--success-color); }
    .stat-icon.orders { background: var(--primary-color); }
    .stat-icon.customers { background: var(--warning-color); }
    .stat-icon.products { background: var(--info-color); }
    .stat-icon.revenue { background: #6f42c1; }
    .stat-icon.reviews { background: var(--danger-color); }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-change.positive { color: var(--success-color); }
    .stat-change.negative { color: var(--danger-color); }

    .admin-section {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    [data-theme="dark"] .admin-section {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .section-content {
        padding: 1.5rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .data-table th,
    .data-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .data-table th {
        background: var(--light-color);
        font-weight: 600;
        color: var(--text-primary);
    }

    [data-theme="dark"] .data-table th {
        background: rgba(255,255,255,0.05);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-pending { background: var(--warning-color); color: white; }
    .status-completed { background: var(--success-color); color: white; }
    .status-cancelled { background: var(--danger-color); color: white; }
    .status-processing { background: var(--info-color); color: white; }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: var(--box-shadow);
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        border: 2px solid transparent;
    }

    [data-theme="dark"] .action-card {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .action-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }

        .admin-header {
            flex-direction: column;
            text-align: center;
        }

        .dashboard-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .data-table {
            font-size: 0.875rem;
        }
    }
</style>

<div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <p>Welcome back! Here's what's happening with your store today.</p>
        </div>
        <div>
            <span style="opacity: 0.9;">Last updated: <span id="lastUpdated"></span></span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="admin-nav">
        <a href="#" class="nav-tab active" onclick="showTab('overview')">Overview</a>
        <a href="#" class="nav-tab" onclick="showTab('products')">Products</a>
        <a href="#" class="nav-tab" onclick="showTab('orders')">Orders</a>
        <a href="#" class="nav-tab" onclick="showTab('customers')">Customers</a>
        <a href="#" class="nav-tab" onclick="showTab('analytics')">Analytics</a>
        <a href="#" class="nav-tab" onclick="showTab('settings')">Settings</a>
    </div>

    <!-- Overview Tab -->
    <div id="overview" class="tab-content">
        <!-- Dashboard Stats -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-number" id="totalRevenue">$12,345</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-change positive">+12.5% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orders">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number" id="totalOrders">156</div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-change positive">+8.3% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon customers">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number" id="totalCustomers">89</div>
                <div class="stat-label">Total Customers</div>
                <div class="stat-change positive">+15.2% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-number" id="totalProducts">234</div>
                <div class="stat-label">Products</div>
                <div class="stat-change negative">-2 this week</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon revenue">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number" id="avgOrderValue">$79.19</div>
                <div class="stat-label">Avg Order Value</div>
                <div class="stat-change positive">+5.8% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon reviews">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number" id="avgRating">4.6</div>
                <div class="stat-label">Average Rating</div>
                <div class="stat-change positive">+0.2 from last month</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="action-card" onclick="showAddProductModal()">
                <i class="fas fa-plus-circle" style="font-size: 2rem; color: var(--success-color); margin-bottom: 0.5rem;"></i>
                <h4>Add Product</h4>
                <p>Add a new product to your inventory</p>
            </div>
            <div class="action-card" onclick="viewOrders()">
                <i class="fas fa-clipboard-list" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
                <h4>View Orders</h4>
                <p>Manage and process customer orders</p>
            </div>
            <div class="action-card" onclick="viewAnalytics()">
                <i class="fas fa-chart-bar" style="font-size: 2rem; color: var(--info-color); margin-bottom: 0.5rem;"></i>
                <h4>Analytics</h4>
                <p>View detailed sales and performance reports</p>
            </div>
            <div class="action-card" onclick="manageCustomers()">
                <i class="fas fa-user-friends" style="font-size: 2rem; color: var(--warning-color); margin-bottom: 0.5rem;"></i>
                <h4>Customers</h4>
                <p>Manage customer accounts and data</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Recent Orders -->
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Recent Orders</h3>
                    <a href="#" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="section-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersTable">
                            <tr>
                                <td>#12345</td>
                                <td>John Doe</td>
                                <td>$129.99</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td>Jun 17, 2025</td>
                            </tr>
                            <tr>
                                <td>#12344</td>
                                <td>Jane Smith</td>
                                <td>$89.50</td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>Jun 16, 2025</td>
                            </tr>
                            <tr>
                                <td>#12343</td>
                                <td>Mike Johnson</td>
                                <td>$199.99</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>Jun 16, 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Recent Activity</h3>
                </div>
                <div class="section-content">
                    <div class="activity-feed" style="max-height: 300px; overflow-y: auto;">
                        <div style="display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--success-color); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500;">New order received</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary);">2 minutes ago</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500;">New customer registered</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary);">15 minutes ago</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; padding: 1rem 0;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--warning-color); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500;">New product review</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary);">1 hour ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Other tabs (placeholder content) -->
    <div id="products" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Product Management</h3>
                <button class="btn btn-primary" onclick="showAddProductModal()">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
            <div class="section-content">
                <p>Advanced product management features coming soon...</p>
            </div>
        </div>
    </div>

    <div id="orders" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Order Management</h3>
            </div>
            <div class="section-content">
                <p>Advanced order management features coming soon...</p>
            </div>
        </div>
    </div>

    <div id="customers" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Customer Management</h3>
            </div>
            <div class="section-content">
                <p>Customer management features coming soon...</p>
            </div>
        </div>
    </div>

    <div id="analytics" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Sales Analytics</h3>
            </div>
            <div class="section-content">
                <div style="height: 300px; background: var(--light-color); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                    <div style="text-align: center;">
                        <i class="fas fa-chart-line" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <div>Advanced analytics dashboard coming soon...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="settings" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">System Settings</h3>
            </div>
            <div class="section-content">
                <p>Advanced settings panel coming soon...</p>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remove active class from all nav tabs
    document.querySelectorAll('.nav-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).style.display = 'block';
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}

function showAddProductModal() {
    showNotification('Add Product modal coming soon!', 'info');
}

function viewOrders() {
    showTab('orders');
}

function viewAnalytics() {
    showTab('analytics');
}

function manageCustomers() {
    showTab('customers');
}

function updateDashboard() {
    // Simulate real-time updates
    const revenue = Math.floor(Math.random() * 5000) + 10000;
    const orders = Math.floor(Math.random() * 50) + 100;
    const customers = Math.floor(Math.random() * 30) + 60;
    
    document.getElementById('totalRevenue').textContent = '$' + revenue.toLocaleString();
    document.getElementById('totalOrders').textContent = orders;
    document.getElementById('totalCustomers').textContent = customers;
    
    // Update last updated time
    const now = new Date();
    document.getElementById('lastUpdated').textContent = now.toLocaleTimeString();
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    updateDashboard();
    
    // Auto-refresh every 30 seconds
    setInterval(updateDashboard, 30000);
    
    // Set initial last updated time
    const now = new Date();
    document.getElementById('lastUpdated').textContent = now.toLocaleTimeString();
});
</script>
@endsection --}}
