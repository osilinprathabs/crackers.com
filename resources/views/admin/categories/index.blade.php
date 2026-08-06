@extends('layouts/contentNavbarLayout')

@section('title', 'Crackers Category Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="ri-price-tag-3-line text-warning me-2"></i> Crackers Categories</h5>
                <button type="button" class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="ri-add-line me-1"></i> Add New Category
                </button>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Icon</th>
                                <th>Category Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td>
                                        @if(!empty($cat->icon))
                                            <span class="avatar avatar-sm rounded-circle bg-label-primary d-inline-flex align-items-center justify-content-center me-2">
                                                <i class="{{ $cat->icon }} fs-5"></i>
                                            </span>
                                        @else
                                            @php $firstLetter = strtoupper(substr($cat->name, 0, 1)); @endphp
                                            <span class="avatar avatar-sm rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-6 shadow-sm me-2">
                                                {{ $firstLetter }}
                                            </span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $cat->name }}</strong></td>
                                    <td><code class="text-muted">{{ $cat->slug }}</code></td>
                                    <td>
                                        <form action="{{ route('admin.categories.toggle-status', $cat->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" onchange="this.form.submit()" {{ $cat->status ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ $cat->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#editCatModal{{ $cat->id }}" title="Edit">
                                                <i class="icon-base ri ri-edit-box-line icon-22px text-primary"></i>
                                            </button>
                                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill" title="Delete">
                                                    <i class="icon-base ri ri-delete-bin-7-line icon-22px text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="editCatModal{{ $cat->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.categories.update', $cat->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Category Name *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Category Icon Class (Optional)</label>
                                                        <input type="text" name="icon" class="form-control" value="{{ $cat->icon }}" placeholder="e.g. ri-sparkling-fill (Leave blank for First Letter icon)">
                                                        <small class="text-muted">Optional. Leave blank to display first letter avatar as category icon.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning fw-bold">Update Category</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No categories created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-add-circle-line me-1 text-warning"></i> Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Multi Sky Shots" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Icon Class (Optional)</label>
                        <input type="text" name="icon" class="form-control" placeholder="e.g. ri-rocket-line (Leave blank for First Letter icon)">
                        <small class="text-muted">Optional. If left blank, the first letter of category name (e.g. "M" for Multi Sky Shots) will be used as icon!</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
