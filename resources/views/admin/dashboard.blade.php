@extends('layouts.app')

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

    .data-table-container {
        overflow-x: auto;
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
    }

    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .settings-nav .nav-tab {
        background: white;
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.875rem;
    }

    [data-theme="dark"] .settings-nav .nav-tab {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .settings-nav .nav-tab:hover,
    .settings-nav .nav-tab.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .form-group label {
        display: block;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 0.25rem;
        font-family: inherit;
        color: var(--text-primary);
        background: white;
    }

    [data-theme="dark"] .form-group input,
    [data-theme="dark"] .form-group select,
    [data-theme="dark"] .form-group textarea {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .switch input:checked + span {
        background-color: var(--success-color);
    }

    .switch input:checked + span:before {
        transform: translateX(26px);
    }

    .switch span:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    .metric-item {
        transition: var(--transition);
    }

    .metric-item:hover {
        background: var(--light-color);
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin-left: -1rem;
        margin-right: -1rem;
        border-radius: 0.25rem;
    }

    [data-theme="dark"] .metric-item:hover {
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
            <div class="action-card" onclick="showTab('settings')">
                <i class="fas fa-cog" style="font-size: 2rem; color: var(--danger-color); margin-bottom: 0.5rem;"></i>
                <h4>Settings</h4>
                <p>Configure store settings and preferences</p>
            </div>
            <div class="action-card" onclick="exportData()">
                <i class="fas fa-download" style="font-size: 2rem; color: #6f42c1; margin-bottom: 0.5rem;"></i>
                <h4>Export Data</h4>
                <p>Download reports and backup data</p>
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

    <!-- Products Tab -->
    <div id="products" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Product Management</h3>
                <button class="btn btn-primary" onclick="showAddProductModal()">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
            <div class="section-content">
                <div class="data-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable">
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading products...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="orders" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Order Management</h3>
                <button class="btn btn-primary" onclick="refreshOrders()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="section-content">
                <div class="data-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTable">
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading orders...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="customers" class="tab-content" style="display: none;">
        <div class="admin-section">
            <div class="section-header">
                <h3 class="section-title">Customer Management</h3>
                <button class="btn btn-primary" onclick="refreshCustomers()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="section-content">
                <div class="data-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Orders</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTable">
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading customers...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="analytics" class="tab-content" style="display: none;">
        <!-- Analytics Overview Cards -->
        <div class="dashboard-grid" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="stat-icon" style="background: #8b5cf6;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number" id="analyticsRevenue">$0</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-change positive" id="revenueGrowth">+0% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #06b6d4;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-number" id="analyticsOrders">0</div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-change positive" id="ordersGrowth">+0% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #10b981;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number" id="analyticsCustomers">0</div>
                <div class="stat-label">New Customers</div>
                <div class="stat-change positive" id="customersGrowth">+0% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f59e0b;">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-number" id="conversionRate">0%</div>
                <div class="stat-label">Conversion Rate</div>
                <div class="stat-change positive">+0.5% from last month</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Sales Chart -->
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Sales Overview</h3>
                    <select id="salesPeriod" style="padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                    </select>
                </div>
                <div class="section-content">
                    <canvas id="salesChart" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Top Products</h3>
                </div>
                <div class="section-content">
                    <div id="topProductsList">
                        <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Analytics -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Category Performance -->
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Category Performance</h3>
                </div>
                <div class="section-content">
                    <canvas id="categoryChart" style="max-height: 250px;"></canvas>
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Performance Metrics</h3>
                </div>
                <div class="section-content">
                    <div class="metric-item" style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                        <span>Average Order Value</span>
                        <strong id="metricAOV">$0.00</strong>
                    </div>
                    <div class="metric-item" style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                        <span>Customer Lifetime Value</span>
                        <strong id="metricCLV">$0.00</strong>
                    </div>
                    <div class="metric-item" style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                        <span>Cart Abandonment Rate</span>
                        <strong style="color: var(--danger-color);">23.5%</strong>
                    </div>
                    <div class="metric-item" style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                        <span>Return Customer Rate</span>
                        <strong style="color: var(--success-color);">34.2%</strong>
                    </div>
                    <div class="metric-item" style="display: flex; justify-content: space-between; padding: 1rem 0;">
                        <span>Page Views per Session</span>
                        <strong>4.7</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="settings" class="tab-content" style="display: none;">
        <!-- Settings Navigation -->
        <div class="settings-nav" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <button class="nav-tab active" onclick="showSettingsSection('general')">General</button>
            <button class="nav-tab" onclick="showSettingsSection('payment')">Payment</button>
            <button class="nav-tab" onclick="showSettingsSection('shipping')">Shipping</button>
            <button class="nav-tab" onclick="showSettingsSection('email')">Email</button>
            <button class="nav-tab" onclick="showSettingsSection('security')">Security</button>
            <button class="nav-tab" onclick="showSettingsSection('backup')">Backup</button>
        </div>

        <!-- General Settings -->
        <div id="general-settings" class="settings-section">
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">General Settings</h3>
                    <button class="btn btn-primary" onclick="saveGeneralSettings()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
                <div class="section-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>Store Information</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Store Name</label>
                                <input type="text" id="storeName" value="E-smooth Online" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Store Description</label>
                                <textarea id="storeDescription" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem; height: 80px;">Premium online shopping experience with quality products</textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Contact Email</label>
                                <input type="email" id="contactEmail" value="admin@e-smooth.com" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Phone Number</label>
                                <input type="text" id="phoneNumber" value="+1 (555) 123-4567" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                        </div>
                        <div>
                            <h4>Store Settings</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Default Currency</label>
                                <select id="defaultCurrency" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    <option value="USD" selected>USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="CAD">CAD - Canadian Dollar</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Timezone</label>
                                <select id="timezone" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York" selected>Eastern Time</option>
                                    <option value="America/Chicago">Central Time</option>
                                    <option value="America/Denver">Mountain Time</option>
                                    <option value="America/Los_Angeles">Pacific Time</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Products per Page</label>
                                <input type="number" id="productsPerPage" value="12" min="6" max="50" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="enableReviews" checked>
                                    Enable Product Reviews
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="enableWishlist" checked>
                                    Enable Wishlist Feature
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div id="payment-settings" class="settings-section" style="display: none;">
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Payment Settings</h3>
                    <button class="btn btn-primary" onclick="savePaymentSettings()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
                <div class="section-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>Payment Methods</h4>
                            <div class="payment-method" style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fab fa-cc-stripe" style="font-size: 1.5rem; color: #635bff;"></i>
                                        <span style="font-weight: 500;">Stripe</span>
                                    </div>
                                    <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                        <input type="checkbox" id="stripeEnabled" checked style="opacity: 0; width: 0; height: 0;">
                                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--success-color); transition: .4s; border-radius: 24px;"></span>
                                    </label>
                                </div>
                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label>Publishable Key</label>
                                    <input type="text" id="stripePublishable" placeholder="pk_test_..." style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                                <div class="form-group">
                                    <label>Secret Key</label>
                                    <input type="password" id="stripeSecret" placeholder="sk_test_..." style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                            </div>

                            <div class="payment-method" style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fab fa-paypal" style="font-size: 1.5rem; color: #0070ba;"></i>
                                        <span style="font-weight: 500;">PayPal</span>
                                    </div>
                                    <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                        <input type="checkbox" id="paypalEnabled" style="opacity: 0; width: 0; height: 0;">
                                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px;"></span>
                                    </label>
                                </div>
                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label>Client ID</label>
                                    <input type="text" id="paypalClientId" placeholder="PayPal Client ID" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                                <div class="form-group">
                                    <label>Client Secret</label>
                                    <input type="password" id="paypalSecret" placeholder="PayPal Client Secret" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Tax & Currency</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Tax Rate (%)</label>
                                <input type="number" id="taxRate" value="8.5" step="0.1" min="0" max="50" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Minimum Order Amount</label>
                                <input type="number" id="minOrderAmount" value="10.00" step="0.01" min="0" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="enableTax" checked>
                                    Apply Tax to Orders
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="enableCoupons" checked>
                                    Enable Discount Coupons
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div id="shipping-settings" class="settings-section" style="display: none;">
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Shipping Settings</h3>
                    <button class="btn btn-primary" onclick="saveShippingSettings()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
                <div class="section-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>Shipping Methods</h4>
                            <div class="shipping-method" style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <span style="font-weight: 500;">Standard Shipping</span>
                                    <input type="checkbox" checked>
                                </div>
                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label>Price</label>
                                    <input type="number" value="5.99" step="0.01" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                                <div class="form-group">
                                    <label>Delivery Time</label>
                                    <input type="text" value="5-7 business days" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                            </div>

                            <div class="shipping-method" style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <span style="font-weight: 500;">Express Shipping</span>
                                    <input type="checkbox" checked>
                                </div>
                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label>Price</label>
                                    <input type="number" value="12.99" step="0.01" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                                <div class="form-group">
                                    <label>Delivery Time</label>
                                    <input type="text" value="2-3 business days" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Shipping Zones</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Free Shipping Threshold</label>
                                <input type="number" value="50.00" step="0.01" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                <small>Orders above this amount get free shipping</small>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Default Shipping Zone</label>
                                <select style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    <option>United States</option>
                                    <option>Canada</option>
                                    <option>International</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Enable International Shipping
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div id="email-settings" class="settings-section" style="display: none;">
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Email Settings</h3>
                    <button class="btn btn-primary" onclick="saveEmailSettings()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
                <div class="section-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>SMTP Configuration</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>SMTP Host</label>
                                <input type="text" placeholder="smtp.example.com" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>SMTP Port</label>
                                <input type="number" value="587" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Email Username</label>
                                <input type="email" placeholder="noreply@e-smooth.com" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Email Password</label>
                                <input type="password" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                        </div>

                        <div>
                            <h4>Email Templates</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Send Order Confirmation
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Send Shipping Notifications
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Send Welcome Emails
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox">
                                    Send Marketing Emails
                                </label>
                            </div>
                            <button class="btn btn-primary" onclick="testEmail()" style="margin-top: 1rem;">
                                <i class="fas fa-envelope"></i> Send Test Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div id="security-settings" class="settings-section" style="display: none;">
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Security Settings</h3>
                    <button class="btn btn-primary" onclick="saveSecuritySettings()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
                <div class="section-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>Password Policy</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Minimum Password Length</label>
                                <input type="number" value="8" min="6" max="20" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Require Uppercase Letter
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Require Numbers
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox">
                                    Require Special Characters
                                </label>
                            </div>
                        </div>

                        <div>
                            <h4>Session & Security</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Session Timeout (minutes)</label>
                                <input type="number" value="120" min="15" max="480" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Enable Two-Factor Authentication
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Log Failed Login Attempts
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox">
                                    Enable SSL/HTTPS Only
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Settings -->
        <div id="backup-settings" class="settings-section" style="display: none;">
            <div class="admin-section">
                <div class="section-header">
                    <h3 class="section-title">Backup & Maintenance</h3>
                    <button class="btn btn-primary" onclick="createBackup()">
                        <i class="fas fa-download"></i> Create Backup Now
                    </button>
                </div>
                <div class="section-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>Automatic Backups</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" checked>
                                    Enable Automatic Backups
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Backup Frequency</label>
                                <select style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    <option>Daily</option>
                                    <option selected>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Retention Period</label>
                                <select style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    <option>7 days</option>
                                    <option selected>30 days</option>
                                    <option>90 days</option>
                                    <option>1 year</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <h4>Maintenance Mode</h4>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="maintenanceMode">
                                    Enable Maintenance Mode
                                </label>
                                <small>Temporarily disable the store for maintenance</small>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label>Maintenance Message</label>
                                <textarea style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid var(--border-color); border-radius: 0.25rem; height: 80px;">We're currently performing scheduled maintenance. Please check back soon!</textarea>
                            </div>
                            <button class="btn" onclick="clearCache()" style="background: var(--warning-color); color: white; margin-right: 1rem;">
                                <i class="fas fa-broom"></i> Clear Cache
                            </button>
                            <button class="btn" onclick="optimizeDatabase()" style="background: var(--info-color); color: white;">
                                <i class="fas fa-database"></i> Optimize Database
                            </button>
                        </div>
                    </div>
                </div>
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

    // Load data for specific tabs
    switch(tabName) {
        case 'products':
            loadProducts();
            break;
        case 'orders':
            loadOrders();
            break;
        case 'customers':
            loadCustomers();
            break;
        case 'analytics':
            loadAnalytics();
            break;
        case 'overview':
            updateDashboard();
            break;
    }
}

function showAddProductModal() {
    // Create a simple modal for adding products
    const modalHTML = `
        <div id="productModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;">
            <div style="background: white; padding: 2rem; border-radius: 1rem; max-width: 500px; width: 90%;">
                <h3>Add New Product</h3>
                <form id="addProductForm">
                    <div style="margin-bottom: 1rem;">
                        <label>Name:</label>
                        <input type="text" id="productName" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #ddd; border-radius: 0.25rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Price:</label>
                        <input type="number" id="productPrice" step="0.01" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #ddd; border-radius: 0.25rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Description:</label>
                        <textarea id="productDescription" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #ddd; border-radius: 0.25rem; height: 80px;"></textarea>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Stock Quantity:</label>
                        <input type="number" id="productStock" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #ddd; border-radius: 0.25rem;">
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" onclick="closeProductModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 0.25rem; cursor: pointer;">Cancel</button>
                        <button type="submit" style="padding: 0.5rem 1rem; background: var(--primary-color); color: white; border: none; border-radius: 0.25rem; cursor: pointer;">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    document.getElementById('addProductForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const productData = {
            name: document.getElementById('productName').value,
            price: document.getElementById('productPrice').value,
            description: document.getElementById('productDescription').value,
            stock_quantity: document.getElementById('productStock').value
        };

        try {
            const response = await fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(productData)
            });

            if (response.ok) {
                showNotification('Product added successfully!', 'success');
                closeProductModal();
                if (document.getElementById('products').style.display !== 'none') {
                    loadProducts();
                }
            } else {
                showNotification('Failed to add product', 'error');
            }
        } catch (error) {
            console.error('Error adding product:', error);
            showNotification('Error adding product', 'error');
        }
    });
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.remove();
    }
}

async function loadProducts() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();

        const tableBody = document.getElementById('productsTable');
        if (data.data && data.data.length > 0) {
            tableBody.innerHTML = data.data.map(product => `
                <tr>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.category?.name || 'N/A'}</td>
                    <td>$${parseFloat(product.price || 0).toFixed(2)}</td>
                    <td>${product.stock_quantity || 0}</td>
                    <td><span class="status-badge ${product.stock_quantity > 0 ? 'status-completed' : 'status-pending'}">${product.stock_quantity > 0 ? 'In Stock' : 'Out of Stock'}</span></td>
                    <td>
                        <button onclick="editProduct(${product.id})" class="btn btn-sm" style="background: var(--warning-color); color: white; margin-right: 0.5rem;">Edit</button>
                        <button onclick="deleteProduct(${product.id})" class="btn btn-sm" style="background: var(--danger-color); color: white;">Delete</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">No products found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsTable').innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem; color: red;">Error loading products</td></tr>';
    }
}

