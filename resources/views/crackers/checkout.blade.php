<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout | Crackers.com</title>

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
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .step-item.active {
            color: #fb8500;
        }
        .step-item.done {
            color: #22c55e;
        }
        .step-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }
        .step-item.active .step-number {
            background: #fb8500;
            color: #000;
            font-weight: 700;
        }
        .step-item.done .step-number {
            background: #22c55e;
            color: #fff;
        }
        .card-custom {
            background: var(--bg-card);
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 20px;
            padding: 1.75rem;
        }
        .payment-box {
            background: #f8fafc;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 0.75rem;
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
            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('crackers.profile') }}" class="btn btn-outline-primary rounded-pill px-3 btn-sm fw-semibold">
                        <i class="ri-user-settings-line me-1"></i> My Profile
                    </a>
                @else
                    <a href="{{ route('crackers.login-page') }}" class="btn btn-warning rounded-pill px-3 btn-sm fw-bold">
                        <i class="ri-user-3-line me-1"></i> Login / Register
                    </a>
                @endauth
                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                    <i class="ri-store-2-line me-1"></i> Return To Shop
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
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
                    <div class="step-item active justify-content-center">
                        <div class="step-number">2</div>
                        <span class="d-none d-md-inline">2. Shipping Info</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="step-item active justify-content-center">
                        <div class="step-number">3</div>
                        <span class="d-none d-md-inline">3. Payment</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="step-item justify-content-center">
                        <div class="step-number">4</div>
                        <span class="d-none d-md-inline">4. Confirmation</span>
                    </div>
                </div>
            </div>
        </div>

        @php
            $billing = is_array($customer->billing_address ?? null) ? $customer->billing_address : [];
            $preAddress = $billing['address'] ?? '';
            $preCity = $billing['city'] ?? '';
            $prePincode = $billing['pincode'] ?? '';
        @endphp

        <div class="row g-4">
            <!-- Left Side: Shipping & Payment Form -->
            <div class="col-lg-7">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0 text-warning" style="font-family: 'Outfit', sans-serif;"><i class="ri-map-pin-line me-2"></i> Shipping & Delivery Details</h4>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="ri-truck-fill me-1"></i> 5 - 7 Days Delivery</span>
                    </div>

                    @auth
                        <div class="alert alert-success py-2 small d-flex align-items-center justify-content-between mb-4 rounded-3">
                            <span><i class="ri-user-smile-line me-1"></i> Logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->phone }})</span>
                            <span class="badge bg-success">Auto-Filled</span>
                        </div>
                    @else
                        <div class="alert alert-info py-2 small d-flex align-items-center justify-content-between mb-4 rounded-3">
                            <span><i class="ri-information-line me-1"></i> Already have an account?</span>
                            <a href="{{ route('crackers.login-page') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold">Login for Faster Checkout</a>
                        </div>
                    @endauth

                    <form id="checkoutForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Full Name *</label>
                                <input type="text" name="customer_name" class="form-control" required value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="Customer Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mobile Phone Number *</label>
                                <input type="text" name="customer_phone" class="form-control" required value="{{ auth()->check() ? auth()->user()->phone : '' }}" placeholder="10-digit mobile number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address (Optional)</label>
                            <input type="email" name="customer_email" class="form-control" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="email@example.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Street Delivery Address *</label>
                            <textarea name="delivery_address" class="form-control" rows="3" required placeholder="Door number, street name, landmark">{{ $preAddress }}</textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">City / Town *</label>
                                <input type="text" name="city" class="form-control" required value="{{ $preCity }}" placeholder="City">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pincode *</label>
                                <input type="text" name="pincode" class="form-control" required value="{{ $prePincode }}" placeholder="Pincode">
                            </div>
                        </div>

                        <h4 class="fw-bold mb-3 text-warning border-top border-secondary pt-3" style="font-family: 'Outfit', sans-serif;"><i class="ri-bank-card-line me-2"></i> Select Payment Method</h4>

                        <div class="d-flex flex-column gap-3 mb-4">
                            @if($settings->enable_cod)
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="payment_method" value="COD" id="payCOD" checked onchange="togglePaymentBox('COD')">
                                    <label class="form-check-label fw-bold" for="payCOD">
                                        <i class="ri-hand-coin-line text-warning me-1"></i> Cash On Delivery (COD)
                                    </label>
                                    <div class="small text-muted mt-1">Pay with cash when your crackers arrive at your door.</div>
                                </div>
                            @endif

                            @if($settings->enable_upi)
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="payment_method" value="UPI" id="payUPI" {{ !$settings->enable_cod ? 'checked' : '' }} onchange="togglePaymentBox('UPI')">
                                    <label class="form-check-label fw-bold" for="payUPI">
                                        <i class="ri-qr-code-line text-info me-1"></i> UPI / GPay / PhonePe / Paytm
                                    </label>
                                    <div id="upiBox" class="payment-box d-none mt-2">
                                        <div class="small fw-bold">UPI ID: <span class="text-warning fs-6 me-2">{{ $settings->upi_id }}</span></div>
                                        @if($settings->upi_qr_code)
                                            <div class="mt-2 text-center">
                                                <img src="{{ $settings->upi_qr_code }}" alt="UPI QR Code" class="img-fluid rounded border" style="max-width: 180px;">
                                                <div class="small text-muted mt-1">Scan QR Code using any UPI App to complete payment</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($settings->enable_bank_transfer)
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="payment_method" value="Bank Transfer" id="payBank" {{ (!$settings->enable_cod && !$settings->enable_upi) ? 'checked' : '' }} onchange="togglePaymentBox('Bank')">
                                    <label class="form-check-label fw-bold" for="payBank">
                                        <i class="ri-bank-line text-success me-1"></i> Direct Bank Transfer
                                    </label>
                                    <div id="bankBox" class="payment-box d-none mt-2">
                                        <div class="small text-muted mb-2"><i class="ri-information-line me-1"></i> Transfer order total to any of the active bank accounts below:</div>
                                        <div class="row g-2">
                                            @forelse($activeBanks as $bank)
                                                <div class="col-12">
                                                    <div class="p-2 border rounded bg-dark-subtle">
                                                        <div class="fw-bold text-warning"><i class="ri-bank-card-line me-1"></i>{{ $bank->bank_name }}</div>
                                                        <div class="small"><strong>A/C Holder:</strong> {{ $bank->account_holder }}</div>
                                                        <div class="small"><strong>A/C Number:</strong> <code class="text-success font-monospace">{{ $bank->account_number }}</code></div>
                                                        <div class="small"><strong>IFSC Code:</strong> {{ $bank->ifsc_code }}</div>
                                                        @if($bank->branch_name)
                                                            <div class="small text-muted"><strong>Branch:</strong> {{ $bank->branch_name }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="small text-muted">No active bank account provided.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Payment Proof Upload Section -->
                        <div class="mb-4 p-3 border border-warning rounded bg-light">
                            <label class="form-label fw-bold d-flex align-items-center mb-1 text-dark">
                                <i class="ri-upload-cloud-2-line text-warning fs-5 me-2"></i> Payment Proof / Receipt Screenshot (Recommended)
                            </label>
                            <input type="file" name="payment_proof" id="paymentProofInput" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1"><i class="ri-information-line me-1"></i> Attach transaction screenshot/receipt for instant order verification.</small>
                        </div>

                        <div id="checkoutError" class="alert alert-danger d-none py-2 mb-3"></div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill" id="submitOrderBtn" style="background: var(--gold-gradient); color:#000;">
                            CONFIRM & PLACE CRACKER ORDER <i class="ri-arrow-right-line ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-lg-5">
                <div class="card-custom">
                    <h5 class="fw-bold mb-3 text-warning" style="font-family: 'Outfit', sans-serif;"><i class="ri-shopping-cart-fill me-2"></i> Order Summary</h5>
                    
                    <div id="checkoutCartItems" class="mb-3"></div>

                    <div class="border-top border-secondary pt-3">
                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotalVal" class="fw-bold">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span>GST Tax ({{ $settings->gst_percentage }}%):</span>
                            <span id="gstVal" class="fw-bold">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 text-success border-top border-secondary pt-2">
                            <span>Grand Total:</span>
                            <span id="grandTotalVal">₹0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const gstPct = {{ $settings->gst_percentage ?: 0 }};
        let cart = JSON.parse(localStorage.getItem('crackers_cart') || '[]');

        function renderCheckoutSummary() {
            let container = document.getElementById('checkoutCartItems');
            let subtotalEl = document.getElementById('subtotalVal');
            let gstEl = document.getElementById('gstVal');
            let grandTotalEl = document.getElementById('grandTotalVal');
            let btn = document.getElementById('submitOrderBtn');

            if (!cart || cart.length === 0) {
                container.innerHTML = `<div class="text-center py-4 text-muted"><i class="ri-shopping-cart-line display-4"></i><p class="mt-2">Your cart is empty.</p><a href="{{ route('crackers.storefront') }}" class="btn btn-sm btn-warning rounded-pill mt-2">Browse Catalog</a></div>`;
                btn.disabled = true;
                return;
            }

            let subtotal = cart.reduce((sum, i) => sum + (i.price * i.quantity), 0);
            let gstAmount = subtotal * (gstPct / 100);
            let grandTotal = subtotal + gstAmount;

            subtotalEl.innerText = '₹' + subtotal.toFixed(2);
            gstEl.innerText = '₹' + gstAmount.toFixed(2);
            grandTotalEl.innerText = '₹' + grandTotal.toFixed(2);

            container.innerHTML = cart.map(item => `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary small">
                    <div>
                        <div class="fw-bold">${item.name}</div>
                        <small class="text-muted">₹${item.price.toFixed(2)} x ${item.quantity} ${item.unit}</small>
                    </div>
                    <div class="fw-bold text-success">₹${(item.price * item.quantity).toFixed(2)}</div>
                </div>
            `).join('');
        }

        function togglePaymentBox(type) {
            let upiBox = document.getElementById('upiBox');
            let bankBox = document.getElementById('bankBox');
            if (upiBox) upiBox.classList.add('d-none');
            if (bankBox) bankBox.classList.add('d-none');

            if (type === 'UPI' && upiBox) upiBox.classList.remove('d-none');
            if (type === 'Bank' && bankBox) bankBox.classList.remove('d-none');
        }

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let btn = document.getElementById('submitOrderBtn');
            let errBox = document.getElementById('checkoutError');

            if (!cart || cart.length === 0) {
                errBox.innerText = 'Your cart is empty.';
                errBox.classList.remove('d-none');
                return;
            }

            btn.disabled = true;
            errBox.classList.add('d-none');

            let formData = new FormData(this);
            formData.append('items_json', JSON.stringify(cart));

            fetch('{{ route("crackers.place-order") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    localStorage.removeItem('crackers_cart');
                    window.location.href = res.redirect_url;
                } else {
                    errBox.innerText = res.message || 'Error placing order.';
                    errBox.classList.remove('d-none');
                    btn.disabled = false;
                }
            })
            .catch(err => {
                errBox.innerText = 'An error occurred while placing your order.';
                errBox.classList.remove('d-none');
                btn.disabled = false;
            });
        });

        renderCheckoutSummary();
    </script>
</body>
</html>
