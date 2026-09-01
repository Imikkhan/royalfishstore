<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Status Update</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; }
        .header { background: #059669; color: #ffffff; padding: 22px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 25px; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 14px; text-transform: uppercase; margin: 10px 0 20px 0; background: #d1fae5; color: #065f46; }
        .status-badge.placed { background: #dbeafe; color: #1e40af; }
        .status-badge.dispatched { background: #fef3c7; color: #92400e; }
        .status-badge.out-for-delivery { background: #e0e7ff; color: #3730a3; }
        .status-badge.delivered { background: #d1fae5; color: #065f46; }
        .status-badge.cancelled { background: #fee2e2; color: #991b1b; }
        .summary-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .summary-card table { width: 100%; border-collapse: collapse; }
        .summary-card td { padding: 6px 0; font-size: 14px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background: #f1f5f9; text-align: left; padding: 8px; font-size: 13px; color: #475569; }
        .items-table td { padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .footer { background: #0f172a; color: #94a3b8; text-align: center; padding: 15px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👑 Royal Fish Store</h1>
        </div>
        <div class="content">
            @php
                $address = $order->address_data ?? [];
                $userName = $order->user->name ?? ($address['name'] ?? 'Valued Customer');
                $status = $statusTitle ?: ($order->shipment_status ?: $order->status);
                $statusClass = strtolower(str_replace(' ', '-', $status));
            @endphp

            <p>Dear <strong>{{ $userName }}</strong>,</p>
            <p>Thank you for choosing Royal Fish Store! Your order status has been updated.</p>

            <div style="text-align: center;">
                <div class="status-badge {{ $statusClass }}">
                    Status: {{ strtoupper($status) }}
                </div>
            </div>

            <div class="summary-card">
                <table>
                    <tr><td><strong>Order ID:</strong></td><td style="text-align: right;">#{{ $order->id }}</td></tr>
                    <tr><td><strong>Total Amount:</strong></td><td style="text-align: right;">₹{{ number_format($order->total_price, 2) }}</td></tr>
                    <tr><td><strong>Payment Method:</strong></td><td style="text-align: right;">{{ strtoupper($order->payment_method) }}</td></tr>
                    <tr><td><strong>Estimated Delivery:</strong></td><td style="text-align: right;">{{ $order->estimated_delivery ?: '30-45 mins' }}</td></tr>
                    @if($order->tracking_number)
                        <tr><td><strong>Tracking Number:</strong></td><td style="text-align: right;">{{ $order->tracking_number }}</td></tr>
                    @endif
                </table>
            </div>

            <h4>Order Items:</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="margin-top: 25px; font-size: 13px; color: #6b7280; text-align: center;">
                If you have any questions or need help, feel free to contact our customer support.
            </p>
        </div>
        <div class="footer">
            Thank you for shopping with Royal Fish Store! &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
