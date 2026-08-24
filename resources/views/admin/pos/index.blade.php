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
                    <!-- Search & Filter Controls with View Switcher -->
                    <div class="row g-2 mb-3 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ri-search-2-line"></i></span>
                                <input type="text" id="posSearchInput" class="form-control border-start-0 bg-light" placeholder="Search product name or code..." onkeyup="filterProducts()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="posCategorySelect" class="form-select bg-light" onchange="filterProducts()">
                                <option value="all">All Categories ({{ $categories->count() }})</option>
                                @foreach($categories as $cat)
                                    <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="btn-group w-100 shadow-sm" role="group" aria-label="POS Layout View Switcher">
                                <button type="button" class="btn btn-primary active px-2 py-1 fw-bold" id="btnPosViewGrid" onclick="setPosView('grid')" title="Card Grid View">
                                    <i class="ri-grid-fill me-1"></i> Card
                                </button>
                                <button type="button" class="btn btn-outline-primary px-2 py-1 fw-bold" id="btnPosViewList" onclick="setPosView('list')" title="List View">
                                    <i class="ri-list-check-2 me-1"></i> List
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- CARD GRID VIEW CONTAINER -->
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

                    <!-- COMPACT LIST VIEW CONTAINER -->
                    <div class="table-responsive overflow-auto d-none border rounded" id="posProductListContainer" style="max-height: calc(100vh - 270px); min-height: 450px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top" style="z-index: 2;">
                                <tr class="small text-muted text-uppercase">
                                    <th style="width: 50px;">Img</th>
                                    <th>Product Details</th>
                                    <th>Category</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end" style="width: 90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="posProductListBody">
                                @forelse($products as $product)
                                    @php
                                        $unitPrice = $product->discount_price ?: $product->price;
                                        $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                                        $isOutOfStock = $product->stock <= 0;
                                    @endphp
                                    <tr class="product-list-item {{ $isOutOfStock ? 'opacity-50 bg-light' : '' }}"
                                        data-name="{{ strtolower($product->name) }}"
                                        data-code="{{ strtolower($product->code) }}"
                                        data-category="{{ strtolower($product->category) }}">
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded border" style="width: 36px; height: 36px;">
                                                    <i class="ri-sparkles-line text-warning"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark mb-0">{{ $product->name }}</div>
                                            @if($product->code)
                                                <small class="text-muted font-monospace me-2">Code: {{ $product->code }}</small>
                                            @endif
                                            <small class="text-muted">({{ $product->unit }})</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $product->category ?: 'Crackers' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $isOutOfStock ? 'bg-danger' : ($product->stock <= 10 ? 'bg-warning text-dark' : 'bg-success') }}">
                                                {{ $isOutOfStock ? 'OUT' : $product->stock . ' ' . $product->unit }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">₹{{ number_format($unitPrice, 2) }}</span>
                                            @if($hasDiscount)
                                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 10px;">₹{{ number_format($product->price, 2) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold add-to-cart-btn" 
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="ri-inbox-line display-4 d-block mb-2 opacity-50"></i>
                                            No active products available in catalog.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        <div class="mb-3 p-2 bg-light rounded border border-warning">
                            <label class="form-label fw-bold small mb-1 text-dark d-flex align-items-center justify-content-between">
                                <span><i class="ri-user-3-line text-primary me-1"></i> Customer Selection</span>
                                <span class="badge bg-danger text-white px-2 py-1" style="font-size: 10px;">Mandatory *</span>
                            </label>
                            <select id="posCustomerType" class="form-select form-select-sm mb-2 fw-semibold" onchange="handleCustomerTypeChange(); checkPosFormValidity();">
                                <option value="walkin" selected>👤 Enter Customer Details (*)</option>
                                <option value="existing">🔍 Select Existing Customer (*)</option>
                                <option value="new">➕ Register New Customer (*)</option>
                            </select>

                            <!-- Existing Customer Dropdown -->
                            <div id="existingCustomerBox" class="d-none mb-2">
                                <select id="posCustomerId" class="form-select form-select-sm" onchange="this.classList.remove('is-invalid'); checkPosFormValidity();">
                                    <option value="">-- Choose Customer (*) --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->contact_person_name ?: $c->company_name }} ({{ $c->contact_person_mobile }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Customer Name/Phone Inputs -->
                            <div id="customerFieldsBox">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="text" id="posCustomerName" class="form-control form-control-sm" placeholder="Customer Name *" oninput="this.classList.remove('is-invalid'); checkPosFormValidity();">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="posCustomerPhone" class="form-control form-control-sm" placeholder="Mobile Number *" oninput="this.classList.remove('is-invalid'); checkPosFormValidity();">
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

                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded border">
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold small text-muted me-1"><i class="ri-percent-line text-primary"></i> Discount:</span>
                                <select id="posDiscountTypeSelect" class="form-select form-select-sm fw-bold border-primary text-primary py-0" style="width: 105px; font-size: 12px;" onchange="calculateTotals()">
                                    <option value="amount" selected>₹ Fixed</option>
                                    <option value="percent">% Percent</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center gap-1">
                                <span class="text-danger fw-bold small d-none" id="posDiscountCalcDisplay" style="font-size: 11px;">-₹0.00</span>
                                <div class="input-group input-group-sm" style="width: 95px;">
                                    <span class="input-group-text bg-white px-2 font-monospace fw-bold text-primary" id="posDiscountSymbol">₹</span>
                                    <input type="number" id="posDiscountInput" class="form-control form-control-sm text-end fw-bold px-1" value="0" min="0" step="any" oninput="calculateTotals()" placeholder="0">
                                </div>
                            </div>
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
                        <div class="row g-2 mb-2">
                            <div class="col-6" id="paymentMethodCol">
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
                        <div id="cashChangeDisplayBox" class="p-2 rounded bg-light border mb-2 d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-muted">Change to Return:</span>
                            <span id="posChangeVal" class="fw-bold text-primary fs-6">₹0.00</span>
                        </div>

                        <!-- UPI / QR Details Display Box -->
                        <div id="upiDetailsDisplayBox" class="p-2 rounded bg-light border border-info mb-2 d-none">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-bold text-info"><i class="ri-qr-code-line me-1"></i> UPI / QR Payment Details</span>
                                <span class="badge bg-info text-white font-monospace" style="font-size: 9px;">Scan & Pay</span>
                            </div>
                            @if(!empty($settings->upi_qr_code))
                                <div class="text-center my-1">
                                    <img src="{{ asset($settings->upi_qr_code) }}" alt="UPI QR Code" class="img-fluid rounded border bg-white p-1" style="max-height: 110px;">
                                </div>
                            @endif
                            <div class="bg-white p-2 rounded border text-center font-monospace small">
                                <div class="text-muted" style="font-size: 10px;">UPI ID / VPA:</div>
                                <strong class="text-dark fs-6">{{ $settings->upi_id ?: 'crackers@upi' }}</strong>
                            </div>
                        </div>

                        <!-- Bank Transfer Details Display Box -->
                        <div id="bankDetailsDisplayBox" class="p-2 rounded bg-light border border-primary mb-2 d-none">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-bold text-primary"><i class="ri-bank-line me-1"></i> Bank Transfer Details</span>
                                <span class="badge bg-primary text-white font-monospace" style="font-size: 9px;">NEFT / RTGS</span>
                            </div>
                            <div class="bg-white p-2 rounded border font-monospace small">
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 11px;">
                                    <span>Bank:</span>
                                    <strong class="text-dark">{{ $settings->bank_name ?: 'N/A' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 11px;">
                                    <span>Account No:</span>
                                    <strong class="text-dark">{{ $settings->account_number ?: 'N/A' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 11px;">
                                    <span>IFSC Code:</span>
                                    <strong class="text-dark">{{ $settings->ifsc_code ?: 'N/A' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                    <span>Holder:</span>
                                    <strong class="text-dark text-truncate" style="max-width: 140px;">{{ $settings->account_holder ?: 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- POS Action Buttons (Sale & Quotation) -->
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" id="posQuotationBtn" class="btn btn-outline-primary btn-md flex-fill rounded-pill fw-bold py-2 text-uppercase" style="font-size: 0.85rem;" onclick="submitPosQuotation()" disabled>
                                <i class="ri-file-paper-2-line me-1 align-middle fs-6"></i> <span class="align-middle">Generate Quotation</span>
                            </button>

                            <button type="button" id="posSubmitBtn" class="btn btn-warning btn-md flex-fill rounded-pill fw-bold shadow-sm py-2 text-dark border-0 text-uppercase" style="background: linear-gradient(135deg, #ffb703 0%, #fb8500 100%); font-size: 0.85rem;" onclick="submitPosSale()" disabled>
                                <i class="ri-printer-line me-1 align-middle fs-6"></i> <span class="align-middle">Complete Sale</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- POS Quotation Modal -->
<div class="modal fade" id="posQuotationModal" tabindex="-1" aria-labelledby="posQuotationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 mb-0" id="posQuotationModalLabel">
                    <i class="ri-file-paper-2-line"></i> POS Quotation Generated
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill font-monospace fw-bold fs-6" id="quoModalNumber">QUO-20260824-001</span>
                </div>
                <h3 class="fw-bold text-success mb-1" id="quoModalTotal">₹0.00</h3>
                <p class="text-muted small mb-4" id="quoModalCustomer">Customer: Walk-In Customer</p>

                <!-- PDF Download / Print Action Button -->
                <div class="d-grid gap-2 mb-4">
                    <a href="#" id="quoModalPdfBtn" target="_blank" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="ri-file-pdf-line me-1"></i> Print / Download Quotation PDF
                    </a>
                </div>

                <!-- WhatsApp Mobile Number Input & Send Section -->
                <div class="p-3 bg-light rounded-3 border text-start">
                    <label class="form-label fw-bold small text-dark mb-2 d-flex align-items-center gap-1">
                        <i class="ri-whatsapp-line text-success fs-5"></i> Enter Mobile Number to Send Quote via WhatsApp
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white font-monospace fw-bold text-success">+91</span>
                        <input type="text" id="quoModalPhoneInput" class="form-control fw-bold" placeholder="Enter 10-digit mobile number">
                        <button type="button" class="btn btn-success fw-bold px-3" onclick="sendQuotationToWhatsAppModal()">
                            <i class="ri-send-plane-fill me-1"></i> Redirect to WhatsApp
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2"><i class="ri-information-line me-1"></i> Formats product price estimate text and redirects directly to WhatsApp chat.</small>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" onclick="clearCart(); $('#posQuotationModal').modal('hide');">New POS Sale</button>
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

        if (posCart.length === 0) {
            listEl.innerHTML = '';
            emptyEl.classList.remove('d-none');
            checkPosFormValidity();
            calculateTotals();
            return;
        }

        emptyEl.classList.add('d-none');
        checkPosFormValidity();

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
        let discountType = document.getElementById('posDiscountTypeSelect')?.value || 'amount';
        let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        
        let discountAmount = 0;
        let symbolEl = document.getElementById('posDiscountSymbol');
        let calcDispEl = document.getElementById('posDiscountCalcDisplay');

        if (discountType === 'percent') {
            symbolEl.innerText = '%';
            discountAmount = (subtotal * discountVal) / 100;
            if (discountVal > 0 && subtotal > 0) {
                calcDispEl.innerText = '-₹' + discountAmount.toFixed(2);
                calcDispEl.classList.remove('d-none');
            } else {
                calcDispEl.classList.add('d-none');
            }
        } else {
            symbolEl.innerText = '₹';
            discountAmount = discountVal;
            calcDispEl.classList.add('d-none');
        }

        discountAmount = Math.min(subtotal, Math.max(0, discountAmount));
        
        let taxableSubtotal = Math.max(0, subtotal - discountAmount);
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
        let upiBox = document.getElementById('upiDetailsDisplayBox');
        let bankBox = document.getElementById('bankDetailsDisplayBox');
        let paymentCol = document.getElementById('paymentMethodCol');

        // Reset display states
        cashBox.classList.add('d-none');
        changeBox.classList.add('d-none');
        if (upiBox) upiBox.classList.add('d-none');
        if (bankBox) bankBox.classList.add('d-none');

        if (method === 'Cash') {
            paymentCol.className = 'col-6';
            cashBox.classList.remove('d-none');
            changeBox.classList.remove('d-none');
        } else {
            paymentCol.className = 'col-12';
            if (method === 'UPI / QR' && upiBox) {
                upiBox.classList.remove('d-none');
            } else if (method === 'Bank Transfer' && bankBox) {
                bankBox.classList.remove('d-none');
            }
        }
    }

    function setPosView(mode) {
        const gridEl = document.getElementById('posProductGrid');
        const listEl = document.getElementById('posProductListContainer');
        const btnGrid = document.getElementById('btnPosViewGrid');
        const btnList = document.getElementById('btnPosViewList');

        if (mode === 'list') {
            gridEl.classList.add('d-none');
            listEl.classList.remove('d-none');

            btnGrid.classList.remove('btn-primary', 'active');
            btnGrid.classList.add('btn-outline-primary');

            btnList.classList.remove('btn-outline-primary');
            btnList.classList.add('btn-primary', 'active');

            try { localStorage.setItem('pos_view_mode', 'list'); } catch(e) {}
        } else {
            gridEl.classList.remove('d-none');
            listEl.classList.add('d-none');

            btnList.classList.remove('btn-primary', 'active');
            btnList.classList.add('btn-outline-primary');

            btnGrid.classList.remove('btn-outline-primary');
            btnGrid.classList.add('btn-primary', 'active');

            try { localStorage.setItem('pos_view_mode', 'grid'); } catch(e) {}
        }
        filterProducts();
    }

    function filterProducts() {
        let search = document.getElementById('posSearchInput').value.toLowerCase().trim();
        let cat = document.getElementById('posCategorySelect').value;

        document.querySelectorAll('.product-card-item, .product-list-item').forEach(el => {
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

    function checkPosFormValidity() {
        let btn = document.getElementById('posSubmitBtn');
        let quoBtn = document.getElementById('posQuotationBtn');

        let hasCartItems = posCart && posCart.length > 0;

        let type = document.getElementById('posCustomerType')?.value || 'walkin';
        let customerId = document.getElementById('posCustomerId')?.value || '';
        let customerName = document.getElementById('posCustomerName')?.value.trim() || '';
        let customerPhone = document.getElementById('posCustomerPhone')?.value.trim() || '';

        let isCustomerValid = false;
        if (type === 'existing') {
            isCustomerValid = (customerId !== '');
        } else {
            isCustomerValid = (customerName !== '') && (customerPhone !== '');
        }

        let isValid = hasCartItems && isCustomerValid;

        if (btn) btn.disabled = !isValid;
        if (quoBtn) quoBtn.disabled = !isValid;
    }

    document.addEventListener('DOMContentLoaded', function() {
        let savedMode = 'grid';
        try { savedMode = localStorage.getItem('pos_view_mode') || 'grid'; } catch(e) {}
        setPosView(savedMode);
        checkPosFormValidity();
    });

    function validateCustomerSelection() {
        let type = document.getElementById('posCustomerType').value;
        let customerId = document.getElementById('posCustomerId').value;
        let nameInput = document.getElementById('posCustomerName');
        let phoneInput = document.getElementById('posCustomerPhone');
        let nameVal = nameInput ? nameInput.value.trim() : '';
        let phoneVal = phoneInput ? phoneInput.value.trim() : '';

        if (type === 'existing') {
            if (!customerId) {
                document.getElementById('posCustomerId').classList.add('is-invalid');
                Swal.fire('Customer Selection Mandatory', 'Please select an existing customer from the dropdown (*).', 'warning');
                return false;
            }
        } else {
            if (!nameVal || !phoneVal) {
                if (!nameVal && nameInput) nameInput.classList.add('is-invalid');
                if (!phoneVal && phoneInput) phoneInput.classList.add('is-invalid');
                Swal.fire('Customer Selection Mandatory', 'Customer Name and Mobile Number are required (*).', 'warning');
                return false;
            }
        }
        return true;
    }

    function submitPosSale() {
        if (posCart.length === 0) {
            Swal.fire('Empty Cart', 'Please add products before submitting sale.', 'warning');
            return;
        }

        if (!validateCustomerSelection()) {
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
                discount_type: discountType,
                discount_value: discountVal,
                discount: discountAmount,
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
            btn.innerHTML = '<i class="ri-printer-line fs-5 me-1 align-middle"></i> <span class="align-middle">COMPLETE SALE</span>';
        });
    }

    let currentQuotationData = null;

    function submitPosQuotation() {
        if (posCart.length === 0) {
            Swal.fire('Empty Cart', 'Please add products before generating quotation.', 'warning');
            return;
        }

        if (!validateCustomerSelection()) {
            return;
        }

        let customerType = document.getElementById('posCustomerType').value;
        let customerId = document.getElementById('posCustomerId').value;
        let customerName = document.getElementById('posCustomerName').value;
        let customerPhone = document.getElementById('posCustomerPhone').value;
        let discountType = document.getElementById('posDiscountTypeSelect')?.value || 'amount';
        let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        let subtotal = posCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        let calculatedDiscount = discountType === 'percent' ? (subtotal * discountVal) / 100 : discountVal;
        let discountAmount = Math.min(subtotal, Math.max(0, calculatedDiscount));

        let btn = document.getElementById('posQuotationBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Creating Quote...';

        fetch('{{ route("admin.pos.quotation.store") }}', {
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
                discount_type: discountType,
                discount_value: discountVal,
                discount: discountAmount,
                items: posCart.map(i => ({ id: i.id, quantity: i.quantity }))
            })
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-file-paper-2-line me-1 align-middle fs-6"></i> <span class="align-middle">Generate Quotation</span>';

            if (res.success) {
                currentQuotationData = res;

                document.getElementById('quoModalNumber').innerText = res.quotation_number;
                document.getElementById('quoModalTotal').innerText = '₹' + res.grand_total.toFixed(2);
                document.getElementById('quoModalCustomer').innerText = 'Customer: ' + (res.customer_name || 'Walk-In Customer');
                document.getElementById('quoModalPdfBtn').href = res.quotation_url;
                document.getElementById('quoModalPhoneInput').value = res.customer_phone ? res.customer_phone.replace(/[^0-9]/g, '') : '';

                var quoModal = new bootstrap.Modal(document.getElementById('posQuotationModal'));
                quoModal.show();
            } else {
                Swal.fire('Quotation Error', res.message || 'Failed to create quotation.', 'error');
            }
        })
        .catch(err => {
            console.error('POS Quotation Error:', err);
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-file-paper-2-line me-1 align-middle fs-6"></i> <span class="align-middle">Generate Quotation</span>';
            Swal.fire('Server Error', 'An unexpected error occurred.', 'error');
        });
    }

    function sendQuotationToWhatsAppModal() {
        if (!currentQuotationData) return;

        let phoneInput = document.getElementById('quoModalPhoneInput').value.replace(/[^0-9]/g, '');
        if (!phoneInput || phoneInput.length < 10) {
            Swal.fire('Mobile Number Required', 'Please enter a valid 10-digit mobile number.', 'warning');
            document.getElementById('quoModalPhoneInput').focus();
            return;
        }

        let formattedPhone = phoneInput.length === 10 ? '91' + phoneInput : phoneInput;
        let encodedMsg = encodeURIComponent(currentQuotationData.whatsapp_text);
        let waUrl = `https://wa.me/${formattedPhone}?text=${encodedMsg}`;

        window.open(waUrl, '_blank');
    }
</script>
@endsection
