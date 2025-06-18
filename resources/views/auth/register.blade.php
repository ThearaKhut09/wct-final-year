@extends('layouts.app')

@section('title', 'Register - E-smooth Online')

@section('content')
<style>
    .auth-container {
        max-width: 500px;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
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

    @media (max-width: 768px) {
        .auth-container {
            margin: 2rem 1rem;
            padding: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="auth-container">
    <div class="auth-header">
        <h1>Create Account</h1>
        <p>Join E-smooth Online and start shopping today</p>
    </div>

    <form id="registerForm">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" class="form-input" required>
                <div class="form-error" id="firstNameError"></div>
            </div>
            <div class="form-group">
                <label class="form-label" for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="form-input" required>
                <div class="form-error" id="lastNameError"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-input" required>
            <div class="form-error" id="emailError"></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required minlength="8">
                <div class="form-error" id="passwordError"></div>
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                <div class="form-error" id="password_confirmationError"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="phone">Phone Number (Optional)</label>
            <input type="tel" id="phone" name="phone" class="form-input">
            <div class="form-error" id="phoneError"></div>
        </div>

        <div class="form-group">
            <label class="form-label" for="address">Address (Optional)</label>
            <input type="text" id="address" name="address" class="form-input">
            <div class="form-error" id="addressError"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width: 100%;" id="registerBtn">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </div>
    </form>

    <div class="auth-links">
        <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const registerBtn = document.getElementById('registerBtn');
        const form = this;
        
        // Clear previous errors
        document.querySelectorAll('.form-error').forEach(error => {
            error.style.display = 'none';
            error.textContent = '';
        });
        
        // Show loading state
        registerBtn.classList.add('loading');
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        
        const formData = new FormData(form);
        const data = {
            name: formData.get('firstName') + ' ' + formData.get('lastName'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation'),
            phone: formData.get('phone'),
            address: formData.get('address')
        };
        
        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Store user data and token
                localStorage.setItem('auth_token', result.data.access_token);
                localStorage.setItem('user_data', JSON.stringify(result.data.user));
                
                showNotification('Account created successfully! Welcome to E-smooth Online!', 'success');
                
                // Update UI to show logged in state
                updateAuthUI();
                
                // Redirect to home page
                setTimeout(() => {
                    window.location.href = '/';
                }, 1500);
                
            } else {
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
                    showNotification(result.message || 'Registration failed. Please try again.', 'error');
                }
            }
            
        } catch (error) {
            console.error('Registration error:', error);
            showNotification('Network error. Please try again.', 'error');
        } finally {
            // Reset button state
            registerBtn.classList.remove('loading');
            registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Account';
        }
    });
    
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        const errorElement = document.getElementById('password_confirmationError');
        
        if (confirmation && password !== confirmation) {
            errorElement.textContent = 'Passwords do not match';
            errorElement.style.display = 'block';
        } else {
            errorElement.style.display = 'none';
        }
    });
</script>
@endsection
