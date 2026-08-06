<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Crackers.com</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --gold-gradient: linear-gradient(135deg, #ffb703 0%, #fb8500 50%, #ff4800 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            min-height: 100vh;
        }
        .navbar-festive {
            background: #ffffff;
            border-bottom: 1px solid rgba(255, 183, 3, 0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }
        .brand-logo {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-custom {
            background: var(--bg-card);
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 20px;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-festive mb-4">
        <div class="container">
            <a class="navbar-brand brand-logo text-decoration-none" href="{{ route('crackers.storefront') }}">
                <i class="ri-fire-fill text-warning"></i> Crackers.com
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('crackers.profile') }}" class="btn btn-outline-primary rounded-pill px-3 btn-sm fw-semibold">
                    <i class="ri-user-settings-line me-1"></i> My Profile
                </a>
                <a href="{{ route('crackers.storefront') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                    <i class="ri-store-2-line me-1"></i> Storefront
                </a>
                <form action="{{ route('crackers.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3 btn-sm">
                        <i class="ri-logout-box-r-line me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Content Container -->
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif;"><i class="ri-shopping-bag-3-line text-warning me-2"></i> My Cracker Orders</h3>
                <p class="text-muted small mb-0">Welcome, {{ $user->name }} ({{ $user->phone }})</p>
            </div>
        </div>

        <div class="card-custom p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="color: var(--text-main);">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>GST</th>
                            <th>Grand Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><span class="fw-bold font-monospace text-warning">{{ $order->order_number }}</span></td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td><span class="badge bg-secondary">{{ $order->items->count() }} Items</span></td>
                                <td><small class="text-muted">₹{{ number_format($order->gst_amount, 2) }}</small></td>
                                <td><strong class="text-success fs-6">₹{{ number_format($order->grand_total, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-outline-info text-info">{{ $order->payment_method }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusBadge = match($order->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-dark',
                                            'dispatched' => 'bg-primary text-white',
                                            'delivered' => 'bg-success text-white',
                                            'cancelled' => 'bg-danger text-white',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusBadge }} fw-bold px-3 py-2 rounded-pill">{{ ucfirst($order->status) }}</span>
                                    <span class="badge bg-light text-dark border d-block mt-1"><i class="ri-truck-line text-warning"></i> 5-7 Days Delivery</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('crackers.order-invoice', $order->order_number) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill" title="View & Download Invoice">
                                            <i class="ri-file-pdf-line me-1"></i> Invoice PDF
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                            Details
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Order Detail Modal -->
                            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="background: #ffffff; color: #0f172a;">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold text-dark">Order Details #{{ $order->order_number }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-light border small mb-3">
                                                <i class="ri-truck-fill text-warning me-1"></i> Delivery Estimate: <strong>5 - 7 Working Days</strong>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted d-block fw-semibold">DELIVERY ADDRESS:</small>
                                                <div class="fw-bold">{{ $order->customer_name }}</div>
                                                <div>{{ $order->delivery_address }}, {{ $order->city }} {{ $order->pincode }}</div>
                                            </div>

                                            <!-- Payment Proof Screenshot Box in Modal -->
                                            <div class="p-3 bg-light rounded-3 border mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-bold small text-dark"><i class="ri-bank-card-line me-1"></i> Payment Screenshot / Receipt</span>
                                                    @if($order->payment_proof)
                                                        <span class="badge bg-success">Uploaded</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Pending Upload</span>
                                                    @endif
                                                </div>

                                                @if($order->payment_proof)
                                                    <div class="d-flex align-items-center justify-content-between pt-1">
                                                        <span class="small text-muted">Screenshot Attached</span>
                                                        <a href="{{ asset($order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                            <i class="ri-eye-line me-1"></i> View Screenshot
                                                        </a>
                                                    </div>
                                                @else
                                                    <form action="{{ route('crackers.upload-payment-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                                        @csrf
                                                        <label class="form-label small text-muted mb-1">Upload Payment Screenshot / Slip:</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                                                            <button type="submit" class="btn btn-warning fw-bold" style="background: var(--gold-gradient); color:#000;">Upload</button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>

                                            <div class="border rounded p-3 mb-3">
                                                <h6 class="fw-bold mb-2">Order Items:</h6>
                                                @foreach($order->items as $item)
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                                                        <span>₹{{ number_format($item->total_price, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="d-flex justify-content-between text-muted mb-1">
                                                <span>Subtotal:</span>
                                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted mb-2">
                                                <span>GST ({{ $order->gst_rate }}%):</span>
                                                <span>₹{{ number_format($order->gst_amount, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between fw-bold fs-5 border-top border-secondary pt-2">
                                                <span>Grand Total:</span>
                                                <span class="text-success">₹{{ number_format($order->grand_total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ri-shopping-bag-line display-4"></i>
                                    <p class="mt-2 mb-0">You have not placed any cracker orders yet.</p>
                                    <a href="{{ route('crackers.storefront') }}" class="btn btn-warning rounded-pill mt-3 px-4" style="background: var(--gold-gradient); color:#000;">Shop Crackers Now</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
