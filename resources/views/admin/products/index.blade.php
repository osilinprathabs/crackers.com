@extends('layouts/layoutMaster')

@section('title', 'Manage Crackers Products')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="ri-sparkling-fill me-2 text-warning"></i>Crackers Products</h5>
        <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="openAddProductModal()">
            <i class="ri-add-line me-1"></i> Add New Product
        </button>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by Product Name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Retail Price (₹)</th>
                        <th>Wholesale Price (₹)</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($products as $product)
                        @php
                            $prodJson = [
                                'id' => $product->id,
                                'name' => $product->name,
                                'category' => $product->category,
                                'unit' => $product->unit,
                                'price' => floatval($product->price),
                                'discount_price' => $product->discount_price ? floatval($product->discount_price) : null,
                                'wholesale_price' => $product->wholesale_price ? floatval($product->wholesale_price) : null,
                                'wholesale_min_qty' => $product->wholesale_min_qty ? intval($product->wholesale_min_qty) : null,
                                'stock' => intval($product->stock),
                                'low_stock_threshold' => intval($product->low_stock_threshold ?: 10),
                                'description' => $product->description ?: '',
                                'is_featured' => $product->is_featured ? 1 : 0,
                                'status' => $product->status ? 1 : 0,
                                'images' => $product->images ?: []
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="rounded" width="45" height="45" style="object-fit: cover;">
                                    @elseif(is_array($product->images) && count($product->images) > 0)
                                        <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}" class="rounded" width="45" height="45" style="object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-md bg-label-warning rounded d-flex align-items-center justify-content-center">
                                            <i class="ri-sparkling-line fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <div class="small text-muted">{{ $product->unit }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">{{ $product->category }}</span></td>
                            <td>
                                <div class="fw-bold text-success fs-6">₹{{ number_format($product->discount_price ?: $product->price, 2) }}</div>
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <small class="text-muted text-decoration-line-through d-block">MRP: ₹{{ number_format($product->price, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($product->wholesale_price)
                                    <span class="badge bg-warning text-dark font-monospace fs-6 px-2 py-1"><i class="ri-store-3-line me-1"></i>₹{{ number_format($product->wholesale_price, 2) }}</span>
                                    @if($product->wholesale_min_qty)
                                        <small class="text-dark d-block fw-bold mt-1"><i class="ri-shopping-basket-line me-1"></i>Min Qty: {{ $product->wholesale_min_qty }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $product->stock > 10 ? 'bg-label-success' : 'bg-label-danger' }}">{{ $product->stock }}</span></td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input product-status-toggle" type="checkbox" data-id="{{ $product->id }}" data-name="{{ $product->name }}" {{ $product->status ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <!-- Edit Icon Button -->
                                    <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" onclick='openEditProductModal(@json($prodJson))' title="Edit Product">
                                        <i class="icon-base ri ri-edit-box-line icon-22px text-primary"></i>
                                    </button>
                                    
                                    <!-- Delete Icon Button -->
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete product \'{{ addslashes($product->name) }}\'?');" class="d-inline mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill" title="Delete">
                                            <i class="icon-base ri ri-delete-bin-7-line icon-22px text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Single Reusable Ultra-Compact Non-Scrollable Theme Product Modal (Add & Edit) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="w-100 mb-0">
            @csrf
            <input type="hidden" name="_method" id="productFormMethod" value="POST">

            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                <!-- Theme Primary Header -->
                <div class="modal-header bg-primary text-white py-2 px-3">
                    <h6 class="modal-title fw-bold text-white mb-0 d-flex align-items-center gap-2">
                        <i id="modalTitleIcon" class="ri-add-circle-fill fs-5 text-warning"></i>
                        <span id="modalTitleText">Add New Crackers Product</span>
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3" style="background-color: #f4f5f9;">
                    <div class="row g-3">
                        <!-- Left Column: Details, Stock & Gallery -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-3 rounded-3 h-100 bg-white">
                                <h6 class="fw-bold text-primary mb-2 border-bottom pb-1 small">
                                    <i class="ri-information-line me-1"></i> Product Identification & Stock
                                </h6>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold mb-1">Product Name *</label>
                                    <input type="text" name="name" id="prodName" class="form-control form-control-sm" required placeholder="e.g. 10cm Electric Sparklers">
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Category *</label>
                                        <select name="category" id="prodCategory" class="form-select form-select-sm" required>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}">{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Unit / Packaging *</label>
                                        <input type="text" name="unit" id="prodUnit" class="form-control form-control-sm" required placeholder="e.g. Box (10 Pcs)">
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Initial Stock *</label>
                                        <input type="number" name="stock" id="prodStock" class="form-control form-control-sm" required value="100">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Low Stock Limit *</label>
                                        <input type="number" name="low_stock_threshold" id="prodLowStock" class="form-control form-control-sm" required value="10" placeholder="Default: 10">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold mb-1">Upload Product Photos (Up to 4 Images)</label>
                                    <input type="file" name="images[]" class="form-control form-control-sm" accept="image/*" multiple>
                                    <div id="currentPhotosContainer" class="mt-2" style="display: none;">
                                        <span class="small text-muted me-1">Current Photos:</span>
                                        <div id="currentPhotosList" class="d-inline-flex gap-1 flex-wrap align-items-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Pricing, Wholesale & Description -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-3 rounded-3 h-100 bg-white">
                                <h6 class="fw-bold text-primary mb-2 border-bottom pb-1 small">
                                    <i class="ri-price-tag-3-line me-1"></i> Pricing & Storefront Settings
                                </h6>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">MRP Price (₹) *</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" name="price" id="prodPrice" class="form-control form-control-sm" required placeholder="e.g. 250">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold text-success mb-1">Retail Offer Price (₹)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-success bg-opacity-10 text-success fw-bold">₹</span>
                                            <input type="number" step="0.01" name="discount_price" id="prodDiscountPrice" class="form-control form-control-sm border-success" placeholder="e.g. 180">
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold text-warning mb-1">Wholesale Price (₹)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-warning bg-opacity-10 text-dark fw-bold">₹</span>
                                            <input type="number" step="0.01" name="wholesale_price" id="prodWholesalePrice" class="form-control form-control-sm border-warning" placeholder="e.g. 120">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold text-dark mb-1"><i class="ri-shopping-basket-line text-warning me-1"></i> Wholesale Min Qty</label>
                                        <input type="number" name="wholesale_min_qty" id="prodWholesaleMinQty" class="form-control form-control-sm border-warning" placeholder="e.g. 10">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold mb-1">Product Description</label>
                                    <textarea name="description" id="prodDescription" class="form-control form-control-sm" rows="2" placeholder="Brief details about quality or effects..."></textarea>
                                </div>
                                <div class="d-flex align-items-center gap-3 pt-2 mt-auto border-top">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="prodFeatured" checked>
                                        <label class="form-check-label small fw-semibold" for="prodFeatured">🌟 Featured</label>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="status" value="1" id="prodStatus" checked>
                                        <label class="form-check-label small fw-semibold text-success" for="prodStatus">✅ Active on Website</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compact Footer -->
                <div class="modal-footer bg-white border-top py-2 px-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="ri-save-3-line me-1"></i> <span id="submitBtnText">Save Product</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-script')
<script>
function openAddProductModal() {
    document.getElementById('productForm').action = "{{ route('admin.products.store') }}";
    document.getElementById('productFormMethod').value = "POST";
    document.getElementById('modalTitleText').innerText = "Add New Crackers Product";
    document.getElementById('modalTitleIcon').className = "ri-add-circle-fill fs-5 text-warning";
    document.getElementById('submitBtnText').innerText = "Save Product";
    
    document.getElementById('productForm').reset();
    document.getElementById('currentPhotosContainer').style.display = 'none';

    let modalEl = document.getElementById('productModal');
    let bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();
}

function openEditProductModal(product) {
    let updateUrl = "{{ route('admin.products.update', ':id') }}".replace(':id', product.id);
    document.getElementById('productForm').action = updateUrl;
    document.getElementById('productFormMethod').value = "PUT";
    document.getElementById('modalTitleText').innerText = "Edit Product: " + product.name;
    document.getElementById('modalTitleIcon').className = "ri-edit-box-line fs-5 text-warning";
    document.getElementById('submitBtnText').innerText = "Update Product";

    document.getElementById('prodName').value = product.name || '';
    document.getElementById('prodCategory').value = product.category || '';
    document.getElementById('prodUnit').value = product.unit || '';
    document.getElementById('prodStock').value = product.stock || 0;
    document.getElementById('prodLowStock').value = product.low_stock_threshold || 10;
    document.getElementById('prodPrice').value = product.price || '';
    document.getElementById('prodDiscountPrice').value = product.discount_price || '';
    document.getElementById('prodWholesalePrice').value = product.wholesale_price || '';
    document.getElementById('prodWholesaleMinQty').value = product.wholesale_min_qty || '';
    document.getElementById('prodDescription').value = product.description || '';
    document.getElementById('prodFeatured').checked = Boolean(product.is_featured);
    document.getElementById('prodStatus').checked = Boolean(product.status);

    let container = document.getElementById('currentPhotosContainer');
    let list = document.getElementById('currentPhotosList');
    if (product.images && product.images.length > 0) {
        list.innerHTML = product.images.map(img => `<img src="/${img.replace(/^\//, '')}" class="rounded border p-1" width="36" height="36" style="object-fit: cover;">`).join('');
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }

    let modalEl = document.getElementById('productModal');
    let bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.product-status-toggle').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const productId = this.getAttribute('data-id');
            const isChecked = this.checked;

            fetch(`/admin/products/${productId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'center'
                        });
                    }
                } else {
                    checkbox.checked = !isChecked;
                }
            })
            .catch(err => {
                checkbox.checked = !isChecked;
                console.error(err);
            });
        });
    });
});
</script>
@endsection
