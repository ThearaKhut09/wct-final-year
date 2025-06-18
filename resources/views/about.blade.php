@extends('layouts.app')

@section('title', 'About Us - E-smooth Online')

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

    .section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .section-title {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .about-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-bottom: 4rem;
    }

    .about-text {
        font-size: 1.125rem;
        line-height: 1.8;
        color: var(--text-secondary);
    }

    .about-text h3 {
        color: var(--text-primary);
        font-size: 1.5rem;
        margin: 2rem 0 1rem 0;
    }

    .about-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
    }

    .stats {
        background: var(--light-color);
        padding: 4rem 2rem;
        margin: 4rem 0;
    }

    [data-theme="dark"] .stats {
        background: var(--dark-color);
    }

    .stats-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        text-align: center;
    }

    .stat-item {
        padding: 2rem;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1.125rem;
        color: var(--text-secondary);
    }

    .values {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .value-card {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--box-shadow);
        text-align: center;
    }

    [data-theme="dark"] .value-card {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .value-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .value-card h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .value-card p {
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .team {
        background: var(--light-color);
        padding: 4rem 2rem;
    }

    [data-theme="dark"] .team {
        background: var(--dark-color);
    }

    .team-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .team-member {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
    }

    [data-theme="dark"] .team-member {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .team-member:hover {
        transform: translateY(-5px);
    }

    .member-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1rem;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .member-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .member-role {
        color: var(--primary-color);
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .member-bio {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2rem;
        }

        .section {
            padding: 2rem 1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .about-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .stats {
            padding: 2rem 1rem;
        }

        .team {
            padding: 2rem 1rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <h1>About E-smooth Online</h1>
    <p>Your trusted partner in premium online shopping, delivering quality products and exceptional experiences since 2020.</p>
</section>

<!-- About Content -->
<section class="section">
    <div class="about-content">
        <div class="about-text">
            <h3>Our Story</h3>
            <p>E-smooth Online was founded with a simple mission: to make premium shopping accessible to everyone. We started as a small team of passionate entrepreneurs who believed that quality products shouldn't come with complicated shopping experiences.</p>
            
            <h3>Our Mission</h3>
            <p>We strive to provide our customers with the finest selection of products, from cutting-edge electronics to stylish fashion items, all while maintaining the highest standards of customer service and satisfaction.</p>
            
            <h3>Why Choose Us?</h3>
            <p>With over 5 years of experience in e-commerce, we've built a reputation for reliability, quality, and customer-first approach. Our team works tirelessly to ensure every purchase is smooth and every customer is happy.</p>
        </div>
        <div>
            <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop" alt="Our Store" class="about-image">
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number">50K+</div>
            <div class="stat-label">Happy Customers</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">10K+</div>
            <div class="stat-label">Products Sold</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">500+</div>
            <div class="stat-label">Product Varieties</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">99%</div>
            <div class="stat-label">Customer Satisfaction</div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section">
    <h2 class="section-title">Our Values</h2>
    <div class="values">
        <div class="value-card">
            <div class="value-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h3>Customer First</h3>
            <p>Every decision we make is centered around providing the best possible experience for our customers. Your satisfaction is our top priority.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <i class="fas fa-gem"></i>
            </div>
            <h3>Quality Assured</h3>
            <p>We carefully curate our product selection to ensure that every item meets our high standards for quality, durability, and value.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h3>Fast & Reliable</h3>
            <p>With our efficient logistics network, we ensure fast shipping and reliable delivery so you get your products when you need them.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <h3>Sustainable</h3>
            <p>We're committed to sustainable practices, from eco-friendly packaging to supporting brands that care about the environment.</p>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team">
    <div class="section">
        <h2 class="section-title">Meet Our Team</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Sarah Johnson</div>
                <div class="member-role">CEO & Founder</div>
                <div class="member-bio">With over 10 years in e-commerce, Sarah leads our vision of making online shopping delightful and accessible for everyone.</div>
            </div>
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Michael Chen</div>
                <div class="member-role">Head of Technology</div>
                <div class="member-bio">Michael ensures our platform runs smoothly and securely, implementing the latest technologies to enhance user experience.</div>
            </div>
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Emily Rodriguez</div>
                <div class="member-role">Customer Success Manager</div>
                <div class="member-bio">Emily leads our customer support team, making sure every customer inquiry is handled with care and professionalism.</div>
            </div>
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">David Kim</div>
                <div class="member-role">Product Manager</div>
                <div class="member-bio">David curates our product catalog, working with suppliers worldwide to bring you the best selection at competitive prices.</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section">
    <div style="text-align: center; background: var(--primary-color); color: white; padding: 3rem; border-radius: 1rem;">
        <h2 style="font-size: 2rem; margin-bottom: 1rem;">Ready to Experience E-smooth Shopping?</h2>
        <p style="font-size: 1.125rem; margin-bottom: 2rem; opacity: 0.9;">Join thousands of satisfied customers who trust us for their shopping needs.</p>
        <a href="{{ route('products') }}" class="btn" style="background: white; color: var(--primary-color); font-weight: 600;">
            <i class="fas fa-shopping-bag"></i> Start Shopping Now
        </a>
    </div>
</section>
@endsection
