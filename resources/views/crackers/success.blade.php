<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed #{{ $order->order_number }} | Crackers.com</title>

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
        }
        .navbar-festive {
            background: #ffffff;
            border-bottom: 1px solid rgba(255, 183, 3, 0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
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
        .step-progress {
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
        }
        .step-item.done {
            color: #22c55e;
        }
        .step-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #22c55e;
            color: #fff;
            font-weight: 700;
        }
        .card-custom {
            background: var(--bg-card);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 20px;
            padding: 2rem;
        }
        .upload-proof-box {
            background: #fff8f0;
            border: 2px dashed #fb8500;
            border-radius: 16px;
            padding: 1.5rem;
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
                    <i class="ri-store-2-line me-1"></i> Continue Shopping
                </a>
            </div>
        </div>
    </nav>

    <!-- Content Container -->
    <div class="container pb-5">

        <!-- eCommerce Step Progress Indicator -->
        <div class="step-progress">
            <div class="row text-center align-items-center">
                <div class="col-3">
                    <div class="step-item done justify-content-center">
                        <div class="step-number"><i class="ri-check-line"></i></div>
                        <span class="d-none d-md-inline">1. Cart Items</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="step-item done justify-content-center">
                        <div class="step-number"><i class="ri-check-line"></i></div>
                        <span class="d-none d-md-inline">2. Shipping Info</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="step-item done justify-content-center">
                        <div class="step-number"><i class="ri-check-line"></i></div>
                        <span class="d-none d-md-inline">3. Payment</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="step-item done justify-content-center">
                        <div class="step-number"><i class="ri-check-line"></i></div>
                        <span class="d-none d-md-inline">4. Confirmation</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-custom text-center mb-4">
                    <div class="text-success display-3 mb-2"><i class="ri-checkbox-circle-fill"></i></div>
                    <h2 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Thank You! Order Confirmed.</h2>
                    <p class="text-muted mb-3">Your cracker order <strong>#{{ $order->order_number }}</strong> has been placed successfully.</p>
                    
                    <div class="mb-4">
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill">
                            <i class="ri-truck-fill me-1"></i> Estimated Doorstep Delivery: <strong>5 - 7 Days</strong>
                        </span>
                    </div>

                    <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
                        <a href="{{ route('crackers.order-invoice', $order->order_number) }}" target="_blank" class="btn btn-warning rounded-pill px-4 fw-bold" style="background: var(--gold-gradient); color:#000;">
                            <i class="ri-file-pdf-line me-1"></i> Download Invoice PDF
                        </a>
                        @auth
                            <a href="{{ route('crackers.my-orders') }}" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                                <i class="ri-shopping-bag-3-line me-1"></i> My Order History
                            </a>
                        @endauth
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <i class="ri-checkbox-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Payment Screenshot / Proof Upload Section -->
                <div class="upload-proof-box mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-1"><i class="ri-bank-card-fill text-warning me-2"></i> Bank Transfer / Payment Proof Screenshot</h5>
                            <p class="text-muted small mb-0">If paid via UPI or Bank Transfer, please upload your payment screenshot/receipt image.</p>
                        </div>
                        <div>
                            @if($order->payment_proof)
                                <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                                    <i class="ri-checkbox-circle-fill me-1"></i> Payment Screenshot Uploaded
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">
                                    <i class="ri-error-warning-line me-1"></i> Proof Pending Upload
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($order->payment_proof)
                        <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset($order->payment_proof) }}" alt="Payment Proof" style="width: 60px; height: 60px; object-fit: cover;" class="rounded border">
                                <div>
                                    <div class="fw-bold text-success"><i class="ri-check-double-line me-1"></i> Screenshot Attached</div>
                                    <small class="text-muted">Uploaded for admin verification</small>
                                </div>
                            </div>
                            <a href="{{ asset($order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="ri-eye-line me-1"></i> View Screenshot
                            </a>
                        </div>
                    @else
                        <form action="{{ route('crackers.upload-payment-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 rounded-3 border">
                            @csrf
                            <div class="row align-items-center g-2">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold small text-muted mb-1">UPLOAD SCREENSHOT / RECIEPT (JPG, PNG, WEBP)</label>
                                    <input type="file" name="payment_proof" class="form-control rounded-3" accept="image/*" required>
                                </div>
                                <div class="col-md-4 pt-md-4">
                                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold" style="background: var(--gold-gradient); color:#000;">
                                        <i class="ri-upload-cloud-line me-1"></i> Upload Screenshot
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Invoice Breakdown Card -->
                <div class="card-custom">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Order Items Breakdown</h5>
                            <small class="text-muted">Order Number: #{{ $order->order_number }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success px-3 py-2 rounded-pill">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <small class="text-muted fw-bold d-block">DELIVERY ADDRESS:</small>
                            <div class="fw-bold">{{ $order->customer_name }}</div>
                            <div>{{ $order->delivery_address }}</div>
                            <div>{{ $order->city }} {{ $order->pincode }}</div>
                            <div>Phone: {{ $order->customer_phone }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted fw-bold d-block">PAYMENT INFORMATION:</small>
                            <div class="fw-bold text-info">{{ str_replace('_', ' ', $order->payment_method) }}</div>
                            <div class="small text-muted">Payment Status: <span class="badge bg-warning text-dark">{{ ucfirst($order->payment_status) }}</span></div>
                            <div class="small text-muted">Placed Date: {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : date('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table align-middle">
                            <thead>
                                <tr class="border-bottom">
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td><span class="fw-bold">{{ $item->product_name }}</span></td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between text-muted mb-1">
                                <span>Subtotal:</span>
                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted mb-2">
                                <span>GST ({{ $order->gst_rate }}%):</span>
                                <span>₹{{ number_format($order->gst_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 text-success border-top pt-2">
                                <span>Grand Total:</span>
                                <span>₹{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
