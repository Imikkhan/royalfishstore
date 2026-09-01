<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Task Assigned</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .header { background: #2563eb; color: #ffffff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .content { padding: 25px; }
        .greeting { font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #1e40af; }
        .task-card { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .task-card h3 { margin-top: 0; color: #1d4ed8; font-size: 16px; }
        .info-row { display: flex; margin-bottom: 8px; font-size: 14px; }
        .info-label { font-weight: bold; width: 140px; color: #4b5563; }
        .items-list { background: #f9fafb; padding: 12px; border-radius: 6px; margin-top: 15px; font-size: 14px; }
        .footer { background: #111827; color: #9ca3af; text-align: center; padding: 15px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛵 Express Delivery Task</h1>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $rider->name ?? 'Delivery Partner' }},</div>
            <p>You have been assigned a new delivery task by the Admin team!</p>

            @php
                $address = $order->address_data ?? [];
                $customerName = $order->user->name ?? ($address['name'] ?? 'Customer');
                $customerPhone = $order->user->phone ?? ($address['phone'] ?? 'N/A');
                $addressLine = $address['addressLine'] ?? ($address['address_line'] ?? 'N/A');
                $city = $address['city'] ?? 'N/A';
                $zipCode = $address['zipCode'] ?? ($address['zip_code'] ?? 'N/A');
            @endphp

            <div class="task-card">
                <h3>Order #{{ $order->id }}</h3>
                <div class="info-row"><strong>Tracking Code:</strong> &nbsp;{{ $order->tracking_number ?: 'RF-TRK-' . rand(100000, 999999) }}</div>
                <div class="info-row"><strong>Customer Name:</strong> &nbsp;{{ $customerName }}</div>
                <div class="info-row"><strong>Customer Phone:</strong> &nbsp;<a href="tel:{{ $customerPhone }}" style="color: #2563eb; font-weight: bold;">{{ $customerPhone }}</a></div>
                <div class="info-row"><strong>Delivery Address:</strong> &nbsp;{{ $addressLine }}, {{ $city }} - {{ $zipCode }}</div>
                <div class="info-row"><strong>Collect Cash/Payment:</strong> &nbsp;<span style="color: #b91c1c; font-weight: bold;">₹{{ number_format($order->total_price, 2) }} ({{ strtoupper($order->payment_method) }})</span></div>
            </div>

            <h4>Package Contents:</h4>
            <div class="items-list">
                @foreach($order->items as $item)
                    <div>• <strong>{{ $item->product_name }}</strong> x {{ $item->quantity }}</div>
                @endforeach
            </div>

            <p style="margin-top: 20px; font-size: 13px; color: #6b7280;">Please login to your Delivery Partner Dashboard to update delivery progress (Out for Delivery / Delivered).</p>
        </div>
        <div class="footer">
            Royal Fish Store Express Logistics &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
