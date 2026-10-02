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
            <div id="customersTableContainer" class="position-relative">
                @include('admin.customers.partials.customers_table')
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('customersTableContainer');

    function loadCustomersAjax(url, updateUrl = true) {
        if (!container) return;

        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.html) {
                container.innerHTML = data.html;

                if (data.totalCustomersCount !== undefined) {
                    const el = document.getElementById('badgeAllCount');
                    if (el) el.innerText = data.totalCustomersCount;
                }
                if (data.wholesaleCount !== undefined) {
                    const el = document.getElementById('badgeWholesaleCount');
                    if (el) el.innerText = data.wholesaleCount;
                }
                if (data.retailCount !== undefined) {
                    const el = document.getElementById('badgeRetailCount');
                    if (el) el.innerText = data.retailCount;
                }

                if (updateUrl) {
                    history.pushState(null, '', url);
                }
            }
        })
        .catch(error => {
            console.error('CRM AJAX Error:', error);
            window.location.href = url;
        })
        .finally(() => {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        });
    }

    document.addEventListener('click', function (e) {
        const tabLink = e.target.closest('.customer-tab-link');
        if (tabLink) {
            e.preventDefault();
            loadCustomersAjax(tabLink.href);
            return;
        }

        const paginationLink = e.target.closest('#customersPaginationBox a');
        if (paginationLink) {
            e.preventDefault();
            loadCustomersAjax(paginationLink.href);
            return;
        }

        const resetLink = e.target.closest('.customer-reset-link');
        if (resetLink) {
            e.preventDefault();
            loadCustomersAjax(resetLink.href);
            return;
        }
    });

    document.addEventListener('submit', function (e) {
        if (e.target && e.target.id === 'customerSearchForm') {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = form.action + '?' + params.toString();
            loadCustomersAjax(url);
        }
    });

    window.addEventListener('popstate', function () {
        loadCustomersAjax(window.location.href, false);
    });
});
</script>
@endsection
