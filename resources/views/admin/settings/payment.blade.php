@extends('layouts/contentNavbarLayout')

@section('title', 'Bank & Payment Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="ri-bank-line me-2 text-warning"></i> Store Bank & Payment Gateway Settings</h5>
                <span class="badge bg-label-primary">Materio Admin Settings</span>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#tabBanks">
                            <i class="ri-bank-card-line me-1 text-success"></i> Multiple Bank Accounts
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#tabPayment">
                            <i class="ri-qr-code-line me-1 text-warning"></i> Payment Toggles & GST
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#tabContact">
                            <i class="ri-customer-service-2-line me-1 text-info"></i> Contact & Support
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#tabPolicies">
                            <i class="ri-file-shield-2-line me-1 text-primary"></i> Legal Policies
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-0">
                    <!-- TAB 1: MULTIPLE BANK ACCOUNTS -->
                    <div class="tab-pane fade show active" id="tabBanks">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-success mb-0"><i class="ri-bank-line me-1"></i> Manage Store Bank Accounts</h6>
                            <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addBankModal">
                                <i class="ri-add-line me-1"></i> Add New Bank Account
                            </button>
                        </div>

                        <div class="table-responsive text-nowrap border rounded mb-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Bank Name</th>
                                        <th>Account Holder</th>
                                        <th>A/C Number</th>
                                        <th>IFSC Code</th>
                                        <th>Branch Name</th>
                                        <th>Enable / Disable Switch</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bankAccounts as $bank)
                                        <tr>
                                            <td><strong>{{ $bank->bank_name }}</strong></td>
                                            <td>{{ $bank->account_holder }}</td>
                                            <td><code class="text-primary fw-bold">{{ $bank->account_number }}</code></td>
                                            <td><span class="badge bg-label-info">{{ $bank->ifsc_code }}</span></td>
                                            <td>{{ $bank->branch_name ?: 'N/A' }}</td>
                                            <td>
                                                <form action="{{ route('admin.payment-settings.bank.toggle', $bank->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input" type="checkbox" onchange="this.form.submit()" {{ $bank->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editBankModal{{ $bank->id }}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('admin.payment-settings.bank.destroy', $bank->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this bank account?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Edit Bank Modal -->
                                        <div class="modal fade" id="editBankModal{{ $bank->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Bank Account Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.payment-settings.bank.update', $bank->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Bank Name *</label>
                                                                <input type="text" name="bank_name" class="form-control" value="{{ $bank->bank_name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Account Holder Name *</label>
                                                                <input type="text" name="account_holder" class="form-control" value="{{ $bank->account_holder }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Account Number *</label>
                                                                <input type="text" name="account_number" class="form-control" value="{{ $bank->account_number }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">IFSC Code *</label>
                                                                <input type="text" name="ifsc_code" class="form-control" value="{{ $bank->ifsc_code }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Branch Name (Optional)</label>
                                                                <input type="text" name="branch_name" class="form-control" value="{{ $bank->branch_name }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success fw-bold">Update Bank Account</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No bank accounts added yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: PAYMENT TOGGLES & GST -->
                    <div class="tab-pane fade" id="tabPayment">
                        <form action="{{ route('admin.payment-settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border p-3">
                                        <h6 class="fw-bold text-primary mb-3"><i class="ri-percent-line me-1"></i> GST Configuration</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Store GST Percentage (%) *</label>
                                            <input type="number" step="0.01" name="gst_percentage" class="form-control" value="{{ old('gst_percentage', $settings->gst_percentage) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h6 class="fw-bold text-success mb-0"><i class="ri-hand-coin-line me-1"></i> Cash On Delivery (COD)</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="enable_cod" id="enable_cod" {{ $settings->enable_cod ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border p-3 mt-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h6 class="fw-bold text-warning mb-0"><i class="ri-bank-line me-1"></i> Enable Bank Transfer Option</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="enable_bank_transfer" id="enable_bank_transfer" {{ $settings->enable_bank_transfer ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="card border p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h6 class="fw-bold text-info mb-0"><i class="ri-qr-code-line me-1"></i> UPI & QR Code Payment</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="enable_upi" id="enable_upi" {{ $settings->enable_upi ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Store UPI ID</label>
                                                <input type="text" name="upi_id" class="form-control" value="{{ old('upi_id', $settings->upi_id) }}" placeholder="e.g. crackers@upi">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Upload Payment QR Code</label>
                                                <input type="file" name="upi_qr_code" class="form-control">
                                                @if($settings->upi_qr_code)
                                                    <div class="mt-2">
                                                        <img src="{{ $settings->upi_qr_code }}" alt="Current QR Code" class="img-thumbnail" style="max-width: 120px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold px-4">Save Payment & GST Toggles</button>
                        </form>
                    </div>

                    <!-- TAB 3: CONTACT & SUPPORT -->
                    <div class="tab-pane fade" id="tabContact">
                        <form action="{{ route('admin.payment-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="gst_percentage" value="{{ $settings->gst_percentage }}">
                            @if($settings->enable_cod) <input type="hidden" name="enable_cod" value="1"> @endif
                            @if($settings->enable_upi) <input type="hidden" name="enable_upi" value="1"> @endif
                            @if($settings->enable_bank_transfer) <input type="hidden" name="enable_bank_transfer" value="1"> @endif

                             <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Company Legal Name *</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $settings->company_name) }}" placeholder="e.g. S.R. TRADERS">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Company Slogan</label>
                                    <input type="text" name="company_slogan" class="form-control" value="{{ old('company_slogan', $settings->company_slogan) }}" placeholder="e.g. Lighting Up Your Celebrations!">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Explosives License Number</label>
                                    <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $settings->license_number) }}" placeholder="e.g. LE/5/1234/2026">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Support Phone Number *</label>
                                    <input type="text" name="support_phone" class="form-control" value="{{ old('support_phone', $settings->support_phone) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Support Email Address *</label>
                                    <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $settings->support_email) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Operating Support Hours</label>
                                    <input type="text" name="support_hours" class="form-control" value="{{ old('support_hours', $settings->support_hours) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Physical Store Address</label>
                                    <textarea name="support_address" class="form-control" rows="3">{{ old('support_address', $settings->support_address) }}</textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-danger"><i class="ri-scales-3-line me-1"></i> Supreme Court 2018 Order Legal Disclaimer Notice</label>
                                    <textarea name="supreme_court_disclaimer" class="form-control" rows="4" placeholder="Supreme Court order statutory disclaimer...">{{ old('supreme_court_disclaimer', $settings->supreme_court_disclaimer) }}</textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-primary"><i class="ri-map-pin-2-line me-1"></i> Google Map Embed URL / Location Iframe Src</label>
                                    <textarea name="google_map_embed" class="form-control" rows="2" placeholder="https://www.google.com/maps/embed?pb=...">{{ old('google_map_embed', $settings->google_map_embed) }}</textarea>
                                    <small class="text-muted">Enter full Google Maps Embed URL or iframe `src` link for dynamic store map display in storefront footer.</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info fw-bold px-4">Save Support Contact Info & Disclaimer</button>
                        </form>
                    </div>

                    <!-- TAB 4: LEGAL POLICIES -->
                    <div class="tab-pane fade" id="tabPolicies">
                        <form action="{{ route('admin.payment-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="gst_percentage" value="{{ $settings->gst_percentage }}">
                            @if($settings->enable_cod) <input type="hidden" name="enable_cod" value="1"> @endif
                            @if($settings->enable_upi) <input type="hidden" name="enable_upi" value="1"> @endif
                            @if($settings->enable_bank_transfer) <input type="hidden" name="enable_bank_transfer" value="1"> @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary"><i class="ri-file-text-line me-1"></i> Terms & Conditions</label>
                                <textarea name="terms_and_conditions" class="form-control" rows="6">{{ old('terms_and_conditions', $settings->terms_and_conditions) }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-info"><i class="ri-shield-user-line me-1"></i> Privacy Policy</label>
                                <textarea name="privacy_policy" class="form-control" rows="6">{{ old('privacy_policy', $settings->privacy_policy) }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-warning"><i class="ri-truck-line me-1"></i> Shipping & Return Policy</label>
                                <textarea name="shipping_policy" class="form-control" rows="6">{{ old('shipping_policy', $settings->shipping_policy) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold px-4">Save Legal Policies</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-bank-line text-success me-1"></i> Add New Store Bank Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payment-settings.bank.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bank Name *</label>
                        <input type="text" name="bank_name" class="form-control" placeholder="e.g. HDFC Bank" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Holder Name *</label>
                        <input type="text" name="account_holder" class="form-control" placeholder="e.g. Crackers Traders Pvt Ltd" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number *</label>
                        <input type="text" name="account_number" class="form-control" placeholder="e.g. 501002345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IFSC Code *</label>
                        <input type="text" name="ifsc_code" class="form-control" placeholder="e.g. HDFC0001234" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Name (Optional)</label>
                        <input type="text" name="branch_name" class="form-control" placeholder="e.g. Main Branch, Sivakasi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold">Save Bank Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
