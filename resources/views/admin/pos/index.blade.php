@extends('layouts/layoutMaster')

@section('title', 'POS Counter Billing - Walk-In Sales')

@section('content')
<div class="container-fluid p-0 mb-4">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-3 bg-primary text-white">
        <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-0 text-white fw-bold d-flex align-items-center">
                    <i class="ri-shopping-cart-2-line fs-3 me-2"></i> POS Store Billing Counter
                </h4>
                <small class="text-white-50">Point of Sale system for Walk-in counter customers & instant receipt generation</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-white text-primary px-3 py-2 rounded-pill font-monospace shadow-sm">
                    <i class="ri-store-2-line me-1"></i> {{ $settings->company_name ?: 'S.R. TRADERS' }}
                </span>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill font-monospace shadow-sm">
                    <i class="ri-percent-line me-1"></i> GST: {{ $settings->gst_percentage ?: 0 }}%
                </span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-semibold">
                    <i class="ri-list-check-2 me-1"></i> All Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dual-Pane POS Workspace -->
    <div class="row g-3">
        <!-- LEFT COLUMN: Product Catalog & Search -->
        <div class="col-lg-7 col-xl-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <!-- Search & Filter Controls -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ri-search-2-line"></i></span>
                                <input type="text" id="posSearchInput" class="form-control border-start-0 bg-light" placeholder="Search product name or code..." onkeyup="filterProducts()">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select id="posCategorySelect" class="form-select bg-light" onchange="filterProducts()">
                                <option value="all">All Categories ({{ $categories->count() }})</option>
                                @foreach($categories as $cat)
                                    <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row g-3 overflow-auto" id="posProductGrid" style="max-height: calc(100vh - 270px); min-height: 450px;">
                        @forelse($products as $product)
                            @php
                                $unitPrice = $product->discount_price ?: $product->price;
                                $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                                $isOutOfStock = $product->stock <= 0;
                            @endphp
                            <div class="col-6 col-md-4 col-xl-3 product-card-item" 
                                 data-name="{{ strtolower($product->name) }}" 
                                 data-code="{{ strtolower($product->code) }}" 
                                 data-category="{{ strtolower($product->category) }}">
                                <div class="card h-100 border shadow-none product-box {{ $isOutOfStock ? 'opacity-50' : '' }}" style="border-radius: 12px;">
                                    <div class="position-relative text-center p-2 bg-light rounded-top">
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="height: 90px; object-fit: contain;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-white rounded" style="height: 90px;">
                                                <i class="ri-sparkles-line fs-1 text-warning"></i>
                                            </div>
                                        @endif

                                        <span class="position-absolute top-0 end-0 badge {{ $isOutOfStock ? 'bg-danger' : ($product->stock <= 10 ? 'bg-warning text-dark' : 'bg-success') }} m-2">
                                            {{ $isOutOfStock ? 'OUT' : 'Stock: ' . $product->stock }}
                                        </span>
                                    </div>

                                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="small text-muted text-truncate mb-1">{{ $product->category ?: 'Crackers' }}</div>
                                            <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div>
                                                    <span class="fw-bold text-success fs-6">₹{{ number_format($unitPrice, 2) }}</span>
                                                    @if($hasDiscount)
                                                        <small class="text-muted text-decoration-line-through d-block" style="font-size: 10px;">₹{{ number_format($product->price, 2) }}</small>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $product->unit }}</small>
                                            </div>

                                            <button type="button" 
                                                    class="btn btn-sm btn-primary w-100 rounded-pill fw-semibold add-to-cart-btn" 
                                                    {{ $isOutOfStock ? 'disabled' : '' }}
                                                    onclick="addToCart({{ json_encode([
                                                        'id' => $product->id,
                                                        'name' => $product->name,
                                                        'price' => floatval($unitPrice),
                                                        'stock' => $product->stock,
                                                        'unit' => $product->unit
                                                    ]) }})">
                                                <i class="ri-add-line me-1"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="ri-inbox-line display-3"></i>
                                <p class="mt-2">No active products available in catalog.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Active Cart & Billing Panel -->
        <div class="col-lg-5 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="ri-shopping-basket-2-line text-primary me-2"></i> Active Order Register</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" onclick="clearCart()">
                            <i class="ri-delete-bin-line me-1"></i> Clear
                        </button>
                    </div>
                </div>

                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Customer Selection Section -->
                        <div class="mb-3 p-2 bg-light rounded border">
                            <label class="form-label fw-bold small mb-1"><i class="ri-user-3-line text-primary me-1"></i> Customer Selection</label>
                            <select id="posCustomerType" class="form-select form-select-sm mb-2" onchange="handleCustomerTypeChange()">
                                <option value="walkin" selected>👤 Walk-In Customer (Default)</option>
                                <option value="existing">🔍 Select Existing Customer</option>
                                <option value="new">➕ Register New Customer</option>
                            </select>

                            <!-- Existing Customer Dropdown -->
                            <div id="existingCustomerBox" class="d-none mb-2">
                                <select id="posCustomerId" class="form-select form-select-sm">
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->contact_person_name ?: $c->company_name }} ({{ $c->contact_person_mobile }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Customer Name/Phone Inputs -->
                            <div id="customerFieldsBox">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="text" id="posCustomerName" class="form-control form-control-sm" placeholder="Customer Name (Optional)">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="posCustomerPhone" class="form-control form-control-sm" placeholder="Mobile Number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Items List Container -->
                        <div class="cart-container overflow-auto mb-3" style="max-height: 250px; min-height: 180px;">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-muted">
                                        <th>ITEM</th>
                                        <th class="text-center" style="width: 90px;">QTY</th>
                                        <th class="text-end">TOTAL</th>
                                        <th style="width: 30px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="posCartItemsList">
                                    <!-- Dynamic JS Cart Rows -->
                                </tbody>
                            </table>
                            
                            <div id="posCartEmpty" class="text-center py-4 text-muted">
                                <i class="ri-shopping-cart-line fs-1 d-block mb-1 opacity-50"></i>
                                <small>Cart is empty. Click on items on the left to add.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Calculations & Payment Action -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Subtotal:</span>
                            <span id="posSubtotalVal" class="fw-bold text-dark">₹0.00</span>
                        </div>

                        <div class="d-flex justify-content-between text-muted small mb-2 align-items-center">
                            <span>Discount (₹):</span>
                            <input type="number" id="posDiscountInput" class="form-control form-control-sm text-end" style="width: 100px;" value="0" min="0" step="any" oninput="calculateTotals()">
                        </div>

                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>GST Tax ({{ $settings->gst_percentage ?: 0 }}%):</span>
                            <span id="posGstVal" class="fw-bold text-dark">₹0.00</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-2 rounded bg-success-subtle border border-success mb-3">
                            <span class="fw-bold text-success fs-6">GRAND TOTAL:</span>
                            <span id="posGrandTotalVal" class="fw-bold text-success fs-4">₹0.00</span>
                        </div>

                        <!-- Payment Method Selector -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small mb-1">Payment Method</label>
                                <select id="posPaymentMethod" class="form-select form-select-sm" onchange="handlePaymentMethodChange()">
                                    <option value="Cash" selected>💵 Cash</option>
                                    <option value="UPI / QR">📱 UPI / QR Code</option>
                                    <option value="Card">💳 Debit / Credit Card</option>
                                    <option value="Bank Transfer">🏦 Bank Transfer</option>
                                </select>
                            </div>

                            <div class="col-6" id="cashTenderedBox">
                                <label class="form-label fw-bold small mb-1">Cash Tendered (₹)</label>
                                <input type="number" id="posAmountTendered" class="form-control form-control-sm" placeholder="Amount Paid" oninput="calculateTotals()">
                            </div>
                        </div>

                        <!-- Cash Change Display Box -->
                        <div id="cashChangeDisplayBox" class="p-2 rounded bg-light border mb-3 d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-muted">Change to Return:</span>
                            <span id="posChangeVal" class="fw-bold text-primary fs-6">₹0.00</span>
                        </div>

                        <!-- Submit Order Button -->
                        <button type="button" id="posSubmitBtn" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow-lg py-3 text-dark border-0 text-uppercase" style="background: linear-gradient(135deg, #ffb703 0%, #fb8500 100%); font-size: 1.1rem; letter-spacing: 0.5px;" onclick="submitPosSale()" disabled>
                            <i class="ri-printer-line fs-5 me-1 align-middle"></i> <span class="align-middle">COMPLETE SALE & PRINT RECEIPT</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for POS Interactivity -->
<script>
    const gstRate = {{ floatval($settings->gst_percentage ?: 0) }};
    let posCart = [];

    function addToCart(product) {
        let existing = posCart.find(i => i.id === product.id);
        if (existing) {
            if (existing.quantity + 1 > product.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit Reached',
                    text: `Only ${product.stock} units available for ${product.name}.`,
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            existing.quantity++;
        } else {
            posCart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock,
                unit: product.unit,
                quantity: 1
            });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        let item = posCart.find(i => i.id === id);
        if (!item) return;

        let newQty = item.quantity + delta;
        if (newQty <= 0) {
            removeFromCart(id);
            return;
        }

        if (newQty > item.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Limit Reached',
                text: `Only ${item.stock} units available.`,
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        item.quantity = newQty;
        renderCart();
    }

    function removeFromCart(id) {
        posCart = posCart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
        posCart = [];
        renderCart();
    }

    function renderCart() {
        let listEl = document.getElementById('posCartItemsList');
        let emptyEl = document.getElementById('posCartEmpty');
        let btn = document.getElementById('posSubmitBtn');

        if (posCart.length === 0) {
            listEl.innerHTML = '';
            emptyEl.classList.remove('d-none');
            btn.disabled = true;
            calculateTotals();
            return;
        }

        emptyEl.classList.add('d-none');
        btn.disabled = false;

        listEl.innerHTML = posCart.map(item => `
            <tr>
                <td>
                    <div class="fw-bold text-dark text-truncate" style="max-width: 140px;" title="${item.name}">${item.name}</div>
                    <small class="text-muted">₹${item.price.toFixed(2)} / ${item.unit}</small>
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm flex-nowrap" style="width: 85px;">
                        <button class="btn btn-outline-secondary px-1" onclick="updateQty(${item.id}, -1)">-</button>
                        <input type="text" class="form-control text-center px-1" value="${item.quantity}" readonly>
                        <button class="btn btn-outline-secondary px-1" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end fw-bold text-success">₹${(item.price * item.quantity).toFixed(2)}</td>
                <td class="text-end">
                    <button class="btn btn-link text-danger p-0 border-0" onclick="removeFromCart(${item.id})"><i class="ri-close-line"></i></button>
                </td>
            </tr>
        `).join('');

        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = posCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        let discount = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        
        let taxableSubtotal = Math.max(0, subtotal - discount);
        let gstAmount = taxableSubtotal * (gstRate / 100);
        let grandTotal = taxableSubtotal + gstAmount;

        document.getElementById('posSubtotalVal').innerText = '₹' + subtotal.toFixed(2);
        document.getElementById('posGstVal').innerText = '₹' + gstAmount.toFixed(2);
        document.getElementById('posGrandTotalVal').innerText = '₹' + grandTotal.toFixed(2);

        // Cash Change Calculation
        let tendered = parseFloat(document.getElementById('posAmountTendered').value) || 0;
        let change = tendered > grandTotal ? (tendered - grandTotal) : 0;
        document.getElementById('posChangeVal').innerText = '₹' + change.toFixed(2);
    }

    function handleCustomerTypeChange() {
        let type = document.getElementById('posCustomerType').value;
        let existingBox = document.getElementById('existingCustomerBox');
        let nameInput = document.getElementById('posCustomerName');
        let phoneInput = document.getElementById('posCustomerPhone');

        if (type === 'existing') {
            existingBox.classList.remove('d-none');
            nameInput.placeholder = "Search / Alternate Name";
        } else if (type === 'new') {
            existingBox.classList.add('d-none');
            nameInput.placeholder = "Full Name *";
            phoneInput.placeholder = "Mobile Number *";
        } else {
            existingBox.classList.add('d-none');
            nameInput.placeholder = "Customer Name (Optional)";
            phoneInput.placeholder = "Mobile Number (Optional)";
        }
    }

    function handlePaymentMethodChange() {
        let method = document.getElementById('posPaymentMethod').value;
        let cashBox = document.getElementById('cashTenderedBox');
        let changeBox = document.getElementById('cashChangeDisplayBox');

        if (method === 'Cash') {
            cashBox.classList.remove('d-none');
            changeBox.classList.remove('d-none');
        } else {
            cashBox.classList.add('d-none');
            changeBox.classList.add('d-none');
        }
    }

    function filterProducts() {
        let search = document.getElementById('posSearchInput').value.toLowerCase().trim();
        let cat = document.getElementById('posCategorySelect').value;

        document.querySelectorAll('.product-card-item').forEach(el => {
            let name = el.getAttribute('data-name');
            let code = el.getAttribute('data-code');
            let category = el.getAttribute('data-category');

            let matchesSearch = !search || name.includes(search) || code.includes(search);
            let matchesCat = cat === 'all' || category === cat;

            if (matchesSearch && matchesCat) {
                el.classList.remove('d-none');
            } else {
                el.classList.add('d-none');
            }
        });
    }

    function submitPosSale() {
        if (posCart.length === 0) {
            Swal.fire('Empty Cart', 'Please add products before submitting sale.', 'warning');
            return;
        }

        let customerType = document.getElementById('posCustomerType').value;
        let customerId = document.getElementById('posCustomerId').value;
        let customerName = document.getElementById('posCustomerName').value;
        let customerPhone = document.getElementById('posCustomerPhone').value;
        let paymentMethod = document.getElementById('posPaymentMethod').value;
        let discount = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        let amountTendered = parseFloat(document.getElementById('posAmountTendered').value) || 0;

        if (customerType === 'existing' && !customerId) {
            Swal.fire('Validation Error', 'Please select an existing customer.', 'warning');
            return;
        }

        let btn = document.getElementById('posSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing Sale...';

        fetch('{{ route("admin.pos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                customer_type: customerType,
                customer_id: customerId,
                customer_name: customerName,
                customer_phone: customerPhone,
                payment_method: paymentMethod,
                discount: discount,
                amount_tendered: amountTendered,
                items: posCart.map(i => ({ id: i.id, quantity: i.quantity }))
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sale Completed!',
                    html: `
                        <div class="text-center py-2">
                            <h5 class="text-success fw-bold">Order #${res.order_number}</h5>
                            <p class="mb-1">Grand Total: <strong>₹${res.grand_total.toFixed(2)}</strong></p>
                            ${res.change_amount > 0 ? `<div class="alert alert-info py-2 font-monospace">Change to Return: <strong>₹${res.change_amount.toFixed(2)}</strong></div>` : ''}
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: '🖨️ Print Receipt',
                    cancelButtonText: 'New Order',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed && res.receipt_url) {
                        window.open(res.receipt_url, '_blank');
                    }
                    clearCart();
                    setTimeout(() => window.location.reload(), 500);
                });
            } else {
                Swal.fire('POS Error', res.message || 'Failed to complete sale.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-printer-line fs-5 me-1 align-middle"></i> <span class="align-middle">COMPLETE SALE & PRINT RECEIPT</span>';
            }
        })
        .catch(err => {
            console.error('POS Error:', err);
            Swal.fire('Server Error', 'An unexpected error occurred.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-printer-line fs-5 me-1 align-middle"></i> <span class="align-middle">COMPLETE SALE & PRINT RECEIPT</span>';
        });
    }
</script>
@endsection
