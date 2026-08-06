@extends('layouts/layoutMaster')

@section('title', 'Crackers Sales & Revenue Report')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1 text-primary"><i class="ri-money-rupee-circle-line me-2"></i>Crackers Revenue & Sales Report</h4>
        <p class="text-muted mb-0">Detailed breakdown of sales revenue, GST collected, and order payment performance.</p>
      </div>
      <div>
        <a href="{{ route('reports-revenue-export', request()->query()) }}" class="btn btn-success">
          <i class="ri-file-excel-2-line me-1"></i> Export Sales CSV
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success p-3 d-flex align-items-center justify-content-center">
              <i class="ri-money-rupee-circle-line fs-3"></i>
            </span>
          </div>
          <span class="text-muted small fw-semibold text-uppercase">Total Sales Revenue</span>
        </div>
        <h3 class="mb-0 fw-bold text-success">₹{{ number_format($overallTotalRevenue, 2) }}</h3>
        <small class="text-muted">Gross sales revenue</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-primary p-3 d-flex align-items-center justify-content-center">
              <i class="ri-shopping-bag-3-line fs-3"></i>
            </span>
          </div>
          <span class="text-muted small fw-semibold text-uppercase">Total Orders</span>
        </div>
        <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalOrdersCount) }}</h3>
        <small class="text-muted">Total orders placed</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning p-3 d-flex align-items-center justify-content-center">
              <i class="ri-percent-line fs-3"></i>
            </span>
          </div>
          <span class="text-muted small fw-semibold text-uppercase">GST Collected</span>
        </div>
        <h3 class="mb-0 fw-bold text-warning">₹{{ number_format($totalGstAmount, 2) }}</h3>
        <small class="text-muted">Total GST revenue</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info p-3 d-flex align-items-center justify-content-center">
              <i class="ri-wallet-3-line fs-3"></i>
            </span>
          </div>
          <span class="text-muted small fw-semibold text-uppercase">Subtotal</span>
        </div>
        <h3 class="mb-0 fw-bold text-info">₹{{ number_format($totalSubtotal, 2) }}</h3>
        <small class="text-muted">Before GST calculation</small>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-header border-bottom py-3">
    <form method="GET" action="{{ route('reports-revenue') }}">
      <div class="row g-3 align-items-center">
        <div class="col-md-4">
          <input type="text" name="search" class="form-control" placeholder="Search by Order #, Name, Phone..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <select name="payment_status" class="form-select">
            <option value="all">All Payment Statuses</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
          </select>
        </div>
        <div class="col-md-3">
          <div class="input-group">
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            <span class="input-group-text">to</span>
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
          </div>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
          <a href="{{ route('reports-revenue') }}" class="btn btn-outline-secondary"><i class="ri-refresh-line"></i></a>
        </div>
      </div>
    </form>
  </div>

  <div class="card-body pt-3">
    @include('admin.revenue.table')
  </div>
</div>
@endsection
