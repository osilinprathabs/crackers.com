<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout & Order Placement | Crackers.com</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --primary-amber: {{ $websiteColor ?? '#fb8500' }};
            --gold-gradient: linear-gradient(135deg, #ffb703 0%, {{ $websiteColor ?? '#fb8500' }} 50%, #ff4800 100%);
            --hero-gradient: linear-gradient(135deg, #fff7ed 0%, #ffedd5 60%, #fef3c7 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --card-border: rgba(148, 163, 184, 0.2);
        }

        /* Dynamic Checkout UI Accent Themes */
        [data-checkout-theme="blue"] {
            --primary-amber: #2563eb;
            --gold-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            --hero-gradient: linear-gradient(135deg, #eff6ff 0%, #dbeafe 60%, #bfdbfe 100%);
        }

        [data-checkout-theme="emerald"] {
            --primary-amber: #10b981;
            --gold-gradient: linear-gradient(135deg, #34d399 0%, #10b981 50%, #059669 100%);
            --hero-gradient: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 60%, #a7f3d0 100%);
        }

        [data-checkout-theme="purple"] {
            --primary-amber: #8b5cf6;
            --gold-gradient: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 50%, #7c3aed 100%);
            --hero-gradient: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 60%, #ddd6fe 100%);
        }

        [data-checkout-theme="crimson"] {
            --primary-amber: #ef4444;
            --gold-gradient: linear-gradient(135deg, #f87171 0%, #ef4444 50%, #dc2626 100%);
            --hero-gradient: linear-gradient(135deg, #fef2f2 0%, #fee2e2 60%, #fecaca 100%);
        }

        [data-checkout-theme="dark"] {
            --primary-amber: #f59e0b;
            --gold-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --hero-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --bg-light: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --card-border: rgba(255, 255, 255, 0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            min-height: 100vh;
        }

        .navbar-festive {
            background: #ffffff;
            border-bottom: 1px solid rgba(255, 183, 3, 0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }

        .brand-logo {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Outfit', sans-serif;
        }

        /* Custom Tab Stepper Styles */
        .checkout-tabs .nav-link {
            color: var(--text-muted);
            background: var(--bg-card);
            border: 1px solid var(--card-border);
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .checkout-tabs .nav-link:hover {
            border-color: var(--primary-amber);
            color: var(--primary-amber);
        }

        .checkout-tabs .nav-link.active {
            background: var(--gold-gradient) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 8px 24px rgba(251, 133, 0, 0.3) !important;
        }

        .checkout-tabs .nav-link.active .badge {
            background-color: #ffffff !important;
            color: #000000 !important;
        }

        .btn-theme-dynamic {
            background: var(--gold-gradient) !important;
            color: #ffffff !important;
            border: none !important;
            font-weight: 700 !important;
            box-shadow: 0 6px 18px rgba(251, 133, 0, 0.25);
            transition: all 0.2s ease;
        }

        .btn-theme-dynamic:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            color: #ffffff !important;
        }

        .card-custom {
            background: var(--bg-card);
            border: 1px solid var(--card-border);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 20px;
            padding: 1.75rem;
        }

        .payment-box {
            background: var(--bg-light);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 0.75rem;
        }

        .text-theme-dynamic {
            color: var(--primary-amber) !important;
        }
    </style>
</head>
<body>

@if(session()->has('impersonator_admin_id'))
    <div class="bg-warning text-dark py-2 px-3 text-center font-monospace fw-bold sticky-top shadow-sm border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="z-index: 9999;">
        <div>
            <i class="ri-user-shared-line me-1 fs-5 align-middle"></i>
            <span class="align-middle">Impersonating Customer Account: <strong>{{ auth()->check() ? auth()->user()->name : 'Customer' }}</strong></span>
        </div>
        <a href="{{ route('admin.stop-impersonating') }}" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-bold shadow-sm">
            <i class="ri-arrow-left-line me-1"></i> Return to Admin Panel
        </a>
    </div>
@endif

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-festive mb-4">
        <div class="container">
            <a class="navbar-brand brand-logo text-decoration-none" href="{{ route('crackers.storefront') }}">
                <i class="ri-fire-fill text-theme-dynamic"></i> Crackers.com
            </a>
            
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- Dynamic UI Color Theme Switcher -->
                <div class="d-none d-md-flex align-items-center gap-2 bg-light p-2 rounded-pill border shadow-sm">
                    <span class="fw-bold small text-muted px-1" style="font-size: 11px;"><i class="ri-palette-line text-theme-dynamic me-1"></i> Theme:</span>
                    <button type="button" class="btn p-0 rounded-circle theme-color-dot active" data-theme="amber" style="width: 20px; height: 20px; background: #fb8500; border: 2px solid #ffffff;" onclick="setCheckoutTheme('amber')" title="Festive Amber (Default)"></button>
                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="blue" style="width: 20px; height: 20px; background: #2563eb; border: 2px solid #ffffff;" onclick="setCheckoutTheme('blue')" title="Sapphire Blue"></button>
                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="emerald" style="width: 20px; height: 20px; background: #10b981; border: 2px solid #ffffff;" onclick="setCheckoutTheme('emerald')" title="Emerald Green"></button>
                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="purple" style="width: 20px; height: 20px; background: #8b5cf6; border: 2px solid #ffffff;" onclick="setCheckoutTheme('purple')" title="Amethyst Purple"></button>
                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="crimson" style="width: 20px; height: 20px; background: #ef4444; border: 2px solid #ffffff;" onclick="setCheckoutTheme('crimson')" title="Ruby Crimson"></button>
                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="dark" style="width: 20px; height: 20px; background: #0f172a; border: 2px solid #ffffff;" onclick="setCheckoutTheme('dark')" title="Midnight Dark Mode"></button>
                </div>

                @auth
                    <a href="{{ route('crackers.profile') }}" class="btn btn-outline-primary rounded-pill px-3 btn-sm fw-semibold">
                        <i class="ri-user-settings-line me-1"></i> My Profile
                    </a>
                @else
                    <a href="{{ route('crackers.login-page') }}" class="btn btn-theme-dynamic rounded-pill px-3 btn-sm fw-bold">
                        <i class="ri-user-3-line me-1"></i> Login / Register
                    </a>
                @endauth
                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                    <i class="ri-store-2-line me-1"></i> Return To Shop
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container pb-5">

        <!-- Tab-Wise Checkout Navigation Header Bar -->
        <div class="card border-0 shadow-sm rounded-4 p-2 mb-4 bg-white">
            <ul class="nav nav-pills nav-justified checkout-tabs gap-2" id="checkoutTabList" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold py-3 px-3 d-flex align-items-center justify-content-center gap-2" id="tab-cart-btn" data-bs-toggle="pill" data-bs-target="#tab-cart" type="button" role="tab">
                        <span class="badge bg-secondary text-white rounded-circle me-1" id="stepBadge1">1</span>
                        <i class="ri-shopping-cart-2-line fs-5"></i>
                        <span class="d-none d-sm-inline">1. Cart Items</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold py-3 px-3 d-flex align-items-center justify-content-center gap-2" id="tab-shipping-btn" data-bs-toggle="pill" data-bs-target="#tab-shipping" type="button" role="tab">
                        <span class="badge bg-secondary text-white rounded-circle me-1" id="stepBadge2">2</span>
                        <i class="ri-truck-line fs-5"></i>
                        <span class="d-none d-sm-inline">2. Shipping Info</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold py-3 px-3 d-flex align-items-center justify-content-center gap-2" id="tab-payment-btn" data-bs-toggle="pill" data-bs-target="#tab-payment" type="button" role="tab">
                        <span class="badge bg-secondary text-white rounded-circle me-1" id="stepBadge3">3</span>
                        <i class="ri-bank-card-line fs-5"></i>
                        <span class="d-none d-sm-inline">3. Payment</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold py-3 px-3 d-flex align-items-center justify-content-center gap-2" id="tab-summary-btn" data-bs-toggle="pill" data-bs-target="#tab-summary" type="button" role="tab">
                        <span class="badge bg-secondary text-white rounded-circle me-1" id="stepBadge4">4</span>
                        <i class="ri-file-list-3-line fs-5"></i>
                        <span class="d-none d-sm-inline">4. Order Summary</span>
                    </button>
                </li>
            </ul>
        </div>

        @php
            $billing = is_array($customer->billing_address ?? null) ? $customer->billing_address : [];
            $preAddress = $billing['address'] ?? '';
            $preCity = $billing['city'] ?? '';
            $prePincode = $billing['pincode'] ?? '';
        @endphp

        <!-- Minimum Order Amount Alert -->
        <div id="minOrderAlert" class="d-none"></div>

        <form id="checkoutForm" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <!-- Left Main Tab Content Area (7 Cols) -->
                <div class="col-lg-7">
                    <div class="tab-content card-custom">

                        <!-- TAB 1: CART ITEMS -->
                        <div class="tab-pane fade show active" id="tab-cart" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0 text-theme-dynamic" style="font-family: 'Outfit', sans-serif;">
                                    <i class="ri-shopping-bag-3-line me-2"></i> Review Cart Items
                                </h4>
                                <span class="badge btn-theme-dynamic font-monospace px-3 py-2 rounded-pill" id="cartItemCountBadge">0 Items</span>
                            </div>

                            <div id="fullCartList" class="mb-4"></div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                                    <i class="ri-arrow-left-line me-1"></i> Continue Shopping
                                </a>
                                <button type="button" class="btn btn-theme-dynamic rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="goToTab('tab-shipping')">
                                    Proceed to Shipping Info <i class="ri-arrow-right-line ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 2: SHIPPING INFO -->
                        <div class="tab-pane fade" id="tab-shipping" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0 text-theme-dynamic" style="font-family: 'Outfit', sans-serif;">
                                    <i class="ri-map-pin-line me-2"></i> Shipping & Delivery Address
                                </h4>
                                <span class="badge btn-theme-dynamic px-3 py-2 rounded-pill"><i class="ri-truck-fill me-1"></i> 5 - 7 Days Delivery</span>
                            </div>

                            @auth
                                <div class="alert alert-success py-2 small d-flex align-items-center justify-content-between mb-4 rounded-3">
                                    <span><i class="ri-user-smile-line me-1"></i> Logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->phone }})</span>
                                    <span class="badge bg-success">Auto-Filled</span>
                                </div>
                            @else
                                <div class="alert alert-info py-2 small d-flex align-items-center justify-content-between mb-4 rounded-3">
                                    <span><i class="ri-information-line me-1"></i> Already registered?</span>
                                    <a href="{{ route('crackers.login-page') }}" class="btn btn-sm btn-theme-dynamic rounded-pill px-3 fw-bold">Login for Faster Checkout</a>
                                </div>
                            @endauth

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Full Name *</label>
                                    <input type="text" name="customer_name" id="inputName" class="form-control form-control-lg fs-6" required value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="Full Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mobile Phone Number *</label>
                                    <input type="text" name="customer_phone" id="inputPhone" class="form-control form-control-lg fs-6" required value="{{ auth()->check() ? auth()->user()->phone : '' }}" placeholder="10-digit mobile number">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address (Optional)</label>
                                <input type="email" name="customer_email" id="inputEmail" class="form-control form-control-lg fs-6" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="email@example.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Street Delivery Address *</label>
                                <textarea name="delivery_address" id="inputAddress" class="form-control fs-6" rows="3" required placeholder="Door number, street name, building, landmark">{{ $preAddress }}</textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">City / Town *</label>
                                    <input type="text" name="city" id="inputCity" class="form-control form-control-lg fs-6" required value="{{ $preCity }}" placeholder="City">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pincode *</label>
                                    <input type="text" name="pincode" id="inputPincode" class="form-control form-control-lg fs-6" required value="{{ $prePincode }}" placeholder="Pincode">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" onclick="goToTab('tab-cart')">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Cart
                                </button>
                                <button type="button" class="btn btn-theme-dynamic rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="validateShippingAndNext()">
                                    Proceed to Payment <i class="ri-arrow-right-line ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 3: PAYMENT METHOD -->
                        <div class="tab-pane fade" id="tab-payment" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0 text-theme-dynamic" style="font-family: 'Outfit', sans-serif;">
                                    <i class="ri-bank-card-line me-2"></i> Select Payment Method
                                </h4>
                                <span class="badge bg-success text-white fw-bold px-3 py-2 rounded-pill"><i class="ri-shield-check-line me-1"></i> 100% Secure Transaction</span>
                            </div>

                            <div class="d-flex flex-column gap-3 mb-4">
                                @if($settings->enable_cod)
                                    <div class="form-check border p-3 rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" value="COD" id="payCOD" checked onchange="togglePaymentBox('COD')">
                                        <label class="form-check-label fw-bold" for="payCOD">
                                            <i class="ri-hand-coin-line text-theme-dynamic me-1"></i> Cash On Delivery (COD)
                                        </label>
                                        <div class="small text-muted mt-1">Pay with cash when your crackers arrive at your doorstep.</div>
                                    </div>
                                @endif

                                @if($settings->enable_upi)
                                    <div class="form-check border p-3 rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" value="UPI" id="payUPI" {{ !$settings->enable_cod ? 'checked' : '' }} onchange="togglePaymentBox('UPI')">
                                        <label class="form-check-label fw-bold" for="payUPI">
                                            <i class="ri-qr-code-line text-info me-1"></i> UPI / GPay / PhonePe / Paytm
                                        </label>
                                        <div id="upiBox" class="payment-box d-none mt-2">
                                            <div class="small fw-bold">Company UPI ID: <span class="text-theme-dynamic fs-6 me-2">{{ $settings->upi_id }}</span></div>
                                            @if($settings->upi_qr_code)
                                                <div class="mt-2 text-center">
                                                    <img src="{{ $settings->upi_qr_code }}" alt="UPI QR Code" class="img-fluid rounded border" style="max-width: 180px;">
                                                    <div class="small text-muted mt-1">Scan QR Code using any UPI App to complete payment</div>
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
                                            <div class="small text-muted mb-2"><i class="ri-information-line me-1"></i> Transfer order total to any active bank account below:</div>
                                            <div class="row g-2">
                                                @forelse($activeBanks as $bank)
                                                    <div class="col-12">
                                                        <div class="p-2 border rounded bg-dark-subtle">
                                                            <div class="fw-bold text-theme-dynamic"><i class="ri-bank-card-line me-1"></i>{{ $bank->bank_name }}</div>
                                                            <div class="small"><strong>A/C Holder:</strong> {{ $bank->account_holder }}</div>
                                                            <div class="small"><strong>A/C Number:</strong> <code class="text-success font-monospace">{{ $bank->account_number }}</code></div>
                                                            <div class="small"><strong>IFSC Code:</strong> {{ $bank->ifsc_code }}</div>
                                                            @if($bank->branch_name)
                                                                <div class="small text-muted"><strong>Branch:</strong> {{ $bank->branch_name }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="small text-muted">No active bank account provided.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Payment Proof Upload Section -->
                            <div class="mb-4 p-3 border border-warning rounded bg-light">
                                <label class="form-label fw-bold d-flex align-items-center mb-1 text-dark">
                                    <i class="ri-upload-cloud-2-line text-theme-dynamic fs-5 me-2"></i> Payment Proof / Receipt Screenshot <span class="badge bg-danger text-white ms-2 px-2 py-1">Mandatory *</span>
                                </label>
                                <input type="file" name="payment_proof" id="paymentProofInput" class="form-control" accept="image/*" onchange="this.classList.remove('is-invalid')">
                                <div class="alert alert-danger border-danger p-2 rounded-3 mt-2 mb-0 small fw-bold">
                                    <i class="ri-error-warning-fill me-1"></i> Mandatory Notice: Payment receipt image ONLY needs to be uploaded. (Orders cannot be processed without valid payment proof.)
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" onclick="goToTab('tab-shipping')">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Shipping Info
                                </button>
                                <button type="button" class="btn btn-theme-dynamic rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="validatePaymentProofAndNext()">
                                    Review Order Summary <i class="ri-arrow-right-line ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 4: ORDER SUMMARY & PLACE ORDER -->
                        <div class="tab-pane fade" id="tab-summary" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0 text-theme-dynamic" style="font-family: 'Outfit', sans-serif;">
                                    <i class="ri-file-list-3-line me-2"></i> Final Order Review
                                </h4>
                                <span class="badge btn-theme-dynamic px-3 py-2 rounded-pill"><i class="ri-check-double-line me-1"></i> Step 4 of 4</span>
                            </div>

                            <!-- Summary Review Details Box -->
                            <div class="bg-light p-3 rounded border mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted fw-bold text-uppercase">Shipping Address:</div>
                                        <div class="fw-bold text-dark mt-1" id="reviewName">—</div>
                                        <div class="small text-dark" id="reviewPhone">—</div>
                                        <div class="small text-muted" id="reviewAddress">—</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted fw-bold text-uppercase">Payment Method:</div>
                                        <div class="fw-bold text-theme-dynamic mt-1" id="reviewPaymentMethod">COD</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Items Summary Breakdown -->
                            <h6 class="fw-bold text-dark mb-2"><i class="ri-shopping-basket-line text-theme-dynamic me-1"></i> Cart Items Breakdown:</h6>
                            <div id="reviewCartItems" class="mb-4 bg-white p-3 rounded border"></div>

                            <div id="checkoutError" class="alert alert-danger d-none py-2 mb-3"></div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" onclick="goToTab('tab-payment')">
                                    <i class="ri-arrow-left-line me-1"></i> Edit Payment / Address
                                </button>
                                <button type="submit" class="btn btn-theme-dynamic btn-lg fw-bold rounded-pill px-5 py-2 shadow" id="submitOrderBtn">
                                    <i class="ri-shopping-bag-3-fill me-1"></i> Place Order
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Side: Sticky Live Order Summary Panel (5 Cols) -->
                <div class="col-lg-5">
                    <div class="card-custom sticky-top" style="top: 20px;">
                        <h5 class="fw-bold mb-3 text-theme-dynamic" style="font-family: 'Outfit', sans-serif;"><i class="ri-file-list-3-line me-2"></i> Live Order Total</h5>
                        
                        <div id="stickyCartSummaryList" class="mb-3 overflow-auto" style="max-height: 250px;"></div>

                        <div class="border-top border-secondary pt-3">
                            <div class="d-flex justify-content-between text-muted mb-2">
                                <span>Items Subtotal:</span>
                                <span id="subtotalVal" class="fw-bold">₹0.00</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted mb-2">
                                <span>GST Tax ({{ $settings->gst_percentage }}%):</span>
                                <span id="gstVal" class="fw-bold">₹0.00</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-4 text-success border-top border-secondary pt-2">
                                <span>Grand Total:</span>
                                <span id="grandTotalVal">₹0.00</span>
                            </div>
                        </div>

                        <div class="alert alert-light border small text-muted mt-3 mb-0">
                            <i class="ri-shield-check-line text-success me-1"></i> Direct Factory Guarantee from Sivakasi Crackers Hub.
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>

    <!-- Validation Warning Modal Popup -->
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header text-white border-0 py-3" style="background: var(--checkout-primary, #fb8500);">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="validationModalTitle">
                        <i class="ri-alert-fill"></i> Shipping Details Required
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="validationModalBody">
                    <!-- Dynamic validation message & field list -->
                </div>
                <div class="modal-footer bg-light border-0 py-2 px-4 justify-content-end">
                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4" data-bs-dismiss="modal" onclick="focusMissingShippingField()">
                        <i class="ri-edit-2-line me-1"></i> Fill Shipping Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const gstPct = {{ $settings->gst_percentage ?: 0 }};
        const minRetailAmount = {{ floatval($settings->min_retail_order_amount ?? 0) }};
        const minWholesaleAmount = {{ floatval($settings->min_wholesale_order_amount ?? 0) }};
        const customerType = '{{ $customerType ?? session('customer_type', 'retail') }}';
        let cart = JSON.parse(localStorage.getItem('crackers_cart') || '[]');

        function setCheckoutTheme(themeKey) {
            document.documentElement.setAttribute('data-checkout-theme', themeKey);
            try {
                localStorage.setItem('crackers_checkout_theme', themeKey);
            } catch(e) {}

            document.querySelectorAll('.theme-color-dot').forEach(dot => {
                if (dot.getAttribute('data-theme') === themeKey) {
                    dot.classList.add('active');
                    dot.style.boxShadow = '0 0 0 2.5px #000';
                } else {
                    dot.classList.remove('active');
                    dot.style.boxShadow = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            let savedTheme = 'amber';
            try {
                savedTheme = localStorage.getItem('crackers_checkout_theme') || 'amber';
            } catch(e) {}
            setCheckoutTheme(savedTheme);
        });

        function goToTab(tabId) {
            const tabTrigger = new bootstrap.Tab(document.querySelector(`#${tabId}-btn`));
            tabTrigger.show();
            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function showValidationModal(title, message, fieldList = []) {
            let titleEl = document.getElementById('validationModalTitle');
            let bodyEl = document.getElementById('validationModalBody');

            if (titleEl) {
                titleEl.innerHTML = `<i class="ri-alert-fill me-1"></i> ${title}`;
            }

            let fieldsHtml = '';
            if (fieldList && fieldList.length > 0) {
                fieldsHtml = `<ul class="mb-0 ps-3 small text-dark fw-semibold mt-2">
                    ${fieldList.map(f => `<li class="mb-1 text-danger"><i class="ri-asterisk me-1"></i>${f}</li>`).join('')}
                </ul>`;
            }

            let html = `
                <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 mb-2">
                    <div class="d-flex align-items-start gap-3">
                        <i class="ri-error-warning-fill text-danger display-6 flex-shrink-0"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">${message}</h6>
                            ${fieldsHtml}
                        </div>
                    </div>
                </div>
            `;

            if (bodyEl) bodyEl.innerHTML = html;

            let modalEl = document.getElementById('validationModal');
            if (modalEl) {
                let bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            }
        }

        function focusMissingShippingField() {
            const fieldMap = [
                { id: 'inputName', name: 'Full Name (*)' },
                { id: 'inputPhone', name: 'Mobile Number (*)' },
                { id: 'inputAddress', name: 'Full Address (*)' },
                { id: 'inputCity', name: 'City / District (*)' },
                { id: 'inputPincode', name: 'Pincode (*)' }
            ];

            for (let f of fieldMap) {
                let el = document.getElementById(f.id);
                if (el && !el.value.trim()) {
                    goToTab('tab-shipping');
                    setTimeout(() => {
                        el.focus();
                        el.classList.add('is-invalid');
                    }, 300);
                    break;
                }
            }
        }

        function validateShippingAndNext() {
            const fieldMap = [
                { id: 'inputName', name: 'Full Name (*)' },
                { id: 'inputPhone', name: 'Mobile Number (*)' },
                { id: 'inputAddress', name: 'Full Address (*)' },
                { id: 'inputCity', name: 'City / District (*)' },
                { id: 'inputPincode', name: 'Pincode (*)' }
            ];

            let missingFields = [];
            fieldMap.forEach(f => {
                let el = document.getElementById(f.id);
                if (el) {
                    if (!el.value.trim()) {
                        missingFields.push(f.name);
                        el.classList.add('is-invalid');
                    } else {
                        el.classList.remove('is-invalid');
                    }
                }
            });

            if (missingFields.length > 0) {
                showValidationModal(
                    'Shipping Details Required',
                    'Please fill out all required shipping fields marked with (*):',
                    missingFields
                );
                return false;
            }
            goToTab('tab-payment');
            return true;
        }

        function updateQuantity(index, newQty) {
            let parsedQty = parseInt(newQty);
            let item = cart[index];
            let minQty = (customerType === 'wholesale' && item.wholesale_min_qty) ? parseInt(item.wholesale_min_qty) : 1;

            if (isNaN(parsedQty) || parsedQty <= 0) {
                cart.splice(index, 1);
            } else if (customerType === 'wholesale' && item.wholesale_min_qty && parsedQty < minQty) {
                showValidationModal(
                    'Wholesale Minimum Quantity Required',
                    `Wholesale minimum order quantity for '${item.name}' is ${minQty} ${item.unit}(s).`,
                    []
                );
                cart[index].quantity = minQty;
            } else {
                cart[index].quantity = parsedQty;
            }
            localStorage.setItem('crackers_cart', JSON.stringify(cart));
            renderCheckoutSummary();
        }

        function renderCheckoutSummary() {
            let fullCartContainer = document.getElementById('fullCartList');
            let stickyContainer = document.getElementById('stickyCartSummaryList');
            let reviewCartContainer = document.getElementById('reviewCartItems');
            let subtotalEl = document.getElementById('subtotalVal');
            let gstEl = document.getElementById('gstVal');
            let grandTotalEl = document.getElementById('grandTotalVal');
            let btn = document.getElementById('submitOrderBtn');
            let badgeCount = document.getElementById('cartItemCountBadge');

            if (!cart || cart.length === 0) {
                const emptyHtml = `<div class="text-center py-5 text-muted"><i class="ri-shopping-cart-line display-4 opacity-50"></i><p class="mt-2 fw-bold">Your shopping cart is empty.</p><a href="{{ route('crackers.storefront') }}" class="btn btn-sm btn-theme-dynamic rounded-pill mt-2 fw-bold px-4">Browse Crackers Store</a></div>`;
                fullCartContainer.innerHTML = emptyHtml;
                stickyContainer.innerHTML = emptyHtml;
                if (reviewCartContainer) reviewCartContainer.innerHTML = emptyHtml;
                subtotalEl.innerText = '₹0.00';
                gstEl.innerText = '₹0.00';
                grandTotalEl.innerText = '₹0.00';
                btn.disabled = true;
                if (badgeCount) badgeCount.innerText = '0 Items';
                return;
            }

            let totalItems = cart.reduce((sum, i) => sum + i.quantity, 0);
            if (badgeCount) badgeCount.innerText = totalItems + ' Items';

            let subtotal = cart.reduce((sum, i) => sum + (i.price * i.quantity), 0);
            let gstAmount = subtotal * (gstPct / 100);
            let grandTotal = subtotal + gstAmount;

            subtotalEl.innerText = '₹' + subtotal.toFixed(2);
            gstEl.innerText = '₹' + gstAmount.toFixed(2);
            grandTotalEl.innerText = '₹' + grandTotal.toFixed(2);

            // Minimum Order Amount Verification
            let minRequired = (customerType === 'wholesale') ? minWholesaleAmount : minRetailAmount;
            let minAlertBox = document.getElementById('minOrderAlert');

            if (minRequired > 0 && subtotal < minRequired) {
                let diff = minRequired - subtotal;
                if (minAlertBox) {
                    minAlertBox.innerHTML = `
                        <div class="alert alert-warning border border-warning rounded-4 shadow-sm mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <strong class="d-block text-dark"><i class="ri-error-warning-fill text-warning me-1 fs-5"></i> Minimum Order Amount Requirement: ₹${minRequired.toFixed(2)} (${customerType.toUpperCase()})</strong>
                                <span class="small text-muted">Your current subtotal is <strong>₹${subtotal.toFixed(2)}</strong>. Please add items worth <strong>₹${diff.toFixed(2)}</strong> more to place order.</span>
                            </div>
                            <a href="{{ route('crackers.storefront') }}" class="btn btn-sm btn-warning text-dark rounded-pill fw-bold px-3">Add More Crackers</a>
                        </div>
                    `;
                    minAlertBox.classList.remove('d-none');
                }
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('disabled');
                }
            } else {
                if (minAlertBox) minAlertBox.classList.add('d-none');
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                }
            }

            // 1. Full Cart Table inside Tab 1
            fullCartContainer.innerHTML = `
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>PRODUCT</th>
                                <th>PRICE</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-end">TOTAL</th>
                                <th class="text-end">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${cart.map((item, idx) => `
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">${item.name}</div>
                                        <small class="text-muted">${item.unit || ''}</small>
                                    </td>
                                    <td>₹${item.price.toFixed(2)}</td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: 0 auto;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(${idx}, ${item.quantity - 1})">-</button>
                                            <input type="number" class="form-control text-center px-1" value="${item.quantity}" min="1" onchange="updateQuantity(${idx}, this.value)">
                                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(${idx}, ${item.quantity + 1})">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-success">₹${(item.price * item.quantity).toFixed(2)}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger rounded-circle" onclick="updateQuantity(${idx}, 0)" title="Remove Item">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;

            // 2. Sticky Sidebar Summary List
            stickyContainer.innerHTML = cart.map(item => `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                    <div>
                        <div class="fw-bold">${item.name}</div>
                        <small class="text-muted">₹${item.price.toFixed(2)} x ${item.quantity}</small>
                    </div>
                    <div class="fw-bold text-success">₹${(item.price * item.quantity).toFixed(2)}</div>
                </div>
            `).join('');

            // 3. Tab 4 Final Review Cart List
            if (reviewCartContainer) {
                reviewCartContainer.innerHTML = cart.map(item => `
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                        <div>
                            <span class="fw-bold">${item.name}</span>
                            <span class="text-muted ms-2">(x${item.quantity})</span>
                        </div>
                        <span class="fw-bold text-success">₹${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `).join('');
            }
        }

        function updateSummaryReview() {
            const name = document.getElementById('inputName').value || '—';
            const phone = document.getElementById('inputPhone').value || '—';
            const address = document.getElementById('inputAddress').value || '';
            const city = document.getElementById('inputCity').value || '';
            const pincode = document.getElementById('inputPincode').value || '';

            const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'COD';

            document.getElementById('reviewName').innerText = name;
            document.getElementById('reviewPhone').innerText = phone;
            document.getElementById('reviewAddress').innerText = `${address}, ${city} - ${pincode}`;
            document.getElementById('reviewPaymentMethod').innerText = selectedPayment;
        }

        function togglePaymentBox(type) {
            let upiBox = document.getElementById('upiBox');
            let bankBox = document.getElementById('bankBox');
            if (upiBox) upiBox.classList.add('d-none');
            if (bankBox) bankBox.classList.add('d-none');

            if (type === 'UPI' && upiBox) upiBox.classList.remove('d-none');
            if (type === 'Bank' && bankBox) bankBox.classList.remove('d-none');
            updateSummaryReview();
        }

        function validatePaymentProofAndNext() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'COD';
            const proofInput = document.getElementById('paymentProofInput');

            if (selectedPayment !== 'COD') {
                if (!proofInput || !proofInput.files || proofInput.files.length === 0) {
                    showValidationModal(
                        'Payment Proof Screenshot Mandatory',
                        'Payment receipt image ONLY needs to be uploaded. (Orders cannot be processed without valid payment proof.!)',
                        ['Please select and attach your payment receipt screenshot image (*)']
                    );
                    if (proofInput) {
                        goToTab('tab-payment');
                        setTimeout(() => {
                            proofInput.focus();
                            proofInput.classList.add('is-invalid');
                        }, 300);
                    }
                    return false;
                }
            }
            if (proofInput) proofInput.classList.remove('is-invalid');
            goToTab('tab-summary');
            return true;
        }

        document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', function() {
                updateSummaryReview();
            });
        });

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let isShippingValid = validateShippingAndNext();
            if (!isShippingValid) {
                return;
            }

            let isPaymentValid = validatePaymentProofAndNext();
            if (!isPaymentValid) {
                return;
            }

            let btn = document.getElementById('submitOrderBtn');
            let errBox = document.getElementById('checkoutError');

            if (!cart || cart.length === 0) {
                showValidationModal('Empty Cart', 'Your shopping cart is empty. Please add items before placing an order.', []);
                return;
            }

            btn.disabled = true;
            errBox.classList.add('d-none');

            let formData = new FormData(this);
            formData.append('items_json', JSON.stringify(cart));

            fetch('{{ route("crackers.place-order") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json().catch(() => ({ message: 'Invalid response from server.' }));
                if (res.ok && data.success) {
                    localStorage.removeItem('crackers_cart');
                    window.location.href = data.redirect_url;
                } else {
                    let msg = data.message || 'Error placing order.';
                    if (data.errors && typeof data.errors === 'object') {
                        msg = Object.values(data.errors).flat().join('<br>');
                    }
                    errBox.innerHTML = '<i class="ri-error-warning-line me-1"></i> ' + msg;
                    errBox.classList.remove('d-none');
                    btn.disabled = false;
                    errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            })
            .catch(err => {
                errBox.innerHTML = '<i class="ri-error-warning-line me-1"></i> Network error: ' + (err.message || 'Unable to place order.');
                errBox.classList.remove('d-none');
                btn.disabled = false;
            });
        });

        renderCheckoutSummary();
    </script>
</body>
</html>
