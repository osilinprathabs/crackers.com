<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account & Profile | Crackers.com</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --gold-gradient: linear-gradient(135deg, #ffb703 0%, #fb8500 50%, #ff4800 100%);
            --hero-gradient: linear-gradient(135deg, #fff7ed 0%, #ffedd5 60%, #fef3c7 100%);
            --primary-amber: #fb8500;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --card-border: rgba(148, 163, 184, 0.18);
        }

        /* Dynamic UI Color Theme Presets */
        [data-profile-theme="blue"] {
            --primary-amber: #2563eb;
            --gold-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            --hero-gradient: linear-gradient(135deg, #eff6ff 0%, #dbeafe 60%, #bfdbfe 100%);
        }

        [data-profile-theme="emerald"] {
            --primary-amber: #10b981;
            --gold-gradient: linear-gradient(135deg, #34d399 0%, #10b981 50%, #059669 100%);
            --hero-gradient: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 60%, #a7f3d0 100%);
        }

        [data-profile-theme="purple"] {
            --primary-amber: #8b5cf6;
            --gold-gradient: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 50%, #7c3aed 100%);
            --hero-gradient: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 60%, #ddd6fe 100%);
        }

        [data-profile-theme="crimson"] {
            --primary-amber: #ef4444;
            --gold-gradient: linear-gradient(135deg, #f87171 0%, #ef4444 50%, #dc2626 100%);
            --hero-gradient: linear-gradient(135deg, #fef2f2 0%, #fee2e2 60%, #fecaca 100%);
        }

        [data-profile-theme="dark"] {
            --primary-amber: #f59e0b;
            --gold-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --hero-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --bg-light: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --card-border: rgba(255, 255, 255, 0.1);
        }

        .ecom-profile-hero {
            background: var(--hero-gradient) !important;
            border: 1px solid var(--primary-amber) !important;
        }

        .avatar-circle-lg {
            background: var(--gold-gradient) !important;
        }

        .ecom-sidebar-card, .ecom-main-card {
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border-color: var(--card-border) !important;
        }

        .sidebar-menu-btn.active {
            background: var(--gold-gradient) !important;
            color: #ffffff !important;
        }

        .btn-save-ecom {
            background: var(--gold-gradient) !important;
            color: #ffffff !important;
        }

        .input-group-text {
            color: var(--primary-amber) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-amber) !important;
            box-shadow: 0 0 0 0.25rem rgba(251, 133, 0, 0.15);
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            padding: 0.9rem 0;
        }

        .brand-logo {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Outfit', sans-serif;
        }

        /* Hero Customer Header */
        .ecom-profile-hero {
            background: var(--hero-gradient);
            border: 1px solid rgba(251, 133, 0, 0.25);
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(251, 133, 0, 0.08);
            padding: 2.25rem;
            position: relative;
            overflow: hidden;
        }

        .avatar-circle-lg {
            width: 88px;
            height: 88px;
            background: var(--gold-gradient);
            color: #000;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.25rem;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(251, 133, 0, 0.3);
            border: 3px solid #ffffff;
        }

        .stat-card-pill {
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 16px;
            padding: 0.85rem 1.35rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        /* eCommerce Sidebar Navigation */
        .ecom-sidebar-card {
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            padding: 1.25rem;
        }

        .sidebar-menu-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.85rem 1.2rem;
            border-radius: 14px;
            color: #475569;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
            text-align: left;
        }

        .sidebar-menu-btn:hover {
            background: #fff7ed;
            color: #fb8500;
        }

        .sidebar-menu-btn.active {
            background: var(--gold-gradient);
            color: #000 !important;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(251, 133, 0, 0.25);
        }

        /* Main Form Card */
        .ecom-main-card {
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            padding: 2.25rem;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #fb8500;
            font-size: 1.15rem;
            padding-left: 1.15rem;
            padding-right: 1.15rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #fb8500;
            box-shadow: 0 0 0 0.25rem rgba(251, 133, 0, 0.15);
        }

        .btn-save-ecom {
            background: var(--gold-gradient);
            color: #000;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            border: none;
            border-radius: 50px;
            padding: 0.85rem 2.5rem;
            box-shadow: 0 8px 25px rgba(251, 133, 0, 0.3);
            transition: all 0.3s ease;
        }

        .btn-save-ecom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(251, 133, 0, 0.45);
            color: #000;
        }

        .recent-order-item {
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 1rem;
            transition: all 0.2s ease;
        }
        .recent-order-item:hover {
            border-color: #fb8500;
            background: #fffdfa;
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
                <i class="ri-fire-fill text-warning"></i> Crackers.com
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-dark rounded-pill px-3 btn-sm fw-semibold">
                    <i class="ri-store-2-line me-1"></i> Storefront
                </a>
                <a href="{{ route('crackers.my-orders') }}" class="btn btn-outline-warning rounded-pill px-3 btn-sm fw-bold">
                    <i class="ri-shopping-bag-3-line me-1"></i> My Orders
                </a>
                <form action="{{ route('crackers.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3 btn-sm">
                        <i class="ri-logout-box-r-line me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container pb-5">

        <!-- Top eCommerce Profile Hero Banner -->
        <div class="ecom-profile-hero mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle-lg">
                            {{ strtoupper(substr($user->name ?? 'C', 0, 2)) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="fw-bold text-dark mb-0" style="font-family: 'Outfit', sans-serif;">{{ $user->name }}</h3>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold small"><i class="ri-checkbox-circle-fill me-1"></i> Verified Account</span>
                            </div>
                            <div class="text-muted small mt-2 d-flex align-items-center gap-3 flex-wrap">
                                <span><i class="ri-phone-fill text-warning me-1"></i> <strong>{{ $user->phone }}</strong></span>
                                @if($user->email)
                                    <span>• <i class="ri-mail-fill text-warning me-1"></i> {{ $user->email }}</span>
                                @endif
                                @if(isset($customer->customer_code))
                                    <span>• Customer Code: <strong>{{ $customer->customer_code }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        <div class="stat-card-pill">
                            <small class="text-muted d-block fw-semibold" style="font-size: 0.75rem;">TOTAL ORDERS</small>
                            <span class="fs-5 fw-bold text-dark"><i class="ri-shopping-bag-line text-warning me-1"></i> {{ $totalOrdersCount ?? 0 }} Orders</span>
                        </div>
                        <div class="stat-card-pill">
                            <small class="text-muted d-block fw-semibold" style="font-size: 0.75rem;">DELIVERY CITY</small>
                            <span class="fs-5 fw-bold text-dark"><i class="ri-map-pin-line text-danger me-1"></i> {{ $customer->billing_address['city'] ?? 'Sivakasi' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert" style="background: #f0fdf4; border-left: 5px solid #22c55e !important;">
                <div class="d-flex align-items-center">
                    <i class="ri-checkbox-circle-fill text-success fs-3 me-2"></i>
                    <div>
                        <strong class="text-success fs-6">{{ session('success') }}</strong>
                        <div class="small text-muted">Your account profile details have been updated cleanly.</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert" style="background: #fef2f2; border-left: 5px solid #ef4444 !important;">
                <div class="d-flex align-items-center mb-2">
                    <i class="ri-error-warning-fill text-danger fs-3 me-2"></i>
                    <strong class="text-danger fs-6">Please resolve the following errors:</strong>
                </div>
                <ul class="mb-0 text-danger ps-4 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 2-Column eCommerce Layout -->
        <div class="row g-4">

            <!-- Left Navigation Sidebar -->
            <div class="col-lg-3">
                <div class="ecom-sidebar-card mb-4">
                    <div class="text-uppercase small fw-bold text-muted mb-3 px-2" style="letter-spacing: 0.5px;">Account Dashboard</div>
                    <div class="d-flex flex-column gap-1">
                        <button class="sidebar-menu-btn active" type="button">
                            <span><i class="ri-user-3-fill me-2 text-warning"></i> Personal Details</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                        <a href="#deliveryAddressSection" class="sidebar-menu-btn">
                            <span><i class="ri-map-pin-user-line me-2 text-warning"></i> Delivery Address</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                        <a href="{{ route('crackers.my-orders') }}" class="sidebar-menu-btn">
                            <span><i class="ri-shopping-bag-3-line me-2 text-warning"></i> My Orders History</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $totalOrdersCount ?? 0 }}</span>
                        </a>
                        <a href="#securitySection" class="sidebar-menu-btn">
                            <span><i class="ri-lock-password-line me-2 text-warning"></i> Security & Password</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </div>

                    <hr class="my-3 text-muted">

                    <form action="{{ route('crackers.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-menu-btn text-danger">
                            <span><i class="ri-logout-box-r-line me-2"></i> Log Out Account</span>
                        </button>
                    </form>
                </div>

                <!-- Recent Orders Quick Widget -->
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <div class="ecom-sidebar-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0"><i class="ri-history-line text-warning me-1"></i> Recent Orders</h6>
                            <a href="{{ route('crackers.my-orders') }}" class="small text-warning text-decoration-none fw-bold">View All</a>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($recentOrders as $ro)
                                <div class="recent-order-item d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bold small text-dark">#{{ $ro->order_number }}</div>
                                        <small class="text-muted">{{ $ro->created_at ? $ro->created_at->format('d M Y') : date('d M Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success small">₹{{ number_format($ro->grand_total, 2) }}</div>
                                        <a href="{{ route('crackers.order-invoice', $ro->order_number) }}" target="_blank" class="badge bg-light text-dark border text-decoration-none" title="Download Invoice">PDF</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Main Form Content Column -->
            <div class="col-lg-9">
                <div class="ecom-main-card">
                    <form action="{{ route('crackers.profile.update') }}" method="POST">
                        @csrf

                        <!-- Section 1: Personal Details -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-light text-warning rounded-3 border"><i class="ri-user-3-line fs-4"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Personal Profile Details</h5>
                                        <small class="text-muted">Manage your full legal name, phone number, and primary email address.</small>
                                    </div>
                                </div>

                                <!-- Dynamic UI Color Theme Switcher -->
                                <div class="d-flex align-items-center gap-2 bg-light p-2 rounded-pill border shadow-sm">
                                    <span class="fw-bold small text-muted px-1" style="font-size: 11px;"><i class="ri-palette-line text-warning me-1"></i> UI Color Theme:</span>
                                    <button type="button" class="btn p-0 rounded-circle theme-color-dot active" data-theme="amber" style="width: 22px; height: 22px; background: #fb8500; border: 2px solid #ffffff;" onclick="setProfileTheme('amber')" title="Festive Amber (Default)"></button>
                                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="blue" style="width: 22px; height: 22px; background: #2563eb; border: 2px solid #ffffff;" onclick="setProfileTheme('blue')" title="Sapphire Blue"></button>
                                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="emerald" style="width: 22px; height: 22px; background: #10b981; border: 2px solid #ffffff;" onclick="setProfileTheme('emerald')" title="Emerald Green"></button>
                                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="purple" style="width: 22px; height: 22px; background: #8b5cf6; border: 2px solid #ffffff;" onclick="setProfileTheme('purple')" title="Amethyst Purple"></button>
                                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="crimson" style="width: 22px; height: 22px; background: #ef4444; border: 2px solid #ffffff;" onclick="setProfileTheme('crimson')" title="Ruby Crimson"></button>
                                    <button type="button" class="btn p-0 rounded-circle theme-color-dot" data-theme="dark" style="width: 22px; height: 22px; background: #0f172a; border: 2px solid #ffffff;" onclick="setProfileTheme('dark')" title="Midnight Dark Mode"></button>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">FULL NAME <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                                        <input type="text" name="name" class="form-control fs-6 rounded-end-3" value="{{ old('name', $user->name) }}" required placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">MOBILE PHONE NUMBER <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                        <input type="text" name="phone" class="form-control fs-6 rounded-end-3" value="{{ old('phone', $user->phone) }}" required placeholder="10-digit mobile number">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">EMAIL ADDRESS (OPTIONAL)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                        <input type="email" name="email" class="form-control fs-6 rounded-end-3" value="{{ old('email', $user->email) }}" placeholder="e.g. customer@gmail.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: rgba(148, 163, 184, 0.2);">

                        <!-- Section 2: Delivery Address -->
                        @php
                            $billing = is_array($customer->billing_address ?? null) ? $customer->billing_address : [];
                        @endphp
                        <div class="mb-4 pb-2" id="deliveryAddressSection">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-light text-warning rounded-3 border"><i class="ri-map-pin-user-line fs-4"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Default Delivery Address</h5>
                                        <small class="text-muted">Your primary shipping address for cracker parcel dispatches.</small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                    <i class="ri-truck-fill text-warning me-1"></i> 5 - 7 Days Express Delivery
                                </span>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">STREET ADDRESS / HOUSE NO. / LANDMARK</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-home-4-line"></i></span>
                                        <textarea name="address" class="form-control rounded-end-3 fs-6" rows="3" placeholder="Door number, building name, street address, landmark...">{{ old('address', $billing['address'] ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">CITY / TOWN</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-building-4-line"></i></span>
                                        <input type="text" name="city" class="form-control fs-6 rounded-end-3" value="{{ old('city', $billing['city'] ?? '') }}" placeholder="e.g. Sivakasi, Madurai, Chennai">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">PINCODE / ZIP CODE</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-map-pin-2-line"></i></span>
                                        <input type="text" name="pincode" class="form-control fs-6 rounded-end-3" value="{{ old('pincode', $billing['pincode'] ?? '') }}" placeholder="e.g. 626123">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: rgba(148, 163, 184, 0.2);">

                        <!-- Section 3: Password Security -->
                        <div class="mb-4" id="securitySection">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="p-2 bg-light text-warning rounded-3 border"><i class="ri-lock-password-line fs-4"></i></div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Account Security & Password</h5>
                                    <small class="text-muted">Update your login security key if needed.</small>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">NEW PASSWORD (LEAVE BLANK TO KEEP CURRENT)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-key-2-line"></i></span>
                                        <input type="password" name="new_password" id="profileNewPassword" class="form-control fs-6" placeholder="Enter new password (min 6 characters)">
                                        <button class="btn btn-outline-secondary px-3 rounded-end-3" type="button" onclick="togglePasswordVisibility('profileNewPassword', 'toggleProfilePasswordIcon')" title="Toggle Password Visibility">
                                            <i class="ri-eye-line" id="toggleProfilePasswordIcon"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2"><i class="ri-shield-check-line text-success me-1"></i> Password requires a minimum of 6 characters.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Bar -->
                        <div class="d-flex justify-content-between align-items-center gap-3 pt-4 border-top">
                            <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                                <i class="ri-arrow-left-line me-1"></i> Return to Store
                            </a>
                            <button type="submit" class="btn btn-save-ecom">
                                <i class="ri-save-3-fill me-1"></i> Save Account Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ri-eye-off-line';
            } else {
                input.type = 'password';
                icon.className = 'ri-eye-line';
            }
        }

        function setProfileTheme(themeKey) {
            document.documentElement.setAttribute('data-profile-theme', themeKey);
            try {
                localStorage.setItem('crackers_profile_theme', themeKey);
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
                savedTheme = localStorage.getItem('crackers_profile_theme') || 'amber';
            } catch(e) {}
            setProfileTheme(savedTheme);
        });
    </script>
</body>
</html>
