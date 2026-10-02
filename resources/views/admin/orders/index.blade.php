@extends('layouts/layoutMaster')

@section('title', 'Cracker Orders & Payment Collection')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="ri-shopping-bag-3-line me-2 text-primary"></i>Crackers Customer Orders & Payment Collection</h5>
        <span class="badge bg-label-success">Order & Payment Admin</span>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Status Filter Tabs -->
        <ul class="nav nav-pills card-header-pills mb-4 gap-2 flex-wrap" id="statusTabsList" role="tablist">
            @php
                $currentStatus = request('status', '');
            @endphp

            <li class="nav-item">
                <a class="nav-link status-tab-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === '' ? 'active bg-primary text-white shadow-sm' : 'bg-light text-dark' }}" 
                   data-status=""
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), [])) }}">
                   <i class="ri-list-check-2 me-1"></i> All Orders
                   <span class="badge badge-count-all {{ $currentStatus === '' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1 rounded-pill">{{ $statusCounts['all'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link status-tab-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'pending' ? 'active bg-warning text-dark shadow-sm' : 'bg-light text-dark' }}" 
                   data-status="pending"
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">
                   <i class="ri-time-line me-1 text-warning"></i> Pending
                   <span class="badge badge-count-pending {{ $currentStatus === 'pending' ? 'bg-dark text-warning' : 'bg-warning text-dark' }} ms-1 rounded-pill">{{ $statusCounts['pending'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link status-tab-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'processing' ? 'active bg-info text-white shadow-sm' : 'bg-light text-dark' }}" 
                   data-status="processing"
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'processing'])) }}">
                   <i class="ri-loader-4-line me-1 text-info"></i> Processing
                   <span class="badge badge-count-processing {{ $currentStatus === 'processing' ? 'bg-white text-info' : 'bg-info text-white' }} ms-1 rounded-pill">{{ $statusCounts['processing'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link status-tab-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'dispatched' ? 'active bg-primary text-white shadow-sm' : 'bg-light text-dark' }}" 
                   data-status="dispatched"
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'dispatched'])) }}">
                   <i class="ri-truck-line me-1 text-primary"></i> Dispatched
                   <span class="badge badge-count-dispatched {{ $currentStatus === 'dispatched' ? 'bg-white text-primary' : 'bg-primary text-white' }} ms-1 rounded-pill">{{ $statusCounts['dispatched'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link status-tab-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'delivered' ? 'active bg-success text-white shadow-sm' : 'bg-light text-dark' }}" 
                   data-status="delivered"
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'delivered'])) }}">
                   <i class="ri-checkbox-circle-line me-1 text-success"></i> Delivered
                   <span class="badge badge-count-delivered {{ $currentStatus === 'delivered' ? 'bg-white text-success' : 'bg-success text-white' }} ms-1 rounded-pill">{{ $statusCounts['delivered'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link status-tab-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'cancelled' ? 'active bg-danger text-white shadow-sm' : 'bg-light text-dark' }}" 
                   data-status="cancelled"
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'cancelled'])) }}">
                   <i class="ri-close-circle-line me-1 text-danger"></i> Cancelled
                   <span class="badge badge-count-cancelled {{ $currentStatus === 'cancelled' ? 'bg-white text-danger' : 'bg-danger text-white' }} ms-1 rounded-pill">{{ $statusCounts['cancelled'] ?? 0 }}</span>
                </a>
            </li>
        </ul>

        <!-- Day-Wise & Custom Date Range Filter Bar -->
        <form method="GET" action="{{ route('admin.orders.index') }}" id="ordersFilterForm" class="mb-4 bg-light p-3 rounded border shadow-sm">
            <input type="hidden" name="status" id="inputStatusFilter" value="{{ request('status', '') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3 col-lg-2">
                    <label class="form-label small fw-bold mb-1"><i class="ri-calendar-event-line text-primary me-1"></i> Date Filter</label>
                    <select name="date_filter" id="dateFilterSelect" class="form-select form-select-sm fw-semibold" onchange="toggleCustomDateInputs(this.value)">
                        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>📅 Today (Daily Default)</option>
                        <option value="yesterday" {{ $dateFilter === 'yesterday' ? 'selected' : '' }}>⏮️ Yesterday</option>
                        <option value="this_week" {{ $dateFilter === 'this_week' ? 'selected' : '' }}>📆 This Week</option>
                        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>🗓️ This Month</option>
                        <option value="custom" {{ $dateFilter === 'custom' ? 'selected' : '' }}>🎯 Custom Date Range</option>
                        <option value="all" {{ $dateFilter === 'all' ? 'selected' : '' }}>🌐 All Time</option>
                    </select>
                </div>

                <div class="col-md-4 col-lg-3 {{ $dateFilter === 'custom' ? '' : 'd-none' }}" id="customDateInputs">
                    <label class="form-label small fw-bold mb-1"><i class="ri-calendar-2-line me-1"></i> Custom Range</label>
                    <div class="input-group input-group-sm">
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control" placeholder="From">
                        <span class="input-group-text">to</span>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control" placeholder="To">
                    </div>
                </div>

                <div class="col-md-3 col-lg-2">
                    <label class="form-label small fw-bold mb-1"><i class="ri-store-2-line me-1"></i> Order Type</label>
                    <select name="order_type" class="form-select form-select-sm">
                        <option value="">All Types (Online & POS)</option>
                        <option value="online" {{ request('order_type') === 'online' ? 'selected' : '' }}>🌐 Online Website</option>
                        <option value="pos" {{ request('order_type') === 'pos' ? 'selected' : '' }}>🏪 Walk-In POS</option>
                    </select>
                </div>

                <div class="col-md-4 col-lg-3">
                    <label class="form-label small fw-bold mb-1"><i class="ri-search-2-line me-1"></i> Search Order</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Order #, Name, Mobile..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary fw-bold w-100"><i class="ri-filter-line me-1"></i> Filter</button>
                    @if(request('search') || request('order_type') || request('status') || request('date_filter') !== 'today' || request('date_from') || request('date_to'))
                        <a href="{{ route('admin.orders.index', ['date_filter' => 'today']) }}" class="btn btn-sm btn-outline-secondary reset-filter-btn" title="Reset Filters"><i class="ri-refresh-line"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Dynamic AJAX Table & Summary Metric Container -->
        <div id="ordersTableContainer" class="position-relative">
            @include('admin.orders.partials.orders_table')
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
function toggleCustomDateInputs(val) {
    let customBox = document.getElementById('customDateInputs');
    if (customBox) {
        if (val === 'custom') {
            customBox.classList.remove('d-none');
        } else {
            customBox.classList.add('d-none');
        }
    }
}

