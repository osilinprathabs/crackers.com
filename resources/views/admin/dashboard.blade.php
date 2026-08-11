@extends('layouts/layoutMaster')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h4 class="mb-1 fw-bold text-primary"><i class="ri-dashboard-3-line me-2"></i>Crackers Store Administration Dashboard</h4>
      <p class="text-muted mb-0">Real-time overview of customers, cracker orders, sales revenue, and inventory</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary d-flex align-items-center" onclick="location.reload();">
        <i class="icon-base ri ri-refresh-line me-1"></i>
        <span>Refresh Data</span>
      </button>
    </div>
  </div>
</div>

<!-- Day-Wise & Custom Date Filter Card -->
<div class="card mb-4 border-0 shadow-sm rounded-3">
  <div class="card-body py-3 bg-white rounded-3">
    <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-center">
      <!-- Quick Period Select Dropdown -->
      <div class="col-lg-5 col-md-12 d-flex align-items-center flex-wrap gap-2">
        <span class="fw-bold text-primary small me-1"><i class="ri-calendar-event-line fs-5 me-1"></i> Date Filter:</span>
        <select name="period" id="dashboardPeriodSelect" class="form-select form-select-sm fw-semibold border-primary-subtle" style="max-width: 190px;" onchange="toggleDashboardCustomDates(this.value)">
          <option value="today" {{ $period === 'today' || $period === 'daily' ? 'selected' : '' }}>📅 Today (Daily)</option>
          <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>⏮️ Yesterday</option>
          <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>📆 This Week</option>
          <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>🗓️ This Month</option>
          <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>📊 This Year</option>
          <option value="custom" {{ $period === 'custom' || (!empty($fromDate) || !empty($toDate)) ? 'selected' : '' }}>🎯 Custom Date Range</option>
          <option value="all" {{ $period === 'all' ? 'selected' : '' }}>🌐 All Time</option>
        </select>
        @if($period !== 'custom' && empty($fromDate) && empty($toDate))
          <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
            <i class="ri-filter-line me-1"></i> Filter
          </button>
        @endif
      </div>

      <!-- Custom From & To Date Range Inputs -->
      <div class="col-lg-7 col-md-12 d-flex align-items-center gap-2 flex-wrap justify-content-lg-end {{ ($period === 'custom' || !empty($fromDate) || !empty($toDate)) ? '' : 'd-none' }}" id="dashboardCustomDates">
        <div class="input-group input-group-sm" style="max-width: 180px;">
          <span class="input-group-text bg-light text-muted fw-semibold">From</span>
          <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
        </div>
        <div class="input-group input-group-sm" style="max-width: 180px;">
          <span class="input-group-text bg-light text-muted fw-semibold">To</span>
          <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold shadow-sm">
          <i class="ri-filter-3-line me-1"></i> Apply Filter
        </button>
        @if(!empty($fromDate) || !empty($toDate) || ($period && $period !== 'monthly'))
          <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-2" title="Reset Filters">
            <i class="ri-refresh-line"></i> Reset
          </a>
        @endif
      </div>
    </form>

    <!-- Active Filter Status Badge -->
    <div class="mt-2 pt-2 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
      <span class="badge bg-label-primary px-3 py-2 rounded-pill font-monospace">
        <i class="ri-time-line me-1"></i> Viewing Data For: 
        @if(!empty($fromDate) || !empty($toDate))
          Custom Date Range ({{ $fromDate ?: 'Beginning' }} to {{ $toDate ?: 'Today' }})
        @elseif($period === 'today' || $period === 'daily')
          Today ({{ now()->format('d M Y') }})
        @elseif($period === 'yesterday')
          Yesterday ({{ \Carbon\Carbon::yesterday()->format('d M Y') }})
        @elseif($period === 'weekly')
          This Week
        @elseif($period === 'yearly')
          This Year ({{ now()->format('Y') }})
        @elseif($period === 'all')
          All Time
        @else
          This Month ({{ now()->format('F Y') }})
        @endif
      </span>
      
      <div class="d-flex align-items-center gap-3 small">
        <span><strong class="text-dark">Filtered Orders:</strong> <span class="badge bg-primary text-white rounded-pill">{{ number_format($filteredOrdersCount) }}</span></span>
        <span><strong class="text-dark">Filtered Sales Revenue:</strong> <span class="badge bg-success text-white rounded-pill">₹{{ number_format($filteredSalesRevenue, 2) }}</span></span>
      </div>
    </div>
  </div>
