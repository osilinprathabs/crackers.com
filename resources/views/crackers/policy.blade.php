<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Crackers.com</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0f19;
            --bg-card: rgba(23, 31, 50, 0.85);
            --gold-gradient: linear-gradient(135deg, #ffb703 0%, #fb8500 50%, #ff4800 100%);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }
        [data-theme="light"] {
            --bg-dark: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
        }
        .navbar-festive {
            background: rgba(11, 15, 25, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 183, 3, 0.2);
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
        .card-custom {
            background: var(--bg-card);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 20px;
            padding: 2.5rem;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-festive mb-4">
        <div class="container">
            <a class="navbar-brand brand-logo text-decoration-none" href="{{ route('crackers.storefront') }}">
                <i class="ri-fire-fill text-warning"></i> Crackers.com
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-warning rounded-pill px-3 btn-sm">
                    <i class="ri-store-2-line me-1"></i> Back To Storefront
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-custom">
                    <h2 class="fw-bold text-warning mb-4" style="font-family: 'Outfit', sans-serif;"><i class="ri-file-shield-2-line me-2"></i> {{ $title }}</h2>
                    
                    <div class="content opacity-90 leading-relaxed" style="white-space: pre-line; line-height: 1.8;">
                        {!! nl2br(e($content)) !!}
                    </div>

                    <div class="border-top border-secondary pt-4 mt-5 d-flex justify-content-between align-items-center">
                        <a href="{{ route('crackers.storefront') }}" class="text-warning text-decoration-none fw-bold">
                            <i class="ri-arrow-left-line me-1"></i> Return To Shop
                        </a>
                        <small class="text-muted">Crackers.com &copy; {{ date('Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
