@extends('layouts.admin')

@section('title', 'Order Tracking')
@section('page_title', 'Order Tracking')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
        <div class="flex items-center gap-2">
            <button id="btn-bulk-delete" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Bulk Delete
            </button>
        </div>
        <span class="text-xs text-slate-400">Total Orders: <strong id="orders-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive">
            <table id="orders-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Payment</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date Placed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Order Detail Modal -->
<div id="order-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-2xl w-full shadow-2xl overflow-hidden animate-fadeIn relative my-8">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 dark:text-white text-base">Order Details: <span id="lbl-order-id" class="font-mono text-red-600 dark:text-red-400">ROYAL-XXXXXX</span></h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <!-- Modal body -->
        <div class="p-8 space-y-6 max-h-[75vh] overflow-y-auto bg-white dark:bg-slate-900" id="bill-print-area">
            <!-- Invoice Header -->
            <div class="flex justify-between items-start border-b-2 border-red-600 pb-6">
                <div>
                    <!-- Logo and Brand -->
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">🐟</span>
                        <h2 class="text-xl font-extrabold text-red-600 tracking-tight uppercase">Royal Fish Store</h2>
                    </div>
                    <p class="text-xs text-slate-500">Premium Fresh Fish & Seafood Delivered to Your Doorstep</p>
                    <p class="text-[11px] text-slate-400 mt-1">Email: care@royalfish.com | Tel: +91 98765 43210</p>
                </div>
                <div class="text-right">
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-wider uppercase mb-1">INVOICE</h1>
                    <p class="text-xs font-mono text-slate-500">No: <span id="lbl-order-id-txt" class="font-bold text-red-600 dark:text-red-400">ROYAL-XXXXXX</span></p>
                    <p class="text-xs text-slate-500 mt-1">Date: <span id="lbl-order-date-txt" class="font-semibold font-mono">19-Jul-2026</span></p>
                </div>
            </div>

            <!-- Customer & Payment Metadata Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-2">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Billed To (Shipping Address)</h3>
                    <div class="space-y-1 bg-slate-50 dark:bg-slate-950/30 p-4 rounded-xl border border-slate-200/50 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200" id="lbl-cust-name">John Doe</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed" id="lbl-cust-address">Flat 402, Royal Residency, Marine Drive, Mumbai</p>
                        <p class="text-xs font-mono text-slate-500 flex items-center gap-1.5 mt-2">
                            <i class="fa-solid fa-phone text-slate-400 text-[10px]"></i> <span id="lbl-cust-phone">+91 98765 43210</span>
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Order & Payment Details</h3>
                    <div class="space-y-2 bg-slate-50 dark:bg-slate-950/30 p-4 rounded-xl border border-slate-200/50 dark:border-slate-800 relative">
                        <!-- Status Update Actions (Shown only on screen) -->
                        <div class="flex items-center gap-2 mb-2 no-print">
                            <select id="sel-order-status" class="px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-lg text-xs font-bold focus:outline-none">
                                <option value="Placed">Placed</option>
                                <option value="Processing">Processing</option>
                                <option value="Dispatched">Dispatched</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <button id="btn-update-status" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold rounded-lg transition-colors shadow-xs">Update</button>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Payment Status:</span>
                            <span id="lbl-invoice-payment-status" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">COD (Pending)</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500">Payment Method:</span>
                            <span id="lbl-order-payment" class="font-bold text-slate-700 dark:text-slate-300 uppercase">COD</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Delivery Slot:</span>
                            <span id="lbl-order-delivery" class="font-semibold text-slate-700 dark:text-slate-300">30-45 mins</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Order Status:</span>
                            <span id="lbl-order-status" class="font-semibold text-slate-700 dark:text-slate-300">Placed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Line Items</h3>
                <div class="overflow-hidden border border-slate-200/60 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-950/50 text-slate-600 dark:text-slate-400 font-bold uppercase border-b border-slate-200/60 dark:border-slate-800 text-[10px]">
                                <th class="py-3 px-4">Item Details</th>
                                <th class="py-3 px-4 text-center w-24">Unit Price</th>
                                <th class="py-3 px-4 text-center w-16">Qty</th>
                                <th class="py-3 px-4 text-right w-28">Total</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-order-items" class="divide-y divide-slate-100 dark:divide-slate-800">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calculation Summary Grid -->
            <div class="flex justify-end pt-2">
                <div class="w-full sm:w-80 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Items Subtotal:</span>
                        <span id="lbl-invoice-subtotal" class="font-semibold text-slate-800 dark:text-slate-200">₹0</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Delivery Charges:</span>
                        <span id="lbl-invoice-delivery" class="font-semibold text-slate-800 dark:text-slate-200">₹0</span>
                    </div>
                    <div class="flex justify-between items-center text-emerald-600 dark:text-emerald-400 font-medium">
                        <span>Discount Applied:</span>
                        <span id="lbl-invoice-discount">₹0</span>
                    </div>
                    <div class="flex justify-between items-center text-base font-black text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800 pt-2">
                        <span>Grand Total:</span>
                        <span id="lbl-order-total" class="text-red-600 dark:text-red-400 font-extrabold text-base">₹0</span>
                    </div>
                </div>
            </div>

            <!-- Invoice Footer / T&C -->
            <div class="border-t border-slate-200/60 dark:border-slate-800 pt-6 text-center text-[10px] text-slate-400 space-y-1 mt-8 select-none">
                <p class="font-bold text-slate-500 dark:text-slate-400">Thank you for ordering fresh catch with Royal Fish Store!</p>
                <p>For return/refund support or shipping queries, please contact our support desk.</p>
                <p class="text-slate-300 dark:text-slate-600 font-mono mt-2">This is a system-generated electronic invoice. No physical signature is required.</p>
            </div>
        </div>

        <div class="p-6 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <button type="button" id="btn-print-bill" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5"><i class="fa-solid fa-print"></i> Print Invoice</button>
            <button type="button" id="btn-close-modal" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Close</button>
        </div>
    </div>
