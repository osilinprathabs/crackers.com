<div class="table-responsive text-nowrap">
  <table class="table table-hover align-middle border">
    <thead class="table-light">
      <tr>
        <th>Order Number</th>
        <th>Customer</th>
        <th>Phone</th>
        <th class="text-end">Subtotal (₹)</th>
        <th class="text-end">GST (₹)</th>
        <th class="text-end">Grand Total (₹)</th>
        <th class="text-center">Payment Method</th>
        <th class="text-center">Payment Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td>
            <strong class="text-primary">{{ $order->order_number }}</strong>
            <small class="d-block text-muted">{{ $order->items->count() }} item(s)</small>
          </td>
          <td>
            <strong class="text-dark">{{ $order->customer_name }}</strong>
            @if($order->customer_email)
              <small class="d-block text-muted">{{ $order->customer_email }}</small>
            @endif
          </td>
          <td>{{ $order->customer_phone }}</td>
          <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
          <td class="text-end text-warning fw-semibold">₹{{ number_format($order->gst_amount, 2) }} ({{ $order->gst_rate }}%)</td>
          <td class="text-end text-success fw-bold">₹{{ number_format($order->grand_total, 2) }}</td>
          <td class="text-center">
            <span class="badge bg-label-info text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
          </td>
          <td class="text-center">
            @if($order->payment_status === 'paid')
              <span class="badge bg-label-success px-3 py-2 rounded-pill"><i class="ri-checkbox-circle-line me-1"></i> Paid</span>
            @elseif($order->payment_status === 'failed')
              <span class="badge bg-label-danger px-3 py-2 rounded-pill"><i class="ri-close-circle-line me-1"></i> Failed</span>
            @else
              <span class="badge bg-label-warning px-3 py-2 rounded-pill"><i class="ri-time-line me-1"></i> Pending</span>
            @endif
          </td>
          <td><small>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '-' }}</small></td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center py-5">
            <div class="text-muted">
              <i class="ri-inbox-line fs-1 d-block mb-2 text-secondary"></i>
              <h5>No revenue records found.</h5>
              <p class="mb-0">Try adjusting your date or search filters.</p>
            </div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="d-flex justify-content-end mt-4">
  {{ $orders->links('pagination::bootstrap-5') }}
</div>
