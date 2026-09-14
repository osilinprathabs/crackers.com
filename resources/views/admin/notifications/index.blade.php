@extends('layouts/layoutMaster')

@section('title', 'Admin Notifications Center')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
      <h4 class="fw-bold mb-1"><i class="ri-notification-3-line text-primary me-2"></i>Admin Notifications Center</h4>
      <p class="text-muted small mb-0">Track all online orders, POS orders, customer registrations, dispatches, deliveries, and payment confirmations in real-time.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <button type="button" class="btn btn-primary fw-bold shadow-sm" id="markAllReadBtn" data-action="{{ route('admin-notifications.mark-all-read') }}">
        <i class="ri-check-double-line me-1"></i> Mark All as Read
      </button>
      <button type="button" class="btn btn-outline-danger fw-bold shadow-sm" id="clearReadBtn" data-action="{{ route('admin-notifications.clear-read') }}">
        <i class="ri-delete-bin-line me-1"></i> Clear Read
      </button>
    </div>
  </div>

  <!-- STATS & STATUS FILTER TABS -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <a href="{{ route('admin-notifications', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}" class="text-decoration-none">
        <div class="card border-0 shadow-sm {{ ($status === 'all' || empty($status)) ? 'border-primary border-2 bg-label-primary' : '' }}">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <span class="avatar-initial rounded-circle bg-primary text-white" style="width: 44px; height: 44px;"><i class="ri-notification-3-fill fs-4"></i></span>
              <div>
                <h6 class="mb-0 fw-bold">All Notifications</h6>
                <small class="text-muted">Total notifications logged</small>
              </div>
            </div>
            <span class="badge bg-primary rounded-pill font-monospace fs-6 px-3">{{ $totalCount }}</span>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-4">
      <a href="{{ route('admin-notifications', array_merge(request()->except(['status', 'page']), ['status' => 'unread'])) }}" class="text-decoration-none">
        <div class="card border-0 shadow-sm {{ $status === 'unread' ? 'border-warning border-2 bg-label-warning' : '' }}">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <span class="avatar-initial rounded-circle bg-warning text-dark" style="width: 44px; height: 44px;"><i class="ri-mail-unread-fill fs-4"></i></span>
              <div>
                <h6 class="mb-0 fw-bold">Unread Notifications</h6>
                <small class="text-muted">Requires admin review</small>
              </div>
            </div>
            <span class="badge bg-warning text-dark rounded-pill font-monospace fs-6 px-3">{{ $unreadCount }}</span>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-4">
      <a href="{{ route('admin-notifications', array_merge(request()->except(['status', 'page']), ['status' => 'read'])) }}" class="text-decoration-none">
        <div class="card border-0 shadow-sm {{ $status === 'read' ? 'border-success border-2 bg-label-success' : '' }}">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <span class="avatar-initial rounded-circle bg-success text-white" style="width: 44px; height: 44px;"><i class="ri-checkbox-circle-fill fs-4"></i></span>
              <div>
                <h6 class="mb-0 fw-bold">Read Notifications</h6>
                <small class="text-muted">Previously reviewed</small>
              </div>
            </div>
            <span class="badge bg-success rounded-pill font-monospace fs-6 px-3">{{ $readCount }}</span>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- MAIN CONTENT CARD -->
  <div class="card border-0 shadow-sm rounded-3">
    <!-- FILTERS & SEARCH HEADER -->
    <div class="card-header bg-transparent border-bottom p-3">
      <form method="GET" action="{{ route('admin-notifications') }}" id="filterForm">
        <input type="hidden" name="status" value="{{ $status ?? 'all' }}">
        
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
          <!-- Notification Type Pills -->
          <div class="d-flex align-items-center gap-1 overflow-x-auto py-1">
            @php
              $typeList = [
                'all' => ['label' => 'All Types', 'icon' => 'ri-apps-fill', 'count' => $typeCounts['all'] ?? 0],
                'orders' => ['label' => 'Online Orders', 'icon' => 'ri-shopping-cart-fill', 'count' => $typeCounts['orders'] ?? 0],
                'pos' => ['label' => 'POS Orders', 'icon' => 'ri-store-2-fill', 'count' => $typeCounts['pos'] ?? 0],
                'customers' => ['label' => 'Customers', 'icon' => 'ri-user-add-fill', 'count' => $typeCounts['customers'] ?? 0],
                'dispatched' => ['label' => 'Dispatches', 'icon' => 'ri-truck-fill', 'count' => $typeCounts['dispatched'] ?? 0],
                'delivered' => ['label' => 'Deliveries', 'icon' => 'ri-checkbox-circle-fill', 'count' => $typeCounts['delivered'] ?? 0],
                'cancelled' => ['label' => 'Cancellations', 'icon' => 'ri-close-circle-fill', 'count' => $typeCounts['cancelled'] ?? 0],
                'payments' => ['label' => 'Payments', 'icon' => 'ri-money-rupee-circle-fill', 'count' => $typeCounts['payments'] ?? 0],
              ];
              $currentType = $type ?? 'all';
            @endphp

            @foreach($typeList as $key => $tInfo)
              <a href="{{ route('admin-notifications', array_merge(request()->except(['type', 'page']), ['type' => $key])) }}" 
                 class="btn btn-sm rounded-pill font-weight-semibold text-nowrap d-flex align-items-center gap-1 {{ $currentType === $key ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                <i class="{{ $tInfo['icon'] }}"></i> {{ $tInfo['label'] }}
                <span class="badge rounded-pill {{ $currentType === $key ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1 font-monospace">{{ $tInfo['count'] }}</span>
              </a>
            @endforeach
          </div>

          <!-- Search Box -->
          <div class="input-group" style="max-width: 320px;">
            <input type="text" name="search" class="form-control form-control-sm rounded-start-pill ps-3" placeholder="Search notification title or order..." value="{{ $search ?? '' }}">
            @if(!empty($search))
              <a href="{{ route('admin-notifications', request()->except(['search', 'page'])) }}" class="btn btn-sm btn-outline-secondary" title="Clear Search"><i class="ri-close-line"></i></a>
            @endif
            <button type="submit" class="btn btn-sm btn-primary rounded-end-pill px-3"><i class="ri-search-line"></i></button>
          </div>
        </div>
      </form>
    </div>

    <!-- NOTIFICATION LIST -->
    <div class="card-body p-0">
      <div class="list-group list-group-flush" id="allNotificationsList">
        @forelse ($notifications as $notification)
          <div class="list-group-item list-group-item-action p-3 border-bottom notif-row {{ $notification->is_read ? '' : 'bg-label-primary border-start border-primary border-4' }}"
               style="transition: all 0.2s ease;"
               data-id="{{ $notification->id }}"
               data-link="{{ $notification->link ?? '' }}"
               data-is-read="{{ $notification->is_read ? '1' : '0' }}">
            
            <div class="d-flex align-items-start gap-3">
              <!-- Type Icon Avatar -->
              <div class="flex-shrink-0">
                <span class="avatar-initial rounded-circle bg-label-{{ $notification->badge_color }}" style="width:46px; height:46px;">
                  <i class="{{ $notification->icon_class }} fs-4"></i>
                </span>
              </div>

              <!-- Content Body -->
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                  <div>
                    <h6 class="mb-0 fw-bold text-dark d-inline-block">{{ $notification->title }}</h6>
                    @if(!$notification->is_read)
                      <span class="badge bg-warning text-dark ms-2 align-middle font-monospace" style="font-size: 0.7rem;">UNREAD</span>
                    @endif
                  </div>
                  <span class="text-muted small font-monospace"><i class="ri-time-line me-1 align-middle"></i>{{ $notification->created_at?->diffForHumans() }} ({{ $notification->created_at?->format('d M Y, h:i A') }})</span>
                </div>

                <p class="mb-2 text-secondary font-medium" style="font-size: 0.92rem; line-height: 1.5;">{{ $notification->message }}</p>

                <!-- Actions Footer -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-2 pt-1 border-top border-light">
                  <div class="d-flex align-items-center gap-2">
                    @if(!empty($notification->link))
                      <a href="{{ $notification->link }}" class="btn btn-xs btn-primary rounded-pill px-3 py-1 fw-bold notif-action-link" onclick="markAndNavigate(event, '{{ $notification->id }}', '{{ $notification->link }}')">
                        <i class="ri-external-link-line me-1"></i> View Details
                      </a>
                    @endif
                  </div>

                  <div class="d-flex align-items-center gap-2">
                    @if(!$notification->is_read)
                      <button type="button" class="btn btn-xs btn-label-success rounded-pill px-3 py-1 fw-bold mark-read-btn" data-id="{{ $notification->id }}">
                        <i class="ri-check-line me-1"></i> Mark as Read
                      </button>
                    @else
                      <button type="button" class="btn btn-xs btn-label-secondary rounded-pill px-3 py-1 fw-bold mark-unread-btn" data-id="{{ $notification->id }}">
                        <i class="ri-mail-line me-1"></i> Mark as Unread
                      </button>
                      <span class="text-success small fw-semibold ms-1"><i class="ri-check-double-line me-1"></i>Read</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-5 text-muted">
            <i class="ri-notification-off-line text-secondary display-4 d-block mb-3"></i>
            <h5 class="fw-bold text-dark">No Notifications Found</h5>
            <p class="mb-0 small">No notifications match your current filter parameters.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      @if($notifications->hasPages())
        <div class="px-4 py-3 border-top">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var baseUrl = window.baseUrl || (document.documentElement.getAttribute('data-base-url') || '').replace(/\/+$/, '') + '/';

    // ── Single Mark as Read ──────────────────────────────────────────────────
    document.querySelectorAll('.mark-read-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = btn.dataset.id;

            fetch(baseUrl + 'admin/notifications/' + id + '/mark-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    // ── Single Mark as Unread ────────────────────────────────────────────────
    document.querySelectorAll('.mark-unread-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = btn.dataset.id;

            fetch(baseUrl + 'admin/notifications/' + id + '/mark-unread', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    // ── Mark All as Read ────────────────────────────────────────────────────
    var markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function () {
            var url = markAllBtn.dataset.action;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    location.reload();
                }
            });
        });
    }

    // ── Clear Read Notifications ────────────────────────────────────────────
    var clearReadBtn = document.getElementById('clearReadBtn');
    if (clearReadBtn) {
        clearReadBtn.addEventListener('click', function () {
            if (!confirm('Are you sure you want to clear all read notifications?')) return;
            var url = clearReadBtn.dataset.action;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    location.reload();
                }
            });
        });
    }
});

// Helper for navigating via View Details button
function markAndNavigate(e, id, link) {
    e.preventDefault();
    e.stopPropagation();
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var baseUrl = window.baseUrl || (document.documentElement.getAttribute('data-base-url') || '').replace(/\/+$/, '') + '/';

    fetch(baseUrl + 'admin/notifications/' + id + '/mark-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    }).finally(function () {
        if (link) window.location.href = link;
    });
}
</script>
@endsection
