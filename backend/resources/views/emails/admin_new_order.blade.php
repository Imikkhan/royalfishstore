<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Alert</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background: #dc2626; color: #ffffff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 25px; }
        .order-badge { display: inline-block; background: #fee2e2; color: #991b1b; font-weight: bold; padding: 6px 12px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 8px 0; border-bottom: 1px dashed #e5e7eb; font-size: 14px; }
        .info-table td.label { font-weight: bold; color: #4b5563; width: 35%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background: #f3f4f6; text-align: left; padding: 10px; font-size: 13px; color: #374151; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .total-box { margin-top: 20px; padding: 15px; background: #fef2f2; border-radius: 6px; text-align: right; font-size: 16px; font-weight: bold; color: #991b1b; }
        .footer { background: #1f2937; color: #9ca3af; text-align: center; padding: 15px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👑 Royal Fish Store - Admin Alert</h1>
        </div>
        <div class="content">
            <span class="order-badge">NEW ORDER #{{ $order->id }}</span>
            <p>A new order has been successfully placed by a customer.</p>

            <h3>Customer & Shipping Details</h3>
            @php
                $address = $order->address_data ?? [];
                $userName = $order->user->name ?? ($address['name'] ?? 'Customer');
                $userPhone = $order->user->phone ?? ($address['phone'] ?? 'N/A');
                $userEmail = $order->user->email ?? ($address['email'] ?? 'N/A');
                $addressLine = $address['addressLine'] ?? ($address['address_line'] ?? 'N/A');
                $city = $address['city'] ?? 'N/A';
                $zipCode = $address['zipCode'] ?? ($address['zip_code'] ?? 'N/A');
            @endphp

            <table class="info-table">
                <tr><td class="label">Customer Name:</td><td>{{ $userName }}</td></tr>
                <tr><td class="label">Phone:</td><td>{{ $userPhone }}</td></tr>
                <tr><td class="label">Email:</td><td>{{ $userEmail }}</td></tr>
                <tr><td class="label">Delivery Address:</td><td>{{ $addressLine }}, {{ $city }} - {{ $zipCode }}</td></tr>
                <tr><td class="label">Payment Method:</td><td><strong>{{ strtoupper($order->payment_method) }}</strong></td></tr>
                <tr><td class="label">Order Time:</td><td>{{ $order->created_at ? $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : 'N/A' }}</td></tr>
            </table>

            <h3>Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-box">
                Total Amount: ₹{{ number_format($order->total_price, 2) }}
            </div>
        </div>
        <div class="footer">
            Royal Fish Store Admin Notification System &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