</div>

<!-- Print invoice CSS styling block -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #bill-print-area, #bill-print-area * {
            visibility: visible;
        }
        #order-modal {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            display: block !important;
            background: transparent !important;
        }
        #order-modal > div {
            position: static !important;
            display: block !important;
            max-width: none !important;
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            overflow: visible !important;
        }
        #order-modal > div > *:not(#bill-print-area) {
            display: none !important;
        }
        #bill-print-area {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: auto !important;
            background: white !important;
            color: #0f172a !important;
            padding: 0px !important;
            margin: 0px !important;
            overflow: visible !important;
            max-height: none !important;
            font-size: 12px !important;
        }
        #bill-print-area * {
            background-color: transparent !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
            box-shadow: none !important;
        }
        #bill-print-area select,
        #bill-print-area button,
        #bill-print-area .no-print,
        .no-print {
            display: none !important;
        }
        /* Ensure table styling is crisp in print */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 10px !important;
        }
        th, td {
            border: 1px solid #cbd5e1 !important;
            padding: 8px 10px !important;
        }
        th {
            background-color: #f1f5f9 !important;
        }
        .text-red-600 {
            color: #dc2626 !important;
        }
        .border-red-600 {
            border-color: #dc2626 !important;
        }
        .text-emerald-600 {
            color: #059669 !important;
        }
        .font-bold {
            font-weight: 700 !important;
        }
        .font-extrabold {
            font-weight: 800 !important;
        }
        .text-base {
            font-size: 14px !important;
        }
    }