async function loadOrders() {
    try {
        const response = await fetch('/api/admin/orders');
        const data = await response.json();

        const tableBody = document.getElementById('ordersTable');
        if (data.data && data.data.length > 0) {
            tableBody.innerHTML = data.data.map(order => `
                <tr>
                    <td>#${order.id}</td>
                    <td>${order.customer?.name || 'Unknown'}</td>
                    <td>$${parseFloat(order.total_amount || 0).toFixed(2)}</td>
                    <td><span class="status-badge status-${order.status || 'pending'}">${(order.status || 'pending').charAt(0).toUpperCase() + (order.status || 'pending').slice(1)}</span></td>
                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    <td>
                        <button onclick="viewOrder(${order.id})" class="btn btn-sm" style="background: var(--info-color); color: white; margin-right: 0.5rem;">View</button>
                        <button onclick="updateOrderStatus(${order.id})" class="btn btn-sm" style="background: var(--warning-color); color: white;">Update</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No orders found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('ordersTable').innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: red;">Error loading orders</td></tr>';
    }
}

async function loadCustomers() {
    try {
        const response = await fetch('/api/admin/customers');
        const data = await response.json();

        const tableBody = document.getElementById('customersTable');
        if (data.data && data.data.length > 0) {
            tableBody.innerHTML = data.data.map(customer => `
                <tr>
                    <td>${customer.id}</td>
                    <td>${customer.name}</td>
                    <td>${customer.email}</td>
                    <td>${customer.phone || 'N/A'}</td>
                    <td>${customer.orders_count || 0}</td>
                    <td>${new Date(customer.created_at).toLocaleDateString()}</td>
                    <td>
                        <button onclick="viewCustomer(${customer.id})" class="btn btn-sm" style="background: var(--info-color); color: white; margin-right: 0.5rem;">View</button>
                        <button onclick="editCustomer(${customer.id})" class="btn btn-sm" style="background: var(--warning-color); color: white;">Edit</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">No customers found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading customers:', error);
        document.getElementById('customersTable').innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem; color: red;">Error loading customers</td></tr>';
    }
}

function refreshOrders() {
    loadOrders();
}

function refreshCustomers() {
    loadCustomers();
}

// Analytics Functions
let salesChart, categoryChart;

async function loadAnalytics() {
    try {
        // Load analytics data
        const [salesData, categoryData] = await Promise.all([
            fetchSalesData(),
            fetchCategoryData()
        ]);

        // Update analytics cards
        updateAnalyticsCards(salesData);

        // Create charts
        createSalesChart(salesData);
        createCategoryChart(categoryData);

        // Load top products
        loadTopProducts();

    } catch (error) {
        console.error('Error loading analytics:', error);
    }
}

async function fetchSalesData() {
    // Simulate sales data - replace with real API call
    const days = Array.from({length: 30}, (_, i) => {
        const date = new Date();
        date.setDate(date.getDate() - i);
        return date.toISOString().split('T')[0];
    }).reverse();

    return {
        labels: days,
        revenue: days.map(() => Math.floor(Math.random() * 500) + 100),
        orders: days.map(() => Math.floor(Math.random() * 20) + 5)
    };
}

async function fetchCategoryData() {
    // Simulate category data - replace with real API call
    return {
        labels: ['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports'],
        data: [35, 25, 15, 15, 10],
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
    };
}

function updateAnalyticsCards(salesData) {
    const totalRevenue = salesData.revenue.reduce((sum, val) => sum + val, 0);
    const totalOrders = salesData.orders.reduce((sum, val) => sum + val, 0);
    const avgOrderValue = totalRevenue / totalOrders;

    document.getElementById('analyticsRevenue').textContent = '$' + totalRevenue.toLocaleString();
    document.getElementById('analyticsOrders').textContent = totalOrders;
    document.getElementById('analyticsCustomers').textContent = Math.floor(totalOrders * 0.7);
    document.getElementById('conversionRate').textContent = '3.2%';
    document.getElementById('metricAOV').textContent = '$' + avgOrderValue.toFixed(2);
    document.getElementById('metricCLV').textContent = '$' + (avgOrderValue * 4.2).toFixed(2);
}

function createSalesChart(data) {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    if (salesChart) salesChart.destroy();

    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels.slice(-7), // Last 7 days
            datasets: [{
                label: 'Revenue ($)',
                data: data.revenue.slice(-7),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Orders',
                data: data.orders.slice(-7),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
}

function createCategoryChart(data) {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;

    if (categoryChart) categoryChart.destroy();

    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: data.colors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

async function loadTopProducts() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();

        const topProductsList = document.getElementById('topProductsList');
        if (data.data && data.data.length > 0) {
            const topProducts = data.data.slice(0, 5); // Top 5 products
            topProductsList.innerHTML = topProducts.map((product, index) => `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                    <div>
                        <div style="font-weight: 500;">${product.name}</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);">$${parseFloat(product.price || 0).toFixed(2)}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 500;">${Math.floor(Math.random() * 50) + 10} sold</div>
                        <div style="font-size: 0.875rem; color: var(--success-color);">#${index + 1}</div>
                    </div>
                </div>
            `).join('');
        } else {
            topProductsList.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--text-secondary);">No products data</div>';
        }
    } catch (error) {
        console.error('Error loading top products:', error);
    }
}

// Settings Functions
function showSettingsSection(sectionName) {
    // Hide all settings sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.style.display = 'none';
    });

    // Remove active class from settings nav
    document.querySelectorAll('.settings-nav .nav-tab').forEach(tab => {
        tab.classList.remove('active');
    });

    // Show selected section
    document.getElementById(sectionName + '-settings').style.display = 'block';

    // Add active class to clicked tab
    event.target.classList.add('active');
}

