@extends('layouts.admin')

@section('title', 'eCommerce Dashboard')
@section('page_title', 'eCommerce Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Sales Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Sales</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">₹{{ number_format($totalSales) }}</h3>
                <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> +12.4% vs last week</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Orders</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalOrders }}</h3>
                <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> +8.2% vs last week</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Customers</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalCustomers }}</h3>
                <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> +4.5% vs last week</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Active Menu items -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Hot Deals</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">Active</h3>
                <p class="text-[10px] text-slate-500">Fast delivery operational</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg animate-pulse">
                <i class="fa-solid fa-fire"></i>
            </div>
        </div>

    </div>

    <!-- Chart & Low Stock Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Weekly Sales Chart -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs lg:col-span-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 dark:text-white text-base">Weekly Sales Trend</h3>
                <span class="text-xs text-slate-400">Live data sync</span>
            </div>
            <div class="h-80 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Low Stock / Menu Items list -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs lg:col-span-4 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4">Popular Store Items</h3>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($lowStockProducts as $p)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $p->image }}" alt="{{ $p->name }}" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 line-clamp-1">{{ $p->name }}</h4>
                                <p class="text-[10px] text-slate-400">{{ $p->weight }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-extrabold text-red-600 dark:text-red-400">₹{{ $p->price }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <a href="{{ url('/admin/products') }}" class="w-full text-center py-2 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors mt-4 block">
                Manage All Products
            </a>
        </div>

    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base">Recent Delivery Orders</h3>
            <a href="{{ url('/admin/orders') }}" class="text-xs text-red-600 hover:underline font-bold">View All Orders</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Order ID</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Total Amount</th>
                        <th class="py-3 px-4">Payment Method</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Date Placed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="py-3 px-4 font-extrabold text-slate-800 dark:text-white">{{ $order->id }}</td>
                        <td class="py-3 px-4 font-semibold">{{ $order->user->name ?? ($order->address_data['name'] ?? 'Guest Customer') }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800 dark:text-white">₹{{ number_format($order->total_price) }}</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold uppercase">{{ $order->payment_method }}</span></td>
                        <td class="py-3 px-4">
                            @php
                                $statusColors = [
                                    'Placed' => 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',
                                    'Processing' => 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
                                    'Dispatched' => 'bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400',
                                    'Delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',
                                    'Cancelled' => 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400'
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $color }}">{{ $order->status }}</span>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-slate-400">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-400">No orders placed yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        const chartData = @json($chartData);
        const days = chartData.map(c => c.day);
        const sales = chartData.map(c => c.sales);

        const ctx = document.getElementById('salesChart').getContext('2d');
        
        let isDark = document.documentElement.classList.contains('dark');
        let gridColor = isDark ? '#334155' : '#f1f5f9';
        let textColor = isDark ? '#94a3b8' : '#64748b';

        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                    label: 'Sales (₹)',
                    data: sales,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#dc2626',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                family: 'Outfit',
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                family: 'Outfit',
                                size: 10
                            },
                            callback: function(value) {
                                return '₹' + value;
                            }
                        }
                    }
                }
            }
        });

        // Toggle colors on dark theme change
        $('#theme-toggle').click(function() {
            setTimeout(function() {
                let isDark = document.documentElement.classList.contains('dark');
                myChart.options.scales.x.grid.color = isDark ? '#334155' : '#f1f5f9';
                myChart.options.scales.x.ticks.color = isDark ? '#94a3b8' : '#64748b';
                myChart.options.scales.y.grid.color = isDark ? '#334155' : '#f1f5f9';
                myChart.options.scales.y.ticks.color = isDark ? '#94a3b8' : '#64748b';
                myChart.update();
            }, 100);
        });
    });
</script>
@endsection