</div>

<!-- Primary Store Statistics Cards -->
<div class="row g-4 mb-4">
  <!-- Total Customers -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-primary">
              <i class="ri-user-star-line fs-4"></i>
            </span>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ number_format($totalCustomers) }}</h3>
            <small class="text-muted">Total Customers</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">Registered Accounts</h6>
        <a href="{{ route('admin.customers.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <!-- Filtered Orders -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-info">
              <i class="ri-shopping-bag-3-line fs-4"></i>
            </span>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ number_format($filteredOrdersCount) }}</h3>
            <small class="text-muted">Orders ({{ ucfirst($period) }})</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">Overall Total: {{ number_format($totalOrders) }}</h6>
        <a href="{{ route('admin.orders.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <!-- Filtered Sales Revenue -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-success">
              <i class="ri-money-rupee-circle-line fs-4"></i>
            </span>
          </div>
          <div>
            <h3 class="mb-0 fw-bold text-success">₹{{ number_format($filteredSalesRevenue, 2) }}</h3>
            <small class="text-muted">Period Sales Revenue</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">All-Time Revenue: ₹{{ number_format($totalSales, 2) }}</h6>
        <a href="{{ route('admin.orders.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <!-- Total Products -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-3">
            <span class="avatar-initial rounded-3 bg-label-warning">
              <i class="ri-sparkling-fill fs-4"></i>
            </span>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ number_format($totalProducts) }}</h3>
            <small class="text-muted">Cracker Products</small>
          </div>
        </div>
        <h6 class="mb-0 h6 fw-normal text-muted mt-2">Active Categories: {{ $totalCategories }}</h6>
        <a href="{{ route('admin.products.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>
</div>

<!-- Interactive Analytics Charts Row -->
<div class="row g-4 mb-4">
  <!-- 1. Sales & Revenue Trend Chart (8 Columns) -->
  <div class="col-lg-8 col-md-12">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div>
          <h5 class="card-title mb-0 fw-bold"><i class="ri-line-chart-line text-primary me-2"></i>Sales & Revenue Analytics Graph</h5>
          <small class="text-muted">Revenue trends grouped by {{ $period }} filter timeline</small>
        </div>
        <span class="badge bg-label-primary px-3 py-2 text-capitalize"><i class="ri-calendar-event-line me-1"></i> {{ $period }} Mode</span>
      </div>
      <div class="card-body">
        <div id="salesAnalyticsChart" style="min-height: 330px;"></div>
      </div>
    </div>
  </div>

  <!-- 2. Category-Based Sales % Share Chart (4 Columns) -->
  <div class="col-lg-4 col-md-12">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div>
          <h5 class="card-title mb-0 fw-bold"><i class="ri-pie-chart-2-line text-warning me-2"></i>Category Sales Share (%)</h5>
          <small class="text-muted">Sales % breakdown by cracker category</small>
        </div>
      </div>
      <div class="card-body d-flex flex-column justify-content-between pt-2">
        <div id="categorySalesDonutChart" class="mb-3" style="min-height: 200px;"></div>
        
        <div class="category-breakdown-list">
          @php
            $colorClasses = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary'];
          @endphp
          @foreach($categorySalesBreakdown as $idx => $catStat)
            @php $badgeColor = $colorClasses[$idx % count($colorClasses)]; @endphp
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $badgeColor }} p-1 rounded-circle"></span>
                <span class="fw-semibold text-dark small">{{ $catStat['category'] }}</span>
              </div>
              <div class="text-end">
                <strong class="text-dark small">{{ $catStat['percentage'] }}%</strong>
                <small class="text-muted d-block" style="font-size: 0.75rem;">(₹{{ number_format($catStat['total'], 2) }})</small>
              </div>
            </div>
            <div class="progress mb-2" style="height: 5px;">
              <div class="progress-bar {{ $badgeColor }}" role="progressbar" style="width: {{ $catStat['percentage'] }}%;"></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Secondary Metrics -->
