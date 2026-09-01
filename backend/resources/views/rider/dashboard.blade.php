<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rider Dashboard - Royal Fish Store</title>

    <!-- PWA Manifest & Mobile App Meta Tags -->
    <link rel="manifest" href="/rider-manifest.json">
    <meta name="theme-color" content="#FF5722">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="RoyalRider">
    <link rel="apple-touch-icon" href="/images/rider-icon-192.png">

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- jQuery & SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <style>
        /* Hide scrollbars for clean mobile app feel */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-full bg-slate-100 font-sans pb-20 sm:pb-12 text-slate-800 antialiased selection:bg-red-500 selection:text-white">
    
    <!-- Top Header (Native Mobile App Header) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-6xl mx-auto px-3.5 py-2.5 space-y-2">
            
            <!-- Primary Row -->
            <div class="flex items-center justify-between">
                
                <!-- Brand & Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center text-white shadow-xs font-black shrink-0">
                        <i class="fa-solid fa-motorcycle text-sm sm:text-base"></i>
                    </div>
                    <div class="truncate">
                        <h1 class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-tight leading-none">Royal Rider</h1>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-medium truncate mt-0.5">{{ $rider->name }}</p>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-1.5 sm:gap-2">
                    
                    <!-- PWA Install App Button -->
                    <button id="btn-install-pwa" class="hidden px-2.5 py-1 bg-amber-500 hover:bg-amber-400 text-slate-950 text-[11px] font-extrabold rounded-lg transition-all shadow-xs active:scale-95 flex items-center gap-1">
                        <i class="fa-solid fa-download text-[10px]"></i> <span>Install</span>
                    </button>

                    <!-- Notifications Bell Popover Button -->
                    <div class="relative">
                        <button id="btn-notif-toggle" class="relative p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors text-xs sm:text-sm w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center">
                            <i class="fa-solid fa-bell"></i>
                            @if(($unreadCount ?? 0) > 0)
                            <span id="notif-badge-count" class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-red-600 text-white text-[9px] sm:text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-pulse">{{ $unreadCount }}</span>
                            @endif
                        </button>

                        <!-- Notifications Popover Dropdown -->
                        <div id="notif-dropdown" class="hidden fixed top-14 right-3 left-3 sm:left-auto sm:right-0 sm:absolute sm:top-full w-auto sm:w-96 max-w-sm bg-white border border-slate-200/90 rounded-2xl shadow-2xl z-50 overflow-hidden animate-fadeIn">
                            <div class="p-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-bell text-red-600"></i> Rider Alerts ({{ count($notifications ?? []) }})
                                </h3>
                                <button id="btn-clear-notifs" class="text-[10px] font-bold text-red-600 hover:underline">Mark all read</button>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-100" id="notif-list-container">
                                
                                @forelse($notifications ?? [] as $n)
                                <div class="p-3 {{ $n['is_unread'] ? 'bg-purple-50/60 hover:bg-purple-100/50' : 'hover:bg-slate-50' }} transition-colors flex gap-2.5 items-start cursor-pointer {{ isset($n['is_chat']) && $n['is_chat'] ? 'btn-open-rider-chat' : '' }}" @if(isset($n['order_id'])) data-order-id="{{ $n['order_id'] }}" data-customer-name="Customer" @endif>
                                    <div class="w-7 h-7 rounded-full {{ $n['bg_color'] }} flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                                        <i class="fa-solid {{ $n['icon'] }}"></i>
                                    </div>
                                    <div class="space-y-0.5 flex-1">
                                        <p class="text-xs font-bold text-slate-900 flex items-center justify-between">
                                            <span>{{ $n['title'] }}</span>
                                            @if(isset($n['is_chat']) && $n['is_chat'])
                                            <span class="text-[8px] font-black bg-purple-600 text-white px-1.5 py-0.5 rounded-full uppercase tracking-wider">NEW CHAT</span>
                                            @endif
                                        </p>
                                        <p class="text-[11px] text-slate-600 leading-tight">{{ $n['message'] }}</p>
                                        <span class="text-[9px] text-slate-400 font-mono">{{ $n['time'] }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-slate-400 text-xs">No notifications right now.</div>
                                @endforelse

                            </div>
                        </div>
                    </div>

                    <!-- Duty Toggle Button (Mobile Compact) -->
                    <button id="btn-toggle-duty" class="px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl text-[11px] sm:text-xs font-extrabold transition-all flex items-center gap-1.5 shadow-xs border shrink-0 {{ $rider->status === 'Available' || $rider->status === 'On Delivery' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-200 text-slate-700 border-slate-300' }}">
                        <i class="fa-solid fa-circle text-[7px] {{ $rider->status === 'Available' || $rider->status === 'On Delivery' ? 'text-emerald-500 animate-pulse' : 'text-slate-400' }}"></i>
                        <span id="lbl-duty-status">{{ $rider->status }}</span>
                    </button>

                    <!-- Logout Button -->
                    <a href="{{ url('/rider/logout') }}" class="p-2 bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 rounded-xl transition-colors text-xs sm:text-sm w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile Sub Info Line -->
            <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-[10px] text-slate-500">
                <span class="font-mono flex items-center gap-1"><i class="fa-solid fa-id-card text-slate-400"></i> Vehicle: <strong class="text-slate-800 uppercase">{{ $rider->vehicle_number }}</strong></span>
                <span class="font-mono text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Rate: ₹{{ $rate }}/ride</span>
            </div>

        </div>
    </header>

    <div class="max-w-6xl mx-auto px-3 sm:px-4 py-4 space-y-4 sm:space-y-6">

        <!-- Stat Cards Grid (Mobile App Compact Design) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
            
            <!-- Total Completed -->
            <div class="bg-white border border-slate-200/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-xs space-y-0.5">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-500">Total Rides</span>
                    <i class="fa-solid fa-motorcycle text-slate-300 text-xs"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">{{ $completedDeliveries }}</h3>
                <p class="text-[9px] sm:text-[10px] text-slate-500 truncate">Successful rides</p>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white border border-slate-200/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-xs space-y-0.5">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-500">Total Earned</span>
                    <i class="fa-solid fa-indian-rupee-sign text-emerald-400 text-xs"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-emerald-600 leading-tight">₹{{ number_format($totalEarnings) }}</h3>
                <p class="text-[9px] sm:text-[10px] text-emerald-700 truncate">Total wallet cash</p>
            </div>

            <!-- Today's Rides -->
            <div class="bg-white border border-slate-200/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-xs space-y-0.5">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-500">Today Rides</span>
                    <i class="fa-solid fa-calendar-day text-amber-400 text-xs"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-amber-600 leading-tight">{{ $todayDeliveriesCount }}</h3>
                <p class="text-[9px] sm:text-[10px] text-amber-700 truncate">Delivered today</p>
            </div>

            <!-- Today's Earnings -->
            <div class="bg-white border border-slate-200/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-xs space-y-0.5">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-500">Today Earned</span>
                    <i class="fa-solid fa-wallet text-emerald-400 text-xs"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-emerald-600 leading-tight">₹{{ number_format($todayEarnings) }}</h3>
                <p class="text-[9px] sm:text-[10px] text-slate-500 truncate">Today's payout</p>
            </div>

        </div>

        <!-- Section 1: Active Deliveries Assigned to Me -->
        <div id="section-orders" class="bg-white border border-slate-200/80 rounded-2xl p-3.5 sm:p-6 shadow-xs space-y-3 sm:space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-2.5 gap-1">
                <h2 class="font-black text-slate-900 text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-box text-red-600"></i> My Active Orders ({{ count($activeDeliveries) }})
                </h2>
                <span class="text-[11px] font-semibold text-slate-500">Live Delivery Queue</span>
            </div>

            <div class="space-y-3 sm:space-y-4">
                @forelse($activeDeliveries as $order)
                @php
                    $address = $order->address_data ?? [];
                    $custName = $order->user->name ?? ($address['name'] ?? 'Customer');
                    $custPhone = $address['phone'] ?? ($order->user->phone ?? 'N/A');
                    $fullAddress = ($address['addressLine'] ?? '') . ', ' . ($address['city'] ?? '') . ' - ' . ($address['zipCode'] ?? '');
                @endphp
                <div class="bg-slate-50 border border-slate-200 rounded-xl sm:rounded-2xl p-3.5 sm:p-5 space-y-3 relative">
                    
                    <div class="flex justify-between items-start gap-2">
                        <div class="space-y-0.5">
                            <span class="text-[11px] font-mono font-extrabold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-200 inline-block">{{ $order->id }}</span>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight pt-1">{{ $custName }}</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed pt-0.5"><i class="fa-solid fa-location-dot text-red-600 mr-1"></i> {{ $fullAddress }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase block {{ $order->payment_method === 'COD' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
                                {{ $order->payment_method }} - ₹{{ $order->total_price }}
                            </span>
                            <p class="text-[10px] text-slate-500 mt-1">Status: <strong class="text-slate-900 font-bold">{{ $order->shipment_status }}</strong></p>
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div class="p-2.5 sm:p-3 bg-white rounded-xl border border-slate-200 text-xs text-slate-700">
                        <p class="font-bold text-slate-400 text-[9px] uppercase mb-1">Package Items:</p>
                        <ul class="space-y-1">
                            @foreach($order->items as $item)
                            <li class="flex justify-between items-center text-[11px]">
                                <span class="truncate pr-2">• {{ $item->product_name }}</span>
                                <span class="font-bold text-slate-900 shrink-0">x{{ $item->quantity }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Touch Action Buttons (Mobile App Buttons) -->
                    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 pt-2 border-t border-slate-200">
                        <div class="flex gap-1.5">
                            <a href="tel:{{ $custPhone }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-phone text-emerald-600"></i> Call
                            </a>
                            <button type="button" class="btn-open-rider-chat px-3 py-2 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-xs font-extrabold rounded-xl transition-colors flex items-center justify-center gap-1.5" data-order-id="{{ $order->id }}" data-customer-name="{{ $custName }}" data-order-status="{{ $order->shipment_status }}">
                                <i class="fa-solid fa-comments text-blue-600"></i> Chat
                            </button>
                        </div>

                        <div class="flex gap-2">
                            @if($order->shipment_status !== 'Out for Delivery')
                            <button class="btn-update-shipment flex-1 sm:flex-none px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition-colors shadow-xs" data-id="{{ $order->id }}" data-status="Out for Delivery">
                                <i class="fa-solid fa-person-biking mr-1"></i> Start Delivery
                            </button>
                            @endif

                            <button class="btn-update-shipment flex-1 sm:flex-none px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors shadow-xs" data-id="{{ $order->id }}" data-status="Delivered">
                                <i class="fa-solid fa-circle-check mr-1"></i> Mark Delivered (+₹{{ $rate }})
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-6 sm:py-8 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-boxes-packing text-2xl sm:text-3xl mb-1.5 text-slate-300 block"></i>
                    No active deliveries assigned right now. Stand by for new orders!
                </div>
                @endforelse
            </div>
        </div>

        <!-- Section 2: Rider Notifications & Broadcasts Card -->
        <div id="section-notifs" class="bg-white border border-slate-200/80 rounded-2xl p-3.5 sm:p-6 shadow-xs space-y-3">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                <h2 class="font-black text-slate-900 text-xs sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-amber-500"></i> Delivery Alerts & Bulletins
                </h2>
                <span class="text-[10px] sm:text-xs font-semibold text-slate-500">Notice Board</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-xl flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 text-xs mt-0.5">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Safety & Insulated Bag Protocol</h4>
                        <p class="text-[11px] text-slate-600 mt-0.5">Always use thermal delivery bag for fresh fish & seafood items.</p>
                    </div>
                </div>

                <div class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-xl flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-xs mt-0.5">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Instant Wallet Payouts</h4>
                        <p class="text-[11px] text-slate-600 mt-0.5">Marking an order delivered instantly adds ₹{{ $rate }} to your wallet log.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Delivery & Earnings History (Responsive Cards on Mobile + Table on Desktop) -->
        <div id="section-history" class="bg-white border border-slate-200/80 rounded-2xl p-3.5 sm:p-6 shadow-xs space-y-3 sm:space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                <h2 class="font-black text-slate-900 text-xs sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> Delivery & Earnings Log
                </h2>
                <span class="text-[10px] sm:text-xs font-semibold text-slate-500">Completed Rides</span>
            </div>

            <!-- Mobile View: Clean App Cards (block sm:hidden) -->
            <div class="block sm:hidden space-y-2.5">
                @forelse($deliveryHistory as $hist)
                @php
                    $address = $hist->address_data ?? [];
                    $cName = $hist->user->name ?? ($address['name'] ?? 'Customer');
                @endphp
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex justify-between items-center">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-mono font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-200">{{ $hist->id }}</span>
                            <span class="text-xs font-bold text-slate-900">{{ $cName }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500">Collected: <strong class="text-slate-800">₹{{ $hist->total_price }}</strong> ({{ $hist->payment_method }})</p>
                        <p class="text-[9px] text-slate-400 font-mono">{{ $hist->delivered_at ? $hist->delivered_at->format('d M, h:i A') : 'Completed' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-200 block">+₹{{ $rate }}</span>
                    </div>
                </div>
                @empty
                <div class="py-6 text-center text-slate-400 text-xs">No completed delivery history yet.</div>
                @endforelse
            </div>

            <!-- Desktop View: Clean Table (hidden sm:block) -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                            <th class="py-3 px-3">Order ID</th>
                            <th class="py-3 px-3">Customer</th>
                            <th class="py-3 px-3">Amount Collected</th>
                            <th class="py-3 px-3">Delivery Completed</th>
                            <th class="py-3 px-3 text-right">Commission Earned</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($deliveryHistory as $hist)
                        @php
                            $address = $hist->address_data ?? [];
                            $cName = $hist->user->name ?? ($address['name'] ?? 'Customer');
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 font-mono font-bold text-red-600">{{ $hist->id }}</td>
                            <td class="py-3 px-3 font-semibold text-slate-900">{{ $cName }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">₹{{ $hist->total_price }} ({{ $hist->payment_method }})</td>
                            <td class="py-3 px-3 text-slate-500 font-mono text-[11px]">
                                {{ $hist->delivered_at ? $hist->delivered_at->format('d M Y, h:i A') : 'Completed' }}
                            </td>
                            <td class="py-3 px-3 text-right font-black text-emerald-600">+₹{{ $rate }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">No completed delivery history yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Fixed Bottom Mobile Navigation App Bar (sm:hidden) -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 py-1.5 px-4 sm:hidden shadow-lg">
        <div class="flex items-center justify-around text-center">
            
            <a href="#section-orders" class="flex flex-col items-center gap-0.5 text-red-600 font-bold">
                <i class="fa-solid fa-box text-base"></i>
                <span class="text-[9px]">Orders</span>
            </a>

            <a href="#section-notifs" class="flex flex-col items-center gap-0.5 text-slate-500 hover:text-slate-900 font-medium">
                <i class="fa-solid fa-bell text-base"></i>
                <span class="text-[9px]">Alerts</span>
            </a>

            <a href="#section-history" class="flex flex-col items-center gap-0.5 text-slate-500 hover:text-slate-900 font-medium">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span class="text-[9px]">History</span>
            </a>

            <button id="btn-bottom-duty-toggle" class="flex flex-col items-center gap-0.5 {{ $rider->status === 'Available' || $rider->status === 'On Delivery' ? 'text-emerald-600' : 'text-slate-400' }} font-bold">
                <i class="fa-solid fa-power-off text-base"></i>
                <span class="text-[9px]">{{ $rider->status }}</span>
            </button>

        </div>
    </nav>

    <script>
        $(document).ready(function() {
            
            // Toggle Notifications Dropdown
            $('#btn-notif-toggle').click(function(e) {
                e.stopPropagation();
                $('#notif-dropdown').toggleClass('hidden');
            });

            $(document).click(function(e) {
                if (!$(e.target).closest('#notif-dropdown, #btn-notif-toggle').length) {
                    $('#notif-dropdown').addClass('hidden');
                }
            });

            $('#btn-clear-notifs').click(function() {
                $('#notif-badge-count').addClass('hidden');
                Swal.fire({
                    icon: 'success',
                    title: 'Notifications Cleared',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1200
                });
            });

            // Toggle Duty Availability
            $(document).on('click', '#btn-toggle-duty, #btn-bottom-duty-toggle', function(e) {
                e.preventDefault();
                let btn = $(this);
                btn.prop('disabled', true);

                $.ajax({
                    url: '/rider/toggle-duty',
                    method: 'POST',
                    data: {
                        rider_id: '{{ $rider->id }}',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('#lbl-duty-status').text(res.status);
                        Swal.fire({
                            icon: 'success',
                            title: 'Duty Updated',
                            text: res.message,
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false);
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update duty status.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg,
                            confirmButtonColor: '#dc2626'
                        });
                    }
                });
            });

            // Update Shipment Status callback
            $('.btn-update-shipment').click(function() {
                let id = $(this).attr('data-id');
                let status = $(this).attr('data-status');

                Swal.fire({
                    title: 'Confirm ' + status + '?',
                    text: status === 'Delivered' ? 'Confirm order delivery & cash collection?' : 'Change status to ' + status + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/rider/update-status',
                            method: 'POST',
                            data: {
                                order_id: id,
                                status: status
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops',
                                    text: 'Failed to update order status.',
                                    confirmButtonColor: '#dc2626'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

<!-- Rider Chat Modal -->
<div id="modal-rider-chat" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-3">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl flex flex-col h-[520px] overflow-hidden border border-slate-200 animate-fadeIn">
        <!-- Header -->
        <div class="p-3.5 bg-slate-900 text-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-tight" id="chat-modal-title">Live Chat</h3>
                    <p class="text-[10px] text-slate-300 font-mono" id="chat-modal-subtitle">Order #</p>
                </div>
            </div>
            <button type="button" id="btn-close-rider-chat" class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Quick Chips Bar -->
        <div class="p-2 bg-slate-50 border-b border-slate-200 flex gap-1.5 overflow-x-auto text-[10px] shrink-0">
            <button type="button" class="btn-chat-chip px-2.5 py-1 bg-white border border-slate-200 hover:bg-slate-100 rounded-full font-semibold text-slate-700 whitespace-nowrap">I have arrived 🚪</button>
            <button type="button" class="btn-chat-chip px-2.5 py-1 bg-white border border-slate-200 hover:bg-slate-100 rounded-full font-semibold text-slate-700 whitespace-nowrap">On my way 🚴</button>
            <button type="button" class="btn-chat-chip px-2.5 py-1 bg-white border border-slate-200 hover:bg-slate-100 rounded-full font-semibold text-slate-700 whitespace-nowrap">Please call me 📞</button>
        </div>

        <!-- Chat Messages Container -->
        <div id="rider-chat-messages" class="flex-1 p-3 overflow-y-auto space-y-2 bg-slate-100/60">
            <div class="text-center text-[10px] text-slate-400 py-4">Loading messages...</div>
        </div>

        <!-- Message Input Bar -->
        <form id="form-rider-send-chat" class="p-2.5 bg-white border-t border-slate-200 flex gap-2 items-center shrink-0">
            <input type="text" id="input-rider-chat-msg" placeholder="Type a message to customer..." class="flex-1 px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-medium">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition-colors shrink-0 flex items-center gap-1">
                <span>Send</span> <i class="fa-solid fa-paper-plane text-[10px]"></i>
            </button>
        </form>
        
        <!-- Locked Banner for Closed / Delivered Orders -->
        <div id="rider-chat-locked-banner" class="hidden p-3 bg-slate-100 text-center text-xs font-extrabold text-slate-500 border-t border-slate-200">
            <i class="fa-solid fa-lock text-slate-400 mr-1"></i> Order Delivered & Closed — Live chat session ended.
        </div>
    </div>
</div>

    <!-- UI Scripts -->
    <script>
        $(document).ready(function() {

            // Rider Chat Module Logic
            let activeChatOrderId = null;
            let chatPollInterval = null;

            function loadRiderChatMessages(orderId) {
                if (!orderId) return;
                $.ajax({
                    url: '/rider/chat/' + orderId,
                    method: 'GET',
                    success: function(messages) {
                        let html = '';
                        if (!messages || messages.length === 0) {
                            html = '<div class="text-center text-[11px] text-slate-400 py-8"><i class="fa-solid fa-comments text-slate-300 text-2xl mb-2 block"></i>No messages yet. Send a quick update to the customer!</div>';
                        } else {
                            messages.forEach(function(m) {
                                let isRider = m.sender_type === 'rider';
                                let bubbleBg = isRider ? 'bg-blue-600 text-white rounded-br-none ml-auto' : 'bg-white text-slate-800 rounded-bl-none border border-slate-200';
                                let align = isRider ? 'justify-end' : 'justify-start';
                                let time = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                                html += `
                                    <div class="flex ${align}">
                                        <div class="max-w-[80%] p-2.5 rounded-2xl ${bubbleBg} shadow-xs space-y-0.5">
                                            <div class="text-[9px] font-bold opacity-80 flex items-center justify-between gap-3">
                                                <span>${m.sender_name || (isRider ? 'You' : 'Customer')}</span>
                                                <span>${time}</span>
                                            </div>
                                            <p class="text-xs font-medium leading-normal">${m.message}</p>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        $('#rider-chat-messages').html(html);
                        $('#rider-chat-messages').scrollTop($('#rider-chat-messages')[0].scrollHeight);
                    },
                    error: function() {
                        $('#rider-chat-messages').html('<div class="text-center text-[11px] text-slate-400 py-8"><i class="fa-solid fa-comments text-slate-300 text-2xl mb-2 block"></i>No messages yet. Send a quick update to the customer!</div>');
                    }
                });
            }

            $(document).on('click', '.btn-open-rider-chat', function(e) {
                e.preventDefault();
                activeChatOrderId = $(this).attr('data-order-id');
                let custName = $(this).attr('data-customer-name') || 'Customer';
                let orderStatus = $(this).attr('data-order-status') || '';

                if (orderStatus === 'Delivered') {
                    $('#form-rider-send-chat').addClass('hidden');
                    $('#rider-chat-locked-banner').removeClass('hidden');
                } else {
                    $('#form-rider-send-chat').removeClass('hidden');
                    $('#rider-chat-locked-banner').addClass('hidden');
                }

                $('#chat-modal-title').text('Chat with ' + custName);
                $('#chat-modal-subtitle').text('Order #' + activeChatOrderId);
                $('#rider-chat-messages').html('<div class="text-center text-[11px] text-slate-400 py-8"><i class="fa-solid fa-spinner fa-spin text-blue-500 text-lg mb-2 block"></i>Loading messages...</div>');
                $('#modal-rider-chat').removeClass('hidden');

                loadRiderChatMessages(activeChatOrderId);

                if (chatPollInterval) clearInterval(chatPollInterval);
                chatPollInterval = setInterval(function() {
                    if (activeChatOrderId) {
                        loadRiderChatMessages(activeChatOrderId);
                    }
                }, 3000);
            });

            $('#btn-close-rider-chat').click(function() {
                $('#modal-rider-chat').addClass('hidden');
                if (chatPollInterval) clearInterval(chatPollInterval);
                activeChatOrderId = null;
            });

            $('.btn-chat-chip').click(function() {
                let txt = $(this).text().trim();
                $('#input-rider-chat-msg').val(txt);
                $('#form-rider-send-chat').submit();
            });

            $('#form-rider-send-chat').submit(function(e) {
                e.preventDefault();
                let msg = $('#input-rider-chat-msg').val().trim();
                if (!msg || !activeChatOrderId) return;

                $('#input-rider-chat-msg').val('');

                $.ajax({
                    url: '/rider/chat/' + activeChatOrderId,
                    method: 'POST',
                    data: {
                        rider_id: '{{ $rider->id }}',
                        message: msg,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        loadRiderChatMessages(activeChatOrderId);
                    }
                });
            });
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/rider-sw.js').then(function(reg) {
                    console.log('Rider PWA Service Worker registered');
                });
            });
        }

        // PWA Install Prompt Listener
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            $('#btn-install-pwa').removeClass('hidden');
        });

        $('#btn-install-pwa').click(function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        $('#btn-install-pwa').addClass('hidden');
                    }
                    deferredPrompt = null;
                });
            }
        });
    </script>
</body>
</html>
