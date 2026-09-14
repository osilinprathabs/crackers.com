<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Login | {{ $settings->company_name ?: 'S.R. TRADERS' }}</title>

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* CSS Variables for Light & Dark Mode */
        [data-theme="light"] {
            --bg-main: radial-gradient(circle at 50% 15%, #fffdf5 0%, #fef3c7 40%, #ffedd5 75%, #fed7aa 100%);
            --card-bg: rgba(255, 255, 255, 0.94);
            --card-border: rgba(245, 158, 11, 0.35);
            --card-shadow: 0 20px 45px -10px rgba(217, 119, 6, 0.22), 0 0 30px rgba(251, 146, 60, 0.2);
            --promo-bg: linear-gradient(155deg, #7c2d12 0%, #9a3412 45%, #c2410c 100%);
            --text-title: #0f172a;
            --text-body: #475569;
            --text-muted: #64748b;
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --gold-gradient: linear-gradient(135deg, #d97706 0%, #ea580c 50%, #dc2626 100%);
            --btn-text: #ffffff;
            --btn-shadow: 0 8px 20px rgba(234, 88, 12, 0.35);
            --switch-bg: rgba(255, 255, 255, 0.85);
            --switch-border: rgba(217, 119, 6, 0.25);
        }

        [data-theme="dark"] {
            --bg-main: #07090e;
            --card-bg: rgba(15, 20, 32, 0.92);
            --card-border: rgba(255, 183, 3, 0.28);
            --card-shadow: 0 25px 50px rgba(0, 0, 0, 0.7), 0 0 30px rgba(255, 140, 0, 0.25);
            --promo-bg: linear-gradient(160deg, rgba(26, 32, 53, 0.98) 0%, rgba(13, 17, 28, 0.98) 100%);
            --text-title: #ffffff;
            --text-body: #94a3b8;
            --text-muted: #64748b;
            --input-bg: rgba(24, 32, 50, 0.75);
            --input-border: rgba(255, 255, 255, 0.14);
            --input-text: #ffffff;
            --gold-gradient: linear-gradient(135deg, #ffc107 0%, #ff8c00 50%, #ff3b00 100%);
            --btn-text: #000000;
            --btn-shadow: 0 8px 25px rgba(255, 140, 0, 0.4);
            --switch-bg: rgba(255, 255, 255, 0.08);
            --switch-border: rgba(255, 255, 255, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-main);
            color: var(--text-body);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: background 0.4s ease;
        }

        /* Desktop specific: Strict 100vh fitting without scrollbar */
        @media (min-width: 992px) {
            body {
                height: 100vh;
                overflow: hidden;
            }
        }

        /* Mobile fallback: Allow normal page scrolling */
        @media (max-width: 991.98px) {
            body {
                height: auto;
                min-height: 100vh;
                overflow-y: auto;
                padding: 1rem 0.5rem;
            }
        }

        /* Fireworks Sky Shot Canvas */
        #fireworksCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 1;
        }

        /* Festive Ambient Background Accents */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }

        .glow-1 {
            top: -8%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(245, 158, 11, 0.2);
            animation: floatGlow 8s infinite alternate ease-in-out;
        }

        .glow-2 {
            bottom: -10%;
            right: -5%;
            width: 450px;
            height: 450px;
            background: rgba(239, 68, 68, 0.16);
            animation: floatGlow 10s infinite alternate-reverse ease-in-out;
        }

        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(25px, 15px) scale(1.08); }
        }

        /* Main Container Wrapper */
        .auth-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 992px) {
            .auth-wrapper {
                height: 100vh;
                max-height: 100vh;
                padding: 1.25rem;
            }
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            width: 100%;
            transition: all 0.4s ease;
            position: relative;
        }

        @media (min-width: 992px) {
            .glass-card {
                max-height: calc(100vh - 2.5rem);
            }
        }

        /* Theme Switcher Top Floating Bar */
        .theme-switcher-bar {
            position: absolute;
            top: 14px;
            right: 18px;
            z-index: 10;
        }

        .btn-theme-toggle {
            background: var(--switch-bg);
            border: 1px solid var(--switch-border);
            color: var(--text-title);
            padding: 0.35rem 0.8rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(10px);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .btn-theme-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.25);
        }

        /* Left Side Promo Banner */
        .promo-side {
            background: var(--promo-bg);
            border-right: 1px solid var(--card-border);
            padding: 2.25rem 2.25rem;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            color: #ffffff;
        }

        .promo-side::after {
            content: '🎆';
            position: absolute;
            bottom: -20px;
            right: -20px;
            font-size: 9rem;
            opacity: 0.08;
            pointer-events: none;
        }

        .brand-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 50px;
            padding: 0.4rem 1.1rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            color: #ffffff;
            backdrop-filter: blur(8px);
        }

        .brand-logo-badge i {
            font-size: 1.4rem;
            color: #fbbf24;
        }

        .promo-heading {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            margin-top: 1rem;
            margin-bottom: 0.6rem;
        }

        .promo-heading span {
            color: #fef08a;
            text-shadow: 0 0 15px rgba(254, 240, 138, 0.4);
        }

        .promo-desc {
            font-size: 0.88rem;
            line-height: 1.5;
            margin-bottom: 0.85rem;
            opacity: 0.9;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            padding: 0.6rem 0.9rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateX(4px);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(254, 240, 138, 0.2);
            color: #fef08a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        /* Interactive Sky Shot Trigger Badge */
        .skyshot-trigger-btn {
            background: rgba(254, 240, 138, 0.18);
            border: 1px dashed rgba(254, 240, 138, 0.5);
            color: #fef08a;
            border-radius: 10px;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            margin-top: 0.25rem;
        }

        .skyshot-trigger-btn:hover {
            background: rgba(254, 240, 138, 0.35);
            transform: scale(1.02);
            color: #ffffff;
        }

        /* Right Form Side */
        .form-side {
            padding: 2.25rem 2.5rem;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (max-width: 991.98px) {
            .form-side {
                padding: 1.75rem 1.25rem;
            }
        }

        .back-home-btn {
            color: var(--text-body);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s ease;
            margin-bottom: 0.85rem;
        }

        .back-home-btn:hover {
            color: #ea580c;
        }

        .auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-title);
            margin-bottom: 0.2rem;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            color: var(--text-body);
            font-size: 0.88rem;
            margin-bottom: 1.25rem;
        }

        /* Custom Input Styling */
        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--text-body);
            text-transform: uppercase;
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .input-group-custom {
            position: relative;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .input-group-custom:focus-within {
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15), 0 0 12px rgba(234, 88, 12, 0.2);
        }

        .input-icon-box {
            padding: 0.65rem 0.25rem 0.65rem 0.95rem;
            color: #ea580c;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control-custom {
            background: transparent !important;
            border: none !important;
            color: var(--input-text) !important;
            font-size: 0.92rem;
            font-weight: 500;
            padding: 0.65rem 0.85rem 0.65rem 0.5rem;
            width: 100%;
            box-shadow: none !important;
        }

        .form-control-custom::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .toggle-pw-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 0 0.95rem;
            font-size: 1.15rem;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .toggle-pw-btn:hover {
            color: #ea580c;
        }

        /* Festive Submit Button */
        .btn-festive-glow {
            background: var(--gold-gradient);
            color: var(--btn-text);
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 0.98rem;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.75rem;
            width: 100%;
            box-shadow: var(--btn-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .btn-festive-glow::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            transform: rotate(45deg) translateY(-100%);
            transition: transform 0.6s ease;
        }

        .btn-festive-glow:hover::before {
            transform: rotate(45deg) translateY(100%);
        }

        .btn-festive-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(234, 88, 12, 0.45);
            color: var(--btn-text);
        }

        .switch-auth-box {
            background: var(--switch-bg);
            border: 1px solid var(--switch-border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            text-align: center;
        }

        .switch-link {
            color: #ea580c;
            font-weight: 700;
            text-decoration: none;
        }

        .switch-link:hover {
            color: #d97706;
            text-decoration: underline;
        }

        .alert-festive-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #b91c1c;
            border-radius: 12px;
        }

        .alert-festive-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #15803d;
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <!-- Canvas for Cracker Blast & Sky Shot Fireworks -->
    <canvas id="fireworksCanvas"></canvas>

    <!-- Ambient Glowing Accents -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <!-- Main Auth Section -->
    <div class="auth-wrapper">
        <div class="glass-card">
            <!-- Floating Theme Switcher inside Card Header -->
            <div class="theme-switcher-bar">
                <button type="button" class="btn-theme-toggle" id="themeToggleBtn" title="Toggle Light / Dark Theme">
                    <i class="ri-sun-line" id="themeIcon"></i>
                    <span id="themeLabel">Light Mode</span>
                </button>
            </div>

            <div class="row g-0 h-100 align-items-stretch">

                <!-- Left Column: Festive Branding & Features -->
                <div class="col-lg-5 d-none d-lg-flex promo-side">
                    <div>
                        <a href="{{ route('crackers.storefront') }}" class="brand-logo-badge text-decoration-none">
                            <i class="ri-fire-fill"></i>
                            <span>{{ $settings->company_name ?: 'S.R. TRADERS' }}</span>
                        </a>

                        <h2 class="promo-heading">
                            Light Up Your World with <span>Genuine Crackers</span>
                        </h2>
                        <p class="promo-desc">
                            Experience festive fireworks with direct factory rates, doorstep delivery, and 100% green cracker guarantee.
                        </p>

                        <!-- Feature Badges -->
                        <div class="feature-item">
                            <div class="feature-icon"><i class="ri-rocket-line"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Sky Shots & Fancy Crackers</h6>
                                <span class="small opacity-80" style="font-size: 0.75rem;">Premium high-altitude sky bursts & glitter effects</span>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon"><i class="ri-percent-line"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Wholesale & Retail Discounts</h6>
                                <span class="small opacity-80" style="font-size: 0.75rem;">Direct Sivakasi manufacturer pricing save up to 80%</span>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon"><i class="ri-truck-line"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Express Safe Transport</h6>
                                <span class="small opacity-80" style="font-size: 0.75rem;">Damage-proof box packing & live delivery updates</span>
                            </div>
                        </div>

                        <!-- Interactive Sky Shot Launcher Button -->
                        <button type="button" class="skyshot-trigger-btn" id="launchSkyShotBtn">
                            <i class="ri-sparkling-fill fs-6"></i>
                            <span>Launch Sky Shot Cracker Blast! 🎆</span>
                        </button>
                    </div>

                    <div class="pt-2 mt-2 border-top border-white border-opacity-25 d-flex align-items-center gap-2 text-warning small fw-semibold">
                        <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                        <span style="font-size: 0.78rem;">Diwali & Festive Mega Sale is LIVE!</span>
                    </div>
                </div>

                <!-- Right Column: Login Form -->
                <div class="col-lg-7 form-side">
                    <!-- Top Navigation Link -->
                    <div class="d-flex justify-content-between align-items-center me-5">
                        <a href="{{ route('crackers.storefront') }}" class="back-home-btn">
                            <i class="ri-arrow-left-line"></i> Back to Store
                        </a>
                        <div class="d-lg-none me-4">
                            <a href="{{ route('crackers.storefront') }}" class="fw-bold text-danger text-decoration-none" style="font-family: 'Outfit', sans-serif; font-size: 0.95rem;">
                                <i class="ri-fire-fill me-1 text-warning"></i> {{ $settings->company_name ?: 'S.R. TRADERS' }}
                            </a>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="mb-3">
                        <h1 class="auth-title">Customer Login</h1>
                        <p class="auth-subtitle">Enter your Mobile Number / Email and Password</p>
                    </div>

                    <!-- Flash Alerts -->
                    @if(session('error'))
                        <div class="alert alert-festive-danger alert-dismissible fade show p-2 px-3 mb-3" role="alert">
                            <div class="d-flex align-items-center small">
                                <i class="ri-error-warning-fill me-2 fs-6"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-festive-success alert-dismissible fade show p-2 px-3 mb-3" role="alert">
                            <div class="d-flex align-items-center small">
                                <i class="ri-checkbox-circle-fill me-2 fs-6"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('crackers.login') }}" method="POST" id="loginForm" autocomplete="off">
                        @csrf

                        <!-- Mobile / Email Field -->
                        <div class="mb-3">
                            <label class="form-label-custom">
                                <span>Mobile Number or Email <span class="text-danger">*</span></span>
                            </label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="ri-cellphone-line"></i>
                                </div>
                                <input type="text"
                                       name="login"
                                       class="form-control-custom"
                                       required
                                       placeholder="e.g. 9876543210 or name@gmail.com"
                                       value="{{ old('login') }}"
                                       autofocus>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label class="form-label-custom">
                                <span>Password <span class="text-danger">*</span></span>
                            </label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="ri-lock-password-line"></i>
                                </div>
                                <input type="password"
                                       name="password"
                                       id="loginPassword"
                                       class="form-control-custom"
                                       required
                                       placeholder="Enter password (default is mobile number)">
                                <button type="button" class="toggle-pw-btn" id="togglePasswordBtn" title="Show / Hide Password">
                                    <i class="ri-eye-line" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Options: Remember Me -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check d-flex align-items-center gap-2 mb-0 ps-0">
                                <input class="form-check-input ms-0" type="checkbox" name="remember" id="rememberMe" checked style="width: 16px; height: 16px; cursor: pointer;">
                                <label class="form-check-label fw-semibold cursor-pointer small" for="rememberMe" style="font-size: 0.82rem;">
                                    Keep me logged in
                                </label>
                            </div>
                        </div>

                        <!-- Submit Festive Button -->
                        <button type="submit" class="btn btn-festive-glow mb-3" id="submitLoginBtn">
                            <span>LOG IN TO STORE</span>
                            <i class="ri-arrow-right-line ms-2 fs-6 align-middle"></i>
                        </button>
                    </form>

                    <!-- Account Switch Box -->
                    <div class="switch-auth-box">
                        <span class="small" style="font-size: 0.82rem;">Don't have an account yet?</span>
                        <a href="{{ route('crackers.register-page') }}" class="switch-link small ms-1" style="font-size: 0.82rem;">
                            CREATE NEW ACCOUNT <i class="ri-user-add-line ms-1 align-middle"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fireworks & Sky Shot Animations Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Theme Toggle Logic
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeIcon = document.getElementById('themeIcon');
            const themeLabel = document.getElementById('themeLabel');
            const htmlTag = document.documentElement;

            function setTheme(mode) {
                htmlTag.setAttribute('data-theme', mode);
                if (mode === 'light') {
                    themeIcon.className = 'ri-moon-line';
                    themeLabel.textContent = 'Dark Mode';
                } else {
                    themeIcon.className = 'ri-sun-line';
                    themeLabel.textContent = 'Light Mode';
                }
                localStorage.setItem('crackers_theme', mode);
            }

            const savedTheme = localStorage.getItem('crackers_theme') || 'light';
            setTheme(savedTheme);

            themeToggleBtn.addEventListener('click', function () {
                const currentTheme = htmlTag.getAttribute('data-theme');
                setTheme(currentTheme === 'light' ? 'dark' : 'light');
            });

            // Password Toggle
            const passwordInput = document.getElementById('loginPassword');
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleIcon.className = isPassword ? 'ri-eye-off-line' : 'ri-eye-line';
                });
            }

            // -------------------------------------------------------------
            // FIREWORKS, SKY SHOT & CRACKER BLAST ENGINE
            // -------------------------------------------------------------
            const canvas = document.getElementById('fireworksCanvas');
            const ctx = canvas.getContext('2d');

            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            const rockets = [];
            const particles = [];
            const colors = ['#ff0055', '#ffb703', '#00f5d4', '#7b2cbf', '#ff5400', '#22c55e', '#3b82f6', '#ff007f'];

            class Rocket {
                constructor(startX, startY, targetX, targetY) {
                    this.x = startX;
                    this.y = startY;
                    this.targetX = targetX;
                    this.targetY = targetY;
                    this.speed = 9 + Math.random() * 4;
                    const angle = Math.atan2(targetY - startY, targetX - startX);
                    this.vx = Math.cos(angle) * this.speed;
                    this.vy = Math.sin(angle) * this.speed;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.trail = [];
                    this.exploded = false;
                }

                update() {
                    this.trail.push({ x: this.x, y: this.y, alpha: 1 });
                    if (this.trail.length > 8) this.trail.shift();

                    this.x += this.vx;
                    this.y += this.vy;

                    // Distance check to explode
                    const dist = Math.hypot(this.targetX - this.x, this.targetY - this.y);
                    if (dist < 15 || this.vy >= 0 || this.y <= this.targetY) {
                        this.exploded = true;
                        createExplosion(this.x, this.y, this.color);
                    }
                }

                draw() {
                    // Draw Rocket Trail
                    for (let i = 0; i < this.trail.length; i++) {
                        const pt = this.trail[i];
                        ctx.beginPath();
                        ctx.arc(pt.x, pt.y, (i + 1) * 0.6, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(255, 200, 50, ${i / this.trail.length})`;
                        ctx.fill();
                    }

                    // Rocket Head Sparkle
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, 3, 0, Math.PI * 2);
                    ctx.fillStyle = '#ffffff';
                    ctx.shadowBlur = 10;
                    ctx.shadowColor = this.color;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }
            }

            class Particle {
                constructor(x, y, color) {
                    this.x = x;
                    this.y = y;
                    this.color = color;
                    const angle = Math.random() * Math.PI * 2;
                    const speed = 2 + Math.random() * 8;
                    this.vx = Math.cos(angle) * speed;
                    this.vy = Math.sin(angle) * speed;
                    this.gravity = 0.12;
                    this.friction = 0.96;
                    this.alpha = 1;
                    this.decay = 0.015 + Math.random() * 0.02;
                    this.size = 2 + Math.random() * 2.5;
                }

                update() {
                    this.vx *= this.friction;
                    this.vy *= this.friction;
                    this.vy += this.gravity;
                    this.x += this.vx;
                    this.y += this.vy;
                    this.alpha -= this.decay;
                }

                draw() {
                    ctx.save();
                    ctx.globalAlpha = Math.max(0, this.alpha);
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = this.color;
                    ctx.shadowBlur = 12;
                    ctx.shadowColor = this.color;
                    ctx.fill();
                    ctx.restore();
                }
            }

            function createExplosion(x, y, baseColor) {
                const particleCount = 45 + Math.floor(Math.random() * 30);
                const chosenColor = baseColor || colors[Math.floor(Math.random() * colors.length)];
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle(x, y, chosenColor));
                }
            }

            function launchSkyShot(x, y) {
                const startX = x || (canvas.width * 0.2 + Math.random() * canvas.width * 0.6);
                const startY = canvas.height;
                const targetX = startX + (Math.random() * 100 - 50);
                const targetY = y || (canvas.height * 0.15 + Math.random() * canvas.height * 0.35);

                rockets.push(new Rocket(startX, startY, targetX, targetY));
            }

            // Animation Loop
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Update & Draw Rockets
                for (let i = rockets.length - 1; i >= 0; i--) {
                    rockets[i].update();
                    rockets[i].draw();
                    if (rockets[i].exploded) {
                        rockets.splice(i, 1);
                    }
                }

                // Update & Draw Explosion Particles
                for (let i = particles.length - 1; i >= 0; i--) {
                    particles[i].update();
                    particles[i].draw();
                    if (particles[i].alpha <= 0) {
                        particles.splice(i, 1);
                    }
                }

                requestAnimationFrame(animate);
            }
            animate();

            // Auto Sky Shot Launcher Interval
            setInterval(() => {
                launchSkyShot();
            }, 1800);

            // Initial Burst on load
            setTimeout(() => {
                launchSkyShot(canvas.width * 0.3, canvas.height * 0.25);
                setTimeout(() => launchSkyShot(canvas.width * 0.7, canvas.height * 0.3), 300);
            }, 500);

            // Interactive Triggers
            const launchBtn = document.getElementById('launchSkyShotBtn');
            if (launchBtn) {
                launchBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    launchSkyShot();
                    setTimeout(() => launchSkyShot(), 250);
                    setTimeout(() => launchSkyShot(), 500);
                });
            }

            // Submit Button & Form Cracker Blast Handler
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitLoginBtn');
            let isSubmitting = false;

            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    if (isSubmitting) return;
                    e.preventDefault();
                    isSubmitting = true;

                    const rect = submitBtn ? submitBtn.getBoundingClientRect() : { left: window.innerWidth / 2, top: window.innerHeight / 2, width: 0 };
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top;

                    // Explosive particle bursts at button location
                    createExplosion(centerX, centerY, '#ffb703');
                    createExplosion(centerX - 40, centerY, '#ff5400');
                    createExplosion(centerX + 40, centerY, '#00f5d4');

                    // Launch rapid sky shots barrage across screen
                    for (let i = 0; i < 7; i++) {
                        setTimeout(() => {
                            launchSkyShot(canvas.width * 0.15 + (i * 0.11 * canvas.width), canvas.height * 0.12 + (Math.random() * 0.3 * canvas.height));
                        }, i * 85);
                    }

                    // Button visual feedback
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span>CELEBRATING & LOGGING IN... 🎆</span>';
                        submitBtn.style.transform = 'scale(0.98)';
                        submitBtn.style.opacity = '0.9';
                    }

                    // Submit form after cracker blast burst
                    setTimeout(function() {
                        loginForm.submit();
                    }, 750);
                });
            }

            // Click Anywhere to Blast Sky Shot
            document.addEventListener('click', function (e) {
                if (!e.target.closest('input, button, a')) {
                    createExplosion(e.clientX, e.clientY);
                }
            });
        });
    </script>
    @include('crackers.partials.celebration_blast')
</body>
</html>
