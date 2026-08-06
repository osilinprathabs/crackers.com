<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Receipt - {{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            color: #000000;
            background-color: #ffffff;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: 700; }
        .text-uppercase { text-transform: uppercase; }

        .receipt-header {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .store-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .store-sub {
            font-size: 10px;
            margin-top: 2px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            font-size: 11px;
        }
        .meta-table td {
            padding: 2px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th {
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
            padding: 4px 0;
            font-size: 11px;
            text-align: left;
        }
        .items-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11px;
        }
        .item-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 11px;
        }

        .totals-table {
            width: 100%;
            margin-bottom: 10px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 8px 0;
            font-size: 12px;
        }
        .totals-table td {
            padding: 2px 0;
        }

        .grand-total-row {
            font-size: 14px;
            font-weight: 700;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 0;
        }

        .receipt-footer {
            margin-top: 12px;
            font-size: 10px;
        }

        .no-print {
            margin-top: 15px;
            text-align: center;
        }
        .btn-print {
            background: #000;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 12px;
        }

        @media print {
            .no-print { display: none !important; }
            body { width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="receipt-header text-center">
        <div class="store-name">{{ $settings->company_name ?: 'S.R. TRADERS' }}</div>
        @if($settings->company_slogan)
            <div class="store-sub">{{ $settings->company_slogan }}</div>
        @endif
        @if($settings->support_address)
            <div class="store-sub">{{ $settings->support_address }}</div>
        @endif
        @if($settings->support_phone)
            <div class="store-sub">Phone: {{ $settings->support_phone }}</div>
        @endif
        @if($settings->license_number)
            <div class="store-sub">Explosive License: {{ $settings->license_number }}</div>
        @endif
    </div>

    <!-- Meta / Order Information -->
    <table class="meta-table">
        <tr>
            <td class="fw-bold">Receipt #:</td>
            <td class="text-right">{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td>Date & Time:</td>
            <td class="text-right">{{ $order->created_at ? $order->created_at->format('d/m/Y h:i A') : date('d/m/Y h:i A') }}</td>
        </tr>
        <tr>
            <td>Customer:</td>
            <td class="text-right fw-bold">{{ $order->customer_name }}</td>
        </tr>
        @if($order->customer_phone && $order->customer_phone !== '9999999999')
        <tr>
            <td>Mobile:</td>
            <td class="text-right">{{ $order->customer_phone }}</td>
        </tr>
        @endif
        <tr>
            <td>Cashier:</td>
            <td class="text-right">{{ auth()->check() ? auth()->user()->name : 'Counter POS' }}</td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">ITEM</th>
                <th style="width: 15%; text-align: center;">QTY</th>
                <th style="width: 17%; text-align: right;">RATE</th>
                <th style="width: 18%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td class="item-name">{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals & Payment Summary -->
    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td>Discount:</td>
            <td class="text-right">-₹{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        @if($order->gst_amount > 0)
        <tr>
            <td>GST ({{ $order->gst_rate }}%):</td>
            <td class="text-right">₹{{ number_format($order->gst_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="grand-total-row">
            <td class="fw-bold">GRAND TOTAL:</td>
            <td class="text-right fw-bold">₹{{ number_format($order->grand_total, 2) }}</td>
        </tr>
        <tr>
            <td style="padding-top: 6px;">Payment Mode:</td>
            <td class="text-right fw-bold text-uppercase" style="padding-top: 6px;">{{ $order->payment_method }}</td>
        </tr>
    </table>

    <!-- Footer Note -->
    <div class="receipt-footer text-center">
        <div class="fw-bold mb-1">*** THANK YOU FOR SHOPPING! ***</div>
        <div>HAPPY & SAFE CELEBRATIONS!</div>
        @if($settings->supreme_court_disclaimer)
            <div style="font-size: 8px; margin-top: 6px; line-height: 1.2;">
                {{ \Illuminate\Support\Str::limit($settings->supreme_court_disclaimer, 120) }}
            </div>
        @endif
    </div>

    <!-- Manual Print Action Button -->
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ PRINT RECEIPT</button>
        <button onclick="window.close()" class="btn-print" style="background:#64748b; margin-left: 5px;">CLOSE</button>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
