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
        <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto" id="bill-print-area">
            
            <!-- Quick Summary Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-950/40 p-4 rounded-xl border border-slate-200/40 dark:border-slate-800">
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Shipping Address</h4>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200" id="lbl-cust-name">John Doe</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400" id="lbl-cust-address">Flat 402, Royal Residency, Marine Drive, Mumbai</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono" id="lbl-cust-phone">+91 9876543210</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status & Delivery</h4>
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
                    <p class="text-xs text-slate-500 dark:text-slate-400">Fulfillment Status: <strong id="lbl-order-status" class="text-slate-700 dark:text-slate-300 font-bold">Placed</strong></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Delivery Slot: <strong id="lbl-order-delivery" class="text-slate-700 dark:text-slate-300 font-bold">30-45 mins</strong></p>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Order Items</h4>
                <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/40 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 text-[10px]">
                                <th class="py-2.5 px-4">Item Name</th>
                                <th class="py-2.5 px-4 text-center">Qty</th>
                                <th class="py-2.5 px-4 text-right">Price</th>
                                <th class="py-2.5 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-order-items" class="divide-y divide-slate-100 dark:divide-slate-800">
                            <!-- Populated dynamically -->
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td colspan="3" class="py-2 px-4 text-right font-bold text-slate-500 text-[10px] uppercase">Order Total</td>
                                <td class="py-2 px-4 text-right font-extrabold text-slate-800 dark:text-white" id="lbl-order-total">₹0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Methods Info -->
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-400 font-medium">Payment Mode: <strong id="lbl-order-payment" class="text-slate-700 dark:text-slate-300 uppercase font-bold bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">COD</strong></span>
                <span class="text-slate-400 font-medium">Order Placed: <span id="lbl-order-date" class="text-slate-600 dark:text-slate-300 font-semibold font-mono">19-Jul-2026</span></span>
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
        #bill-print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
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
                    $('#lbl-order-date').text(date.toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'}));

                    // Build items list
                    let itemsHtml = '';
                    (order.items || []).forEach(item => {
                        itemsHtml += `
                            <tr class="text-slate-700 dark:text-slate-300">
                                <td class="py-3 px-4 flex items-center gap-2">
                                    ${item.product_image ? `<img src="${item.product_image}" class="w-8 h-8 rounded object-cover bg-slate-100 shrink-0">` : ''}
                                    <span class="font-semibold line-clamp-1">${item.product_name}</span>
                                </td>
                                <td class="py-3 px-4 text-center font-bold">${item.quantity}</td>
                                <td class="py-3 px-4 text-right font-medium">₹${item.price}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-800 dark:text-white">₹${item.price * item.quantity}</td>
                            </tr>
                        `;
                    });
                    $('#tbl-order-items').html(itemsHtml);

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
