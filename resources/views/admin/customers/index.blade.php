@extends('layouts/layoutMaster')

@section('title', 'Customer Management')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="ri-user-star-line me-2 text-primary"></i>Customer List</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
            <i class="ri-add-line me-1"></i> Add New Customer
        </button>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="Search by Code, Name, Phone, or Email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary"><i class="ri-refresh-line"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Customer Type</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Order Type</th>
                        <th>Address</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($customers as $customer)
                        @php
                            $addr = is_array($customer->billing_address) ? ($customer->billing_address['address'] ?? '') : (is_string($customer->billing_address) ? $customer->billing_address : '');
                            $displayAddr = \Illuminate\Support\Str::limit($addr, 30) ?: '—';

                            $custOrders = $customer->crackersOrders;
                            if ($custOrders->isEmpty() && $customer->contact_person_mobile) {
                                $custOrders = \App\Models\CrackersOrder::where('customer_phone', $customer->contact_person_mobile)->get();
                            }
                            $hasPos = $custOrders->contains(fn($o) => $o->is_pos);
                            $hasOnline = $custOrders->contains(fn($o) => !$o->is_pos);
                        @endphp
                        <tr>
                            <td><span class="badge bg-label-primary font-monospace">{{ $customer->customer_code }}</span></td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-body fw-bold">
                                    {{ $customer->company_name ?: $customer->contact_person_name }}
                                </a>
                            </td>
                            <td>
                                @if($customer->customer_type === 'wholesale')
                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill"><i class="ri-store-3-line me-1"></i> Wholesale</span>
                                @else
                                    <span class="badge bg-label-info text-info fw-bold px-2 py-1 rounded-pill"><i class="ri-user-heart-line me-1"></i> Retail</span>
                                @endif
                            </td>
                            <td>{{ $customer->contact_person_mobile ?: '—' }}</td>
                            <td>{{ $customer->contact_person_email ?: '—' }}</td>
                            <td>
                                @if($hasPos && $hasOnline)
                                    <span class="badge bg-primary text-white px-2 py-1 rounded-pill">
                                        <i class="ri-global-line me-1"></i> Online + <i class="ri-store-2-line ms-1 me-1"></i> POS
                                    </span>
                                @elseif($hasPos)
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-bold">
                                        <i class="ri-store-2-line me-1"></i> Walk-In POS
                                    </span>
                                @elseif($hasOnline)
                                    <span class="badge bg-info text-white px-2 py-1 rounded-pill fw-bold">
                                        <i class="ri-global-line me-1"></i> Online Website
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td><span title="{{ $addr }}">{{ $displayAddr }}</span></td>
                            <td>{{ $customer->created_at ? $customer->created_at->format('d M Y') : '—' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <!-- View Customer Profile & Orders -->
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-icon btn-text-info rounded-pill" title="View Profile & Orders">
                                        <i class="ri-eye-line fs-5"></i>
                                    </a>

                                    <!-- Login As Customer (Impersonate) -->
                                    <form action="{{ route('admin.customers.login-as', $customer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-warning rounded-pill" title="Login as Customer">
                                            <i class="ri-user-shared-line fs-5"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Customer -->
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill" title="Delete Customer">
                                            <i class="ri-delete-bin-7-line fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>

<!-- Modal to create customer -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name / Business Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Rahul Sharma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile Number *</label>
                        <input type="text" name="phone" class="form-control" required placeholder="e.g. 9876543210">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. rahul@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">GST / Tax Number</label>
                        <input type="text" name="tax_number" class="form-control" placeholder="Optional">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Full delivery address"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
