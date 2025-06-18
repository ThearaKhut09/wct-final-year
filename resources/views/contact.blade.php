@extends('layouts.app')

@section('title', 'Contact Us - E-smooth Online')

@section('content')
<style>
    .hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .hero p {
        font-size: 1.25rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .contact-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .info-card {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    [data-theme="dark"] .info-card {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .info-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .info-content h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .info-content p {
        color: var(--text-secondary);
        margin: 0;
    }

    .contact-form {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
    }

    [data-theme="dark"] .contact-form {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .form-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
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

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 1rem;
        background: white;
        color: var(--text-primary);
        transition: var(--transition);
    }

    [data-theme="dark"] .form-input,
    [data-theme="dark"] .form-textarea,
    [data-theme="dark"] .form-select {
        background: var(--dark-color);
        border-color: var(--border-color);
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .faq-section {
        background: var(--light-color);
        padding: 4rem 2rem;
        margin-top: 4rem;
    }

    [data-theme="dark"] .faq-section {
        background: var(--dark-color);
    }

    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .faq-item {
        background: white;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        box-shadow: var(--box-shadow);
        overflow: hidden;
    }

    [data-theme="dark"] .faq-item {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .faq-question {
        width: 100%;
        padding: 1.5rem;
        border: none;
        background: none;
        text-align: left;
        font-size: 1.125rem;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: var(--transition);
    }

    .faq-question:hover {
        background: rgba(37, 99, 235, 0.05);
    }

    .faq-answer {
        padding: 0 1.5rem 1.5rem;
        color: var(--text-secondary);
        line-height: 1.6;
        display: none;
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    .faq-icon {
        transition: var(--transition);
    }

    .faq-item.active .faq-icon {
        transform: rotate(180deg);
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2rem;
        }

        .contact-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .contact-section {
            padding: 2rem 1rem;
        }

        .faq-section {
            padding: 2rem 1rem;
        }

        .section-title {
            font-size: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <h1>Contact Us</h1>
    <p>We're here to help! Get in touch with our friendly support team for any questions or assistance.</p>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="contact-container">
        <!-- Contact Information -->
        <div class="contact-info">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h3>Phone</h3>
                    <p>+1 (555) 123-4567</p>
                    <p>Mon-Fri 9AM-6PM EST</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Email</h3>
                    <p>support@esmooth.com</p>
                    <p>We respond within 24 hours</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h3>Address</h3>
                    <p>123 Commerce Street</p>
                    <p>New York, NY 10001</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 9AM - 6PM</p>
                    <p>Saturday: 10AM - 4PM</p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h2 class="form-title">Send us a Message</h2>
            <form id="contactForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="subject">Subject</label>
                    <select id="subject" name="subject" class="form-select" required>
                        <option value="">Select a subject</option>
                        <option value="order">Order Inquiry</option>
                        <option value="product">Product Question</option>
                        <option value="shipping">Shipping & Returns</option>
                        <option value="technical">Technical Support</option>
                        <option value="general">General Inquiry</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Message</label>
                    <textarea id="message" name="message" class="form-textarea" placeholder="Tell us how we can help you..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="faq-container">
        <h2 class="section-title">Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
                How long does shipping take?
                <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <p>Standard shipping typically takes 3-5 business days. Express shipping is available for 1-2 business days. Free shipping is offered on orders over $50.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
                What is your return policy?
                <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <p>We offer a 30-day return policy for most items. Products must be in original condition with tags attached. Return shipping is free for defective items.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
                Do you ship internationally?
                <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <p>Yes, we ship to over 50 countries worldwide. International shipping rates and delivery times vary by destination. Additional customs fees may apply.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
                How can I track my order?
                <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <p>Once your order ships, you'll receive a tracking number via email. You can use this number to track your package on our website or the carrier's website.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
                What payment methods do you accept?
                <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <p>We accept all major credit cards (Visa, MasterCard, American Express), PayPal, Apple Pay, and Google Pay. All payments are processed securely.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
                Is my personal information secure?
                <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
                <p>Absolutely. We use industry-standard SSL encryption to protect your personal and payment information. We never share your data with third parties without your consent.</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleFAQ(button) {
        const faqItem = button.parentElement;
        const isActive = faqItem.classList.contains('active');
        
        // Close all FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Open clicked item if it wasn't active
        if (!isActive) {
            faqItem.classList.add('active');
        }
    }

    // Contact form submission
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const firstName = formData.get('firstName');
        const lastName = formData.get('lastName');
        const email = formData.get('email');
        const subject = formData.get('subject');
        const message = formData.get('message');
        
        // Simulate form submission
        showNotification('Thank you for your message! We\'ll get back to you within 24 hours.', 'success');
        
        // Reset form
        this.reset();
        
        // In a real application, you would send this data to your backend:
        /*
        fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                firstName,
                lastName,
                email,
                subject,
                message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Message sent successfully!', 'success');
                this.reset();
            } else {
                showNotification('Failed to send message. Please try again.', 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred. Please try again.', 'error');
        });
        */
    });
</script>
@endpush
@endsection
