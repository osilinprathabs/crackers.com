@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <div>
      <h4 class="mb-1 fw-bold">Crackers Store Administration</h4>
      <p class="text-muted mb-0">System overview, management & financial summary</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center" id="refreshStatsBtn" onclick="location.reload();">
      <i class="icon-base ri ri-refresh-line me-1"></i>
      <span>Refresh Data</span>
    </button>
  </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <!-- Total Staff -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-primary">
              <i class="icon-base ri ri-team-line icon-24px"></i>
            </span>
          </div>
          <div>
            <h4 class="mb-0 fw-bold">{{ $totalStaff ?? 0 }}</h4>
            <small class="text-muted">Staff Members</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">Active Staff Management</h6>
        <a href="{{ route('admin.staff.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <!-- Total System Users -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-info">
              <i class="icon-base ri ri-user-settings-line icon-24px"></i>
            </span>
          </div>
          <div>
            <h4 class="mb-0 fw-bold">{{ $totalUsers ?? 0 }}</h4>
            <small class="text-muted">Registered Users</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">User Access Control</h6>
        <a href="{{ route('user-management') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <!-- Total Revenues -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-success">
              <i class="icon-base ri ri-arrow-up-circle-line icon-24px"></i>
            </span>
          </div>
          <div>
            <h4 class="mb-0 fw-bold">₹{{ number_format($totalRevenues ?? 0, 2) }}</h4>
            <small class="text-muted">Total Revenues</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">Accounting Revenues</h6>
        <a href="{{ route('account.revenues.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <!-- Total Expenses -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-warning">
              <i class="icon-base ri ri-arrow-down-circle-line icon-24px"></i>
            </span>
          </div>
          <div>
            <h4 class="mb-0 fw-bold">₹{{ number_format($totalExpenses ?? 0, 2) }}</h4>
            <small class="text-muted">Total Expenses</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">Accounting Expenses</h6>
        <a href="{{ route('account.expenses.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>
</div>

<!-- Quick Navigation Banner -->
<div class="card border-0 text-white bg-dark mb-4 overflow-hidden">
  <div class="card-body p-4 position-relative">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h4 class="text-white mb-1 fw-bold">🎆 Welcome to Crackers Store Admin</h4>
        <p class="text-white-50 mb-0">Use the sidebar menu to manage Accounting, Staff, System Settings, and Website Configurations.</p>
      </div>
      <div>
        <a href="{{ route('account.index') }}" class="btn btn-primary btn-md px-4">
          <i class="ri-book-2-line me-1"></i> Accounting Dashboard
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
