<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Quotation #{{ $order->order_number }} | {{ $settings->company_name ?: 'Crackers Store' }}</title>
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
        .quotation-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 3rem;
            max-width: 880px;
            margin: 0 auto;
        }
        .brand-header {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: #fb8500;
        }
        .quotation-badge {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
        }
        .table-quotation th {
            background-color: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        .whatsapp-box {
            background: #f0fdf4;
            border: 1px dashed #86efac;
            border-radius: 14px;
            padding: 1.25rem;
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
            .quotation-card {
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
    <div class="container no-print mb-4" style="max-width: 880px;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 border shadow-sm flex-wrap gap-2">
            <button onclick="window.close()" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="ri-arrow-left-line me-1"></i> Close Window
            </button>

            <!-- WhatsApp Direct Share Input -->
            <div class="d-flex align-items-center gap-2">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-light text-success border-end-0 fw-bold"><i class="ri-whatsapp-line"></i> +91</span>
                    <input type="text" id="waMobileInput" class="form-control border-start-0" placeholder="Mobile Number" value="{{ preg_replace('/[^0-9]/', '', $order->customer_phone) !== '9999999999' ? preg_replace('/[^0-9]/', '', $order->customer_phone) : '' }}">
                </div>
                <button type="button" onclick="redirectToWhatsApp()" class="btn btn-success rounded-pill px-3 fw-bold">
                    <i class="ri-whatsapp-line me-1"></i> Send to WhatsApp
                </button>
            </div>

            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="ri-printer-line me-1"></i> Print / Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Quotation Card Container -->
    <div class="quotation-card">

        <!-- Top Header Row -->
        <div class="row align-items-center border-bottom pb-4 mb-4">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="brand-header d-flex align-items-center gap-2">
                    <i class="ri-fire-fill text-warning"></i> {{ $settings->company_name ?: 'S.R. TRADERS' }}
                </div>
                <div class="small text-muted mt-1">
                    {{ $settings->support_address ?: 'Main Store Road, Sivakasi' }}<br>
                    Contact: {{ $settings->support_phone ?: '+91 9876543210' }} | {{ $settings->support_email ?: 'info@crackers.com' }}<br>
                    @if($settings->gst_percentage)
                        GSTIN: <strong>{{ $settings->gst_number ?: '33AAAAA0000A1Z5' }}</strong> | 
                    @endif
                    @if($settings->license_number)
                        Explosive License: <strong>{{ $settings->license_number }}</strong>
                    @endif
                </div>
            </div>
            <div class="col-sm-6 text-sm-end">
                <span class="quotation-badge text-uppercase"><i class="ri-file-text-line me-1"></i> OFFICIAL PRICE QUOTATION</span>
                <h3 class="fw-bold mt-2 mb-0 text-primary">#{{ $order->order_number }}</h3>
                <div class="small text-muted">Date: <strong>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</strong></div>
                <div class="small text-muted">Validity: <strong>Valid for 7 Days</strong></div>
            </div>
        </div>

        <!-- Customer & Meta Info -->
        <div class="row g-4 mb-4">
            <div class="col-6">
                <div class="text-uppercase small fw-bold text-muted mb-1">Quotation Prepared For:</div>
                <h6 class="fw-bold mb-1 text-dark">{{ $order->customer_name ?: 'Walk-In Customer' }}</h6>
                <div class="small text-muted">
                    @if($order->customer_phone && $order->customer_phone !== '9999999999')
                        <i class="ri-phone-line me-1 text-primary"></i>Mobile: <strong>{{ $order->customer_phone }}</strong><br>
                    @endif
                    @if($order->customer_email)
                        <i class="ri-mail-line me-1 text-primary"></i>Email: {{ $order->customer_email }}<br>
                    @endif
                    <i class="ri-store-line me-1 text-primary"></i>Type: <strong>POS Counter Estimate</strong>
                </div>
            </div>

            <div class="col-6 text-end">
                <div class="text-uppercase small fw-bold text-muted mb-1">Prepared By / Status:</div>
                <div class="mb-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="ri-checkbox-circle-line me-1"></i> QUOTATION ESTIMATE</span>
                </div>
                <div class="small text-muted">
                    Prepared By: <strong>{{ auth()->check() ? auth()->user()->name : 'POS Counter Staff' }}</strong><br>
                    Payment Terms: <strong>Pay on Order Confirmation</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-quotation align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Product Description</th>
                        <th class="text-end" style="width: 120px;">Unit Price</th>
                        <th class="text-center" style="width: 90px;">Qty</th>
                        <th class="text-end" style="width: 140px;">Estimated Line Total</th>
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

        <!-- Financial Totals Summary -->
        <div class="row justify-content-end mb-4">
            <div class="col-sm-6 col-md-5">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-semibold">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Discount:</span>
                            <span class="fw-semibold text-danger">-₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    @if($order->gst_amount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">GST Tax ({{ $order->gst_rate }}%):</span>
                            <span class="fw-semibold text-primary">₹{{ number_format($order->gst_amount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fs-5 fw-bold text-dark">
                        <span>ESTIMATED GRAND TOTAL:</span>
                        <span class="text-success">₹{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Transfer Details for Quote Confirmation -->
        @if(!empty($settings->bank_name) || !empty($settings->upi_id))
            <div class="p-3 bg-light rounded-3 border mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class="ri-bank-card-line text-primary me-1"></i> Payment Details for Order Confirmation</h6>
                <div class="row g-2 small text-muted">
                    @if(!empty($settings->bank_name))
                        <div class="col-md-6">
                            <strong>Bank Name:</strong> {{ $settings->bank_name }}<br>
                            <strong>A/C Number:</strong> {{ $settings->account_number }}<br>
                            <strong>IFSC Code:</strong> {{ $settings->ifsc_code }}<br>
                            <strong>Account Holder:</strong> {{ $settings->account_holder }}
                        </div>
                    @endif
                    @if(!empty($settings->upi_id))
                        <div class="col-md-6">
                            <strong>UPI ID / VPA:</strong> <span class="badge bg-white text-dark border">{{ $settings->upi_id }}</span><br>
                            <small class="text-muted">Accepts GPay, PhonePe, Paytm & all UPI Apps.</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Terms Footer -->
        <div class="text-center mt-4 pt-3 border-top text-muted small">
            <p class="mb-1">This quotation is valid for 7 days from the date of issue. Prices & stock levels are subject to availability at order time.</p>
            <p class="mb-0 fw-bold">Thank you for inquiring with {{ $settings->company_name ?: 'S.R. TRADERS' }}!</p>
        </div>

    </div>

    <!-- JavaScript for WhatsApp Redirect & Print -->
    <script>
        @php
            $quotationPublicUrl = route('public.pos.quotation.view', $order->id);
            $waText = "📜 *OFFICIAL QUOTATION / PRICE ESTIMATE*\n";
            $waText .= "🏢 *" . ($settings->company_name ?: 'S.R. TRADERS') . "*\n";
            $waText .= "Quotation No: *" . $order->order_number . "*\n";
            $waText .= "Date: " . ($order->created_at ? $order->created_at->format('d M Y, h:i A') : date('d M Y, h:i A')) . "\n";
            if ($order->customer_name) $waText .= "Customer: *" . $order->customer_name . "*\n";
            $waText .= "----------------------------------\n";
            $waText .= "*ESTIMATED ITEMS:*\n";
            foreach ($order->items as $idx => $d) {
                $waText .= ($idx + 1) . ". " . $d->product_name . "\n   " . $d->quantity . " x ₹" . number_format($d->unit_price, 2) . " = *₹" . number_format($d->total_price, 2) . "*\n";
            }
            $waText .= "----------------------------------\n";
            $waText .= "Subtotal: ₹" . number_format($order->subtotal, 2) . "\n";
            if ($order->discount > 0) $waText .= "Discount: -₹" . number_format($order->discount, 2) . "\n";
            if ($order->gst_amount > 0) $waText .= "GST Tax ({$order->gst_rate}%): ₹" . number_format($order->gst_amount, 2) . "\n";
            $waText .= "👉 *GRAND TOTAL: ₹" . number_format($order->grand_total, 2) . "*\n";
            $waText .= "----------------------------------\n";
            $waText .= "📄 *View & Download Quotation PDF:*\n";
            $waText .= $quotationPublicUrl . "\n";
            $waText .= "----------------------------------\n";
            $waText .= "Thank you for inquiring with us! Reply to this message to confirm your order.";
        @endphp

        const defaultWaText = @json($waText);

        function redirectToWhatsApp() {
            let phoneInput = document.getElementById('waMobileInput').value.replace(/[^0-9]/g, '');
            if (!phoneInput || phoneInput.length < 10) {
                alert('Please enter a valid 10-digit mobile number for WhatsApp redirect.');
                document.getElementById('waMobileInput').focus();
                return;
            }

            // Append India country code 91 if 10 digits
            let formattedPhone = phoneInput.length === 10 ? '91' + phoneInput : phoneInput;
            let encodedMsg = encodeURIComponent(defaultWaText);
            let waUrl = `https://wa.me/${formattedPhone}?text=${encodedMsg}`;

            window.open(waUrl, '_blank');
        }
    </script>
</body>
</html>
