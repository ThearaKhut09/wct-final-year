@extends('layouts.app')

@section('title', 'Login - E-smooth Online')

@section('content')
<style>
    .auth-container {
        max-width: 400px;
        margin: 4rem auto;
        padding: 2rem;
        background: white;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
    }

    [data-theme="dark"] .auth-container {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-header h1 {
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .auth-header p {
        color: var(--text-secondary);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 1rem;
        background: white;
        color: var(--text-primary);
        transition: var(--transition);
    }

    [data-theme="dark"] .form-input {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-error {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: none;
    }

    .form-actions {
        margin-top: 2rem;
    }

    .auth-links {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .auth-links a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .auth-links a:hover {
        text-decoration: underline;
    }

    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .demo-accounts {
        max-width: 400px; 
        margin: 2rem auto; 
        padding: 1rem; 
        background: rgba(37, 99, 235, 0.1); 
        border-radius: 0.5rem; 
        text-align: center;
    }

    .demo-account-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.3rem;
        cursor: pointer;
        font-size: 0.8rem;
        margin: 0.2rem;
    }

    @media (max-width: 768px) {
        .auth-container {
            margin: 2rem 1rem;
            padding: 1.5rem;
        }
    }
</style>

<div class="auth-container">
    <div class="auth-header">
        <h1>Welcome Back</h1>
        <p>Sign in to your E-smooth Online account</p>
    </div>

    <form id="loginForm">
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-input" required>
            <div class="form-error" id="emailError"></div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-input" required>
            <div class="form-error" id="passwordError"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width: 100%;" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </div>
    </form>

    <div class="auth-links">
        <p>Don't have an account? <a href="{{ route('register') }}">Create one here</a></p>
        <p><a href="#" onclick="showForgotPassword()">Forgot your password?</a></p>
    </div>
</div>

<!-- Demo Accounts Info -->
<div class="demo-accounts">
    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Demo Accounts</h4>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.875rem;">
        <div>
            <strong>Admin Account:</strong><br>
            admin@esmooth.com<br>
            <button class="demo-account-btn" onclick="fillDemoAccount('admin')">Fill Admin</button>
        </div>        <div>
            <strong>Customer Account:</strong><br>
            customer@esmooth.com<br>
            <button class="demo-account-btn" onclick="fillDemoAccount('customer')">Fill Customer</button>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const loginBtn = document.getElementById('loginBtn');
    const form = this;
    
    // Clear previous errors
    document.querySelectorAll('.form-error').forEach(error => {
        error.style.display = 'none';
        error.textContent = '';
    });
    
    // Show loading state
    loginBtn.classList.add('loading');
    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
    
    const formData = new FormData(form);
    const data = {
        email: formData.get('email'),
        password: formData.get('password')
    };      try {
        console.log('Sending login request:', data);
        
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        const result = await response.json();
        console.log('Login response:', result);
        
        if (response.ok && result.success) {
            // Store user data and token
            localStorage.setItem('auth_token', result.data.access_token);
            localStorage.setItem('user_data', JSON.stringify(result.data.user));
            
            showNotification('Login successful! Welcome back!', 'success');
            
            // Update UI to show logged in state
            if (typeof updateAuthUI === 'function') {
                updateAuthUI();
            }
            
            // Redirect based on user role
            setTimeout(() => {
                if (result.data.user.role === 'admin') {
                    window.location.href = '/admin';
                } else {
                    window.location.href = '/';
                }
            }, 1000);
            
        } else {
            // Handle error responses
            if (result.errors) {
                // Show validation errors
                Object.keys(result.errors).forEach(field => {
                    const errorElement = document.getElementById(field + 'Error');
                    if (errorElement) {
                        errorElement.textContent = result.errors[field][0];
                        errorElement.style.display = 'block';
                    }
                });
            } else {
                const errorMessage = result.message || 'Login failed. Please check your credentials.';
                showNotification(errorMessage, 'error');
            }
        }
        
    } catch (error) {
        console.error('Login error:', error);
        showNotification('Network error. Please try again.', 'error');
    } finally {
        // Reset button state
        loginBtn.classList.remove('loading');
        loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
    }
});

function showForgotPassword() {
    showNotification('Password reset feature coming soon! Use the demo accounts for now.', 'info');
}

// Auto-fill demo account (for testing)
function fillDemoAccount(type) {
    if (type === 'admin') {
        document.getElementById('email').value = 'admin@esmooth.com';
        document.getElementById('password').value = 'admin123';
    } else {
        document.getElementById('email').value = 'customer@esmooth.com';
        document.getElementById('password').value = 'customer123';
    }
}
</script>
@endsection
