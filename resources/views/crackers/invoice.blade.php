<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} | Crackers.com</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            padding-top: 2rem;
            padding-bottom: 3rem;
        }
        .invoice-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 3rem;
            max-width: 860px;
            margin: 0 auto;
        }
        .brand-header {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: #fb8500;
        }
        .invoice-badge {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #ffedd5;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
        }
        .table-invoice th {
            background-color: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        .bank-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            padding: 1.25rem;
        }
        .qr-code-img {
            width: 130px;
            height: 130px;
            border-radius: 12px;
            border: 2px solid #fb8500;
            padding: 4px;
            background: #fff;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hidden on Print) -->
    <div class="container no-print mb-4" style="max-width: 860px;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 border shadow-sm">
            <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="ri-arrow-left-line me-1"></i> Return to Store
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-warning rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #ffb703 0%, #fb8500 100%); color:#000;">
                    <i class="ri-printer-line me-1"></i> Print / Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Invoice Card Container -->
    <div class="invoice-card">

        <!-- Top Header Row -->
        <div class="row align-items-center border-bottom pb-4 mb-4">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="brand-header d-flex align-items-center gap-2">
                    <i class="ri-fire-fill text-warning"></i> Crackers.com
                </div>
                <div class="small text-muted mt-1">
                    {{ $settings->website_name ?? 'Crackers Store' }}<br>
                    Contact: {{ $settings->support_phone ?? '+91 9876543210' }} | {{ $settings->support_email ?? 'support@crackers.com' }}<br>
                    @if($settings->gst_percentage)
                        GSTIN: <strong>{{ $settings->gst_number ?? '33AAAAA0000A1Z5' }}</strong>
                    @endif
                </div>
            </div>
            <div class="col-sm-6 text-sm-end">
                <span class="invoice-badge text-uppercase">OFFICIAL INVOICE</span>
                <h3 class="fw-bold mt-2 mb-0">#{{ $order->order_number }}</h3>
                <div class="small text-muted">Date: {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : date('d M Y') }}</div>
            </div>
        </div>

        <!-- Customer & Order Meta Information -->
        <div class="row g-4 mb-4">
            <div class="col-6">
                <div class="text-uppercase small fw-bold text-muted mb-1">Billed To / Delivery Address:</div>
                <h6 class="fw-bold mb-1">{{ $order->customer_name }}</h6>
                <div class="small text-muted">
                    <i class="ri-phone-line me-1"></i>{{ $order->customer_phone }}<br>
                    @if($order->customer_email)
                        <i class="ri-mail-line me-1"></i>{{ $order->customer_email }}<br>
                    @endif
                    <i class="ri-map-pin-line me-1"></i>{{ $order->delivery_address }}<br>
                    {{ $order->city }} @if($order->pincode) - {{ $order->pincode }} @endif
                </div>
                <div class="mt-2">
                    <span class="badge bg-light text-dark border"><i class="ri-truck-line text-warning me-1"></i> Estimated Delivery: <strong>5 - 7 Days</strong></span>
                </div>
            </div>

            <div class="col-6 text-end">
                <div class="text-uppercase small fw-bold text-muted mb-1">Payment Status & Method:</div>
                <div class="mb-2">
                    @if($order->payment_status === 'paid')
                        <span class="badge bg-success px-3 py-2 rounded-pill"><i class="ri-checkbox-circle-fill me-1"></i> PAID</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="ri-time-line me-1"></i> PENDING PAYMENT</span>
                    @endif
                </div>
                <div class="small text-muted">
                    Payment Method: <strong>{{ str_replace('_', ' ', $order->payment_method) }}</strong><br>
                    @if($order->payment_proof)
                        <span class="text-success fw-bold"><i class="ri-file-shield-2-line me-1"></i> Payment Proof Verified</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-invoice align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Product Description</th>
                        <th class="text-end" style="width: 120px;">Unit Price</th>
                        <th class="text-center" style="width: 90px;">Qty</th>
                        <th class="text-end" style="width: 140px;">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td class="text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark">{{ $item->product_name }}</strong>
                            </td>
                            <td class="text-end text-muted">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold text-dark">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Financial Summary -->
        <div class="row justify-content-end mb-4">
            <div class="col-sm-6 col-md-5">
                <div class="p-3 bg-light rounded-3">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-semibold">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->gst_amount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">GST ({{ $order->gst_rate }}%):</span>
                            <span class="fw-semibold text-warning">₹{{ number_format($order->gst_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Discount:</span>
                            <span class="fw-semibold text-danger">-₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fs-5 fw-bold text-dark">
                        <span>Grand Total:</span>
                        <span class="text-success">₹{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details & Payment QR Code Section -->
        @if($activeBanks->count() > 0)
            <div class="bank-box mt-4">
                <div class="row align-items-center g-3">
                    <div class="col-sm-8">
                        <h6 class="fw-bold text-dark mb-2"><i class="ri-bank-card-line text-warning me-1"></i> Bank Account / UPI Transfer Details</h6>
                        <p class="small text-muted mb-2">Scan the QR code or transfer directly to any of our official bank accounts below:</p>
                        <div class="row g-2 small">
                            @foreach($activeBanks as $bank)
                                <div class="col-12 col-md-6">
                                    <div class="p-2 bg-white rounded border">
                                        <strong class="d-block text-primary">{{ $bank->bank_name }}</strong>
                                        A/C: <strong>{{ $bank->account_number }}</strong><br>
                                        IFSC: <strong>{{ $bank->ifsc_code }}</strong><br>
                                        Holder: {{ $bank->account_holder_name }}
                                        @if($bank->upi_id)
                                            <br>UPI ID: <span class="badge bg-light text-dark border">{{ $bank->upi_id }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-4 text-center">
                        @php
                            $primaryBank = $activeBanks->first();
                            $upiId = $primaryBank->upi_id ?? 'crackers@upi';
                            $qrData = "upi://pay?pa={$upiId}&pn=CrackersStore&am={$order->grand_total}&cu=INR&tn=Order_{$order->order_number}";
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
                        @endphp
                        <img src="{{ $qrUrl }}" alt="UPI QR Code" class="qr-code-img mb-2">
                        <div class="small fw-bold text-dark">Scan to Pay via UPI</div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Instant GPay / PhonePe / Paytm</small>
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer Terms -->
        <div class="text-center mt-5 pt-3 border-top text-muted small">
            <p class="mb-0">Thank you for shopping with <strong>Crackers.com</strong>! Have a bright & safe celebration.</p>
        </div>

    </div>

</body>
</html>