<div class="row g-4 mb-4">
  <!-- Pending Orders -->
  <div class="col-sm-6 col-lg-4">
    <div class="card bg-warning-subtle border-warning h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="badge bg-warning text-dark mb-1">Action Required</span>
          <h4 class="fw-bold mb-0">{{ $pendingOrdersCount }} Pending Orders</h4>
          <small class="text-muted">Orders awaiting fulfillment</small>
        </div>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm fw-bold">Process Now</a>
      </div>
    </div>
  </div>

  <!-- Categories -->
  <div class="col-sm-6 col-lg-4">
    <div class="card bg-info-subtle border-info h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="badge bg-info mb-1">Catalog</span>
          <h4 class="fw-bold mb-0">{{ $totalCategories }} Active Categories</h4>
          <small class="text-muted">Sparklers, Pots, Rockets & hampers</small>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-info btn-sm fw-bold">Manage Categories</a>
      </div>
    </div>
  </div>

  <!-- Bank & Payment Settings -->
  <div class="col-sm-6 col-lg-4">
    <div class="card bg-success-subtle border-success h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="badge bg-success mb-1">Store Setup</span>
          <h4 class="fw-bold mb-0">Bank & UPI Gateway</h4>
          <small class="text-muted">GST, UPI QR & Bank accounts</small>
        </div>
        <a href="{{ route('admin.payment-settings.edit') }}" class="btn btn-success btn-sm fw-bold">Payment Settings</a>
      </div>
    </div>
  </div>
</div>

