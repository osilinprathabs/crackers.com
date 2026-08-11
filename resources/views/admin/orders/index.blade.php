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
        <ul class="nav nav-pills card-header-pills mb-4 gap-2 flex-wrap" role="tablist">
            @php
                $currentStatus = request('status', '');
            @endphp

            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === '' ? 'active bg-primary text-white shadow-sm' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), [])) }}">
                   <i class="ri-list-check-2 me-1"></i> All Orders
                   <span class="badge {{ $currentStatus === '' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1 rounded-pill">{{ $statusCounts['all'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'pending' ? 'active bg-warning text-dark shadow-sm' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">
                   <i class="ri-time-line me-1 text-warning"></i> Pending
                   <span class="badge {{ $currentStatus === 'pending' ? 'bg-dark text-warning' : 'bg-warning text-dark' }} ms-1 rounded-pill">{{ $statusCounts['pending'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'processing' ? 'active bg-info text-white shadow-sm' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'processing'])) }}">
                   <i class="ri-loader-4-line me-1 text-info"></i> Processing
                   <span class="badge {{ $currentStatus === 'processing' ? 'bg-white text-info' : 'bg-info text-white' }} ms-1 rounded-pill">{{ $statusCounts['processing'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'dispatched' ? 'active bg-primary text-white shadow-sm' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'dispatched'])) }}">
                   <i class="ri-truck-line me-1 text-primary"></i> Dispatched
                   <span class="badge {{ $currentStatus === 'dispatched' ? 'bg-white text-primary' : 'bg-primary text-white' }} ms-1 rounded-pill">{{ $statusCounts['dispatched'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'delivered' ? 'active bg-success text-white shadow-sm' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'delivered'])) }}">
                   <i class="ri-checkbox-circle-line me-1 text-success"></i> Delivered
                   <span class="badge {{ $currentStatus === 'delivered' ? 'bg-white text-success' : 'bg-success text-white' }} ms-1 rounded-pill">{{ $statusCounts['delivered'] ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 fw-semibold {{ $currentStatus === 'cancelled' ? 'active bg-danger text-white shadow-sm' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'cancelled'])) }}">
                   <i class="ri-close-circle-line me-1 text-danger"></i> Cancelled
                   <span class="badge {{ $currentStatus === 'cancelled' ? 'bg-white text-danger' : 'bg-danger text-white' }} ms-1 rounded-pill">{{ $statusCounts['cancelled'] ?? 0 }}</span>
                </a>
            </li>
        </ul>

        <!-- Day-Wise & Custom Date Range Filter Bar -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 bg-light p-3 rounded border shadow-sm">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
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
                        <a href="{{ route('admin.orders.index', ['date_filter' => 'today']) }}" class="btn btn-sm btn-outline-secondary" title="Reset Filters"><i class="ri-refresh-line"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Summary Metric Card Banner -->
        <div class="alert bg-primary bg-opacity-10 border border-primary-subtle text-primary p-3 rounded-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-calendar-check-line fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-primary">
                        @if($dateFilter === 'today')
                            📅 Today's Day-Wise Orders ({{ now()->format('d M Y') }})
                        @elseif($dateFilter === 'yesterday')
                            ⏮️ Yesterday's Day-Wise Orders ({{ \Carbon\Carbon::yesterday()->format('d M Y') }})
                        @elseif($dateFilter === 'this_week')
                            📆 This Week's Day-Wise Orders
                        @elseif($dateFilter === 'this_month')
                            🗓️ This Month's Day-Wise Orders ({{ now()->format('F Y') }})
                        @elseif($dateFilter === 'custom')
                            🎯 Custom Date Range Orders ({{ $dateFrom ?: 'Start' }} to {{ $dateTo ?: 'End' }})
                        @else
                            🌐 All Time Orders
                        @endif
                    </h6>
                    <small class="text-muted">Filtered results for selected date period</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="text-end">
                    <small class="text-muted d-block fw-semibold">Filtered Orders</small>
                    <span class="fs-5 fw-bold text-dark">{{ $statusCounts['all'] ?? 0 }} Orders</span>
                </div>
                <div class="text-end border-start ps-4">
                    <small class="text-muted d-block fw-semibold">Period Total Sales</small>
                    <span class="fs-4 fw-bold text-success font-monospace">₹{{ number_format($totalPeriodRevenue, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Order Type</th>
                        <th>Customer Details</th>
                        <th>Items Count</th>
                        <th>Grand Total</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><span class="fw-bold font-monospace text-primary">{{ $order->order_number }}</span></td>
                            <td>
                                @if($order->is_pos)
                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill">
                                        <i class="ri-store-2-line me-1"></i> Walk-In POS
                                    </span>
                                @else
                                    <span class="badge bg-info text-white fw-bold px-2 py-1 rounded-pill">
                                        <i class="ri-global-line me-1"></i> Online Website
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div><strong>{{ $order->customer_name }}</strong></div>
                                <small class="text-muted"><i class="ri-phone-line"></i> {{ $order->customer_phone }}</small>
                            </td>
                            <td><span class="badge bg-label-info">{{ $order->items->count() }} Items</span></td>
                            <td><strong class="text-success fs-6">₹{{ number_format($order->grand_total, 2) }}</strong></td>
                            <td>
                                <div>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success text-white' : 'bg-warning text-dark' }} fw-bold">
                                        <i class="{{ $order->payment_status === 'paid' ? 'ri-checkbox-circle-line' : 'ri-time-line' }} me-1"></i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <small class="text-muted"><i class="ri-bank-card-line me-1"></i>{{ $order->payment_method }}</small>
                            </td>
                            <td>
                                @php
                                    $statusBadge = match($order->status) {
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'dispatched' => 'bg-primary',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '—' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    @if($order->is_pos)
                                        <a href="{{ route('admin.pos.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-icon btn-text-warning rounded-pill" title="Print POS Receipt">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                    @endif

                                    <!-- Collect Payment Button -->
                                    <button type="button" class="btn btn-sm {{ $order->payment_status === 'paid' ? 'btn-outline-success' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#collectPaymentModal{{ $order->id }}">
                                        <i class="ri-hand-coin-line me-1"></i> {{ $order->payment_status === 'paid' ? 'Paid' : 'Collect Payment' }}
                                    </button>

                                    <!-- Status / Actions Dropdown -->
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line fs-5 text-muted"></i></button>
                                        <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                            @if($order->status === 'pending')
                                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit" class="dropdown-item"><i class="ri-refresh-line me-1 text-info"></i> Mark Processing</button>
                                                </form>
                                            @endif

                                            @if($order->status === 'pending' || $order->status === 'processing')
                                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="dispatched">
                                                    <button type="submit" class="dropdown-item"><i class="ri-truck-line me-1 text-primary"></i> Mark Dispatched</button>
                                                </form>
                                            @endif

                                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="delivered">
                                                    <button type="submit" class="dropdown-item"><i class="ri-checkbox-circle-line me-1 text-success"></i> Mark Delivered</button>
                                                </form>
                                            @endif

                                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item text-warning"><i class="ri-close-circle-line me-1"></i> Mark Cancelled</button>
                                                </form>
                                            @endif

                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="ri-delete-bin-line me-1"></i> Delete Order</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Collect Payment Modal -->
                        <div class="modal fade" id="collectPaymentModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-success"><i class="ri-hand-coin-line me-1"></i> Collect Payment for Order #{{ $order->order_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="alert alert-info py-2 small mb-3">
                                                <strong>Customer:</strong> {{ $order->customer_name }} ({{ $order->customer_phone }})<br>
                                                <strong>Amount to Collect:</strong> <span class="fw-bold text-success fs-6">₹{{ number_format($order->grand_total, 2) }}</span>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Payment Status *</label>
                                                <select name="payment_status" class="form-select" required>
                                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Payment Received)</option>
                                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Payment Method Used</label>
                                                <select name="payment_method" class="form-select">
                                                    <option value="COD" {{ $order->payment_method === 'COD' ? 'selected' : '' }}>Cash On Delivery (COD Cash)</option>
                                                    <option value="UPI" {{ $order->payment_method === 'UPI' ? 'selected' : '' }}>UPI / GPay / PhonePe</option>
                                                    <option value="Bank Transfer" {{ $order->payment_method === 'Bank Transfer' ? 'selected' : '' }}>Direct Bank Transfer</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success fw-bold">
                                                <i class="ri-check-double-line me-1"></i> Update Payment Status
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No cracker orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
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
</script>
@endsection
