@extends('layouts/layoutMaster')

@section('title', 'Customer Profile & History - ' . ($customer->contact_person_name ?: $customer->company_name))

@section('content')
<div class="container-fluid p-0">

    <!-- Header & Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 border-bottom pb-3">
        <div>
            <h4 class="fw-bold mb-1">
                <span class="text-muted fw-light">Dashboard / <a href="{{ route('admin.customers.index') }}">Customers</a> /</span> 
                {{ $customer->contact_person_name ?: $customer->company_name }}
            </h4>
            <span class="badge bg-label-primary font-monospace fs-6"><i class="ri-user-star-line me-1"></i>{{ $customer->customer_code }}</span>
        </div>

        <div class="d-flex gap-2">
            <!-- Login as Customer Button -->
            <form action="{{ route('admin.customers.login-as', $customer->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning rounded-pill px-3 fw-bold shadow-sm" title="Log in as customer to view storefront on their behalf">
                    <i class="ri-user-shared-line me-1"></i> Login As Customer
                </button>
            </form>

            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="ri-arrow-left-line me-1"></i> Back to Customers
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-white-50 text-uppercase fw-bold">Total Spent</small>
                        <h3 class="mb-0 text-white fw-bold">₹{{ number_format($totalSpent, 2) }}</h3>
                    </div>
                    <div class="avatar avatar-md bg-white text-success rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-wallet-3-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-white-50 text-uppercase fw-bold">Total Orders</small>
                        <h3 class="mb-0 text-white fw-bold">{{ $totalOrdersCount }}</h3>
                    </div>
                    <div class="avatar avatar-md bg-white text-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-shopping-bag-3-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-white-50 text-uppercase fw-bold">Online Store Orders</small>
                        <h3 class="mb-0 text-white fw-bold">{{ $onlineOrdersCount }}</h3>
                    </div>
                    <div class="avatar avatar-md bg-white text-info rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-global-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-dark-50 text-uppercase fw-bold">Offline POS Orders</small>
                        <h3 class="mb-0 text-dark fw-bold">{{ $posOrdersCount }}</h3>
                    </div>
                    <div class="avatar avatar-md bg-dark text-warning rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-store-2-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Pane: Customer Details Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="ri-user-line text-primary me-2"></i> Customer Profile Details
                    </h5>
                </div>
                <div class="card-body py-4">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl bg-label-primary mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="ri-user-smile-line display-5"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $customer->contact_person_name ?: $customer->company_name }}</h5>
                        <span class="badge bg-label-primary font-monospace">{{ $customer->customer_code }}</span>
                    </div>

                    <div class="border-top pt-3">
                        <div class="mb-3">
                            <small class="text-muted d-block fw-semibold"><i class="ri-phone-line me-1"></i> Mobile Phone</small>
                            <span class="fw-bold text-dark fs-6">{{ $customer->contact_person_mobile ?: 'N/A' }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block fw-semibold"><i class="ri-mail-line me-1"></i> Email Address</small>
                            <span class="fw-bold text-dark">{{ $customer->contact_person_email ?: 'N/A' }}</span>
                        </div>

                        @php
                            $addr = is_array($customer->billing_address) ? ($customer->billing_address['address'] ?? '') : (is_string($customer->billing_address) ? $customer->billing_address : '');
                            $city = is_array($customer->billing_address) ? ($customer->billing_address['city'] ?? '') : '';
                            $pincode = is_array($customer->billing_address) ? ($customer->billing_address['pincode'] ?? '') : '';
                            $fullAddr = trim($addr . ' ' . $city . ' ' . $pincode);
                        @endphp

                        <div class="mb-3">
                            <small class="text-muted d-block fw-semibold"><i class="ri-map-pin-line me-1"></i> Street Address</small>
                            <span class="text-dark">{{ $fullAddr ?: 'No address saved.' }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block fw-semibold"><i class="ri-calendar-line me-1"></i> Registered On</small>
                            <span class="text-dark">{{ $customer->created_at ? $customer->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Pane: Order History Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="ri-history-line text-primary me-2"></i> Order & Sales History ({{ $orders->count() }})
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ORDER #</th>
                                    <th>SOURCE</th>
                                    <th>DATE</th>
                                    <th>PAYMENT</th>
                                    <th>GRAND TOTAL</th>
                                    <th>STATUS</th>
                                    <th class="text-end">RECEIPT / INVOICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-bold font-monospace text-primary">{{ $order->order_number }}</span>
                                        </td>
                                        <td>
                                            @if($order->is_pos)
                                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                                    <i class="ri-store-2-line me-1"></i> Offline POS
                                                </span>
                                            @else
                                                <span class="badge bg-info text-white px-2 py-1 rounded-pill">
                                                    <i class="ri-global-line me-1"></i> Online Store
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-dark">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '—' }}</small>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold text-uppercase small">{{ $order->payment_method }}</span>
                                                @if($order->payment_status === 'paid')
                                                    <span class="badge bg-label-success ms-1">Paid</span>
                                                @else
                                                    <span class="badge bg-label-warning ms-1">{{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">₹{{ number_format($order->grand_total, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($order->status === 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($order->is_pos)
                                                <a href="{{ route('admin.pos.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill">
                                                    <i class="ri-printer-line me-1"></i> POS Receipt
                                                </a>
                                            @else
                                                <a href="{{ route('admin.orders.index') }}?search={{ $order->order_number }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                    <i class="ri-file-list-3-line me-1"></i> View Order
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="ri-shopping-cart-2-line fs-1 d-block mb-2 opacity-50"></i>
                                            No order history found for this customer.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
