@extends('layouts/layoutMaster')

@section('title', 'Manage Crackers Products')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="ri-sparkling-fill me-2 text-warning"></i>Crackers Products</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
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
                        <th>Price (₹)</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="rounded" width="45" height="45" style="object-fit: cover;">
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
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <strong class="text-success fs-6">₹{{ number_format($product->discount_price, 2) }}</strong>
                                    <small class="text-muted text-decoration-line-through d-block">MRP: ₹{{ number_format($product->price, 2) }}</small>
                                @else
                                    <strong class="text-dark fs-6">₹{{ number_format($product->price, 2) }}</strong>
                                @endif
                                @if($product->wholesale_price)
                                    <div class="mt-1"><span class="badge bg-label-warning text-dark font-monospace"><i class="ri-store-3-line me-1"></i>WS: ₹{{ number_format($product->wholesale_price, 2) }}</span></div>
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
                                    <!-- Edit Icon Button (Frameless) -->
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}" title="Edit">
                                        <i class="icon-base ri ri-edit-box-line icon-22px text-primary"></i>
                                    </button>
                                    
                                    <!-- Delete Icon Button (Frameless) -->
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

                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Crackers Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Product Name *</label>
                                                <input type="text" name="name" class="form-control" required value="{{ $product->name }}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Category *</label>
                                                    <select name="category" class="form-select" required>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat }}" {{ $product->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Unit *</label>
                                                    <input type="text" name="unit" class="form-control" required value="{{ $product->unit }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="col-md-4 mb-3">
                                                     <label class="form-label">MRP Price (₹) *</label>
                                                     <input type="number" step="0.01" name="price" class="form-control" required value="{{ $product->price }}">
                                                 </div>
                                                 <div class="col-md-4 mb-3">
                                                     <label class="form-label">Retail Offer Price (₹)</label>
                                                     <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ $product->discount_price }}">
                                                 </div>
                                                 <div class="col-md-4 mb-3">
                                                     <label class="form-label">Wholesale Price (₹)</label>
                                                     <input type="number" step="0.01" name="wholesale_price" class="form-control" value="{{ $product->wholesale_price }}" placeholder="Bulk Wholesale">
                                                 </div>
                                             </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Stock Quantity *</label>
                                                    <input type="number" name="stock" class="form-control" required value="{{ $product->stock }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Low Stock Alert Limit *</label>
                                                    <input type="number" name="low_stock_threshold" class="form-control" required value="{{ $product->low_stock_threshold ?: 10 }}" placeholder="Default: 10">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Main Image / URL</label>
                                                <input type="url" name="image_url" class="form-control mb-2" value="{{ $product->image }}">
                                                <input type="file" name="image" class="form-control" accept="image/*">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Upload Additional Images (Up to 4 Photos for Hover Swap)</label>
                                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                                <small class="text-muted d-block mt-1">On hover, storefront cards smoothly cycle through uploaded images.</small>
                                                @if(is_array($product->images) && count($product->images) > 0)
                                                    <div class="d-flex gap-2 mt-2 flex-wrap align-items-center">
                                                        <span class="small fw-bold me-1">Current Photos ({{ count($product->images) }}):</span>
                                                        @foreach($product->images as $imgSrc)
                                                            <img src="{{ asset($imgSrc) }}" class="rounded border p-1" width="45" height="45" style="object-fit: cover;">
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $product->description }}</textarea>
                                            </div>
                                            <div class="d-flex gap-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                                    <label class="form-check-label">Featured Product</label>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ $product->status ? 'checked' : '' }}>
                                                    <label class="form-check-label">Active Status</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Product</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No products found.</td>
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

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Crackers Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. 10cm Electric Sparklers">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unit *</label>
                            <input type="text" name="unit" class="form-control" required placeholder="e.g. Box (10 Pcs)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">MRP Price (₹) *</label>
                            <input type="number" step="0.01" name="price" class="form-control" required placeholder="e.g. 250">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Retail Offer Price (₹)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" placeholder="e.g. 180">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Wholesale Price (₹)</label>
                            <input type="number" step="0.01" name="wholesale_price" class="form-control" placeholder="e.g. 120 (Bulk)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock" class="form-control" required value="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Low Stock Alert Limit *</label>
                            <input type="number" name="low_stock_threshold" class="form-control" required value="10" placeholder="Default: 10">
                        </div>
                    </div>
                    <div class="mb-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Main Product Image / URL</label>
                        <input type="url" name="image_url" class="form-control mb-2" placeholder="https://example.com/image.jpg (Optional URL)">
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Additional Product Images (Up to 4 Photos for Hover Swap)</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Select up to 4 images. On mouse hover, the customer website will smoothly transition between product images!</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Product details..."></textarea>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" checked>
                            <label class="form-check-label">Featured Product</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                            <label class="form-check-label">Active Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.product-status-toggle').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
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