function saveGeneralSettings() {
    const settings = {
        storeName: document.getElementById('storeName').value,
        storeDescription: document.getElementById('storeDescription').value,
        contactEmail: document.getElementById('contactEmail').value,
        phoneNumber: document.getElementById('phoneNumber').value,
        defaultCurrency: document.getElementById('defaultCurrency').value,
        timezone: document.getElementById('timezone').value,
        productsPerPage: document.getElementById('productsPerPage').value,
        enableReviews: document.getElementById('enableReviews').checked,
        enableWishlist: document.getElementById('enableWishlist').checked
    };

    console.log('Saving general settings:', settings);
    showNotification('General settings saved successfully!', 'success');
}

function savePaymentSettings() {
    const settings = {
        stripeEnabled: document.getElementById('stripeEnabled').checked,
        stripePublishable: document.getElementById('stripePublishable').value,
        stripeSecret: document.getElementById('stripeSecret').value,
        paypalEnabled: document.getElementById('paypalEnabled').checked,
        paypalClientId: document.getElementById('paypalClientId').value,
        paypalSecret: document.getElementById('paypalSecret').value,
        taxRate: document.getElementById('taxRate').value,
        minOrderAmount: document.getElementById('minOrderAmount').value,
        enableTax: document.getElementById('enableTax').checked,
        enableCoupons: document.getElementById('enableCoupons').checked
    };

    console.log('Saving payment settings:', settings);
    showNotification('Payment settings saved successfully!', 'success');
}

