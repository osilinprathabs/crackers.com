@extends('layouts/layoutMaster')

@section('title', 'CRM & Customer Relationship Management')

@section('content')
<div class="container-fluid p-0 mb-4">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-0 text-white fw-bold d-flex align-items-center">
                    <i class="ri-user-star-line fs-3 me-2"></i> CRM & Customer Management
                </h4>
                <small class="text-white-50">Centralized database for B2B Wholesale clients, Retail shoppers & Walk-In POS customers</small>
            </div>
            <div>
                <button class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                    <i class="ri-user-add-line me-1"></i> Register New Customer Lead
                </button>
            </div>
        </div>
    </div>

    <!-- CRM Metric KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block fw-semibold">Total Customers</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">{{ number_format($totalCustomersCount) }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="ri-group-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block fw-semibold">Wholesale (B2B)</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1">{{ number_format($wholesaleCount) }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="ri-store-3-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block fw-semibold">Retail Consumers</span>
                        <h4 class="fw-bold text-info mb-0 mt-1">{{ number_format($retailCount) }}</h4>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="ri-user-heart-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block fw-semibold">Customer Revenue (LTV)</span>
                        <h4 class="fw-bold text-success mb-0 mt-1">₹{{ number_format($totalRevenue, 2) }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="ri-money-rupee-circle-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main CRM Table Workspace -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <!-- Segment Filter Nav Tabs -->
            <ul class="nav nav-pills card-header-pills mb-4 gap-2 flex-wrap" role="tablist">
                @php
                    $currentType = request('type', 'all');
                @endphp

                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentType === 'all' ? 'active bg-primary text-white shadow-sm' : 'bg-light text-dark' }}" 
                       href="{{ route('admin.customers.index', array_merge(request()->except(['type', 'page']), ['type' => 'all'])) }}">
                       <i class="ri-team-line me-1"></i> All Customers
                       <span class="badge {{ $currentType === 'all' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1 rounded-pill">{{ $totalCustomersCount }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentType === 'wholesale' ? 'active bg-warning text-dark shadow-sm' : 'bg-light text-dark' }}" 
                       href="{{ route('admin.customers.index', array_merge(request()->except(['type', 'page']), ['type' => 'wholesale'])) }}">
                       <i class="ri-store-3-line me-1 text-warning"></i> Wholesale (B2B)
                       <span class="badge {{ $currentType === 'wholesale' ? 'bg-dark text-warning' : 'bg-warning text-dark' }} ms-1 rounded-pill">{{ $wholesaleCount }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentType === 'retail' ? 'active bg-info text-white shadow-sm' : 'bg-light text-dark' }}" 
                       href="{{ route('admin.customers.index', array_merge(request()->except(['type', 'page']), ['type' => 'retail'])) }}">
                       <i class="ri-user-heart-line me-1 text-info"></i> Retail Shoppers
                       <span class="badge {{ $currentType === 'retail' ? 'bg-white text-info' : 'bg-info text-white' }} ms-1 rounded-pill">{{ $retailCount }}</span>
                    </a>
                </li>
            </ul>

            <!-- Search & Filter Form Bar -->
            <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4 bg-light p-3 rounded border shadow-sm">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <div class="row g-2 align-items-center">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="ri-search-2-line text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search customer by Code, Name, Phone, Email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold w-100"><i class="ri-search-line me-1"></i> Search CRM</button>
                        @if(request('search') || request('type'))
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="ri-refresh-line"></i></a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Customers Table -->
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>CUSTOMER</th>
                            <th>SEGMENT</th>
                            <th>CONTACT DETAILS</th>
                            <th>ORDER CHANNEL</th>
                            <th class="text-center">TOTAL ORDERS</th>
                            <th class="text-end">LIFETIME VALUE (LTV)</th>
                            <th>JOINED DATE</th>
                            <th class="text-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $customer->contact_person_mobile);
                                $waUrl = !empty($cleanPhone) ? "https://wa.me/91" . substr($cleanPhone, -10) : null;

                                $custOrders = $customer->crackersOrders;
                                if ($custOrders->isEmpty() && $customer->contact_person_mobile) {
                                    $custOrders = \App\Models\CrackersOrder::where('customer_phone', $customer->contact_person_mobile)->get();
                                }
                                $hasPos = $custOrders->contains(fn($o) => $o->is_pos);
                                $hasOnline = $custOrders->contains(fn($o) => !$o->is_pos);
                                $totalLtv = $custOrders->where('payment_status', 'paid')->sum('grand_total');
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold">
                                            {{ strtoupper(substr($customer->contact_person_name ?: $customer->company_name ?: 'C', 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-dark fw-bold text-decoration-none">
                                                {{ $customer->company_name ?: $customer->contact_person_name }}
                                            </a>
                                            <div class="small font-monospace text-primary" style="font-size: 11px;">#{{ $customer->customer_code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->customer_type === 'wholesale')
                                        <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill"><i class="ri-store-3-line me-1"></i> Wholesale (B2B)</span>
                                    @else
                                        <span class="badge bg-label-info text-info fw-bold px-2 py-1 rounded-pill"><i class="ri-user-heart-line me-1"></i> Retail</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-dark"><i class="ri-phone-line text-muted me-1"></i>{{ $customer->contact_person_mobile ?: 'N/A' }}</strong>
                                        @if($waUrl)
                                            <a href="{{ $waUrl }}" target="_blank" class="ms-1 text-success text-decoration-none" title="Chat on WhatsApp">
                                                <i class="ri-whatsapp-line fs-6"></i>
                                            </a>
                                        @endif
                                    </div>
                                    @if($customer->contact_person_email)
                                        <small class="text-muted d-block"><i class="ri-mail-line me-1"></i>{{ $customer->contact_person_email }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($hasPos && $hasOnline)
                                        <span class="badge bg-primary text-white px-2 py-1 rounded-pill" style="font-size: 11px;">
                                            <i class="ri-global-line me-1"></i> Online + <i class="ri-store-2-line me-1"></i> POS
                                        </span>
                                    @elseif($hasPos)
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                            <i class="ri-store-2-line me-1"></i> Walk-In POS
                                        </span>
                                    @elseif($hasOnline)
                                        <span class="badge bg-info text-white px-2 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                            <i class="ri-global-line me-1"></i> Online Website
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-secondary font-monospace fs-6">{{ $custOrders->count() }} Orders</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success fs-6 font-monospace">₹{{ number_format($totalLtv, 2) }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $customer->created_at ? $customer->created_at->format('d M Y') : '—' }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <!-- View Customer CRM Profile -->
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-pill" title="View CRM Profile & Order History">
                                            <i class="ri-eye-line"></i>
                                        </a>

                                        <!-- Login As Customer -->
                                        <form action="{{ route('admin.customers.login-as', $customer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-warning rounded-pill" title="Impersonate / Login as Customer">
                                                <i class="ri-user-shared-line"></i>
                                            </button>
                                        </form>

                                        <!-- Delete Customer Profile -->
                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer profile from CRM database?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-pill" title="Delete Profile">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ri-user-search-line display-4 opacity-50 mb-2 d-block"></i>
                                    <span>No customer profiles found in CRM database matching criteria.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal to Register New Customer Lead -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white"><i class="ri-user-add-line me-1"></i> Register New Customer Lead</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label class="form-label fw-bold">Full Name / Business Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Rahul Sharma">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold">Segment *</label>
                            <select name="customer_type" class="form-select" required>
                                <option value="retail" selected>Retail</option>
                                <option value="wholesale">Wholesale (B2B)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Mobile Number *</label>
                            <input type="text" name="phone" class="form-control" required placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. rahul@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">GST / Tax Identification Number</label>
                        <input type="text" name="tax_number" class="form-control" placeholder="Optional (e.g. 33AAAAA0000A1Z5)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Delivery / Billing Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Full address details"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="ri-check-line me-1"></i> Save Customer Lead
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
