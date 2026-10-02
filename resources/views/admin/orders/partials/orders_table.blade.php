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
                            @php
                                $payBadgeClass = match($order->payment_status) {
                                    'paid' => 'bg-success text-white',
                                    'customer_paid', 'unverified_paid' => 'text-white',
                                    'failed', 'refunded' => 'bg-danger text-white',
                                    default => 'bg-warning text-dark',
                                };
                                $payBadgeStyle = match($order->payment_status) {
                                    'customer_paid', 'unverified_paid' => 'background-color: #fd7e14 !important;',
                                    default => '',
                                };
                                $payBadgeIcon = match($order->payment_status) {
                                    'paid' => 'ri-checkbox-circle-fill',
                                    'customer_paid', 'unverified_paid' => 'ri-time-fill',
                                    'failed', 'refunded' => 'ri-close-circle-line',
                                    default => 'ri-time-line',
                                };
                                $payBadgeText = match($order->payment_status) {
                                    'paid' => 'Paid & Verified',
                                    'customer_paid', 'unverified_paid' => 'Customer Paid (Unverified)',
                                    'failed' => 'Failed',
                                    'refunded' => 'Refunded',
                                    default => 'Pending',
                                };
                            @endphp
                            <span class="badge {{ $payBadgeClass }} fw-bold px-2 py-1 rounded-pill" style="{{ $payBadgeStyle }}">
                                <i class="{{ $payBadgeIcon }} me-1"></i>
                                {{ $payBadgeText }}
                            </span>
                        </div>
                        <small class="text-muted"><i class="ri-bank-card-line me-1"></i>{{ $order->payment_method }}</small>
                        @if($order->bankAccount)
                            <div class="mt-1">
                                <span class="badge bg-label-primary text-primary fw-bold px-2 py-1 rounded-pill" title="Bank Account ID: #{{ $order->bank_account_id }}">
                                    <i class="ri-bank-line me-1"></i>{{ $order->bankAccount->bank_name }} (A/C: {{ $order->bankAccount->account_number }})
                                </span>
                            </div>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusBadge = match($order->status) {
                                'pending' => 'bg-warning text-dark',
                                'processing' => 'bg-info text-white',
                                'dispatched' => 'bg-primary text-white',
                                'delivered' => 'bg-success text-white',
                                'cancelled' => 'bg-danger text-white',
                                'quotation' => 'bg-primary text-white',
                                default => 'bg-secondary text-white',
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
                                        @if($order->bankAccount)
                                            <div class="mt-2 pt-2 border-top">
                                                <strong class="text-primary"><i class="ri-bank-line me-1"></i>Selected Bank Account:</strong>
                                                <span class="fw-bold text-dark">{{ $order->bankAccount->bank_name }}</span> 
                                                <span class="badge bg-primary ms-1">ID: #{{ $order->bank_account_id }}</span><br>
                                                <small class="text-muted">Holder: {{ $order->bankAccount->account_holder }} | A/C: <code>{{ $order->bankAccount->account_number }}</code> | IFSC: {{ $order->bankAccount->ifsc_code }}</small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Payment Status *</label>
                                        <select name="payment_status" class="form-select" required>
                                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>✅ Paid & Verified (Admin Confirmed - Green)</option>
                                            <option value="customer_paid" {{ ($order->payment_status === 'customer_paid' || $order->payment_status === 'unverified_paid') ? 'selected' : '' }}>🟠 Customer Paid (Pending Admin Verification - Orange)</option>
                                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>⏳ Pending / Unpaid (Yellow)</option>
                                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>❌ Failed / Rejected (Red)</option>
                                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>↩️ Refunded (Red)</option>
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

                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><i class="ri-bank-line text-primary me-1"></i>Collected / Receiving Bank Account</label>
                                        <select name="bank_account_id" class="form-select">
                                            <option value="">-- No Bank Selected / Cash --</option>
                                            @foreach($activeBanks as $bank)
                                                <option value="{{ $bank->id }}" {{ $order->bank_account_id == $bank->id ? 'selected' : '' }}>
                                                    [ID #{{ $bank->id }}] {{ $bank->bank_name }} - {{ $bank->account_holder }} (A/C: {{ $bank->account_number }})
                                                </option>
                                            @endforeach
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
                    <td colspan="9" class="text-center py-4 text-muted">No cracker orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 ajax-pagination-container">
    {{ $orders->links() }}
</div>
