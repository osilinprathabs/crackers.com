<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->company_name ?: 'S.R. TRADERS' }} - {{ strtoupper($mode) }} Crackers Price List & Catalog (PDF)</title>

    <!-- Google Fonts & Remix Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 14px;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .catalog-container {
            max-width: 1000px;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 16px;
            padding: 40px;
        }

        .catalog-header {
            border-bottom: 3px solid {{ $mode === 'wholesale' ? '#f59e0b' : '#2563eb' }};
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .mode-badge {
            font-size: 1rem;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mode-wholesale {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #000000;
        }

        .mode-retail {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
        }

        .category-header {
            background: {{ $mode === 'wholesale' ? '#fffbe6' : '#eff6ff' }};
            border-left: 5px solid {{ $mode === 'wholesale' ? '#f59e0b' : '#2563eb' }};
            padding: 10px 16px;
            border-radius: 6px;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .product-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .product-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f59e0b;
        }

        .top-action-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        @media print {
            .top-action-bar {
                display: none !important;
            }
            body {
                background: #ffffff;
            }
            .catalog-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .category-section {
                page-break-inside: avoid;
            }
            a {
                text-decoration: none !important;
                color: inherit !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Screen Action Bar -->
    <div class="top-action-bar mb-4">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-white fw-bold me-2"><i class="ri-file-pdf-line text-warning fs-4 align-middle"></i> Catalog Mode:</span>
                <a href="{{ route('crackers.price-list', 'retail') }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $mode === 'retail' ? 'btn-primary' : 'btn-outline-light' }}">
                    <i class="ri-user-heart-line me-1"></i> Retail Catalog
                </a>
                <a href="{{ route('crackers.price-list', 'wholesale') }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ $mode === 'wholesale' ? 'btn-warning text-dark' : 'btn-outline-light' }}">
                    <i class="ri-store-3-line me-1"></i> Wholesale (B2B Bulk)
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button onclick="window.print()" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">
                    <i class="ri-printer-line me-1"></i> Print / Save as PDF
                </button>
                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="ri-store-2-line me-1"></i> Back to Store
                </a>
            </div>
        </div>
    </div>

    <!-- Printable PDF Document Body -->
    <div class="catalog-container">
        <!-- Header Section -->
        <div class="catalog-header">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h2 class="fw-bold mb-1 text-dark brand-font">{{ $settings->company_name ?: 'S.R. TRADERS' }}</h2>
                            <p class="text-muted mb-1 fw-medium">{{ $settings->company_slogan ?: 'Premium Festive Crackers & Fireworks Whole & Retail Supplier' }}</p>
                            <small class="text-primary font-monospace d-block fw-semibold"><i class="ri-shield-check-line me-1"></i> GSTIN: {{ $settings->gst_number }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 text-md-end">
                    <span class="mode-badge {{ $mode === 'wholesale' ? 'mode-wholesale' : 'mode-retail' }} d-inline-block mb-2">
                        <i class="{{ $mode === 'wholesale' ? 'ri-store-3-line' : 'ri-shopping-bag-3-line' }} me-1"></i>
                        {{ $mode === 'wholesale' ? 'WHOLESALE B2B PRICE CATALOG' : 'RETAIL CRACKERS CATALOG' }}
                    </span>
                    <div class="small text-muted font-monospace">Date: {{ date('d M Y') }} | GSTIN: {{ $settings->gst_number }} | GST: {{ $settings->gst_percentage ?: 0 }}%</div>
                    <div class="small text-dark font-monospace fw-bold"><i class="ri-phone-line text-success me-1"></i> {{ $settings->support_phone ?: 'Support Contact' }}</div>
                </div>
            </div>
        </div>

        <!-- Info / Notice Bar -->
        <div class="alert {{ $mode === 'wholesale' ? 'alert-warning border-warning' : 'alert-info border-info' }} rounded-3 mb-4 py-2 px-3 small">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span>
                    <i class="ri-information-line me-1 fw-bold"></i>
                    @if($mode === 'wholesale')
                        <strong>B2B Wholesale Rates:</strong> Prices listed below are special wholesale rates. Bulk order minimum limit: <strong>₹{{ number_format($settings->min_wholesale_order_amount ?: 5000, 2) }}</strong>.
                    @else
                        <strong>Retail Store Prices:</strong> Prices listed below include special retail discounts. Minimum order limit: <strong>₹{{ number_format($settings->min_retail_order_amount ?: 1000, 2) }}</strong>.
                    @endif
                </span>
                <span class="font-monospace text-muted">Total Categories: {{ count($productsGrouped) }}</span>
            </div>
        </div>

        <!-- Category-Wise Product List Table -->
        @forelse($productsGrouped as $categoryName => $productList)
            <div class="category-section">
                <div class="category-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                        <i class="ri-sparkles-line text-warning me-2"></i> {{ $categoryName }}
                    </h5>
                    <span class="badge bg-white text-dark border px-3 py-1 rounded-pill font-monospace fw-semibold">
                        {{ count($productList) }} Items
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th style="width: 60px;">IMAGE</th>
                                <th style="width: 90px;">CODE</th>
                                <th>CRACKER ITEM & SPECIFICATIONS</th>
                                <th>PACKING / UNIT</th>
                                <th class="text-end">MRP (₹)</th>
                                @if($mode === 'wholesale')
                                    <th class="text-end" style="width: 140px;">WHOLESALE RATE</th>
                                    <th class="text-center" style="width: 110px;">MIN BULK QTY</th>
                                @else
                                    <th class="text-end" style="width: 140px;">RETAIL OFFER PRICE</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productList as $prod)
                                @php
                                    $unitPrice = $prod->discount_price ?: $prod->price;
                                    $wholesalePrice = $prod->wholesale_price ?: $unitPrice;
                                    $wholesaleMin = $prod->wholesale_min_qty ?: 1;
                                @endphp
                                <tr>
                                    <td>
                                        @if($prod->image)
                                            <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" class="product-img">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="ri-sparkles-line fs-5"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="text-primary font-monospace fw-bold">{{ $prod->code ?: 'CRK-'.$prod->id }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-0">{{ $prod->name }}</div>
                                        @if($prod->description)
                                            <small class="text-muted d-block text-truncate" style="max-width: 320px;">{{ $prod->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $prod->unit ?: 'Box' }}</span>
                                    </td>
                                    <td class="text-end text-muted text-decoration-line-through">
                                        ₹{{ number_format($prod->price, 2) }}
                                    </td>

                                    @if($mode === 'wholesale')
                                        <td class="text-end">
                                            <strong class="text-dark fs-6 font-monospace">₹{{ number_format($wholesalePrice, 2) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark font-monospace fw-bold">{{ $wholesaleMin }} {{ $prod->unit ?: 'Pcs' }}</span>
                                        </td>
                                    @else
                                        <td class="text-end">
                                            <strong class="text-success fs-6 font-monospace">₹{{ number_format($unitPrice, 2) }}</strong>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="ri-inbox-line display-3 d-block mb-2 opacity-50"></i>
                <p>No products available in price catalog at this time.</p>
            </div>
        @endforelse

        <!-- Footer / Ordering Info -->
        <div class="mt-5 pt-4 border-top text-center text-muted small">
            <div class="row g-3 text-start mb-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-2"><i class="ri-customer-service-2-line text-primary me-1"></i> How to Place Order?</h6>
                    <p class="mb-1">1. Visit store website: <strong>{{ request()->getSchemeAndHttpHost() }}</strong></p>
                    <p class="mb-1">2. Or call / WhatsApp us directly with your order item list on <strong>{{ $settings->support_phone ?: 'Store Phone' }}</strong>.</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-2"><i class="ri-shield-star-line text-warning me-1"></i> Safety & Terms Notice</h6>
                    <p class="mb-1">• Quality checked & 100% green fireworks complying with safety standards.</p>
                    <p class="mb-1">• Safe transport & door delivery / counter pickup available across India.</p>
                </div>
            </div>
            <div class="pt-2 border-top">
                <p class="mb-0 font-monospace">© {{ date('Y') }} {{ $settings->company_name ?: 'S.R. TRADERS' }}. All rights reserved.</p>
            </div>
        </div>
    </div>

</body>
</html>