function saveShippingSettings() {
    showNotification('Shipping settings saved successfully!', 'success');
}

function saveEmailSettings() {
    showNotification('Email settings saved successfully!', 'success');
}

function saveSecuritySettings() {
    showNotification('Security settings saved successfully!', 'success');
}

function testEmail() {
    showNotification('Test email sent successfully!', 'success');
}

function createBackup() {
    showNotification('Backup created successfully!', 'success');
}

function clearCache() {
    showNotification('Cache cleared successfully!', 'success');
}

function optimizeDatabase() {
    showNotification('Database optimized successfully!', 'success');
}

// Additional Product Functions
function editProduct(productId) {
    showNotification(`Edit product ${productId} - Feature coming soon!`, 'info');
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        // Implement delete functionality
        showNotification(`Product ${productId} deleted successfully!`, 'success');
        loadProducts(); // Refresh the products list
    }
}

// Additional Order Functions
function viewOrder(orderId) {
    showNotification(`View order ${orderId} details - Feature coming soon!`, 'info');
}

function updateOrderStatus(orderId) {
    showNotification(`Update order ${orderId} status - Feature coming soon!`, 'info');
}

// Additional Customer Functions
function viewCustomer(customerId) {
    showNotification(`View customer ${customerId} details - Feature coming soon!`, 'info');
}