function loadOrdersAjax(url) {
    const container = document.getElementById('ordersTableContainer');
    if (container) {
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data && data.html) {
            if (container) {
                container.innerHTML = data.html;
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
            if (data.statusCounts) {
                updateTabBadgeCounts(data.statusCounts);
            }
            window.history.pushState(null, '', url);
            bindAjaxEvents();
        } else {
            window.location.href = url;
        }
    })
    .catch(err => {
        console.error('AJAX error:', err);
        window.location.href = url;
    });
}

function updateTabBadgeCounts(counts) {
    for (const key in counts) {
        let badge = document.querySelector('.badge-count-' + key);
        if (badge) {
            badge.innerText = counts[key];
        }
    }
}

function bindAjaxEvents() {
    // Intercept pagination clicks inside #ordersTableContainer
    document.querySelectorAll('#ordersTableContainer .pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadOrdersAjax(this.getAttribute('href'));
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Intercept status tab clicks
    document.querySelectorAll('.status-tab-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');
            const targetStatus = this.getAttribute('data-status');

            document.querySelectorAll('.status-tab-link').forEach(t => {
                t.classList.remove('active', 'bg-primary', 'bg-warning', 'bg-info', 'bg-success', 'bg-danger', 'text-white', 'text-dark', 'shadow-sm');
                t.classList.add('bg-light', 'text-dark');
            });
            this.classList.remove('bg-light', 'text-dark');
            this.classList.add('active', 'shadow-sm');

            if (targetStatus === 'pending') {
                this.classList.add('bg-warning', 'text-dark');
            } else if (targetStatus === 'processing') {
                this.classList.add('bg-info', 'text-white');
            } else if (targetStatus === 'dispatched') {
                this.classList.add('bg-primary', 'text-white');
            } else if (targetStatus === 'delivered') {
                this.classList.add('bg-success', 'text-white');
            } else if (targetStatus === 'cancelled') {
                this.classList.add('bg-danger', 'text-white');
            } else {
                this.classList.add('bg-primary', 'text-white');
            }

            const inputStatus = document.getElementById('inputStatusFilter');
            if (inputStatus) inputStatus.value = targetStatus;

            loadOrdersAjax(targetUrl);
        });
    });

    // Intercept filter form submit
    const filterForm = document.getElementById('ordersFilterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            const targetUrl = this.getAttribute('action') + '?' + params;
            loadOrdersAjax(targetUrl);
        });
    }

    // Intercept filter reset button
    const resetBtn = document.querySelector('.reset-filter-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadOrdersAjax(this.getAttribute('href'));
        });
    }

    bindAjaxEvents();
});
</script>
@endsection
