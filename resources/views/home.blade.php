<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - E-Commerce</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        nav a {
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #2563eb;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        
        .hero-content {
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background-color: #fff;
            color: #667eea;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        /* Stats Section */
        .stats {
            background-color: #fff;
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        
        .stat-item h3 {
            font-size: 2.5rem;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        
        .stat-item p {
            color: #6b7280;
            font-size: 1rem;
        }
        
        /* Products Section */
        .products-section {
            margin-bottom: 3rem;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .section-header h2 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .section-header p {
            color: #6b7280;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .product-card {
            background-color: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-size: 1.5rem;
            color: #2563eb;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .product-stock {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .product-stock.in-stock {
            color: #059669;
        }
        
        .product-stock.low-stock {
            color: #d97706;
        }
        
        .add-to-cart {
            width: 100%;
            padding: 0.75rem;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .add-to-cart:hover {
            background-color: #1d4ed8;
        }
        
        .add-to-cart:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
        }
        
        .empty-state p {
            font-size: 1.125rem;
            color: #6b7280;
        }
        
        /* Footer */
        footer {
            background-color: #1f2937;
            color: #9ca3af;
            padding: 2rem 0;
            margin-top: 4rem;
        }
        
        .footer-content {
            text-align: center;
        }
        
        .footer-content p {
            margin-bottom: 0.5rem;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }
        
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <a href="/" class="logo">{{ config('app.name') }}</a>
                <nav>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/products">Products</a></li>
                        <li><a href="/cart">Cart</a></li>
                        <li><a href="/about">About</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to Our Store</h1>
                <p>Discover amazing products at great prices</p>
                <a href="#products" class="btn">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="container">
        <div class="stats">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-item">
                    <h3>{{ $productsInStock }}</h3>
                    <p>Products In Stock</p>
                </div>
                <div class="stat-item">
                    <h3>100%</h3>
                    <p>Quality Guarantee</p>
                </div>
            </div>
        </div>

        <!-- Featured Products Section -->
        <section class="products-section" id="products">
            <div class="section-header">
                <h2>Featured Products</h2>
                <p>Check out our latest and most popular items</p>
            </div>

            @if($featuredProducts->count() > 0)
                <div class="products-grid">
                    @foreach($featuredProducts as $product)
                        <div class="product-card">
                            <div class="product-image">
                                🛍️
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="product-price">${{ number_format($product->price, 2) }}</div>
                                <p class="product-stock {{ $product->stock > 10 ? 'in-stock' : 'low-stock' }}">
                                    @if($product->stock > 10)
                                        In Stock ({{ $product->stock }} available)
                                    @elseif($product->stock > 0)
                                        Low Stock ({{ $product->stock }} left)
                                    @else
                                        Out of Stock
                                    @endif
                                </p>
                                <button class="add-to-cart" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No products available at the moment. Please check back later!</p>
                </div>
            @endif
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <div class="footer-links">
                    <a href="/privacy">Privacy Policy</a>
                    <a href="/terms">Terms of Service</a>
                    <a href="/contact">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