</style>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#sel-order-status').select2({
            placeholder: "Select Status",
            dropdownParent: $('#order-modal'),
            width: '120px'
        });

        let table = $('#orders-table').DataTable({
            ajax: {
                url: '/admin/orders',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="order-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'id', className: 'font-extrabold font-mono text-slate-800 dark:text-white' },
                {
                    data: null,
                    render: function(data, type, row) {
                        let name = row.user ? row.user.name : (row.address_data ? row.address_data.name : 'Guest Customer');
                        return `<span class="font-semibold">${name}</span>`;
                    }
                },
                { data: 'payment_method', className: 'font-mono text-xs uppercase' },
                { data: 'total_price', className: 'font-extrabold text-red-600 dark:text-red-400', render: p => '₹' + p },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        let statusColors = {
                            'Placed': 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',
                            'Processing': 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
                            'Dispatched': 'bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400',
                            'Delivered': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',
                            'Cancelled': 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400'
                        };
                        let color = statusColors[data] || 'bg-slate-100 text-slate-700';
                        return `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${color}">${data}</span>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        let date = new Date(data);
                        return `<span class="text-xs text-slate-400 font-mono">${date.toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'})}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="flex gap-1.5">
                                <button class="btn-view p-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded transition-colors text-xs" data-id="${row.id}"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-delete p-1.5 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 hover:bg-red-100 rounded transition-colors text-xs" data-id="${row.id}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            drawCallback: function(settings) {
                $('#orders-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Close Modal callbacks
        $('#close-modal, #btn-close-modal').click(function() {
            $('#order-modal').addClass('hidden');
        });

        // View Order Details Modal trigger
        $('#orders-table').on('click', '.btn-view', function() {
            let id = $(this).attr('data-id');
            
            $.ajax({
                url: `/admin/orders/details/${id}`,
                method: 'GET',
                success: function(order) {
                    $('#lbl-order-id').text(order.id);
                    $('#lbl-order-id-txt').text(order.id);
                    
                    let address = order.address_data || {};
                    let custName = order.user ? order.user.name : (address.name || 'Guest');
                    
                    $('#lbl-cust-name').text(custName);
                    $('#lbl-cust-address').text(`${address.addressLine || ''}, ${address.city || ''} - ${address.zipCode || ''}`);
                    $('#lbl-cust-phone').text(address.phone || order.user?.phone || '');
                    
                    $('#lbl-order-status').text(order.status);
                    $('#sel-order-status').val(order.status).trigger('change');
                    $('#lbl-order-delivery').text(order.estimated_delivery || '30-45 mins');
                    $('#lbl-order-payment').text(order.payment_method);
                    $('#lbl-order-total').text('₹' + order.total_price);
                    
                    let date = new Date(order.created_at);
                    let formattedDate = date.toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
                    $('#lbl-order-date').text(formattedDate);
                    $('#lbl-order-date-txt').text(formattedDate);

                    // Set invoice payment status text & styling dynamically
                    let pStatus = order.payment_method === 'COD' ? 'COD (Pending)' : 'PAID (UPI/Card)';
                    let pClass = order.payment_method === 'COD' 
                        ? 'px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50' 
                        : 'px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50';
                    $('#lbl-invoice-payment-status').text(pStatus).attr('class', pClass);

                    // Build items list & calculate subtotal
                    let subtotal = 0;
                    let itemsHtml = '';
                    (order.items || []).forEach(item => {
                        let itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        
                        let imgSrc = item.product_image || 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=100&q=80';
                        
                        itemsHtml += `
                            <tr class="text-slate-700 dark:text-slate-300">
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <img src="${imgSrc}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 shrink-0 border border-slate-100 dark:border-slate-800" onerror="this.src='https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=100&q=80'">
                                    <span class="font-semibold line-clamp-2">${item.product_name}</span>
                                </td>
                                <td class="py-3 px-4 text-center font-medium">₹${item.price}</td>
                                <td class="py-3 px-4 text-center font-bold text-slate-800 dark:text-white">${item.quantity}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900 dark:text-white">₹${itemTotal}</td>
                            </tr>
                        `;
                    });
                    $('#tbl-order-items').html(itemsHtml);

                    // Dynamic summary calculations
                    let delivery = subtotal >= 499 ? 0 : 49;
                    let discount = (subtotal + delivery) - order.total_price;
                    if (discount < 0) discount = 0;

                    $('#lbl-invoice-subtotal').text('₹' + subtotal);
                    $('#lbl-invoice-delivery').text(delivery === 0 ? 'FREE' : '₹' + delivery);
                    $('#lbl-invoice-discount').text(discount === 0 ? '₹0' : '-₹' + discount);

                    // Store active ID on button
                    $('#btn-update-status').attr('data-id', order.id);

                    $('#order-modal').removeClass('hidden');
                }
            });
        });

        // Update status click callback
        $('#btn-update-status').click(function() {
            let id = $(this).attr('data-id');
            let status = $('#sel-order-status').val();

            $.ajax({
                url: `/admin/orders/update-status/${id}`,
                method: 'POST',
                data: { status: status },
                success: function(res) {
                    $('#lbl-order-status').text(res.status);
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        // Print Bill callback
        $('#btn-print-bill').click(function() {
            window.print();
        });

        // Single delete callback
        $('#orders-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Order Record?',
                text: 'This order record will be permanently deleted from logs.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/orders/delete',
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

        // Check All checkboxes
        $('#check-all').click(function() {
            $('.order-checkbox').prop('checked', this.checked);
        });

        // Bulk Delete Callback
        $('#btn-bulk-delete').click(function() {
            let selectedIds = [];
            $('.order-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more orders to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Orders?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/orders/delete',
                        method: 'POST',
                        data: { ids: selectedIds },
                        success: function(res) {
                            table.ajax.reload();
                            $('#check-all').prop('checked', false);
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
