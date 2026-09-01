@extends('layouts.admin')

@section('title', 'Logistics & Shipping')
@section('page_title', 'Logistics & Shipping Control')

@section('content')
<div class="space-y-6">

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Riders -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Fleet Strength</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalRiders }}</h3>
                <p class="text-[10px] text-slate-400">Total Delivery Agents</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-id-card"></i>
            </div>
        </div>

        <!-- Available Riders -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Available Fleet</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $availableRiders }}</h3>
                <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-circle text-[8px]"></i> Ready for Dispatch</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
        </div>

        <!-- Pending / Active Deliveries -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Shipments</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $pendingShipments }}</h3>
                <p class="text-[10px] text-amber-500 font-semibold"><i class="fa-solid fa-truck-ramp-box"></i> In Transit / Pending</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg animate-pulse">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
        </div>

        <!-- Delivered Today -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Fulfilled Today</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $completedToday }}</h3>
                <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-circle-check"></i> Delivered successfully</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-house-circle-check"></i>
            </div>
        </div>

    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="flex gap-4">
            <button id="tab-btn-shipments" class="py-3 px-4 text-xs font-bold border-b-2 border-red-600 text-red-600 dark:text-red-400 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-truck-fast"></i> Shipments & Dispatch Control
            </button>
            <button id="tab-btn-riders" class="py-3 px-4 text-xs font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-users"></i> Fleet & Riders Roster
            </button>
        </nav>
    </div>

    <!-- TAB 1: SHIPMENTS & DISPATCH CONTROL -->
    <div id="tab-content-shipments" class="space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-base">Live Orders Dispatch Console</h3>
                    <span class="text-xs text-slate-400">Assign rider and track delivery status</span>
                </div>
                <a href="{{ url('/rider/login') }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white text-xs font-bold rounded-xl flex items-center gap-2 transition-all shadow-xs cursor-pointer">
                    <i class="fa-solid fa-motorcycle text-amber-400"></i> Open Rider Portal <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                </a>
            </div>

            <div class="table-responsive overflow-x-auto">
                <table id="shipments-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800 text-xs">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 text-slate-400 font-bold uppercase text-[10px]">
                            <th>Order ID</th>
                            <th>Customer & Location</th>
                            <th>Payment Mode</th>
                            <th>Assign Delivery Agent</th>
                            <th>Shipment Status</th>
                            <th>Dispatch Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($activeOrders as $order)
                        @php
                            $address = $order->address_data ?? [];
                            $custName = $order->user->name ?? ($address['name'] ?? 'Guest');
                            $custPhone = $address['phone'] ?? ($order->user->phone ?? 'N/A');
                            $custCity = ($address['city'] ?? '') . ' (' . ($address['zipCode'] ?? 'Pincode N/A') . ')';
                        @endphp
                        <tr>
                            <td class="font-mono font-extrabold text-slate-800 dark:text-white">{{ $order->id }}</td>
                            <td>
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $custName }}</div>
                                <div class="text-[10px] text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-phone text-[9px]"></i> {{ $custPhone }} | {{ $custCity }}
                                </div>
                            </td>
                            <td>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_method === 'COD' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' }}">
                                    {{ $order->payment_method }} (₹{{ $order->total_price }})
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5 min-w-[240px]">
                                    <select class="sel-assign-rider px-2.5 py-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:outline-none flex-1" data-order-id="{{ $order->id }}">
                                        <option value="">-- Select Rider --</option>
                                        @foreach($ridersList as $r)
                                            @php
                                                $isOnline = ($r->status === 'Available' || $r->status === 'On Delivery');
                                                $dot = $isOnline ? '🟢' : '🔴';
                                            @endphp
                                            <option value="{{ $r->id }}" {{ $order->rider_id == $r->id ? 'selected' : '' }}>
                                                {{ $dot }} {{ $r->name }} ({{ $r->vehicle_type }} - {{ $r->status }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-assign-rider-now px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold rounded-lg transition-all shadow-xs active:scale-95 shrink-0" data-order-id="{{ $order->id }}" title="Assign & Dispatch Order">
                                        <i class="fa-solid fa-truck-fast"></i> Assign
                                    </button>
                                </div>
                            </td>
                            <td>
                                @php
                                    $shipColors = [
                                        'Pending Assignment' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                        'Dispatched' => 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',
                                        'Out for Delivery' => 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
                                        'Delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',
                                        'Delivery Failed' => 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400',
                                    ];
                                    $shipColor = $shipColors[$order->shipment_status] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <select class="sel-shipment-status px-2.5 py-1 rounded-lg text-xs font-bold {{ $shipColor }} border border-slate-200 dark:border-slate-700 focus:outline-none" data-order-id="{{ $order->id }}">
                                    <option value="Pending Assignment" {{ $order->shipment_status === 'Pending Assignment' ? 'selected' : '' }}>Pending Assignment</option>
                                    <option value="Dispatched" {{ $order->shipment_status === 'Dispatched' ? 'selected' : '' }}>Dispatched</option>
                                    <option value="Out for Delivery" {{ $order->shipment_status === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="Delivered" {{ $order->shipment_status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="Delivery Failed" {{ $order->shipment_status === 'Delivery Failed' ? 'selected' : '' }}>Delivery Failed</option>
                                </select>
                            </td>
                            <td class="font-mono text-[11px] text-slate-400">
                                {{ $order->dispatched_at ? $order->dispatched_at->format('d M, h:i A') : 'Not Dispatched' }}
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-eye"></i> View Order
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: FLEET & RIDERS ROSTER -->
    <div id="tab-content-riders" class="space-y-6 hidden">
        
        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
            <div class="flex items-center gap-2">
                <button id="btn-add-rider" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm active:scale-98">
                    <i class="fa-solid fa-user-plus"></i> Add Delivery Rider
                </button>
                <button id="btn-bulk-delete-riders" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-trash-can"></i> Bulk Delete
                </button>
            </div>
            <span class="text-xs text-slate-400">Registered Fleet: <strong id="riders-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
        </div>

        <!-- Data Table Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
            <div class="table-responsive overflow-x-auto">
                <table id="riders-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                    <thead>
                        <tr>
                            <th class="w-10 text-center"><input type="checkbox" id="check-all-riders" class="rounded border-slate-300"></th>
                            <th>ID</th>
                            <th>Rider Name</th>
                            <th>Login Email</th>
                            <th>Phone</th>
                            <th>Vehicle Type</th>
                            <th>Reg Number</th>
                            <th>Rate (₹)</th>
                            <th>Duty Status</th>
                            <th>Active Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Create/Edit Rider Modal -->
<div id="rider-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-lg w-full shadow-2xl overflow-hidden animate-fadeIn relative my-8">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 id="rider-modal-title" class="font-bold text-slate-800 dark:text-white text-base">Add Delivery Rider</h3>
            <button id="close-rider-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="rider-form" class="p-6 space-y-4">
            <input type="hidden" id="rider-id">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Rider Full Name</label>
                    <input type="text" id="rider-name" required placeholder="Rajesh Kumar" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                    <input type="text" id="rider-phone" required placeholder="9876543210" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <!-- Login Credentials -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3 bg-slate-50 dark:bg-slate-950/40 rounded-2xl border border-slate-200/50 dark:border-slate-800">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Portal Login Email</label>
                    <input type="email" id="rider-email" required placeholder="rajesh@royalfish.com" class="block w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-lg focus:outline-none text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="rider-password" placeholder="••••••••" class="block w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-lg focus:outline-none text-xs">
                    <span class="text-[9px] text-slate-400">Leave blank to keep current</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Vehicle Type</label>
                    <select id="rider-vehicle-type" required class="block w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none text-xs">
                        <option value="Motorbike">Motorbike</option>
                        <option value="Scooter">Scooter</option>
                        <option value="EV Delivery Van">EV Delivery Van</option>
                        <option value="Auto">Auto Cargo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Reg Number</label>
                    <input type="text" id="rider-vehicle-number" required placeholder="MH-01-AB-1234" class="block w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none text-xs uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Payout Rate (₹)</label>
                    <input type="number" id="rider-rate" value="50" required placeholder="50" class="block w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none text-xs">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Assigned Pincodes (Comma Separated)</label>
                <input type="text" id="rider-pincodes" placeholder="400001, 400002, 400003" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Current Duty Status</label>
                <select id="rider-status" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                    <option value="Available">Available</option>
                    <option value="On Delivery">On Delivery</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" id="btn-cancel-rider" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="btn-save-rider" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Save Rider Account</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        
        // Tab switching logic
        $('#tab-btn-shipments').click(function() {
            $(this).addClass('border-red-600 text-red-600 dark:text-red-400').removeClass('border-transparent text-slate-400');
            $('#tab-btn-riders').removeClass('border-red-600 text-red-600 dark:text-red-400').addClass('border-transparent text-slate-400');
            $('#tab-content-shipments').removeClass('hidden');
            $('#tab-content-riders').addClass('hidden');
        });

        $('#tab-btn-riders').click(function() {
            $(this).addClass('border-red-600 text-red-600 dark:text-red-400').removeClass('border-transparent text-slate-400');
            $('#tab-btn-shipments').removeClass('border-red-600 text-red-600 dark:text-red-400').addClass('border-transparent text-slate-400');
            $('#tab-content-riders').removeClass('hidden');
            $('#tab-content-shipments').addClass('hidden');
        });

        // Initialize Select2 Searchable Dropdown for Rider Assignment
        if ($.fn.select2) {
            $('.sel-assign-rider').select2({
                placeholder: '🔍 Search Rider Name, Vehicle, Status...',
                allowClear: true,
                width: '100%'
            });
        }

        function performRiderAssignment(orderId, riderId) {
            if (!riderId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Rider',
                    text: 'Please select a delivery rider from the dropdown first.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            $.ajax({
                url: '/admin/logistics/assign-rider',
                method: 'POST',
                data: {
                    order_id: orderId,
                    rider_id: riderId
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Shipment Dispatched!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Could not assign rider.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        }

        // Quick Assign Rider Click & Select2 change callback
        $(document).on('click', '.btn-assign-rider-now', function(e) {
            e.preventDefault();
            let orderId = $(this).attr('data-order-id');
            let selectBox = $(this).closest('td').find('.sel-assign-rider');
            let riderId = selectBox.val();
            performRiderAssignment(orderId, riderId);
        });

        $(document).on('change select2:select', '.sel-assign-rider', function() {
            let orderId = $(this).attr('data-order-id');
            let riderId = $(this).val();
            if (riderId) {
                performRiderAssignment(orderId, riderId);
            }
        });

        // Quick Update Shipment Status AJAX callback
        $('.sel-shipment-status').on('change', function() {
            let orderId = $(this).attr('data-order-id');
            let shipmentStatus = $(this).val();

            $.ajax({
                url: '/admin/logistics/update-shipment-status',
                method: 'POST',
                data: {
                    order_id: orderId,
                    shipment_status: shipmentStatus
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
            });
        });

        // Initialize DataTables for Riders Roster
        let table = $('#riders-table').DataTable({
            ajax: {
                url: '/admin/logistics/riders',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="rider-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'id', className: 'font-mono text-xs text-slate-400' },
                { data: 'name', className: 'font-bold text-slate-800 dark:text-white' },
                { data: 'email', className: 'font-mono text-xs text-blue-600 dark:text-blue-400' },
                { data: 'phone', className: 'font-mono text-xs' },
                { 
                    data: 'vehicle_type',
                    render: function(data) {
                        return `<span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold text-[10px] uppercase">${data}</span>`;
                    }
                },
                { data: 'vehicle_number', className: 'font-mono text-xs font-bold uppercase' },
                { 
                    data: 'earnings_per_delivery', 
                    className: 'font-bold text-emerald-600 dark:text-emerald-400 text-xs',
                    render: rate => '₹' + (rate || 50)
                },
                {
                    data: 'status',
                    render: function(data) {
                        let statusColors = {
                            'Available': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',
                            'On Delivery': 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
                            'Offline': 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400'
                        };
                        let color = statusColors[data] || 'bg-slate-100 text-slate-700';
                        return `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${color}">${data}</span>`;
                    }
                },
                {
                    data: 'is_active',
                    render: function(data, type, row) {
                        let checked = (data == 1 || data === true || data === '1') ? 'checked' : '';
                        return `
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="status-toggle sr-only peer" data-id="${row.id}" ${checked}>
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-red-600"></div>
                            </label>
                        `;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="flex gap-1.5">
                                <button class="btn-edit-rider p-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded transition-colors text-xs" data-row='${JSON.stringify(row)}'><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn-delete-rider p-1.5 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 hover:bg-red-100 rounded transition-colors text-xs" data-id="${row.id}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            drawCallback: function(settings) {
                $('#riders-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Toggle Active Status AJAX
        $('#riders-table').on('change', '.status-toggle', function() {
            let id = $(this).attr('data-id');
            let checkbox = $(this);
            $.ajax({
                url: '/admin/logistics/riders/toggle-status',
                method: 'POST',
                data: { id: id },
                success: function(res) {
                    table.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.message,
                        timer: 1000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                },
                error: function() {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            });
        });

        // Modal Helpers
        function openRiderModal(title) {
            $('#rider-modal-title').text(title);
            $('#rider-modal').removeClass('hidden');
        }

        function closeRiderModal() {
            $('#rider-modal').addClass('hidden');
            $('#rider-form')[0].reset();
            $('#rider-id').val('');
        }

        $('#btn-add-rider').click(function() {
            openRiderModal('Add Delivery Rider');
        });

        $('#close-rider-modal, #btn-cancel-rider').click(closeRiderModal);

        // Edit Rider Trigger
        $('#riders-table').on('click', '.btn-edit-rider', function() {
            let row = JSON.parse($(this).attr('data-row'));
            $('#rider-id').val(row.id);
            $('#rider-name').val(row.name);
            $('#rider-phone').val(row.phone);
            $('#rider-email').val(row.email || '');
            $('#rider-password').val('');
            $('#rider-vehicle-type').val(row.vehicle_type);
            $('#rider-vehicle-number').val(row.vehicle_number);
            $('#rider-rate').val(row.earnings_per_delivery || 50);
            
            let pincodes = [];
            try {
                pincodes = typeof row.operating_pincodes === 'string' ? JSON.parse(row.operating_pincodes) : row.operating_pincodes;
            } catch(e){}
            $('#rider-pincodes').val(pincodes ? pincodes.join(', ') : '');
            $('#rider-status').val(row.status || 'Available');

            openRiderModal('Edit Delivery Rider');
        });

        // Submit Form via AJAX
        $('#rider-form').submit(function(e) {
            e.preventDefault();
            
            let id = $('#rider-id').val();
            let data = {
                name: $('#rider-name').val(),
                phone: $('#rider-phone').val(),
                email: $('#rider-email').val(),
                password: $('#rider-password').val(),
                vehicle_type: $('#rider-vehicle-type').val(),
                vehicle_number: $('#rider-vehicle-number').val(),
                earnings_per_delivery: $('#rider-rate').val(),
                operating_pincodes: $('#rider-pincodes').val(),
                status: $('#rider-status').val() || 'Available'
            };

            let url = id ? `/admin/logistics/riders/update/${id}` : '/admin/logistics/riders/store';

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(res) {
                    closeRiderModal();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    let msg = errors ? Object.values(errors).flat().join('<br>') : 'Could not save rider details.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: msg,
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        });

        // Single Delete
        $('#riders-table').on('click', '.btn-delete-rider', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Delivery Rider?',
                text: 'This rider record will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/logistics/riders/delete',
                        method: 'POST',
                        data: { ids: [id] },
                        success: function(res) {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

        // Bulk Delete
        $('#check-all-riders').click(function() {
            $('.rider-checkbox').prop('checked', this.checked);
        });

        $('#btn-bulk-delete-riders').click(function() {
            let selectedIds = [];
            $('.rider-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more riders to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Riders?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/logistics/riders/delete',
                        method: 'POST',
                        data: { ids: selectedIds },
                        success: function(res) {
                            table.ajax.reload();
                            $('#check-all-riders').prop('checked', false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

    });
</script>
@endsection
@endsection
