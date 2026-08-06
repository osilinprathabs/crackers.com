<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Register | Crackers.com</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --gold-gradient: linear-gradient(135deg, #ffb703 0%, #fb8500 50%, #ff4800 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid rgba(251, 133, 0, 0.25);
            border-radius: 24px;
            max-width: 560px;
            width: 100%;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        .brand-logo {
            font-size: 2.25rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Outfit', sans-serif;
        }

        .btn-festive {
            background: var(--gold-gradient);
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-festive:hover {
            box-shadow: 0 6px 20px rgba(251, 133, 0, 0.3);
            transform: translateY(-1px);
            color: #000;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Logo & Title -->
        <div class="text-center mb-4">
            <a href="{{ route('crackers.storefront') }}" class="brand-logo text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                <i class="ri-fire-fill text-warning"></i> Crackers.com
            </a>
            <h3 class="fw-bold text-dark text-uppercase mb-1" style="font-family: 'Outfit', sans-serif;">Create Customer Account</h3>
            <p class="text-muted small mb-0">Register for easy ordering & saved delivery addresses</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('crackers.register') }}" method="POST">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">FULL NAME *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-warning border-end-0"><i class="ri-user-line"></i></span>
                        <input type="text" name="name" class="form-control border-start-0" required placeholder="e.g. Rahul Sharma" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">MOBILE NUMBER *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-warning border-end-0"><i class="ri-phone-line"></i></span>
                        <input type="text" name="phone" class="form-control border-start-0" required placeholder="10-digit mobile" value="{{ old('phone') }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted small">EMAIL ADDRESS (OPTIONAL)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-warning border-end-0"><i class="ri-mail-line"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="e.g. rahul@gmail.com" value="{{ old('email') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted small">DELIVERY ADDRESS</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-warning border-end-0"><i class="ri-map-pin-line"></i></span>
                    <input type="text" name="address" class="form-control border-start-0" placeholder="Street Address / House No." value="{{ old('address') }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">CITY / TOWN</label>
                    <input type="text" name="city" class="form-control" placeholder="e.g. Sivakasi" value="{{ old('city') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">PINCODE</label>
                    <input type="text" name="pincode" class="form-control" placeholder="e.g. 626123" value="{{ old('pincode') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-muted small">PASSWORD (OPTIONAL)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-warning border-end-0"><i class="ri-lock-2-line"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="Leave empty to set Mobile Number as Password">
                </div>
                <small class="text-muted">If empty, your mobile number will be set as your initial login password.</small>
            </div>

            <button type="submit" class="btn btn-festive mb-3">
                REGISTER & LOG IN <i class="ri-user-add-line ms-1"></i>
            </button>
        </form>

        <div class="text-center pt-3 border-top">
            <span class="text-muted small">Already have an account?</span>
            <a href="{{ route('crackers.login-page') }}" class="text-warning fw-bold small text-decoration-none ms-1">LOG IN HERE</a>
        </div>
    </div>

</body>
</html>
