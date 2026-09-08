<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Registration | {{ $settings->company_name ?: 'S.R. TRADERS' }}</title>

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        [data-theme="light"] {
            --bg-main: radial-gradient(circle at 50% 15%, #fffdf5 0%, #fef3c7 40%, #ffedd5 75%, #fed7aa 100%);
            --card-bg: rgba(255, 255, 255, 0.92);
            --card-border: rgba(245, 158, 11, 0.35);
            --card-shadow: 0 25px 50px -12px rgba(217, 119, 6, 0.2), 0 0 35px rgba(251, 146, 60, 0.25);
            --promo-bg: linear-gradient(155deg, #7c2d12 0%, #9a3412 45%, #c2410c 100%);
            --text-title: #0f172a;
            --text-body: #475569;
            --text-muted: #64748b;
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --gold-gradient: linear-gradient(135deg, #d97706 0%, #ea580c 50%, #dc2626 100%);
            --btn-text: #ffffff;
            --btn-shadow: 0 10px 25px rgba(234, 88, 12, 0.35);
            --switch-bg: rgba(255, 255, 255, 0.7);
            --switch-border: rgba(217, 119, 6, 0.25);
        }

        [data-theme="dark"] {
            --bg-main: #07090e;
            --card-bg: rgba(15, 20, 32, 0.88);
            --card-border: rgba(255, 183, 3, 0.28);
            --card-shadow: 0 30px 60px rgba(0, 0, 0, 0.7), 0 0 35px rgba(255, 140, 0, 0.25);
            --promo-bg: linear-gradient(160deg, rgba(26, 32, 53, 0.98) 0%, rgba(13, 17, 28, 0.98) 100%);
            --text-title: #ffffff;
            --text-body: #94a3b8;
            --text-muted: #64748b;
            --input-bg: rgba(24, 32, 50, 0.75);
            --input-border: rgba(255, 255, 255, 0.14);
            --input-text: #ffffff;
            --gold-gradient: linear-gradient(135deg, #ffc107 0%, #ff8c00 50%, #ff3b00 100%);
            --btn-text: #000000;
            --btn-shadow: 0 10px 30px rgba(255, 140, 0, 0.4);
            --switch-bg: rgba(255, 255, 255, 0.05);
            --switch-border: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-main);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            position: relative;
            overflow-x: hidden;
            transition: background 0.4s ease;
        }

        #fireworksCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 1;
        }

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
            width: 450px;
            height: 450px;
            background: rgba(245, 158, 11, 0.22);
            animation: floatGlow 8s infinite alternate ease-in-out;
        }

        .glow-2 {
            bottom: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: rgba(239, 68, 68, 0.18);
            animation: floatGlow 10s infinite alternate-reverse ease-in-out;
        }

        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.1); }
        }

        .auth-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1050px;
            margin: 0 auto;
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .theme-switcher-bar {
            position: absolute;
            top: 15px;
            right: 20px;
            z-index: 10;
        }

        .btn-theme-toggle {
            background: var(--switch-bg);
            border: 1px solid var(--switch-border);
            color: var(--text-title);
            padding: 0.45rem 0.9rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(10px);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .promo-side {
            background: var(--promo-bg);
            border-right: 1px solid var(--card-border);
            padding: 3.5rem 3rem;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            color: #ffffff;
        }

        .brand-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #ffffff;
        }

        .brand-logo-badge i {
            font-size: 1.6rem;
            color: #fbbf24;
        }

        .promo-heading {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .promo-heading span {
            color: #fef08a;
            text-shadow: 0 0 20px rgba(254, 240, 138, 0.4);
        }

        .benefit-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .benefit-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(254, 240, 138, 0.2);
            color: #fef08a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .skyshot-trigger-btn {
            background: rgba(254, 240, 138, 0.18);
            border: 1px dashed rgba(254, 240, 138, 0.5);
            color: #fef08a;
            border-radius: 12px;
            padding: 0.65rem 1rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .skyshot-trigger-btn:hover {
            background: rgba(254, 240, 138, 0.35);
            transform: scale(1.03);
            color: #ffffff;
        }

        .form-side {
            padding: 3.5rem 3rem;
        }

        @media (max-width: 991.98px) {
            .form-side {
                padding: 2.5rem 1.75rem;
            }
        }

        .back-home-btn {
            color: var(--text-body);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
            margin-bottom: 1.75rem;
        }

        .back-home-btn:hover {
            color: #ea580c;
        }

        .auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-title);
            margin-bottom: 0.35rem;
        }

        .auth-subtitle {
            color: var(--text-body);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            color: var(--text-body);
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            display: block;
        }

        .input-group-custom {
            position: relative;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 14px;
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .input-group-custom:focus-within {
            border-color: #ea580c;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.15);
        }

        .input-icon-box {
            padding: 0.75rem 0.25rem 0.75rem 1.15rem;
            color: #ea580c;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control-custom {
            background: transparent !important;
            border: none !important;
            color: var(--input-text) !important;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.75rem 1rem 0.75rem 0.65rem;
            width: 100%;
            box-shadow: none !important;
        }

        .form-control-custom::placeholder {
            color: var(--text-muted);
        }

        .btn-festive-glow {
            background: var(--gold-gradient);
            color: var(--btn-text);
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.08rem;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 50px;
            padding: 0.9rem 2rem;
            width: 100%;
            box-shadow: var(--btn-shadow);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-festive-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(234, 88, 12, 0.5);
            color: var(--btn-text);
        }

        .switch-auth-box {
            background: var(--switch-bg);
            border: 1px solid var(--switch-border);
            border-radius: 16px;
            padding: 1rem 1.25rem;
            text-align: center;
        }

        .switch-link {
            color: #ea580c;
            font-weight: 700;
            text-decoration: none;
        }

        .alert-festive-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #b91c1c;
            border-radius: 14px;
        }
    </style>