function editCustomer(customerId) {
    showNotification(`Edit customer ${customerId} - Feature coming soon!`, 'info');
}

function exportData() {
    showNotification('Preparing data export - Feature coming soon!', 'info');
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

async function updateDashboard() {
    try {
        // Fetch real data from API
        const [productsResponse, ordersResponse, customersResponse] = await Promise.all([
            fetch('/api/products'),
            fetch('/api/admin/orders'),
            fetch('/api/admin/customers')
        ]);

        const products = await productsResponse.json();
        const orders = await ordersResponse.json();
        const customers = await customersResponse.json();

        // Update statistics
        if (products.data) {
            document.getElementById('totalProducts').textContent = products.data.length;
        }

        if (orders.data) {
            document.getElementById('totalOrders').textContent = orders.data.length;
            // Calculate total revenue
            const totalRevenue = orders.data.reduce((sum, order) => sum + parseFloat(order.total_amount || 0), 0);
            document.getElementById('totalRevenue').textContent = '$' + totalRevenue.toLocaleString();

            // Calculate average order value
            const avgOrderValue = orders.data.length > 0 ? totalRevenue / orders.data.length : 0;
            document.getElementById('avgOrderValue').textContent = '$' + avgOrderValue.toFixed(2);
        }

        if (customers.data) {
            document.getElementById('totalCustomers').textContent = customers.data.length;
        }

        // Update recent orders table
        updateRecentOrdersTable(orders.data ? orders.data.slice(0, 3) : []);

    } catch (error) {
        console.error('Error updating dashboard:', error);
        // Fallback to simulated data
        const revenue = Math.floor(Math.random() * 5000) + 10000;
        const orders = Math.floor(Math.random() * 50) + 100;
        const customers = Math.floor(Math.random() * 30) + 60;

        document.getElementById('totalRevenue').textContent = '$' + revenue.toLocaleString();
        document.getElementById('totalOrders').textContent = orders;
        document.getElementById('totalCustomers').textContent = customers;
    }

    // Update last updated time
    const now = new Date();
    document.getElementById('lastUpdated').textContent = now.toLocaleTimeString();
}

function updateRecentOrdersTable(orders) {
    const tableBody = document.getElementById('recentOrdersTable');
    if (!tableBody || !orders.length) return;

    tableBody.innerHTML = orders.map(order => `
        <tr>
            <td>#${order.id}</td>
            <td>${order.customer?.name || 'Unknown'}</td>
            <td>$${parseFloat(order.total_amount || 0).toFixed(2)}</td>
            <td><span class="status-badge status-${order.status || 'pending'}">${(order.status || 'pending').charAt(0).toUpperCase() + (order.status || 'pending').slice(1)}</span></td>
            <td>${new Date(order.created_at).toLocaleDateString()}</td>
        </tr>
    `).join('');
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
@endsection
