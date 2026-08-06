<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crackers.com | Premium Festive Crackers & Fireworks Store</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Remix Icons & Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

@php
    // Secondary Color selected in Admin Website Setup -> Appearance is used for Customer Website (1 solid color, no gradient)
    $websiteColor = (isset($appearance) && !empty($appearance->secondary_color)) ? $appearance->secondary_color : '#3a3cdf';
@endphp
    <style>
        :root {
            --primary-color: {{ $websiteColor }};
            --secondary-color: {{ $websiteColor }};
            --bg-dark: #f8fafc;
            --bg-card: #ffffff;
            --border-glow: {{ $websiteColor }}40;
            --gold-gradient: {{ $websiteColor }};
            --fire-red: {{ $websiteColor }};
            --spark-gold: {{ $websiteColor }};
            --text-main: #0f172a;
            --text-muted: #64748b;
            --nav-bg: rgba(255, 255, 255, 0.95);
            --modal-bg: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .text-warning {
            color: var(--primary-color) !important;
        }

        .bg-warning {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
        }

        .btn-warning {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 15px {{ $websiteColor }}40;
        }

        .btn-warning:hover {
            opacity: 0.92;
            color: #ffffff !important;
            box-shadow: 0 6px 20px {{ $websiteColor }}60;
        }

        .btn-outline-warning {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-warning:hover {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
        }

        .border-warning {
            border-color: var(--primary-color) !important;
        }

        .navbar-festive {
            background: var(--nav-bg);
            border-bottom: 1px solid {{ $websiteColor }}30;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-header-bar {
            background-color: {{ $websiteColor }};
            border-bottom: 3px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .nav-header-link {
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-header-link:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .brand-logo {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-banner {
            background-size: cover;
            background-position: center;
            border-radius: 16px;
            padding: 3rem 2rem;
            margin-top: 1.5rem;
            margin-bottom: 2rem;
            min-height: 280px;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .banner-flex-img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            border-radius: 16px;
            display: block;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .hero-banner {
                min-height: 200px;
                padding: 1.5rem 1rem;
            }
            .banner-flex-img {
                max-height: 280px;
            }
        }

        .category-pill {
            background: rgba(148, 163, 184, 0.15);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: var(--text-muted);
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .category-pill:hover, .category-pill.active {
            background: var(--gold-gradient);
            color: #000;
            border-color: transparent;
            box-shadow: 0 4px 20px rgba(251, 133, 0, 0.4);
        }

        .product-card {
            background: var(--bg-card);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 20px;
            padding: 1.25rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 183, 3, 0.5);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.15);
        }

        .btn-wishlist {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: rgba(255, 255, 255, 0.95);
            color: #dc3545;
            border: 1px solid rgba(0, 0, 0, 0.08);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .btn-wishlist.active, .btn-wishlist:hover {
            background: #ffffff;
            color: #dc3545;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .product-badge-discount {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--fire-red);
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            z-index: 10;
        }

        .product-img-box {
            background: rgba(148, 163, 184, 0.08);
            border-radius: 14px;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 3.5rem;
            color: var(--spark-gold);
        }

        .price-current {
            font-size: 1.35rem;
            font-weight: 800;
            color: #22c55e;
        }

        .btn-add-cart {
            background: var(--gold-gradient);
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            width: 100%;
            transition: all 0.25s ease;
        }

        .btn-add-cart:hover {
            box-shadow: 0 6px 20px rgba(251, 133, 0, 0.4);
        }

        .theme-toggle-btn {
            background: rgba(148, 163, 184, 0.15);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: var(--text-main);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .payment-box {
            background: rgba(148, 163, 184, 0.1);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 0.75rem;
        }
    </style>
</head>
<body>

    <!-- 1. TOP ANNOUNCEMENT STRIP (Solid Secondary Color, No Gradient) -->
    <div class="top-header-strip py-2 text-white shadow-sm overflow-hidden" style="background-color: {{ $websiteColor }}; font-size: 0.95rem; font-weight: 600;">
        <div class="container-fluid px-2">
            <marquee direction="left" scrollamount="6" behavior="scroll" onmouseover="this.stop();" onmouseout="this.start();" class="align-middle text-white fw-bold">
                <span class="me-5"><i class="ri-leaf-fill text-warning fs-5 align-middle me-1"></i> 🌿 100% Green Crackers Approved & Certified Safe</span>
                <span class="me-5"><i class="ri-map-pin-2-fill text-warning fs-5 align-middle me-1"></i> 🏭 Sivakasi Factory Direct Hub - Pure Quality Guaranteed</span>
                <span class="me-5"><i class="ri-whatsapp-line text-white fs-5 align-middle me-1"></i> 📱 WhatsApp Support: +91 98765 43210</span>
                <span class="me-5"><i class="ri-sparkling-fill text-warning fs-5 align-middle me-1"></i> ✨ GST {{ $settings->gst_percentage ?? 18 }}% Included at Checkout - Fast Doorstep Dispatch!</span>
            </marquee>
        </div>
    </div>

    <!-- 2. MAIN BRANDING & ACTION HEADER -->
    <header class="py-3 bg-white border-bottom shadow-sm">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
            <!-- Brand Logo & Company Slogan -->
            <a class="navbar-brand brand-logo text-decoration-none d-flex align-items-center gap-2" href="{{ route('crackers.storefront') }}">
                @if(isset($appearance) && !empty($appearance->logo))
                    <img src="{{ asset('storage/' . $appearance->logo) }}" alt="Logo" class="img-fluid" style="max-height: 48px; max-width: 160px; object-fit: contain;">
                @else
                    <i class="ri-fire-fill text-warning fs-1"></i>
                @endif
                <div>
                    <span class="brand-font fw-bold d-block" style="font-size: 1.85rem; background: var(--gold-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $companyDetail->company_name ?? ($appearance->title ?? 'Crackers.com') }}
                    </span>
                    <small class="d-block text-muted small fst-italic" style="font-size: 0.75rem; margin-top: -6px;">
                        {{ $companyDetail->company_slogan ?? ($appearance->subtitle ?? 'Festive Fireworks Direct Store') }}
                    </small>
                </div>
            </a>

            <!-- Search Input Bar -->
            <form method="GET" action="{{ route('crackers.storefront') }}" class="d-none d-md-flex align-items-center gap-2 flex-grow-1 mx-lg-5" style="max-width: 420px;">
                <input type="hidden" name="type" value="{{ $customerType }}">
                <input type="hidden" name="category" value="{{ $category }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded-start-pill ps-3" placeholder="Search crackers, sparklers..." value="{{ $search }}">
                    <button type="submit" class="btn btn-warning rounded-end-pill px-3" style="background: var(--gold-gradient); color:#000;"><i class="ri-search-line fw-bold"></i></button>
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <!-- Wishlist Icon Button -->
                <button class="btn btn-light border rounded-circle shadow-sm position-relative d-flex align-items-center justify-content-center p-0 me-1" style="width: 42px; height: 42px;" data-bs-toggle="offcanvas" data-bs-target="#wishlistOffcanvas" title="My Wishlist">
                    <i class="ri-heart-3-line fs-4 text-danger"></i>
                    <span id="wishlistCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger font-monospace" style="font-size: 0.7rem;">0</span>
                </button>

                @auth
                    <!-- Customer Account Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-warning text-dark rounded-pill px-3 fw-bold dropdown-toggle d-flex align-items-center gap-1 shadow-sm" type="button" data-bs-toggle="dropdown">
                            <i class="ri-user-smile-line text-warning fs-5"></i> {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                            <li><a class="dropdown-item py-2" href="{{ route('crackers.my-orders') }}"><i class="ri-shopping-bag-3-line text-warning me-2 fs-5 align-middle"></i> My Orders</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('crackers.profile') }}"><i class="ri-user-settings-line text-primary me-2 fs-5 align-middle"></i> My Profile & Address</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('crackers.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="ri-logout-box-r-line me-2 fs-5 align-middle"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Customer Login / Register Button -->
                    <a href="{{ route('crackers.login-page') }}" class="btn btn-outline-warning text-dark rounded-pill px-3 fw-bold d-flex align-items-center gap-1 shadow-sm">
                        <i class="ri-user-line text-warning fs-5"></i> Login / Register
                    </a>
                @endauth

                <!-- Cart Button -->
                <button class="btn btn-warning rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" style="background: var(--gold-gradient); color:#000;">
                    <i class="ri-shopping-cart-fill fs-5"></i> Cart (<span id="cartCount">0</span>)
                </button>
            </div>
        </div>
    </header>

    <!-- 3. DEDICATED SEPARATE MAIN NAVIGATION HEADER BAR -->
    <div class="nav-header-bar py-2">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
            <!-- Dedicated Navigation Bar Header Links -->
            <div class="d-flex align-items-center gap-1 gap-md-2 overflow-x-auto py-1">
                <a class="nav-header-link" href="#home">
                    <i class="ri-home-4-line text-warning me-1"></i> Home
                </a>
                <a class="nav-header-link" href="#catalog">
                    <i class="ri-fire-line text-warning me-1"></i> Products & Categories
                </a>
                <a class="nav-header-link" href="#about-us">
                    <i class="ri-information-line text-warning me-1"></i> About Us
                </a>
                <a class="nav-header-link" href="#safety-tips">
                    <i class="ri-shield-cross-line text-warning me-1"></i> Safety Tips (Do's & Don'ts)
                </a>
            </div>

            <!-- Store Mode Switcher -->
            <div class="d-flex align-items-center gap-2 py-1">
                <span class="small text-light fw-semibold me-1 d-none d-sm-inline">Store Mode:</span>
                <a href="{{ route('crackers.storefront', ['type' => 'retail', 'category' => $category, 'search' => $search]) }}" class="btn btn-xs btn-sm {{ $customerType === 'retail' ? 'btn-primary fw-bold' : 'btn-outline-light' }} rounded-pill px-3">
                    🛍️ Retail Store
                </a>
                <a href="{{ route('crackers.storefront', ['type' => 'wholesale', 'category' => $category, 'search' => $search]) }}" class="btn btn-xs btn-sm {{ $customerType === 'wholesale' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning' }} rounded-pill px-3">
                    🏭 Wholesale Bulk
                </a>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">

        <!-- Hero Dynamic Banner Slider Carousel -->
        <div id="home" class="pt-4 pt-md-5 mb-5">
            @if(isset($heroBanners) && $heroBanners->count() > 0)
                <div id="heroCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="5000">
                    @if($heroBanners->count() > 1)
                        <div class="carousel-indicators mb-3">
                            @foreach($heroBanners as $idx => $b)
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    @endif
                    <div class="carousel-inner">
                        @foreach($heroBanners as $idx => $b)
                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                @if(empty(trim($b->title)) && empty(trim($b->description)))
                                    <!-- Full Graphical Banner (Original Colors & Flexible Natural Aspect Ratio) -->
                                    <a href="{{ $b->link ?: '#catalog' }}" class="d-block w-100 text-center">
                                        <img src="{{ asset($b->image_path) }}" class="d-block w-100 banner-flex-img shadow-lg" alt="Festive Banner">
                                    </a>
                                @else
                                    <!-- Banner with Custom Title, Description & Button Overlay (Left Aligned Above Original Image) -->
                                    <div class="hero-banner shadow-lg" style="background: url('{{ asset($b->image_path) }}') center/cover no-repeat;">
                                        <div class="row align-items-center justify-content-start">
                                            <div class="col-lg-7 text-start ps-3 ps-md-5">
                                                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3 shadow">
                                                    <i class="ri-sparkling-fill me-1"></i> GST {{ $settings->gst_percentage }}% Included at Checkout
                                                </span>
                                                <h1 class="text-white fw-bold display-5 mb-3" style="text-shadow: 0 2px 12px rgba(0,0,0,0.85);">{{ $b->title }}</h1>
                                                @if(!empty($b->description))
                                                    <p class="text-white lead mb-4 fw-semibold" style="text-shadow: 0 2px 10px rgba(0,0,0,0.85);">{{ $b->description }}</p>
                                                @endif
                                                <a href="{{ $b->link ?: '#catalog' }}" class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow" style="background: var(--gold-gradient); color:#000;">
                                                    <i class="ri-fire-line me-1"></i> Explore Fireworks Catalog
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if($heroBanners->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <!-- Default Hero Banner (Left Aligned Above Image) -->
                <div class="hero-banner shadow-lg rounded-4" style="background: url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat;">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-lg-7 text-start ps-3 ps-md-5">
                            <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3 shadow">
                                <i class="ri-sparkling-fill me-1"></i> GST {{ $settings->gst_percentage }}% Included at Checkout
                            </span>
                            <h1 class="text-white fw-bold display-5 mb-3" style="text-shadow: 0 2px 12px rgba(0,0,0,0.85);">Light Up Celebrations With Crackers.com!</h1>
                            <p class="text-white lead mb-4 fw-semibold" style="text-shadow: 0 2px 10px rgba(0,0,0,0.85);">Purely Premium Firecrackers, Sparklers, Flower Pots & Gift Boxes Delivered Direct.</p>
                            <a href="#catalog" class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow" style="background: var(--gold-gradient); color:#000;">
                                <i class="ri-fire-line me-1"></i> Explore Fireworks Catalog
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Search & Category Filters -->
        <div id="catalog" class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <div>
                    <h3 class="fw-bold mb-1"><i class="ri-sparkling-2-line text-warning me-2"></i> Crackers Catalog</h3>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="small text-muted fw-bold me-1">Pricing Mode:</span>
                        <a href="{{ route('crackers.storefront', ['type' => 'retail', 'category' => $category, 'search' => $search]) }}" class="btn btn-xs btn-sm {{ $customerType === 'retail' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                            🛍️ Retail Store
                        </a>
                        <a href="{{ route('crackers.storefront', ['type' => 'wholesale', 'category' => $category, 'search' => $search]) }}" class="btn btn-xs btn-sm {{ $customerType === 'wholesale' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning' }} rounded-pill px-3">
                            🏭 Wholesale Bulk
                        </a>
                    </div>
                </div>
                
                <form method="GET" action="{{ route('crackers.storefront') }}" class="d-flex gap-2">
                    <input type="hidden" name="type" value="{{ $customerType }}">
                    <input type="hidden" name="category" value="{{ $category }}">
                    <input type="text" name="search" class="form-control rounded-pill px-3" placeholder="Search crackers..." value="{{ $search }}">
                    <button type="submit" class="btn btn-warning rounded-pill"><i class="ri-search-line"></i></button>
                </form>
            </div>

            <!-- Categories Pills -->
            <div class="d-flex gap-2 overflow-x-auto pb-2 mb-4">
                @foreach($categories as $cat)
                    <a href="{{ route('crackers.storefront', ['type' => $customerType, 'category' => $cat, 'search' => $search]) }}" 
                       class="category-pill {{ ($category === $cat || (empty($category) && $cat === 'All')) ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Products Grid & Sticky Right-Side Order Summary Layout -->
        <div class="row g-4 mb-5">
            <!-- Left Side: Products Grid -->
            <div class="col-lg-7 col-xl-8">
                <div class="row g-3 g-md-4">
                    @forelse($products as $product)
                        @php
                            $activePrice = ($customerType === 'wholesale' && $product->wholesale_price) 
                                ? $product->wholesale_price 
                                : ($product->discount_price ?: $product->price);
                        @endphp
                        <div class="col-6 col-md-6 col-lg-6 col-xl-4">
                            <div class="product-card h-100 d-flex flex-column justify-content-between">
                                <!-- Wishlist Toggle Button -->
                                <button class="btn-wishlist" id="wishlistBtn{{ $product->id }}" onclick="event.stopPropagation(); toggleWishlist({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $activePrice }})" title="Add to Wishlist">
                                    <i class="ri-heart-line"></i>
                                </button>

                                @if($product->stock <= 0)
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill">Out of Stock</span>
                                @elseif($customerType === 'wholesale' && $product->wholesale_price)
                                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 px-2 py-1 rounded-pill fw-bold">Wholesale Price</span>
                                @elseif($product->discount_price && $product->price > $product->discount_price)
                                    @php $discountPct = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                                    <span class="product-badge-discount">{{ $discountPct }}% OFF</span>
                                @endif

                                @php
                                    $prodImgs = is_array($product->images) && count($product->images) > 0 
                                        ? $product->images 
                                        : ($product->image ? [$product->image] : []);
                                    $mainImg = $prodImgs[0] ?? null;
                                    $hoverImg = $prodImgs[1] ?? $mainImg;
                                    $prodJson = [
                                        'id' => $product->id,
                                        'name' => $product->name,
                                        'category' => $product->category,
                                        'unit' => $product->unit,
                                        'price' => floatval($product->price),
                                        'discount_price' => $product->discount_price ? floatval($product->discount_price) : null,
                                        'wholesale_price' => $product->wholesale_price ? floatval($product->wholesale_price) : null,
                                        'active_price' => floatval($activePrice),
                                        'stock' => intval($product->stock),
                                        'description' => $product->description ?: 'High quality, certified green festive cracker product.',
                                        'images' => array_values(array_map(function($img) { return asset($img); }, $prodImgs))
                                    ];
                                @endphp
                                <div class="product-img-box text-center p-2 position-relative overflow-hidden cursor-pointer" 
                                     onclick='openProductQuickView(@json($prodJson))'
                                     @if(count($prodImgs) > 1)
                                         onmouseenter="let img=this.querySelector('.product-card-img'); if(img){ img.src='{{ asset($hoverImg) }}'; }"
                                         onmouseleave="let img=this.querySelector('.product-card-img'); if(img){ img.src='{{ asset($mainImg) }}'; }"
                                     @endif
                                >
                                    @if($mainImg)
                                        <img src="{{ asset($mainImg) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded product-card-img transition-all" 
                                             style="max-height: 130px; object-fit: contain; width: 100%; transition: opacity 0.3s ease, transform 0.3s ease;"
                                        >
                                        @if(count($prodImgs) > 1)
                                            <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 end-0 m-2 rounded-pill px-2 py-1" style="font-size: 0.65rem; z-index: 2;">
                                                <i class="ri-image-line me-1 text-warning"></i> +{{ count($prodImgs) - 1 }}
                                            </span>
                                        @endif
                                    @else
                                        @php
                                            $iconMap = [
                                                'Sparklers' => 'ri-magic-line',
                                                'Flower Pots' => 'ri-fire-line',
                                                'Chakkars' => 'ri-restart-line',
                                                'Rockets' => 'ri-rocket-line',
                                                'Sound Crackers' => 'ri-volume-up-line',
                                                'Gift Boxes' => 'ri-gift-line',
                                            ];
                                            $iconClass = $iconMap[$product->category] ?? 'ri-sparkling-fill';
                                        @endphp
                                        <i class="{{ $iconClass }}"></i>
                                    @endif
                                </div>

                                <div>
                                    <span class="text-warning small font-monospace"><i class="ri-price-tag-3-line me-1"></i>{{ $product->category }}</span>
                                    <h5 class="product-title text-truncate cursor-pointer" onclick='openProductQuickView(@json($prodJson))'>{{ $product->name }}</h5>
                                    <div class="small text-muted mb-2">
                                        {{ $product->unit }}
                                        @if($product->stock > 0 && $product->stock <= 10)
                                            <span class="text-warning fw-bold ms-2"><i class="ri-error-warning-line me-1"></i>Only {{ $product->stock }} left!</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        @if($customerType === 'wholesale' && $product->wholesale_price)
                                            <div class="d-flex align-items-baseline gap-2">
                                                <span class="price-current text-warning fw-bold">₹{{ number_format($product->wholesale_price, 2) }}</span>
                                            </div>
                                            <small class="text-muted text-decoration-line-through d-block">Retail: ₹{{ number_format($product->discount_price ?: $product->price, 2) }}</small>
                                        @else
                                            <div class="d-flex align-items-baseline gap-2">
                                                <span class="price-current">₹{{ number_format($product->discount_price ?: $product->price, 2) }}</span>
                                                @if($product->discount_price)
                                                    <span class="small text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </div>
                                            @if($product->wholesale_price)
                                                <div class="small text-warning fw-semibold mt-1"><i class="ri-store-3-line me-1"></i>Wholesale Rate: ₹{{ number_format($product->wholesale_price, 2) }}</div>
                                            @endif
                                        @endif
                                    </div>

                                    <div id="cartActionBox{{ $product->id }}" data-name="{{ addslashes($product->name) }}" data-price="{{ $activePrice }}" data-unit="{{ $product->unit }}">
                                        @if($product->stock > 0)
                                            <button class="btn-add-cart" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $activePrice }}, '{{ $product->unit }}')">
                                                <i class="ri-shopping-cart-add-line me-1"></i> Add To Cart
                                            </button>
                                        @else
                                            <button class="btn btn-secondary w-100 rounded-pill py-2" disabled>
                                                <i class="ri-close-circle-line me-1"></i> Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="ri-ghost-line display-3"></i>
                            <h4 class="mt-3">No crackers found.</h4>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Sticky Live Order Summary Box -->
            <div class="col-lg-5 col-xl-4 d-none d-lg-block">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 90px; background: var(--bg-card); color: var(--text-main); border: 1px solid rgba(251, 133, 0, 0.25) !important;">
                    <div class="card-header bg-warning bg-opacity-10 border-0 py-3 px-3 rounded-top-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold text-dark" style="font-family: 'Outfit', sans-serif;">
                            <i class="ri-file-list-3-line text-warning me-2 fs-5"></i> Order Summary
                        </h6>
                        <span id="rightCartCountBadge" class="badge bg-warning text-dark font-monospace px-2 py-1 rounded-pill">0 Items</span>
                    </div>

                    <div class="card-body p-3">
                        <!-- Mini Cart Items Preview -->
                        <div id="rightCartItemsList" class="mb-3 overflow-y-auto" style="max-height: 260px;">
                            <div class="text-center py-4 text-muted">
                                <i class="ri-shopping-cart-line fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                                <small>Your order cart is empty.</small>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="border-top pt-3 mb-3">
                            <div class="d-flex justify-content-between text-muted small mb-2">
                                <span>Subtotal:</span>
                                <span id="rightCartSubtotal" class="fw-bold text-dark">₹0.00</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mb-2">
                                <span>GST ({{ $settings->gst_percentage }}%):</span>
                                <span id="rightCartGst" class="fw-bold text-dark">₹0.00</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 text-dark border-top pt-2 mt-2">
                                <span>Grand Total:</span>
                                <span id="rightCartTotal" class="text-success fw-bold">₹0.00</span>
                            </div>
                        </div>

                        <!-- Proceed to Checkout Button -->
                        <a href="{{ route('crackers.checkout-page') }}" class="btn btn-warning w-100 py-3 rounded-pill fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2 text-dark text-decoration-none" id="rightCheckoutBtn" style="background: var(--gold-gradient);">
                            <i class="ri-shopping-bag-3-line fs-5"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Us Section -->
        <div id="about-us" class="my-5 pt-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%); border: 1px solid rgba(251, 133, 0, 0.2) !important;">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3">
                                <i class="ri-award-fill me-1"></i> Certified Sivakasi Fireworks Direct
                            </span>
                            <h2 class="fw-bold text-dark display-6 mb-3">About Crackers.com</h2>
                            <p class="text-muted lead mb-4" style="font-size: 1.05rem; line-height: 1.7;">
                                Welcome to <strong>Crackers.com</strong> (S.R. Traders) — India's premier online platform for certified green crackers, sparklers, flower pots, sky rockets, and festive gift boxes straight from Sivakasi manufacturing hubs.
                            </p>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar bg-warning text-dark rounded-circle p-2 flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ri-leaf-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">100% Green Crackers</h6>
                                            <small class="text-muted">Supreme Court & CSIR-NEERI approved low-smoke formulation.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar bg-danger text-white rounded-circle p-2 flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ri-price-tag-3-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Direct Factory Pricing</h6>
                                            <small class="text-muted">Wholesale direct rates with zero middleman markup.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar bg-success text-white rounded-circle p-2 flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ri-shield-check-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Moisture-Proof Packing</h6>
                                            <small class="text-muted">Heavy-duty vacuum box packing keeps crackers 100% dry & safe.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar bg-info text-white rounded-circle p-2 flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ri-truck-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Fast & Safe Transit</h6>
                                            <small class="text-muted">Dedicated licensed logistics network across major cities.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src="https://images.unsplash.com/photo-1531844251246-9a1bfaae09fc?q=80&w=800&auto=format&fit=crop" alt="Festive Crackers Celebration" class="img-fluid rounded-4 shadow-lg border border-warning" style="max-height: 380px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Safety Tips Section (Do's & Don'ts) -->
        <div id="safety-tips" class="my-5 pt-3">
            <div class="text-center mb-4">
                <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill mb-2">
                    <i class="ri-shield-cross-fill me-1"></i> Safety First
                </span>
                <h2 class="fw-bold display-6 mb-2">Fireworks Safety Guidelines: Do's & Don'ts</h2>
                <p class="text-muted lead mx-auto" style="max-width: 680px;">Follow these vital statutory safety guidelines issued by the Petroleum & Explosives Safety Organization (PESO) for a joyful, accident-free festival.</p>
            </div>

            <div class="row g-4">
                <!-- DO'S CARD -->
                <div class="col-lg-6">
                    <div class="card h-100 border-success shadow-sm rounded-4">
                        <div class="card-header bg-success text-white py-3 rounded-top-4 d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill fs-3 me-2 text-warning"></i>
                            <h4 class="mb-0 text-white fw-bold brand-font">DO'S (Safe Practices)</h4>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-group list-group-flush border-0">
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="ri-check-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Burst Crackers Outdoors in Open Spaces</strong>
                                        <small class="text-muted">Always ignite fireworks in open ground, clear of electrical poles, dry grass, or parked vehicles.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="ri-check-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Use Long Sparklers or Incense Sticks (Agarbatti)</strong>
                                        <small class="text-muted">Ignite crackers from an arm's length distance using long agarbatti to keep a safe body gap.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="ri-check-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Keep Buckets of Water & Sand Handy</strong>
                                        <small class="text-muted">Always keep a bucket filled with clean water and dry sand nearby for immediate emergency extinguishing.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="ri-check-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Wear Well-Fitted Cotton Clothing</strong>
                                        <small class="text-muted">Wear 100% cotton garments while bursting crackers. Avoid synthetic, nylon, or loose hanging clothes.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="ri-check-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Supervise Children at All Times</strong>
                                        <small class="text-muted">Ensure an adult is present to guide and monitor children while lighting sparklers or flower pots.</small>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- DON'TS CARD -->
                <div class="col-lg-6">
                    <div class="card h-100 border-danger shadow-sm rounded-4">
                        <div class="card-header bg-danger text-white py-3 rounded-top-4 d-flex align-items-center">
                            <i class="ri-close-circle-fill fs-3 me-2 text-warning"></i>
                            <h4 class="mb-0 text-white fw-bold brand-font">DON'TS (Important Precautions)</h4>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-group list-group-flush border-0">
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-danger-subtle text-danger rounded-circle p-2"><i class="ri-close-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Never Ignite Crackers Indoors or on Balconies</strong>
                                        <small class="text-muted">Do not light crackers inside rooms, staircases, covered verandas, or narrow balconies.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-danger-subtle text-danger rounded-circle p-2"><i class="ri-close-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Never Bend Over Lit Crackers</strong>
                                        <small class="text-muted">Never lean over fireworks while lighting them or inspect misfired crackers closely.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-danger-subtle text-danger rounded-circle p-2"><i class="ri-close-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Never Attempt to Re-Ignite Misfired Fireworks</strong>
                                        <small class="text-muted">If a cracker fails to explode, never try to relight it. Pour water over it and discard safely.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-danger-subtle text-danger rounded-circle p-2"><i class="ri-close-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Never Carry Crackers in Pockets or Throw Them</strong>
                                        <small class="text-muted">Do not store fireworks in trouser pockets or throw lit crackers at people, animals, or vehicles.</small>
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2">
                                    <span class="badge bg-danger-subtle text-danger rounded-circle p-2"><i class="ri-close-line fs-5 fw-bold"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">Never Buy Uncertified or Illegal Crackers</strong>
                                        <small class="text-muted">Avoid uncertified or banned fireworks. Only purchase Supreme Court approved green crackers.</small>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Festive Features Banner -->
    <div class="py-4 my-5" style="background: rgba(148, 163, 184, 0.05); border-top: 1px solid rgba(148, 163, 184, 0.15); border-bottom: 1px solid rgba(148, 163, 184, 0.15);">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ri-shield-check-fill text-warning fs-1 mb-2"></i>
                        <h6 class="fw-bold mb-1">100% Safe & Tested</h6>
                        <small class="text-muted">Certified Green & Standard Fireworks</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ri-truck-fill text-success fs-1 mb-2"></i>
                        <h6 class="fw-bold mb-1">Express Delivery</h6>
                        <small class="text-muted">Fast & Safe Doorstep Dispatch</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ri-price-tag-3-fill text-danger fs-1 mb-2"></i>
                        <h6 class="fw-bold mb-1">Best Festival Discounts</h6>
                        <small class="text-muted">Up to 50% Off Retail Prices</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ri-customer-service-2-fill text-info fs-1 mb-2"></i>
                        <h6 class="fw-bold mb-1">Festive Support</h6>
                        <small class="text-muted">Dedicated Customer Care</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Website Footer -->
    <footer class="pt-5 pb-4" style="background: var(--nav-bg); border-top: 2px solid {{ $websiteColor }}40;">
        <div class="container">
            
            <!-- Supreme Court 2018 Order Statutory Legal Disclaimer Notice Box -->
            <div class="card bg-white border-warning mb-4 rounded-4 shadow-sm p-4 text-start">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2 text-danger">
                        <i class="ri-scales-3-line fs-3"></i>
                        <h5 class="fw-bold mb-0 text-uppercase text-danger" style="font-family: 'Outfit', sans-serif;">Statutory Compliance & Legal Disclaimer</h5>
                    </div>
                    <span class="badge bg-danger text-white rounded-pill px-3 py-2 fs-6 fw-bold">License No: {{ $settings->license_number ?? 'LE/5/1234/2026' }}</span>
                </div>
                <p class="mb-0 text-dark" style="font-size: 0.95rem; line-height: 1.7; text-align: justify; font-weight: 500;">
                    {{ $settings->supreme_court_disclaimer }}
                </p>
            </div>

            <div class="row g-4 mb-4">
                <!-- Brand Info & Slogan -->
                <div class="col-lg-4 col-md-6">
                    <a class="brand-logo mb-2 text-decoration-none d-inline-block" href="{{ route('crackers.storefront') }}">
                        <i class="ri-fire-fill text-warning fs-3"></i> S.R. TRADERS (Crackers.com)
                    </a>
                    @if($settings->company_slogan)
                        <div class="fst-italic text-warning fw-bold fs-6 mb-2"><i class="ri-double-quotes-l"></i> {{ $settings->company_slogan }} <i class="ri-double-quotes-r"></i></div>
                    @endif
                    <p class="text-dark pe-lg-4 fw-medium" style="font-size: 0.95rem; line-height: 1.6;">Your trusted source for 100% legal, certified festive crackers, sparklers, ground chakkars, aerial rockets, and family gift hampers. Celebrating happiness safely!</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="btn btn-outline-warning rounded-circle px-2 py-1"><i class="ri-facebook-fill fs-5"></i></a>
                        <a href="#" class="btn btn-outline-warning rounded-circle px-2 py-1"><i class="ri-instagram-line fs-5"></i></a>
                        <a href="https://wa.me/919876543210" target="_blank" class="btn btn-outline-success rounded-circle px-2 py-1"><i class="ri-whatsapp-line fs-5"></i></a>
                        <a href="#" class="btn btn-outline-danger rounded-circle px-2 py-1"><i class="ri-youtube-fill fs-5"></i></a>
                    </div>
                </div>

                <!-- Cracker Categories (Dynamic From DB) -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold text-warning mb-3" style="font-family: 'Outfit', sans-serif;"><i class="ri-sparkling-line me-1"></i> Cracker Categories</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-dark mb-0 fw-semibold custom-scroll" style="font-size: 0.95rem; max-height: 240px; overflow-y: auto;">
                        @php
                            $catList = is_array($categories) ? $categories : (isset($categories) ? $categories->toArray() : []);
                            $filteredCats = array_filter($catList, function($c) { return $c !== 'All'; });
                        @endphp
                        @forelse($filteredCats as $catItem)
                            <li>
                                <a href="{{ route('crackers.storefront', ['category' => is_object($catItem) ? $catItem->name : $catItem]) }}" class="text-dark text-decoration-none hover-warning d-flex align-items-center">
                                    <i class="ri-arrow-right-s-line text-warning me-1"></i> {{ is_object($catItem) ? $catItem->name : $catItem }}
                                </a>
                            </li>
                        @empty
                            <li><a href="#catalog" class="text-dark text-decoration-none"><i class="ri-arrow-right-s-line text-warning me-1"></i> Sparklers & Fancy Sticks</a></li>
                            <li><a href="#catalog" class="text-dark text-decoration-none"><i class="ri-arrow-right-s-line text-warning me-1"></i> Flower Pots & Fountains</a></li>
                            <li><a href="#catalog" class="text-dark text-decoration-none"><i class="ri-arrow-right-s-line text-warning me-1"></i> Ground Chakkars & Spinners</a></li>
                            <li><a href="#catalog" class="text-dark text-decoration-none"><i class="ri-arrow-right-s-line text-warning me-1"></i> Sky Rockets & Multi-Shots</a></li>
                            <li><a href="#catalog" class="text-dark text-decoration-none"><i class="ri-arrow-right-s-line text-warning me-1"></i> Family Gift Hampers</a></li>
                        @endforelse
                    </ul>
                </div>

                <!-- Legal Policies -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold text-warning mb-3" style="font-family: 'Outfit', sans-serif;"><i class="ri-file-shield-2-line me-1"></i> Customer Policies</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-dark mb-0 fw-semibold" style="font-size: 0.95rem;">
                        <li><a href="{{ route('crackers.policy', 'terms') }}" class="text-dark text-decoration-none"><i class="ri-article-line text-warning me-1"></i> Terms & Conditions</a></li>
                        <li><a href="{{ route('crackers.policy', 'privacy') }}" class="text-dark text-decoration-none"><i class="ri-shield-user-line text-warning me-1"></i> Privacy Policy</a></li>
                        <li><a href="{{ route('crackers.policy', 'shipping') }}" class="text-dark text-decoration-none"><i class="ri-truck-line text-warning me-1"></i> Shipping & Return Policy</a></li>
                    </ul>
                </div>

                <!-- Dynamic Contact Info & Google Map Location -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold text-warning mb-3" style="font-family: 'Outfit', sans-serif;"><i class="ri-phone-find-line me-1"></i> Shop Location & Support</h5>
                    <div class="d-flex flex-column gap-2 text-dark mb-3 fw-semibold" style="font-size: 0.95rem;">
                        @if($settings->support_address)
                            <div><i class="ri-map-pin-line text-warning me-2 fs-6"></i> {{ $settings->support_address }}</div>
                        @endif
                        @if($settings->support_phone)
                            <div><i class="ri-phone-line text-warning me-2 fs-6"></i> {{ $settings->support_phone }}</div>
                        @endif
                        @if($settings->support_email)
                            <div><i class="ri-mail-line text-warning me-2 fs-6"></i> {{ $settings->support_email }}</div>
                        @endif
                        @if($settings->support_hours)
                            <div><i class="ri-time-line text-warning me-2 fs-6"></i> {{ $settings->support_hours }}</div>
                        @endif
                    </div>

                    @if($settings->google_map_embed)
                        <div class="rounded-3 overflow-hidden border shadow-sm" style="height: 130px;">
                            <iframe src="{{ $settings->google_map_embed }}" width="100%" height="130" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="border-top border-secondary pt-3 mt-3 d-flex flex-wrap justify-content-between align-items-center text-dark fw-semibold" style="font-size: 0.95rem;">
                <div>&copy; {{ date('Y') }} <strong class="text-warning">S.R. TRADERS (Crackers.com)</strong>. All Rights Reserved. Purely Festive Crackers Store.</div>
                <div class="d-flex gap-3">
                    <span><i class="ri-shield-line me-1 text-success fs-6"></i> 100% Legal & Statutory Compliant</span>
                    <span><i class="ri-bank-card-line me-1 text-info fs-6"></i> COD / UPI / Bank Transfer</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Product Quick View Popup Modal -->
    <div class="modal fade" id="productQuickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: var(--modal-bg); color: var(--text-main);">
                <div class="modal-header border-0 pb-0 pe-4 pt-3 d-flex justify-content-between align-items-center">
                    <span class="badge bg-warning text-dark font-monospace px-3 py-1 rounded-pill" id="qvCategoryBadge"></span>
                    <button type="button" class="btn btn-sm btn-light border rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" data-bs-dismiss="modal" onclick="closeQuickViewModal()" aria-label="Close" style="width: 36px; height: 36px; z-index: 1056; cursor: pointer;">
                        <i class="ri-close-line fs-4 text-dark"></i>
                    </button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <div class="row g-4 align-items-center">
                        <!-- Left: Main Image Preview & Multi-Image Gallery -->
                        <div class="col-md-6 text-center">
                            <div class="p-3 bg-light rounded-4 border mb-3 position-relative d-flex align-items-center justify-content-center shadow-sm" style="min-height: 270px; background: #fafafa;">
                                <img id="qvMainImage" src="" alt="Product Preview" class="img-fluid rounded transition-all" style="max-height: 240px; object-fit: contain; width: 100%;">
                            </div>
                            <!-- Thumbnails Gallery Row (Up to 4 Images) -->
                            <div class="mb-1 text-start">
                                <small class="text-muted fw-bold" style="font-size: 0.75rem;"><i class="ri-gallery-line me-1"></i> Product Images Gallery (<span id="qvImageCount">0</span>):</small>
                            </div>
                            <div id="qvThumbnailsRow" class="d-flex justify-content-start gap-2 flex-wrap p-2 bg-light rounded-3 border"></div>
                        </div>

                        <!-- Right: Product Details & Add to Cart -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <span id="qvStockBadge" class="badge rounded-pill px-3 py-1 fw-bold"></span>
                            </div>
                            <h3 id="qvTitle" class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;"></h3>
                            <div id="qvUnit" class="text-muted small mb-3"></div>

                            <div class="p-3 rounded-3 mb-3 border" style="background: rgba(255, 183, 3, 0.08); border-color: rgba(255, 183, 3, 0.3) !important;">
                                <div class="d-flex align-items-baseline gap-2 mb-1">
                                    <span id="qvActivePrice" class="fs-2 fw-bold text-warning"></span>
                                    <span id="qvOriginalPrice" class="text-muted text-decoration-line-through fs-5"></span>
                                </div>
                                <small class="text-muted d-block"><i class="ri-shield-check-line text-success me-1"></i> 100% Certified Green Crackers - Factory Direct Rate</small>
                            </div>

                            <p id="qvDescription" class="text-muted small mb-4" style="line-height: 1.6; max-height: 110px; overflow-y: auto;"></p>

                            <!-- Action Area -->
                            <div id="qvActionArea">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="fw-bold text-dark fs-6">Quantity:</span>
                                    <div class="input-group" style="width: 140px;">
                                        <button class="btn btn-outline-dark px-3 fw-bold fs-5" type="button" onclick="adjustQvQty(-1)" style="border-color: #ced4da;">-</button>
                                        <input type="number" id="qvQtyInput" class="form-control text-center fw-bold fs-5 px-1" value="1" min="1" style="color: #000000 !important; background-color: #ffffff !important; border-color: #ced4da !important; font-weight: 700 !important;">
                                        <button class="btn btn-outline-dark px-3 fw-bold fs-5" type="button" onclick="adjustQvQty(1)" style="border-color: #ced4da;">+</button>
                                    </div>
                                </div>
                                <button id="qvAddToCartBtn" class="btn btn-warning w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm d-flex align-items-center justify-content-center gap-2 text-dark">
                                    <i class="ri-shopping-cart-add-line fs-4"></i> Add To Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" style="background: var(--modal-bg); color: var(--text-main);">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title text-warning"><i class="ri-shopping-cart-line me-2"></i> Your Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <div id="cartItemsList"></div>

            <div class="border-top border-secondary pt-3">
                <div class="d-flex justify-content-between text-muted mb-1">
                    <span>Items Subtotal:</span>
                    <span id="cartSubtotal" class="fw-bold">₹0.00</span>
                </div>
                <div class="d-flex justify-content-between text-muted mb-2">
                    <span>GST ({{ $settings->gst_percentage }}%):</span>
                    <span id="cartGstAmount" class="fw-bold">₹0.00</span>
                </div>
                <div class="d-flex justify-content-between fw-bold mb-3 fs-5">
                    <span>Grand Total:</span>
                    <span id="cartTotal" class="text-success fw-bold">₹0.00</span>
                </div>
                <a href="{{ route('crackers.checkout-page') }}" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill text-center d-flex align-items-center justify-content-center gap-1" id="checkoutBtn" style="background: var(--gold-gradient); color:#000; text-decoration:none;">
                    Proceed to Checkout <i class="ri-arrow-right-line ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Offcanvas Wishlist Drawer -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="wishlistOffcanvas" style="background: var(--modal-bg); color: var(--text-main);">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title text-danger fw-bold"><i class="ri-heart-fill me-2"></i> My Wishlist</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
            <div id="wishlistItemsList" class="flex-grow-1 overflow-y-auto"></div>
            <div id="wishlistFooter" class="pt-3 border-top mt-3" style="display: none;">
                <button class="btn btn-warning w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm d-flex align-items-center justify-content-center gap-2 text-dark" onclick="moveAllWishlistToCart()">
                    <i class="ri-shopping-cart-2-line fs-4"></i> Move All to Cart & View Cart
                </button>
    </div>

    <!-- Floating Fixed Bottom-Right Corner Order Summary Widget -->
    <div id="floatingCornerSummaryWidget" class="position-fixed bottom-0 end-0 m-3 m-md-4" style="z-index: 1040;">
        <!-- Expanded Floating Card (Right Corner) -->
        <div id="floatingSummaryCard" class="card border-0 shadow-lg rounded-4 overflow-hidden mb-2 d-none" style="width: 350px; max-width: calc(100vw - 30px); background: var(--bg-card); color: var(--text-main); border: 2px solid rgba(251, 133, 0, 0.4) !important;">
            <div class="card-header bg-warning bg-opacity-10 py-3 px-3 border-0 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                    <i class="ri-file-list-3-line text-warning fs-5"></i> Live Order Summary
                </h6>
                <button type="button" class="btn-close" onclick="toggleCornerOrderSummary()"></button>
            </div>

            <div class="card-body p-3">
                <div id="cornerCartItemsList" class="mb-3 overflow-y-auto" style="max-height: 200px;">
                    <div class="text-center py-3 text-muted">
                        <small>Your cart is empty.</small>
                    </div>
                </div>

                <div class="border-top pt-2 mb-3">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Subtotal:</span>
                        <span id="cornerSubtotal" class="fw-bold text-dark">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>GST ({{ $settings->gst_percentage }}%):</span>
                        <span id="cornerGst" class="fw-bold text-dark">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 text-dark border-top pt-2 mt-1">
                        <span>Grand Total:</span>
                        <span id="cornerTotalVal" class="text-success fw-bold">₹0.00</span>
                    </div>
                </div>

                <a href="{{ route('crackers.checkout-page') }}" class="btn btn-warning w-100 py-2 rounded-pill fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2 text-dark text-decoration-none" id="cornerCheckoutBtn" style="background: var(--gold-gradient);">
                    <i class="ri-shopping-bag-3-line"></i> Proceed to Checkout
                </a>
            </div>
        </div>

        <!-- Collapsed Floating Button (Right Corner) -->
        <button id="floatingSummaryToggleBtn" class="btn btn-warning rounded-pill shadow-lg px-3 py-2 px-md-4 py-md-3 fw-bold d-flex align-items-center gap-2 text-dark border border-2 border-white ms-auto" onclick="toggleCornerOrderSummary()" style="background: var(--gold-gradient); display: none;">
            <i class="ri-file-list-3-fill fs-4"></i>
            <span class="d-none d-sm-inline">Order Summary:</span>
            <span id="cornerGrandTotal" class="fw-extrabold text-success bg-white px-2 py-1 rounded-pill shadow-sm">₹0.00</span>
            <span id="cornerCartBadge" class="badge bg-dark text-warning rounded-pill font-monospace">0</span>
        </button>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: var(--modal-bg); color: var(--text-main);">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-warning"><i class="ri-truck-line me-2"></i> Cracker Order Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="checkoutForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="customer_name" class="form-control" required placeholder="Full Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number *</label>
                                <input type="text" name="customer_phone" class="form-control" required placeholder="e.g. 9876543210">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delivery Address *</label>
                            <textarea name="delivery_address" class="form-control" rows="2" required placeholder="Street address, door number, landmark"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" placeholder="City">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="form-control" placeholder="Pincode">
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ri-bank-card-line me-1"></i> Select Payment Method</label>
                            <div class="d-flex flex-column gap-2">
                                @if($settings->enable_cod)
                                    <div class="form-check border p-3 rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" value="COD" id="payCOD" checked onchange="togglePaymentBox('COD')">
                                        <label class="form-check-label fw-bold" for="payCOD">
                                            <i class="ri-hand-coin-line text-warning me-1"></i> Cash On Delivery (COD)
                                        </label>
                                    </div>
                                @endif

                                @if($settings->enable_upi)
                                    <div class="form-check border p-3 rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" value="UPI" id="payUPI" {{ !$settings->enable_cod ? 'checked' : '' }} onchange="togglePaymentBox('UPI')">
                                        <label class="form-check-label fw-bold" for="payUPI">
                                            <i class="ri-qr-code-line text-info me-1"></i> UPI / GPay / PhonePe QR
                                        </label>
                                        <div id="upiBox" class="payment-box d-none mt-2">
                                            <div class="small fw-bold">UPI ID: <span class="text-warning">{{ $settings->upi_id }}</span></div>
                                            @if($settings->upi_qr_code)
                                                <div class="mt-2 text-center">
                                                    <img src="{{ $settings->upi_qr_code }}" alt="UPI QR Code" class="img-fluid rounded" style="max-width: 180px;">
                                                    <div class="small text-muted mt-1">Scan QR code to pay instantly</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($settings->enable_bank_transfer)
                                    <div class="form-check border p-3 rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" value="Bank Transfer" id="payBank" {{ (!$settings->enable_cod && !$settings->enable_upi) ? 'checked' : '' }} onchange="togglePaymentBox('Bank')">
                                        <label class="form-check-label fw-bold" for="payBank">
                                            <i class="ri-bank-line text-success me-1"></i> Direct Bank Transfer
                                        </label>
                                        <div id="bankBox" class="payment-box d-none mt-2">
                                            <div class="small"><strong>Bank:</strong> {{ $settings->bank_name }}</div>
                                            <div class="small"><strong>Account Holder:</strong> {{ $settings->account_holder }}</div>
                                            <div class="small"><strong>A/C No:</strong> {{ $settings->account_number }}</div>
                                            <div class="small"><strong>IFSC:</strong> {{ $settings->ifsc_code }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning fw-bold px-4 rounded-pill" id="submitOrderBtn" style="background: var(--gold-gradient); color:#000;">
                            Place Cracker Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Customer Auth Modal (Login / Register) -->
    <div class="modal fade" id="customerAuthModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--modal-bg); color: var(--text-main);">
                <div class="modal-header border-secondary">
                    <ul class="nav nav-pills card-header-pills w-100" id="authTabs" role="tablist">
                        <li class="nav-item flex-fill text-center">
                            <button class="nav-link active w-100 fw-bold" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTabContent" type="button">Customer Login</button>
                        </li>
                        <li class="nav-item flex-fill text-center">
                            <button class="nav-link w-100 fw-bold" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTabContent" type="button">Create Account</button>
                        </li>
                    </ul>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <!-- Login Form -->
                        <div class="tab-pane fade show active" id="loginTabContent">
                            <form id="customerLoginForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Phone Number or Email *</label>
                                    <input type="text" name="login" class="form-control" required placeholder="e.g. 9876543210 or email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password *</label>
                                    <input type="password" name="password" class="form-control" required placeholder="Password">
                                </div>
                                <div id="loginError" class="alert alert-danger d-none py-2 small"></div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill mt-2" id="loginSubmitBtn" style="background: var(--gold-gradient); color:#000;">
                                    Login To Account
                                </button>
                            </form>
                        </div>

                        <!-- Register Form -->
                        <div class="tab-pane fade" id="registerTabContent">
                            <form id="customerRegisterForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Full Name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number *</label>
                                    <input type="text" name="phone" class="form-control" required placeholder="10-digit mobile number">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address (Optional)</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password *</label>
                                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="At least 6 characters">
                                </div>
                                <div id="registerError" class="alert alert-danger d-none py-2 small"></div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill mt-2" id="registerSubmitBtn" style="background: var(--gold-gradient); color:#000;">
                                    Create New Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('crackers_cart') || '[]');
        let wishlist = JSON.parse(localStorage.getItem('crackers_wishlist') || '[]');
        const gstPct = {{ $settings->gst_percentage ?: 0 }};
        let currentQvProduct = null;

        // Product Quick View Popup Modal Handler
        function openProductQuickView(prodData) {
            currentQvProduct = prodData;
            document.getElementById('qvTitle').innerText = prodData.name;
            document.getElementById('qvCategoryBadge').innerText = prodData.category;
            document.getElementById('qvUnit').innerText = 'Packing: ' + prodData.unit;
            document.getElementById('qvDescription').innerText = prodData.description;
            document.getElementById('qvQtyInput').value = 1;

            // Stock Badge
            let stockEl = document.getElementById('qvStockBadge');
            if (prodData.stock > 0) {
                stockEl.className = 'badge bg-success text-white rounded-pill px-3 py-1';
                stockEl.innerText = 'In Stock (' + prodData.stock + ' Available)';
                document.getElementById('qvActionArea').style.display = 'block';
            } else {
                stockEl.className = 'badge bg-danger text-white rounded-pill px-3 py-1';
                stockEl.innerText = 'Out of Stock';
                document.getElementById('qvActionArea').style.display = 'none';
            }

            // Price Display
            document.getElementById('qvActivePrice').innerText = '₹' + prodData.active_price.toFixed(2);
            let origEl = document.getElementById('qvOriginalPrice');
            if (prodData.discount_price && prodData.price > prodData.discount_price) {
                origEl.innerText = '₹' + prodData.price.toFixed(2);
                origEl.style.display = 'inline';
            } else {
                origEl.style.display = 'none';
            }

            // Images & Gallery Thumbnails (Up to 4 Images)
            let mainImgEl = document.getElementById('qvMainImage');
            let thumbsRow = document.getElementById('qvThumbnailsRow');
            let countEl = document.getElementById('qvImageCount');
            thumbsRow.innerHTML = '';

            let images = prodData.images && prodData.images.length > 0 ? prodData.images : [];
            if (countEl) countEl.innerText = images.length;

            if (images.length > 0) {
                mainImgEl.src = images[0];
                images.forEach((imgUrl, idx) => {
                    let thumb = document.createElement('img');
                    thumb.src = imgUrl;
                    thumb.className = 'rounded border p-1 cursor-pointer transition-all ' + (idx === 0 ? 'border-warning border-3 shadow-sm bg-white' : 'opacity-75 bg-white');
                    thumb.style.width = '60px';
                    thumb.style.height = '60px';
                    thumb.style.objectFit = 'contain';
                    thumb.onclick = function() {
                        mainImgEl.src = imgUrl;
                        Array.from(thumbsRow.children).forEach(c => c.className = 'rounded border p-1 cursor-pointer opacity-75 bg-white transition-all');
                        thumb.className = 'rounded border p-1 cursor-pointer border-warning border-3 shadow-sm bg-white transition-all';
                    };
                    thumbsRow.appendChild(thumb);
                });
            } else {
                mainImgEl.src = 'https://via.placeholder.com/250?text=No+Image';
            }

            // Add To Cart Button Handler inside Modal
            let cartBtn = document.getElementById('qvAddToCartBtn');
            cartBtn.onclick = function() {
                let qty = parseInt(document.getElementById('qvQtyInput').value) || 1;
                for (let i = 0; i < qty; i++) {
                    addToCart(prodData.id, prodData.name, prodData.active_price, prodData.unit);
                }
                let modalEl = bootstrap.Modal.getInstance(document.getElementById('productQuickViewModal'));
                if (modalEl) modalEl.hide();
            };

            let bsModal = new bootstrap.Modal(document.getElementById('productQuickViewModal'));
            bsModal.show();
        }

        function closeQuickViewModal() {
            let modalEl = document.getElementById('productQuickViewModal');
            if (modalEl) {
                let bsModal = bootstrap.Modal.getInstance(modalEl);
                if (bsModal) {
                    bsModal.hide();
                } else {
                    let m = new bootstrap.Modal(modalEl);
                    m.hide();
                }
            }
        }

        function adjustQvQty(delta) {
            let input = document.getElementById('qvQtyInput');
            let val = (parseInt(input.value) || 1) + delta;
            if (val < 1) val = 1;
            if (currentQvProduct && val > currentQvProduct.stock) val = currentQvProduct.stock;
            input.value = val;
        }

        // Theme Switcher Logic
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            themeIcon.className = theme === 'light' ? 'ri-moon-line' : 'ri-sun-line';
        }

        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            setTheme(current === 'dark' ? 'light' : 'dark');
        });

        // Initialize saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);

        // Cart Logic
        function addToCart(id, name, price, unit) {
            let existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ id, name, price, unit, quantity: 1 });
            }
            updateCartUI();
        }

        function updateQuantity(id, change) {
            let item = cart.find(i => i.id === id);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    cart = cart.filter(i => i.id !== id);
                }
            }
            updateCartUI();
        }

        function toggleCornerOrderSummary() {
            let card = document.getElementById('floatingSummaryCard');
            if (card) {
                card.classList.toggle('d-none');
            }
        }

        function updateCartUI() {
            let list = document.getElementById('cartItemsList');
            let countEl = document.getElementById('cartCount');
            let subtotalEl = document.getElementById('cartSubtotal');
            let gstEl = document.getElementById('cartGstAmount');
            let totalEl = document.getElementById('cartTotal');
            let checkoutBtn = document.getElementById('checkoutBtn');

            // Right-Side Sticky Order Summary Sidebar Elements
            let rightCountBadge = document.getElementById('rightCartCountBadge');
            let rightSubtotalEl = document.getElementById('rightCartSubtotal');
            let rightGstEl = document.getElementById('rightCartGst');
            let rightTotalEl = document.getElementById('rightCartTotal');
            let rightList = document.getElementById('rightCartItemsList');
            let rightCheckoutBtn = document.getElementById('rightCheckoutBtn');

            // Fixed Bottom-Right Corner Order Summary Elements
            let cornerToggleBtn = document.getElementById('floatingSummaryToggleBtn');
            let cornerCard = document.getElementById('floatingSummaryCard');
            let cornerGrandTotal = document.getElementById('cornerGrandTotal');
            let cornerCartBadge = document.getElementById('cornerCartBadge');
            let cornerSubtotal = document.getElementById('cornerSubtotal');
            let cornerGst = document.getElementById('cornerGst');
            let cornerTotalVal = document.getElementById('cornerTotalVal');
            let cornerList = document.getElementById('cornerCartItemsList');
            let cornerCheckoutBtn = document.getElementById('cornerCheckoutBtn');

            let totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            let gstAmount = subtotal * (gstPct / 100);
            let grandTotal = subtotal + gstAmount;

            if (countEl) countEl.innerText = totalQty;
            if (subtotalEl) subtotalEl.innerText = '₹' + subtotal.toFixed(2);
            if (gstEl) gstEl.innerText = '₹' + gstAmount.toFixed(2);
            if (totalEl) totalEl.innerText = '₹' + grandTotal.toFixed(2);

            if (rightCountBadge) rightCountBadge.innerText = totalQty + ' Items';
            if (rightSubtotalEl) rightSubtotalEl.innerText = '₹' + subtotal.toFixed(2);
            if (rightGstEl) rightGstEl.innerText = '₹' + gstAmount.toFixed(2);
            if (rightTotalEl) rightTotalEl.innerText = '₹' + grandTotal.toFixed(2);

            if (cornerGrandTotal) cornerGrandTotal.innerText = '₹' + grandTotal.toFixed(2);
            if (cornerCartBadge) cornerCartBadge.innerText = totalQty;
            if (cornerSubtotal) cornerSubtotal.innerText = '₹' + subtotal.toFixed(2);
            if (cornerGst) cornerGst.innerText = '₹' + gstAmount.toFixed(2);
            if (cornerTotalVal) cornerTotalVal.innerText = '₹' + grandTotal.toFixed(2);
            if (cornerToggleBtn) cornerToggleBtn.style.display = cart.length > 0 ? 'flex' : 'none';

            localStorage.setItem('crackers_cart', JSON.stringify(cart));

            if (checkoutBtn) {
                if (cart.length === 0) {
                    checkoutBtn.classList.add('disabled');
                    checkoutBtn.style.pointerEvents = 'none';
                } else {
                    checkoutBtn.classList.remove('disabled');
                    checkoutBtn.style.pointerEvents = 'auto';
                }
            }

            if (rightCheckoutBtn) {
                if (cart.length === 0) {
                    rightCheckoutBtn.classList.add('disabled');
                    rightCheckoutBtn.style.pointerEvents = 'none';
                } else {
                    rightCheckoutBtn.classList.remove('disabled');
                    rightCheckoutBtn.style.pointerEvents = 'auto';
                }
            }

            if (cornerCheckoutBtn) {
                if (cart.length === 0) {
                    cornerCheckoutBtn.classList.add('disabled');
                    cornerCheckoutBtn.style.pointerEvents = 'none';
                } else {
                    cornerCheckoutBtn.classList.remove('disabled');
                    cornerCheckoutBtn.style.pointerEvents = 'auto';
                }
            }

            syncCardActions();

            if (cart.length === 0) {
                let emptyHtml = `<div class="text-center py-4 text-muted"><i class="ri-shopping-cart-line display-4 opacity-50"></i><p class="mt-2 small">Cart is empty.</p></div>`;
                if (list) list.innerHTML = emptyHtml;
                if (rightList) rightList.innerHTML = emptyHtml;
                if (cornerList) cornerList.innerHTML = emptyHtml;
                if (cornerCard) cornerCard.classList.add('d-none');
                return;
            }

            let cartItemsHtml = cart.map(item => `
                <div class="d-flex justify-content-between align-items-center p-2 mb-2 border rounded bg-white shadow-sm">
                    <div>
                        <div class="fw-bold text-dark small text-truncate" style="max-width: 140px;">${item.name}</div>
                        <small class="text-success fw-bold">₹${item.price.toFixed(2)}</small>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2 fw-bold" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span class="fw-bold px-1 small">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2 fw-bold" onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                </div>
            `).join('');

            if (list) list.innerHTML = cartItemsHtml;
            if (rightList) rightList.innerHTML = cartItemsHtml;
            if (cornerList) cornerList.innerHTML = cartItemsHtml;
        }

        function syncCardActions() {
            document.querySelectorAll('[id^="cartActionBox"]').forEach(box => {
                let id = parseInt(box.id.replace('cartActionBox', ''));
                let cartItem = cart.find(i => i.id === id);
                let name = box.getAttribute('data-name');
                let price = parseFloat(box.getAttribute('data-price'));
                let unit = box.getAttribute('data-unit');

                if (cartItem && cartItem.quantity > 0) {
                    box.innerHTML = `
                        <div class="d-flex align-items-center justify-content-between border border-warning rounded-pill p-1">
                            <button class="btn btn-sm btn-outline-warning rounded-circle px-2 fw-bold" onclick="updateQuantity(${id}, -1)">-</button>
                            <span class="fw-bold px-2 text-warning small">${cartItem.quantity} in Cart</span>
                            <button class="btn btn-sm btn-warning rounded-circle px-2 text-dark fw-bold" onclick="updateQuantity(${id}, 1)" style="background: var(--gold-gradient); border:none;">+</button>
                        </div>
                    `;
                } else if (name && price) {
                    box.innerHTML = `
                        <button class="btn-add-cart" onclick="addToCart(${id}, '${name.replace(/'/g, "\\'")}', ${price}, '${unit}')">
                            <i class="ri-shopping-cart-add-line me-1"></i> Add To Cart
                        </button>
                    `;
                }
            });
        }

        // Wishlist Logic
        function toggleWishlist(id, name, price) {
            let idx = wishlist.findIndex(item => item.id === id);
            if (idx > -1) {
                wishlist.splice(idx, 1);
            } else {
                wishlist.push({ id, name, price });
            }
            try { localStorage.setItem('crackers_wishlist', JSON.stringify(wishlist)); } catch(e) {}
            updateWishlistUI();
        }

        function removeFromWishlist(id) {
            let idx = wishlist.findIndex(item => item.id === id);
            if (idx > -1) {
                wishlist.splice(idx, 1);
                try { localStorage.setItem('crackers_wishlist', JSON.stringify(wishlist)); } catch(e) {}
            }
            updateWishlistUI();
        }

        function moveWishlistItemToCart(id) {
            let item = wishlist.find(i => i.id === id);
            if (item) {
                addToCart(item.id, item.name, item.price, 'Box');
                removeFromWishlist(id);
            }
        }

        function moveAllWishlistToCart() {
            if (wishlist.length === 0) return;
            let itemsToMove = [...wishlist];
            itemsToMove.forEach(item => {
                addToCart(item.id, item.name, item.price, 'Box');
            });
            wishlist = [];
            try { localStorage.setItem('crackers_wishlist', JSON.stringify(wishlist)); } catch(e) {}
            updateWishlistUI();

            // Close Wishlist offcanvas & Open Cart offcanvas
            let wishlistEl = document.getElementById('wishlistOffcanvas');
            if (wishlistEl) {
                let bsW = bootstrap.Offcanvas.getInstance(wishlistEl);
                if (bsW) bsW.hide();
            }
            let cartEl = document.getElementById('cartOffcanvas');
            if (cartEl) {
                let bsC = bootstrap.Offcanvas.getOrCreateInstance(cartEl);
                if (bsC) bsC.show();
            }
        }

        function updateWishlistUI() {
            let countEl = document.getElementById('wishlistCount');
            let list = document.getElementById('wishlistItemsList');
            let footer = document.getElementById('wishlistFooter');
            if (countEl) countEl.innerText = wishlist.length;

            if (footer) {
                footer.style.display = wishlist.length > 0 ? 'block' : 'none';
            }

            if (list) {
                if (wishlist.length === 0) {
                    list.innerHTML = `<div class="text-center py-5 text-muted"><i class="ri-heart-line display-4"></i><p class="mt-2">Wishlist is empty.</p></div>`;
                } else {
                    list.innerHTML = wishlist.map(item => `
                        <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded-3 shadow-sm bg-white">
                            <div>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 170px;">${item.name}</div>
                                <small class="text-success fw-bold fs-6">₹${item.price.toFixed(2)}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 border-0 shadow-sm" style="width: 38px; height: 38px; min-width: 38px; background-color: #0d6efd !important; color: #ffffff !important;" title="Add to Cart & Remove from Wishlist" onclick="moveWishlistItemToCart(${item.id})">
                                    <i class="ri-shopping-cart-2-fill fs-5 text-white"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 38px; height: 38px; min-width: 38px;" title="Remove from Wishlist" onclick="removeFromWishlist(${item.id})">
                                    <i class="ri-delete-bin-7-line fs-5"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            }

            // Sync all wishlist heart buttons on product cards
            document.querySelectorAll('.btn-wishlist').forEach(btn => {
                let id = parseInt(btn.id.replace('wishlistBtn', ''));
                if (wishlist.some(item => item.id === id)) {
                    btn.classList.add('active');
                    btn.innerHTML = '<i class="ri-heart-fill text-danger fs-5"></i>';
                } else {
                    btn.classList.remove('active');
                    btn.innerHTML = '<i class="ri-heart-line fs-5"></i>';
                }
            });
        }

        // Payment Toggle Box Handler
        function togglePaymentBox(type) {
            let upiBox = document.getElementById('upiBox');
            let bankBox = document.getElementById('bankBox');
            if (upiBox) upiBox.classList.add('d-none');
            if (bankBox) bankBox.classList.add('d-none');

            if (type === 'UPI' && upiBox) upiBox.classList.remove('d-none');
            if (type === 'Bank' && bankBox) bankBox.classList.remove('d-none');
        }

        // Order Form Handler
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let data = Object.fromEntries(formData.entries());
            data.items = cart;

            let btn = document.getElementById('submitOrderBtn');
            btn.disabled = true;

            fetch('{{ route("crackers.place-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = res.redirect_url;
                } else {
                    alert(res.message || 'Error placing order.');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                alert('An error occurred while processing order.');
                btn.disabled = false;
            });
        });

        // Customer Login Handler
        document.getElementById('customerLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let btn = document.getElementById('loginSubmitBtn');
            let errBox = document.getElementById('loginError');
            btn.disabled = true;
            errBox.classList.add('d-none');

            fetch('{{ route("crackers.login") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(Object.fromEntries(formData.entries()))
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = res.redirect_url;
                } else {
                    errBox.innerText = res.message || 'Invalid credentials.';
                    errBox.classList.remove('d-none');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                errBox.innerText = 'Login failed. Please check your credentials.';
                errBox.classList.remove('d-none');
                btn.disabled = false;
            });
        });

        // Customer Register Handler
        document.getElementById('customerRegisterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let btn = document.getElementById('registerSubmitBtn');
            let errBox = document.getElementById('registerError');
            btn.disabled = true;
            errBox.classList.add('d-none');

            fetch('{{ route("crackers.register") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(Object.fromEntries(formData.entries()))
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = res.redirect_url;
                } else {
                    errBox.innerText = res.message || 'Error creating account.';
                    errBox.classList.remove('d-none');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                errBox.innerText = 'Registration failed. Mobile number or email may already be in use.';
                errBox.classList.remove('d-none');
                btn.disabled = false;
            });
        });

        // Initialize UI & Auto-slide Carousel every 5 seconds
        updateCartUI();
        updateWishlistUI();

        const heroCarouselEl = document.getElementById('heroCarousel');
        if (heroCarouselEl && typeof bootstrap !== 'undefined') {
            const carousel = new bootstrap.Carousel(heroCarouselEl, {
                interval: 5000,
                ride: 'carousel',
                pause: 'hover'
            });
            carousel.cycle();
        }
    </script>
</body>
</html>
