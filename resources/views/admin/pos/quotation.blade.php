<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Quotation #{{ $order->order_number }} | {{ $settings->company_name ?: 'S.R. TRADERS' }}</title>
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
            color: #2563eb;
        }
        .quotation-badge {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table-quotation {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .table-quotation th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
            padding: 12px 16px;
        }
        .table-quotation td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totals-box {
            background: #fafafa;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
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
            <button onclick="window.close()" class="btn btn-outline-secondary rounded-pill px-3 fw-bold">
                <i class="ri-arrow-left-line me-1"></i> Close
            </button>

            <!-- WhatsApp Direct Share Input -->
            <div class="d-flex align-items-center gap-2">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-light text-success border-end-0 fw-bold"><i class="ri-whatsapp-line"></i> +91</span>
                    <input type="text" id="waMobileInput" class="form-control border-start-0 fw-bold" placeholder="Mobile Number" value="{{ preg_replace('/[^0-9]/', '', $order->customer_phone) !== '9999999999' ? preg_replace('/[^0-9]/', '', $order->customer_phone) : '' }}">
                </div>
                <button type="button" onclick="redirectToWhatsApp()" class="btn btn-success rounded-pill px-3 fw-bold shadow-sm">
                    <i class="ri-whatsapp-line me-1"></i> Send via WhatsApp
                </button>
            </div>

            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="ri-printer-line me-1"></i> Print / Save PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Quotation Card Container -->
    <div class="quotation-card">

        <!-- Top Header Row -->
        <div class="row align-items-center border-bottom pb-4 mb-4">
            <div class="col-sm-7 mb-3 mb-sm-0">
                <div class="brand-header d-flex align-items-center gap-2">
                    <i class="ri-file-text-fill text-primary"></i> {{ $settings->company_name ?: 'S.R. TRADERS' }}
                </div>
                <div class="small text-muted mt-1">
                    {{ $settings->support_address ?: 'Main Store Road, Sivakasi' }}<br>
                    Contact: <strong>{{ $settings->support_phone ?: '+91 9876543210' }}</strong> | {{ $settings->support_email ?: ('info@' . \Illuminate\Support\Str::slug($settings->company_name ?: 'crackers') . '.com') }}<br>
                    <span class="text-primary font-monospace fw-semibold"><i class="ri-shield-check-line me-1"></i> GSTIN: {{ $settings->gst_number }}</span>
                </div>
            </div>
            <div class="col-sm-5 text-sm-end">
                <span class="quotation-badge text-uppercase"><i class="ri-file-paper-2-line me-1"></i> OFFICIAL PRICE QUOTATION</span>
                <h3 class="fw-bold mt-2 mb-0 text-dark font-monospace">#{{ $order->order_number }}</h3>
                <div class="small text-muted mt-1">Date: <strong>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</strong></div>
                <div class="small text-muted">Validity: <strong>Valid for 7 Days</strong></div>
            </div>
        </div>

        <!-- Customer & Meta Info -->
        <div class="row g-4 mb-4">
            <div class="col-6">
                <div class="text-uppercase small fw-bold text-muted mb-1">Quotation Prepared For:</div>
                <h6 class="fw-bold mb-1 text-dark fs-5">{{ $order->customer_name ?: 'Walk-In Customer' }}</h6>
                <div class="small text-muted">
                    @if($order->customer_phone && $order->customer_phone !== '9999999999')
                        <i class="ri-phone-line me-1 text-primary"></i>Mobile: <strong>{{ $order->customer_phone }}</strong><br>
                    @endif
                    @if($order->customer_email)
                        <i class="ri-mail-line me-1 text-primary"></i>Email: {{ $order->customer_email }}<br>
                    @endif
                    <i class="ri-file-list-3-line me-1 text-primary"></i>Type: <strong>POS Counter Price Estimate</strong>
                </div>
            </div>

            <div class="col-6 text-end">
                <div class="text-uppercase small fw-bold text-muted mb-1">Status:</div>
                <div class="mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill fw-bold"><i class="ri-price-tag-3-line me-1"></i> PRICE ESTIMATE</span>
                </div>
                <div class="small text-muted">
                    Prepared By: <strong>{{ auth()->check() ? auth()->user()->name : 'POS Billing Counter' }}</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-quotation align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Product Description & Item Name</th>
                        <th class="text-end" style="width: 130px;">Unit Price (₹)</th>
                        <th class="text-center" style="width: 90px;">Qty</th>
                        <th class="text-end" style="width: 150px;">Estimated Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td class="text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark fs-6">{{ $item->product_name }}</strong>
                            </td>
                            <td class="text-end font-monospace text-muted">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center fw-bold font-monospace">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold text-dark font-monospace">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Totals Summary & Payment Bank Details -->
        <div class="row g-4 mb-4 align-items-stretch">
            <!-- Bank Details Column -->
            <div class="col-sm-7 col-md-7">
                @php
                    $bankAcc = $primaryBank ?? \App\Models\CrackersBankAccount::getPrimaryAccount();
                @endphp
                @if($bankAcc)
                    <div class="p-3 border rounded-4 bg-light text-dark h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary text-white me-2 px-2 py-1 rounded-pill" style="font-size: 0.75rem;"><i class="ri-bank-line me-1"></i> PRIMARY BANK</span>
                                <h6 class="fw-bold mb-0 text-dark">Store Payment / Bank Wire Details</h6>
                            </div>
                            <div class="row g-2 mt-1">
                                <div class="col-{{ $bankAcc->qr_code ? '7' : '12' }}">
                                    <div class="small"><span class="text-muted">Bank Name:</span> <strong class="text-dark">{{ $bankAcc->bank_name }}</strong></div>
                                    <div class="small"><span class="text-muted">Account Holder:</span> <strong class="text-dark">{{ $bankAcc->account_holder }}</strong></div>
                                    <div class="small"><span class="text-muted">Account No:</span> <strong class="font-monospace text-primary fs-6">{{ $bankAcc->account_number }}</strong></div>
                                    <div class="small"><span class="text-muted">IFSC Code:</span> <strong class="font-monospace text-dark">{{ $bankAcc->ifsc_code }}</strong></div>
                                    @if($bankAcc->branch_name)
                                        <div class="small"><span class="text-muted">Branch:</span> <span class="text-dark">{{ $bankAcc->branch_name }}</span></div>
                                    @endif
                                    @if($bankAcc->upi_id)
                                        <div class="small mt-1"><span class="text-muted">UPI ID:</span> <strong class="text-success font-monospace">{{ $bankAcc->upi_id }}</strong></div>
                                    @endif
                                </div>
                                @if($bankAcc->qr_code)
                                    <div class="col-5 text-center d-flex flex-column align-items-center justify-content-center border-start ps-2">
                                        <img src="{{ asset('storage/' . $bankAcc->qr_code) }}" alt="Payment QR Code" class="img-fluid rounded border p-1 bg-white shadow-sm" style="max-height: 90px; object-fit: contain;">
                                        <span class="badge bg-success bg-opacity-10 text-success mt-1" style="font-size: 0.65rem;">Scan & Pay UPI</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Financial Totals Summary Box -->
            <div class="col-sm-5 col-md-5">
                <div class="totals-box h-100 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold font-monospace">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Discount:</span>
                            <span class="fw-bold text-danger font-monospace">-₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    @if($order->gst_amount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">GST Tax ({{ $order->gst_rate }}%):</span>
                            <span class="fw-bold text-primary font-monospace">₹{{ number_format($order->gst_amount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fs-5 fw-bold text-dark">
                        <span>ESTIMATED TOTAL:</span>
                        <span class="text-success font-monospace fs-4">₹{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms Footer -->
        <div class="text-center mt-5 pt-3 border-top text-muted small">
            <p class="mb-1">This quotation is valid for 7 days from the date of issue. Stock availability and offer rates are locked for this quote period.</p>
            <p class="mb-0 fw-bold text-dark">Thank you for inquiring with {{ $settings->company_name ?: 'S.R. TRADERS' }}!</p>
        </div>

    </div>

    <!-- JavaScript for WhatsApp Redirect & Print -->
    <script>
        @php
            $quotationPublicUrl = route('public.pos.quotation.view', $order->id);
            $bankAcc = $primaryBank ?? \App\Models\CrackersBankAccount::getPrimaryAccount();

            $waText = "📜 *OFFICIAL PRICE QUOTATION / ESTIMATE*\n";
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
            $waText .= "👉 *ESTIMATED TOTAL: ₹" . number_format($order->grand_total, 2) . "*\n";

            if ($bankAcc) {
                $waText .= "----------------------------------\n";
                $waText .= "💳 *PRIMARY BANK DETAILS:*\n";
                $waText .= "Bank Name: *" . $bankAcc->bank_name . "*\n";
                $waText .= "A/C Holder: *" . $bankAcc->account_holder . "*\n";
                $waText .= "A/C No: *" . $bankAcc->account_number . "*\n";
                $waText .= "IFSC Code: *" . $bankAcc->ifsc_code . "*\n";
                if ($bankAcc->upi_id) $waText .= "UPI ID: *" . $bankAcc->upi_id . "*\n";
            }

            $waText .= "----------------------------------\n";
            $waText .= "📄 *View & Print Official Quotation PDF:* \n";
            $waText .= $quotationPublicUrl . "\n";
            $waText .= "----------------------------------\n";
            $waText .= "Thank you for inquiring with us! Click the link above to view or download your PDF quotation.";
        @endphp

        const defaultWaText = @json($waText);

        function redirectToWhatsApp() {
            let phoneInput = document.getElementById('waMobileInput').value.replace(/[^0-9]/g, '');
            if (!phoneInput || phoneInput.length < 10) {
                alert('Please enter a valid 10-digit mobile number for WhatsApp redirect.');
                document.getElementById('waMobileInput').focus();
                return;
            }

            let formattedPhone = phoneInput.length === 10 ? '91' + phoneInput : phoneInput;
            let encodedMsg = encodeURIComponent(defaultWaText);
            let waUrl = `https://wa.me/${formattedPhone}?text=${encodedMsg}`;

            window.open(waUrl, '_blank');
        }
    </script>
</body>
</html>
