@extends('layouts/layoutMaster')

@section('title', __('Account Dashboard'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-1">{{ __('Accounting dashboard') }}</h4>
      <p class="text-muted mb-0">{{ __('Overview from integrated ERP-style accounting module') }}</p>
    </div>
  </div>

 <div class="row g-3 mb-4">
  <div class="col-12">
    <div class="card bg-lighter border-0 shadow-none">
      <div class="card-body py-3">
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <span class="fw-medium me-1 text-primary"><i class="ri-flashlight-line me-1"></i> {{ __('Quick Actions') }}:</span>
          
          <!-- 1. Green: Add Revenue -->
          <button type="button" class="btn btn-sm btn-label-success" data-bs-toggle="modal" data-bs-target="#addRevenueDraftModal">
            <i class="ri-add-circle-line me-1"></i> {{ __('Add New Revenue') }}
          </button>

          <!-- 2. Purple: Add Revenue Category -->
          <button type="button" class="btn btn-sm btn-label-secondary" style="color: #6610f2; background-color: #ebe3ff; border-color: transparent;" data-bs-toggle="modal" data-bs-target="#addRevenueCategoryModal">
            <i class="ri-bookmark-2-line me-1"></i> {{ __('Add Revenue Category') }}
          </button>

          <!-- 3. Cyan: View Revenues -->
          <a href="{{ route('account.revenues.index') }}" class="btn btn-sm btn-label-info">
            <i class="ri-arrow-up-circle-line me-1"></i> {{ __('View All Revenues') }}
          </a>

          <!-- 4. Red: Add Expense -->
          <button type="button" class="btn btn-sm btn-label-danger" data-bs-toggle="modal" data-bs-target="#addExpenseDraftModal">
            <i class="ri-add-circle-line me-1"></i> {{ __('Add New Expense') }}
          </button>

          <!-- 5. Pink: Add Expense Category -->
          <button type="button" class="btn btn-sm btn-label-secondary" style="color: #e83e8c; background-color: #ffe5f0; border-color: transparent;" data-bs-toggle="modal" data-bs-target="#addExpenseCategoryModal">
            <i class="ri-bookmark-2-line me-1"></i> {{ __('Add Expense Category') }}
          </button>

          <!-- 6. Warning: View Expenses -->
          <a href="{{ route('account.expenses.index') }}" class="btn btn-sm btn-label-warning">
            <i class="ri-arrow-down-circle-line me-1"></i> {{ __('View All Expenses') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</div>


  <!-- Crackers ERP Store Financial Metrics -->
  <div class="row g-4 mb-4">
    <div class="col-12">
      <h5 class="mb-0 fw-bold text-dark"><i class="ri-fire-fill text-warning me-2 fs-4"></i>Crackers.com Integrated ERP Sales & Financial Summary</h5>
    </div>
    
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm bg-primary text-white h-100">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <span class="d-block text-white-50 small fw-bold text-uppercase">Total Fireworks Sales</span>
            <h3 class="mb-0 text-white fw-bold">₹{{ number_format((float) ($crackersErp['total_sales'] ?? 0), 2) }}</h3>
            <small class="text-white-50"><i class="ri-shopping-bag-3-line me-1"></i>{{ $crackersErp['total_orders'] ?? 0 }} Total Orders</small>
          </div>
          <div class="avatar avatar-md bg-white text-primary rounded-circle d-flex align-items-center justify-content-center">
            <i class="ri-fire-line fs-3"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm bg-success text-white h-100">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <span class="d-block text-white-50 small fw-bold text-uppercase">Online Store Sales</span>
            <h3 class="mb-0 text-white fw-bold">₹{{ number_format((float) ($crackersErp['online_sales'] ?? 0), 2) }}</h3>
            <small class="text-white-50"><i class="ri-global-line me-1"></i>Website Cart Checkout</small>
          </div>
          <div class="avatar avatar-md bg-white text-success rounded-circle d-flex align-items-center justify-content-center">
            <i class="ri-global-line fs-3"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm bg-warning text-dark h-100">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <span class="d-block text-dark-50 small fw-bold text-uppercase">Walk-In POS Sales</span>
            <h3 class="mb-0 text-dark fw-bold">₹{{ number_format((float) ($crackersErp['pos_sales'] ?? 0), 2) }}</h3>
            <small class="text-dark-50"><i class="ri-store-2-line me-1"></i>Counter POS Register</small>
          </div>
          <div class="avatar avatar-md bg-dark text-warning rounded-circle d-flex align-items-center justify-content-center">
            <i class="ri-store-2-line fs-3"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm bg-info text-white h-100">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <span class="d-block text-white-50 small fw-bold text-uppercase">GST Tax Collected</span>
            <h3 class="mb-0 text-white fw-bold">₹{{ number_format((float) ($crackersErp['total_gst'] ?? 0), 2) }}</h3>
            <small class="text-white-50"><i class="ri-bank-card-line me-1"></i>Tax Liability Collected</small>
          </div>
          <div class="avatar avatar-md bg-white text-info rounded-circle d-flex align-items-center justify-content-center">
            <i class="ri-government-line fs-3"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 border shadow-sm">
        <div class="card-body">
          <span class="d-block mb-1 text-muted fw-semibold"><i class="ri-user-line me-1"></i> Registered ERP Customers</span>
          <h3 class="mb-1 text-dark fw-bold">{{ number_format($stats['total_clients'] ?? 0) }}</h3>
          <div class="d-flex gap-2 mt-2">
            <span class="badge bg-label-info"><i class="ri-user-heart-line me-1"></i>Retail: {{ $crackersErp['retail_customers'] ?? 0 }}</span>
            <span class="badge bg-label-warning text-dark"><i class="ri-store-3-line me-1"></i>Wholesale: {{ $crackersErp['wholesale_customers'] ?? 0 }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 border shadow-sm">
        <div class="card-body">
          <span class="d-block mb-1 text-muted fw-semibold"><i class="ri-box-3-line me-1"></i> Inventory Asset Valuation</span>
          <h3 class="mb-1 text-success fw-bold">₹{{ number_format((float) ($crackersErp['inventory_valuation'] ?? 0), 2) }}</h3>
          <small class="text-muted">Total Stock × Product MRP</small>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 border shadow-sm">
        <div class="card-body">
          <span class="d-block mb-1 text-muted fw-semibold"><i class="ri-money-dollar-circle-line me-1"></i> Total Gross Revenue</span>
          <h3 class="mb-1 text-primary fw-bold">₹{{ number_format((float) ($stats['total_revenue'] ?? 0), 2) }}</h3>
          <small class="text-muted">GL Ledger + Crackers Sales</small>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 border shadow-sm">
        <div class="card-body">
          <span class="d-block mb-1 text-muted fw-semibold"><i class="ri-shopping-cart-2-line me-1"></i> Total Ledger Expenses</span>
          <h3 class="mb-1 text-danger fw-bold">₹{{ number_format((float) ($stats['total_expense'] ?? 0), 2) }}</h3>
          <small class="text-muted">Posted Operating Expenses</small>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4 border-info">
    <div class="card-header"><strong>{{ __('Posted (GL)') }}</strong></div>
    <div class="card-body row g-3">
      <div class="col-md-4">
        <span class="text-muted d-block">{{ __('Posted revenue') }}</span>
        <h5 class="mb-0">₹{{ number_format((float) ($stats['total_revenue_posted'] ?? 0), 2) }}</h5>
      </div>
      <div class="col-md-4">
        <span class="text-muted d-block">{{ __('Posted expense') }}</span>
        <h5 class="mb-0">₹{{ number_format((float) ($stats['total_expense_posted'] ?? 0), 2) }}</h5>
      </div>
      <div class="col-md-4">
        <span class="text-muted d-block">{{ __('Net (posted only)') }}</span>
        <h5 class="mb-0 {{ ($stats['net_profit_posted'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
          ₹{{ number_format((float) ($stats['net_profit_posted'] ?? 0), 2) }}
        </h5>
      </div>
    </div>
    <div class="card-footer small text-muted">{{ __('Totals above include draft + approved + posted; posted row shows only items with status Posted.') }}</div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header"><strong>{{ __('Customer payments (total)') }}</strong></div>
        <div class="card-body">
          <h4 class="mb-0">₹{{ number_format((float) ($stats['total_customer_payment'] ?? 0), 2) }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header"><strong>{{ __('Vendor payments (total)') }}</strong></div>
        <div class="card-body">
          <h4 class="mb-0">₹{{ number_format((float) ($stats['total_vendor_payment'] ?? 0), 2) }}</h4>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><strong>{{ __('Net profit (revenue − expense)') }}</strong></div>
    <div class="card-body">
      <h3 class="mb-0 {{ ($stats['net_profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
        ₹{{ number_format((float) ($stats['net_profit'] ?? 0), 2) }}
      </h3>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header"><strong>{{ __('Recent revenues') }}</strong></div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0">
              <thead><tr><th>#</th><th>{{ __('Title') }}</th><th class="text-end">{{ __('Amount') }}</th></tr></thead>
              <tbody>
                @forelse($recentRevenues as $r)
                  <tr>
                    <td>{{ $r['id'] }}</td>
                    <td>{{ $r['title'] }}</td>
                    <td class="text-end">₹{{ number_format((float) $r['amount'], 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted py-4">{{ __('No data yet') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header"><strong>{{ __('Recent expenses') }}</strong></div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0">
              <thead><tr><th>#</th><th>{{ __('Title') }}</th><th class="text-end">{{ __('Amount') }}</th></tr></thead>
              <tbody>
                @forelse($recentExpenses as $e)
                  <tr>
                    <td>{{ $e['id'] }}</td>
                    <td>{{ $e['title'] }}</td>
                    <td class="text-end">₹{{ number_format((float) $e['amount'], 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted py-4">{{ __('No data yet') }}</td></tr>
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
