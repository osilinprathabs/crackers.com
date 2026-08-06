<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Login | Crackers.com</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-gradient: radial-gradient(circle at 50% 20%, #fff7ed 0%, #ffedd5 45%, #fed7aa 100%);
            --gold-gradient: linear-gradient(135deg, #ffb703 0%, #fb8500 50%, #ff4800 100%);
            --card-shadow: 0 20px 50px rgba(251, 133, 0, 0.12);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative background glow accents */
        .bg-glow-1 {
            position: absolute;
            top: -100px;
            left: -100px;
            width: 350px;
            height: 350px;
            background: rgba(255, 183, 3, 0.3);
            filter: blur(90px);
            border-radius: 50%;
            z-index: 0;
        }

        .bg-glow-2 {
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 350px;
            height: 350px;
            background: rgba(255, 72, 0, 0.25);
            filter: blur(90px);
            border-radius: 50%;
            z-index: 0;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid rgba(251, 133, 0, 0.28);
            border-radius: 28px;
            max-width: 480px;
            width: 100%;
            padding: 2.75rem 2.25rem;
            box-shadow: var(--card-shadow);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }

        .brand-logo {
            font-size: 2.2rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.5px;
        }

        .form-label-title {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #475569;
            text-transform: uppercase;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #fb8500;
            font-size: 1.25rem;
            padding-left: 1.15rem;
            padding-right: 1.15rem;
        }

        .form-control {
            border-color: #cbd5e1;
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: #fb8500;
            box-shadow: 0 0 0 0.25rem rgba(251, 133, 0, 0.18);
        }

        .btn-festive-submit {
            background: var(--gold-gradient);
            color: #000000;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 50px;
            padding: 0.85rem 1.5rem;
            width: 100%;
            box-shadow: 0 8px 25px rgba(251, 133, 0, 0.35);
            transition: all 0.3s ease;
        }

        .btn-festive-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(251, 133, 0, 0.48);
            color: #000000;
        }

        .password-toggle-btn {
            border-color: #cbd5e1;
            background-color: #f8fafc;
            color: #64748b;
            cursor: pointer;
        }

        .password-toggle-btn:hover {
            color: #fb8500;
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="auth-card">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <a href="{{ route('crackers.storefront') }}" class="brand-logo text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                <i class="ri-fire-fill text-warning"></i> Crackers.com
            </a>
            <h3 class="fw-bold text-dark text-uppercase mb-1" style="font-family: 'Outfit', sans-serif;">Customer Login</h3>
            <p class="text-muted small mb-0">Enter your Mobile Number / Email and Password</p>
        </div>

        <!-- Alert Notifications -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 py-2 px-3 small mb-4" role="alert" style="background: #fef2f2; border-left: 4px solid #ef4444;">
                <div class="d-flex align-items-center">
                    <i class="ri-error-warning-fill text-danger fs-5 me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 py-2 px-3 small mb-4" role="alert" style="background: #f0fdf4; border-left: 4px solid #22c55e;">
                <div class="d-flex align-items-center">
                    <i class="ri-checkbox-circle-fill text-success fs-5 me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('crackers.login') }}" method="POST">
            @csrf

            <!-- Mobile / Email Input -->
            <div class="mb-3">
                <label class="form-label form-label-title">MOBILE NUMBER OR EMAIL <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="ri-smartphone-line"></i></span>
                    <input type="text" name="login" class="form-control rounded-end-3 fs-6" required placeholder="e.g. 9876543210 or name@gmail.com" value="{{ old('login') }}">
                </div>
            </div>

            <!-- Password Input with Toggle -->
            <div class="mb-3">
                <label class="form-label form-label-title">PASSWORD <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                    <input type="password" name="password" id="loginPassword" class="form-control fs-6" required placeholder="Enter password (default is mobile number)">
                    <button class="btn password-toggle-btn rounded-end-3" type="button" id="togglePasswordBtn" title="Show/Hide Password">
                        <i class="ri-eye-line" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Info -->
            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked style="border-color: #cbd5e1;">
                    <label class="form-check-label text-muted fw-semibold" for="rememberMe">Keep Me Logged In</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-festive-submit mb-4">
                LOG IN TO STORE <i class="ri-arrow-right-line ms-1"></i>
            </button>
        </form>

        <!-- Switch to Register -->
        <div class="text-center pt-3 border-top">
            <span class="text-muted small">New to Crackers.com?</span>
            <a href="{{ route('crackers.register-page') }}" class="text-warning fw-bold small text-decoration-none ms-1" style="color: #fb8500 !important;">
                CREATE NEW ACCOUNT <i class="ri-user-add-line ms-1"></i>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
</body>
</html>
