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

        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search by Order #, Customer Name or Phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="order_type" class="form-select">
                        <option value="">All Order Types (Online & POS)</option>
                        <option value="online" {{ request('order_type') === 'online' ? 'selected' : '' }}>🌐 Online Website Orders</option>
                        <option value="pos" {{ request('order_type') === 'pos' ? 'selected' : '' }}>🏪 Walk-In POS Orders</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="dispatched" {{ request('status') === 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
                    @if(request('search') || request('order_type') || request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="ri-refresh-line"></i></a>
                    @endif
                </div>
            </div>
        </form>

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
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-label-success' : 'bg-label-warning' }} fw-bold">
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
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                        <div class="dropdown-menu">
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="dropdown-item"><i class="ri-refresh-line me-1 text-info"></i> Mark Processing</button>
                                            </form>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="dispatched">
                                                <button type="submit" class="dropdown-item"><i class="ri-truck-line me-1 text-primary"></i> Mark Dispatched</button>
                                            </form>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="dropdown-item"><i class="ri-checkbox-circle-line me-1 text-success"></i> Mark Delivered</button>
                                            </form>
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