</head>
<body>

    <!-- Canvas for Fireworks Sky Shot Animations -->
    <canvas id="fireworksCanvas"></canvas>

    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="auth-wrapper">
        <!-- Floating Theme Switcher -->
        <div class="theme-switcher-bar">
            <button type="button" class="btn-theme-toggle" id="themeToggleBtn" title="Toggle Light / Dark Theme">
                <i class="ri-sun-line" id="themeIcon"></i>
                <span id="themeLabel">Light Mode</span>
            </button>
        </div>

        <div class="glass-card">
            <div class="row g-0">

                <!-- Left Column: Branding -->
                <div class="col-lg-5 d-none d-lg-flex promo-side">
                    <div>
                        <a href="{{ route('crackers.storefront') }}" class="brand-logo-badge text-decoration-none">
                            <i class="ri-fire-fill"></i>
                            <span>{{ $settings->company_name ?: 'S.R. TRADERS' }}</span>
                        </a>

                        <h2 class="promo-heading">
                            Create Account for <span>Exclusive Benefits</span>
                        </h2>
                        <p class="mb-4 opacity-90" style="font-size: 0.95rem; line-height: 1.6;">
                            Join thousands of happy customers buying factory-direct Sivakasi crackers with instant trackable shipping.
                        </p>

                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="ri-history-line"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Order History & Tracking</h6>
                                <span class="small opacity-80">Easily reorder festive packs & track dispatch</span>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="ri-price-tag-3-line"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Saved Delivery Addresses</h6>
                                <span class="small opacity-80">1-click checkout for fast seasonal shopping</span>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="ri-shield-user-line"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Instant Mobile Password Access</h6>
                                <span class="small opacity-80">Use your phone number as default initial password</span>
                            </div>
                        </div>

                        <button type="button" class="skyshot-trigger-btn" id="launchSkyShotBtn">
                            <i class="ri-sparkling-fill fs-5"></i>
                            <span>Launch Sky Shot Cracker Blast! 🎆</span>
                        </button>
                    </div>

                    <div class="pt-3 border-top border-white border-opacity-25 d-flex align-items-center gap-2 text-warning small fw-semibold">
                        <i class="ri-shield-check-fill text-warning fs-5"></i>
                        <span>100% Certified Sivakasi Green Crackers</span>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="col-lg-7 form-side">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('crackers.storefront') }}" class="back-home-btn">
                            <i class="ri-arrow-left-line"></i> Back to Store
                        </a>
                        <div class="d-lg-none">
                            <a href="{{ route('crackers.storefront') }}" class="fw-bold text-danger text-decoration-none" style="font-family: 'Outfit', sans-serif;">
                                <i class="ri-fire-fill me-1 text-warning"></i> {{ $settings->company_name ?: 'S.R. TRADERS' }}
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h1 class="auth-title">Create Account</h1>
                        <p class="auth-subtitle">Fill in your details for quick checkout & delivery management</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-festive-danger p-3 mb-4">
                            <div class="fw-bold mb-1"><i class="ri-error-warning-fill me-1"></i> Please check the form errors:</div>
                            <ul class="mb-0 ps-3 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('crackers.register') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <div class="input-icon-box"><i class="ri-user-3-line"></i></div>
                                    <input type="text" name="name" class="form-control-custom" required placeholder="e.g. Rahul Sharma" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <div class="input-icon-box"><i class="ri-phone-line"></i></div>
                                    <input type="text" name="phone" class="form-control-custom" required placeholder="10-digit mobile" value="{{ old('phone') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Email Address <span class="text-secondary font-monospace">(Optional)</span></label>
                            <div class="input-group-custom">
                                <div class="input-icon-box"><i class="ri-mail-line"></i></div>
                                <input type="email" name="email" class="form-control-custom" placeholder="e.g. rahul@gmail.com" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Delivery Address <span class="text-secondary font-monospace">(Optional)</span></label>
                            <div class="input-group-custom">
                                <div class="input-icon-box"><i class="ri-map-pin-line"></i></div>
                                <input type="text" name="address" class="form-control-custom" placeholder="Street Address / House No." value="{{ old('address') }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">City / Town</label>
                                <div class="input-group-custom">
                                    <div class="input-icon-box"><i class="ri-building-4-line"></i></div>
                                    <input type="text" name="city" class="form-control-custom" placeholder="e.g. Sivakasi" value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Pincode</label>
                                <div class="input-group-custom">
                                    <div class="input-icon-box"><i class="ri-map-pin-user-line"></i></div>
                                    <input type="text" name="pincode" class="form-control-custom" placeholder="e.g. 626123" value="{{ old('pincode') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Set Custom Password <span class="text-secondary font-monospace">(Optional)</span></label>
                            <div class="input-group-custom">
                                <div class="input-icon-box"><i class="ri-lock-2-line"></i></div>
                                <input type="password" name="password" class="form-control-custom" placeholder="Leave empty to use Mobile Number as password">
                            </div>
                            <div class="small mt-1 opacity-75">
                                <i class="ri-information-line me-1"></i> If left empty, your 10-digit mobile number will be set as your initial password.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-festive-glow mb-4" id="submitRegisterBtn">
                            <span>REGISTER & LOG IN</span>
                            <i class="ri-arrow-right-line ms-2 fs-5 align-middle"></i>
                        </button>
                    </form>

                    <div class="switch-auth-box">
                        <span class="small">Already have an account?</span>
                        <a href="{{ route('crackers.login-page') }}" class="switch-link small ms-1">
                            LOG IN HERE <i class="ri-login-box-line ms-1 align-middle"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fireworks Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // Canvas Fireworks
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
                    this.trail.push({ x: this.x, y: this.y });
                    if (this.trail.length > 8) this.trail.shift();

                    this.x += this.vx;
                    this.y += this.vy;

                    const dist = Math.hypot(this.targetX - this.x, this.targetY - this.y);
                    if (dist < 15 || this.vy >= 0 || this.y <= this.targetY) {
                        this.exploded = true;
                        createExplosion(this.x, this.y, this.color);
                    }
                }

                draw() {
                    for (let i = 0; i < this.trail.length; i++) {
                        const pt = this.trail[i];
                        ctx.beginPath();
                        ctx.arc(pt.x, pt.y, (i + 1) * 0.6, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(255, 200, 50, ${i / this.trail.length})`;
                        ctx.fill();
                    }
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

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                for (let i = rockets.length - 1; i >= 0; i--) {
                    rockets[i].update();
                    rockets[i].draw();
                    if (rockets[i].exploded) rockets.splice(i, 1);
                }

                for (let i = particles.length - 1; i >= 0; i--) {
                    particles[i].update();
                    particles[i].draw();
                    if (particles[i].alpha <= 0) particles.splice(i, 1);
                }

                requestAnimationFrame(animate);
            }
            animate();

            setInterval(() => launchSkyShot(), 1800);

            const launchBtn = document.getElementById('launchSkyShotBtn');
            if (launchBtn) {
                launchBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    launchSkyShot();
                    setTimeout(() => launchSkyShot(), 250);
                    setTimeout(() => launchSkyShot(), 500);
                });
            }

            document.addEventListener('click', function (e) {
                if (!e.target.closest('input, button, a')) {
                    createExplosion(e.clientX, e.clientY);
                }
            });
        });
    </script>
</body>
</html>
