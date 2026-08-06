@extends('layouts/layoutMaster')

@section('title', 'Crackers Inventory Management')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Total Products</span>
                    <h3 class="card-title mb-0 fw-bold">{{ number_format($totalProducts) }}</h3>
                </div>
                <div class="avatar avatar-md bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-box-3-line fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Total Stock Units</span>
                    <h3 class="card-title mb-0 fw-bold text-success">{{ number_format($totalUnits) }}</h3>
                </div>
                <div class="avatar avatar-md bg-label-success rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-stack-line fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Low Stock Alert</span>
                    <h3 class="card-title mb-0 fw-bold text-warning">{{ number_format($lowStockCount) }}</h3>
                </div>
                <div class="avatar avatar-md bg-label-warning rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-error-warning-line fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Out of Stock</span>
                    <h3 class="card-title mb-0 fw-bold text-danger">{{ number_format($outOfStockCount) }}</h3>
                </div>
                <div class="avatar avatar-md bg-label-danger rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-close-circle-line fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center justify-content-between border-bottom pb-3">
        <h5 class="mb-0 fw-bold"><i class="ri-archive-stack-line text-primary me-2"></i>Crackers Inventory Stock Control</h5>
        <span class="badge bg-label-secondary"><i class="ri-refresh-line me-1"></i> Real-time Stock Sync</span>
    </div>
    <div class="card-body pt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search by Product Name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stock_status" class="form-select">
                        <option value="">All Stock Statuses</option>
                        <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (> 10)</option>
                        <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock (1 - 10)</option>
                        <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-filter-line me-1"></i> Filter</button>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="ri-refresh-line"></i></a>
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Product Details</th>
                        <th>Category</th>
                        <th>Price (₹)</th>
                        <th>Stock Level</th>
                        <th>Status Badge</th>
                        <th>Quick Update</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="rounded shadow-sm" width="48" height="48" style="object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-md bg-label-warning rounded d-flex align-items-center justify-content-center">
                                            <i class="ri-sparkling-fill fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong class="d-block text-dark">{{ $product->name }}</strong>
                                        <small class="text-muted"><i class="ri-price-tag-line me-1"></i>Unit: {{ $product->unit }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info fw-semibold">{{ $product->category }}</span>
                            </td>
                            <td>
                                @if($product->discount_price)
                                    <div><strong class="text-success">₹{{ number_format($product->discount_price, 2) }}</strong></div>
                                    <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                                @else
                                    <strong>₹{{ number_format($product->price, 2) }}</strong>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold fs-6" id="stock-val-{{ $product->id }}">{{ number_format($product->stock) }}</span>
                                <small class="text-muted d-block">{{ $product->unit }}s</small>
                            </td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge bg-label-success px-3 py-2 rounded-pill"><i class="ri-checkbox-circle-line me-1"></i> In Stock</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-label-warning px-3 py-2 rounded-pill"><i class="ri-alert-line me-1"></i> Low Stock</span>
                                @else
                                    <span class="badge bg-label-danger px-3 py-2 rounded-pill"><i class="ri-close-circle-line me-1"></i> Out of Stock</span>
                                @endif
                            </td>
                            <td style="min-width: 170px;">
                                <div class="input-group input-group-sm">
                                    <input type="number" min="0" class="form-control" id="input-stock-{{ $product->id }}" value="{{ $product->stock }}">
                                    <button class="btn btn-outline-primary btn-quick-update" data-id="{{ $product->id }}" title="Quick Save">
                                        <i class="ri-save-line"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#adjustStockModal-{{ $product->id }}">
                                    <i class="ri-edit-box-line me-1"></i> Adjust
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="viewInventoryLogs({{ $product->id }}, '{{ addslashes($product->name) }}')">
                                    <i class="ri-history-line me-1"></i> Logs
                                </button>
                            </td>
                        </tr>

                        <!-- Adjust Stock Modal -->
                        <div class="modal fade" id="adjustStockModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('admin.inventory.adjust', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold"><i class="ri-stack-line text-primary me-2"></i>Stock Adjustment: {{ $product->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="bg-light p-3 rounded mb-3">
                                                <div class="row text-center">
                                                    <div class="col-6 border-end">
                                                        <small class="text-muted d-block">Current Stock</small>
                                                        <span class="fs-4 fw-bold text-primary">{{ $product->stock }} {{ $product->unit }}s</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Category</small>
                                                        <span class="fw-semibold">{{ $product->category }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Adjustment Action</label>
                                                <select name="adjustment_type" class="form-select" required>
                                                    <option value="add">Add Stock (+) (New Stock Arrived)</option>
                                                    <option value="subtract">Reduce Stock (-) (Damaged / Lost / Outward)</option>
                                                    <option value="set">Set Exact Stock Count (=)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Quantity / Units</label>
                                                <input type="number" name="quantity" class="form-control" min="0" placeholder="e.g. 50" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Reason / Notes</label>
                                                <input type="text" name="notes" class="form-control" placeholder="e.g. Received new shipment batch #402">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary"><i class="ri-check-line me-1"></i> Apply Stock Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-inbox-line fs-1 d-block mb-2 text-secondary"></i>
                                    <h5>No products found matching inventory criteria.</h5>
                                    <p class="mb-0">Try clearing your search or status filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Stock Logs Modal -->
<div class="modal fade" id="inventoryLogsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="logsModalTitle"><i class="ri-history-line text-info me-2"></i>Stock Adjustment Audit History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Old Stock</th>
                                <th>Qty Changed</th>
                                <th>New Stock</th>
                                <th>Notes / Reason</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">Loading logs...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick stock update via AJAX
    document.querySelectorAll('.btn-quick-update').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const stockInput = document.getElementById('input-stock-' + productId);
            const newStock = stockInput.value;

            if (newStock === '' || newStock < 0) {
                alert('Please enter a valid stock quantity.');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            fetch(`/admin/inventory/${productId}/quick-update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ stock: newStock })
            })
            .then(res => res.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = '<i class="ri-check-line text-success"></i>';

                if (data.success) {
                    const stockVal = document.getElementById('stock-val-' + productId);
                    if (stockVal) stockVal.innerText = Number(data.new_stock).toLocaleString();
                    setTimeout(() => { button.innerHTML = '<i class="ri-save-line"></i>'; }, 2000);
                } else {
                    alert(data.message || 'Failed to update stock.');
                    button.innerHTML = '<i class="ri-save-line"></i>';
                }
            })
            .catch(err => {
                console.error(err);
                button.disabled = false;
                button.innerHTML = '<i class="ri-save-line"></i>';
                alert('An error occurred while saving stock.');
            });
        });
    });
});

function viewInventoryLogs(productId, productName) {
    document.getElementById('logsModalTitle').innerHTML = `<i class="ri-history-line text-info me-2"></i>Stock Logs: ${productName}`;
    const tbody = document.getElementById('logsTableBody');
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Loading audit logs...</td></tr>`;

    const logsModal = new bootstrap.Modal(document.getElementById('inventoryLogsModal'));
    logsModal.show();

    fetch(`/admin/inventory/${productId}/logs`)
    .then(res => res.json())
    .then(data => {
        if (data.success && data.logs.length > 0) {
            tbody.innerHTML = data.logs.map(log => {
                let badgeClass = 'bg-label-secondary';
                if (log.type === 'addition') badgeClass = 'bg-label-success';
                else if (log.type === 'subtraction' || log.type === 'order_deduction') badgeClass = 'bg-label-danger';
                else badgeClass = 'bg-label-info';

                const dateStr = new Date(log.created_at).toLocaleString();

                return `
                    <tr>
                        <td><small>${dateStr}</small></td>
                        <td><span class="badge ${badgeClass} text-uppercase">${log.type.replace('_', ' ')}</span></td>
                        <td>${log.old_stock}</td>
                        <td><strong>${log.quantity}</strong></td>
                        <td><strong class="text-primary">${log.new_stock}</strong></td>
                        <td><small>${log.notes || '-'}</small></td>
                        <td><small class="fw-semibold">${log.created_by || 'Admin'}</small></td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">No stock adjustment logs recorded yet for this product.</td></tr>`;
        }
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Failed to fetch inventory logs.</td></tr>`;
    });
}

// Auto open adjust stock modal if redirected from low stock alert popup
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const openAdjustId = urlParams.get('open_adjust');
    if (openAdjustId) {
        let modalEl = document.getElementById(`adjustStockModal-${openAdjustId}`);
        if (modalEl && typeof bootstrap !== 'undefined') {
            setTimeout(function() {
                new bootstrap.Modal(modalEl).show();
            }, 350);
        } else {
            let inputEl = document.getElementById(`input-stock-${openAdjustId}`);
            if (inputEl) {
                inputEl.focus();
                inputEl.select();
            }
        }
    }
});
</script>
@endsection