<!-- Recent Orders Table Section (Day-Wise Grouped) -->
<div class="card mb-4 border-0 shadow-sm" id="dayWiseOrdersSection">
  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 bg-white py-3 border-bottom">
    <div class="d-flex align-items-center gap-2">
      <h5 class="mb-0 fw-bold text-primary"><i class="ri-calendar-check-line me-2 text-primary"></i>Day-Wise Cracker Orders</h5>
      <span class="badge bg-label-primary font-monospace">{{ $recentOrdersGrouped->flatten()->count() }} Orders Shown</span>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary fw-bold">View All Orders <i class="ri-arrow-right-line ms-1"></i></a>
  </div>

  <!-- Day-Wise Cracker Orders Card Filter Bar -->
  <div class="card-body bg-light py-2 px-3 border-bottom">
    <form method="GET" action="{{ route('dashboard') }}#dayWiseOrdersSection" class="row g-2 align-items-center">
      @if(request('period')) <input type="hidden" name="period" value="{{ request('period') }}"> @endif
      @if(request('from_date')) <input type="hidden" name="from_date" value="{{ request('from_date') }}"> @endif
      @if(request('to_date')) <input type="hidden" name="to_date" value="{{ request('to_date') }}"> @endif

      <div class="col-md-4 col-lg-3">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white"><i class="ri-search-2-line"></i></span>
          <input type="text" name="order_search" class="form-control" placeholder="Search Order #, Name, Mobile..." value="{{ request('order_search') }}">
        </div>
      </div>

      <div class="col-md-3 col-lg-2">
        <select name="order_type" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Types (Online & POS)</option>
          <option value="online" {{ request('order_type') === 'online' ? 'selected' : '' }}>🌐 Online Orders</option>
          <option value="pos" {{ request('order_type') === 'pos' ? 'selected' : '' }}>🏪 Walk-In POS</option>
        </select>
      </div>

      <div class="col-md-3 col-lg-2">
        <select name="order_status" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Statuses</option>
          <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
          <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
          <option value="dispatched" {{ request('order_status') === 'dispatched' ? 'selected' : '' }}>🚚 Dispatched</option>
          <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
          <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
        </select>
      </div>

      <div class="col-md-2 col-lg-2 d-flex gap-1">
        <button type="submit" class="btn btn-sm btn-primary fw-bold w-100"><i class="ri-filter-3-line me-1"></i> Filter</button>
        @if(request('order_search') || request('order_type') || request('order_status'))
          <a href="{{ route('dashboard', request()->except(['order_search', 'order_type', 'order_status'])) }}#dayWiseOrdersSection" class="btn btn-sm btn-outline-secondary" title="Reset Filters"><i class="ri-refresh-line"></i></a>
        @endif
      </div>
    </form>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th class="ps-3">Order #</th>
          <th>Customer Name</th>
          <th>Phone</th>
          <th>Items</th>
          <th>Grand Total</th>
          <th>Payment</th>
          <th>Status</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentOrdersGrouped as $dateGroup => $ordersInDay)
          <!-- Day Wise Header Row -->
          <tr class="table-light border-top border-bottom border-primary border-opacity-25">
            <td colspan="8" class="py-2">
              <div class="d-flex align-items-center justify-content-between px-2">
                <span class="fw-bold text-primary fs-6">
                  <i class="ri-calendar-fill me-1 text-primary"></i> {{ $dateGroup }}
                </span>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-primary px-3 py-1 font-monospace fw-bold">
                    <i class="ri-shopping-cart-2-line me-1"></i> {{ $ordersInDay->count() }} Orders
                  </span>
                  <span class="badge bg-label-success px-3 py-1 font-monospace fw-bold fs-6">
                    <i class="ri-money-rupee-circle-line me-1"></i> Day Total: ₹{{ number_format($ordersInDay->sum('grand_total'), 2) }}
                  </span>
                </div>
              </div>
            </td>
          </tr>

          <!-- Orders inside this day -->
          @foreach($ordersInDay as $order)
            <tr>
              <td class="ps-3"><strong class="text-primary font-monospace">{{ $order->order_number }}</strong></td>
              <td><strong>{{ $order->customer_name }}</strong></td>
              <td><small class="text-muted"><i class="ri-phone-line"></i> {{ $order->customer_phone }}</small></td>
              <td><span class="badge bg-label-info">{{ $order->items->count() }} Items</span></td>
              <td><strong class="text-success fs-6">₹{{ number_format($order->grand_total, 2) }}</strong></td>
              <td>
                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-label-success' : 'bg-label-warning' }}">
                  {{ ucfirst($order->payment_status) }}
                </span>
              </td>
              <td>
                @php
                  $statusBadge = match($order->status) {
                      'pending' => 'bg-warning',
                      'processing' => 'bg-info',
                      'dispatched' => 'bg-primary',
                      'delivered' => 'bg-success',
                      'cancelled' => 'bg-danger',
                      default => 'bg-secondary',
                  };
                @endphp
                <span class="badge {{ $statusBadge }}">{{ ucfirst($order->status) }}</span>
              </td>
              <td><span class="small font-monospace text-muted">{{ $order->created_at->format('h:i A') }}</span></td>
            </tr>
          @endforeach
        @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">No orders placed yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Sales Trend Graph (Area / Line Chart)
    const salesOptions = {
      series: [{
        name: 'Sales Revenue (₹)',
        data: @json($chartSalesData)
      }, {
        name: 'Orders Count',
        data: @json($chartOrdersData)
      }],
      chart: {
        height: 330,
        type: 'area',
        toolbar: { show: false }
      },
      colors: ['#6610f2', '#0d6efd'],
      stroke: { curve: 'smooth', width: 3 },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.45,
          opacityTo: 0.05,
          stops: [0, 95, 100]
        }
      },
      dataLabels: { enabled: false },
      xaxis: {
        categories: @json($chartLabels),
        labels: { style: { colors: '#64748b', fontSize: '12px' } }
      },
      yaxis: [{
        labels: {
          formatter: function (val) { return '₹' + Math.round(val).toLocaleString(); },
          style: { colors: '#64748b' }
        }
      }, {
        opposite: true,
        labels: {
          formatter: function (val) { return Math.round(val) + ' Orders'; },
          style: { colors: '#64748b' }
        }
      }],
      tooltip: {
        y: [{
          formatter: function (y) {
            return typeof y !== 'undefined' ? '₹' + y.toLocaleString() : y;
          }
        }, {
          formatter: function (y) {
            return typeof y !== 'undefined' ? y + ' Orders' : y;
          }
        }]
      }
    };

    const salesChart = new ApexCharts(document.querySelector('#salesAnalyticsChart'), salesOptions);
    salesChart.render();

    // 2. Category Sales % Share Donut Chart
    const catLabels = @json(array_column($categorySalesBreakdown, 'category'));
    const catSeries = @json(array_column($categorySalesBreakdown, 'percentage'));

    const donutOptions = {
      series: catSeries,
      labels: catLabels,
      chart: {
        height: 220,
        type: 'donut'
      },
      colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6c757d'],
      legend: { show: false },
      dataLabels: { enabled: false },
      plotOptions: {
        pie: {
          donut: {
            size: '70%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Categories',
                formatter: function () {
                  return catLabels.length;
                }
              }
            }
          }
        }
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return val + '% Share';
          }
        }
      }
    };

    const donutChart = new ApexCharts(document.querySelector('#categorySalesDonutChart'), donutOptions);
    donutChart.render();
  });

  function toggleDashboardCustomDates(val) {
      let box = document.getElementById('dashboardCustomDates');
      if (box) {
          if (val === 'custom') {
              box.classList.remove('d-none');
          } else {
              box.classList.add('d-none');
          }
      }
  }
</script>
@endsection
@endsection
