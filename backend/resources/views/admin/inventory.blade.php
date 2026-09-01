@extends('layouts.admin')

@section('title', 'Stock & Inventory Management')
@section('page_title', 'Stock & Inventory Management')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Stock Units -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Stock Units</span>
                <h3 id="stat-total-units" class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalStockUnits) }}</h3>
                <p class="text-[10px] text-slate-400">Across {{ $totalItems }} products</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- In Stock Items -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between cursor-pointer hover:border-emerald-500/50 transition-colors stat-filter-card" data-filter="in_stock">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">In Stock Ready</span>
                <h3 id="stat-in-stock" class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $totalItems - $outOfStockCount - $lowStockCount }}</h3>
                <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-circle-check"></i> Normal Inventory</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-square-check"></i>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between cursor-pointer hover:border-amber-500/50 transition-colors stat-filter-card" data-filter="low_stock">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Low Stock Warnings</span>
                <h3 id="stat-low-stock" class="text-2xl font-black text-amber-500 dark:text-amber-400">{{ $lowStockCount }}</h3>
                <p class="text-[10px] text-amber-500 font-semibold"><i class="fa-solid fa-triangle-exclamation"></i> Below alert threshold</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-500 dark:text-amber-400 flex items-center justify-center text-lg animate-pulse">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between cursor-pointer hover:border-red-500/50 transition-colors stat-filter-card" data-filter="out_of_stock">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Out of Stock</span>
                <h3 id="stat-out-stock" class="text-2xl font-black text-red-600 dark:text-red-400">{{ $outOfStockCount }}</h3>
                <p class="text-[10px] text-red-500 font-semibold"><i class="fa-solid fa-circle-xmark"></i> Unavailable on site</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>

    </div>

    <!-- Filters & Action Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs space-y-4">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            <!-- Quick Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2" id="stock-filter-tabs">
                <button type="button" data-filter="all" class="filter-tab px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm active">
                    All Products
                </button>
                <button type="button" data-filter="in_stock" class="filter-tab px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                    🟢 In Stock
                </button>
                <button type="button" data-filter="low_stock" class="filter-tab px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30">
                    🟡 Low Stock Alert (<span class="low-stock-badge-count">{{ $lowStockCount }}</span>)
                </button>
                <button type="button" data-filter="out_of_stock" class="filter-tab px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30">
                    🔴 Out of Stock (<span class="out-stock-badge-count">{{ $outOfStockCount }}</span>)
                </button>
            </div>

            <!-- Category Filter & Bulk Actions -->
            <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                <select id="filter-category" class="px-3 py-2 text-xs font-semibold rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button id="btn-bulk-stock" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-layer-group"></i> Bulk Actions
                </button>

                <button id="btn-refresh" class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition-colors" title="Reload Table">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>
            </div>

        </div>

    </div>

    <!-- Inventory Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive overflow-x-auto">
            <table id="inventory-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>Code</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Current Stock Units</th>
                        <th>Purchase Limits (Min / Max)</th>
                        <th>Status</th>
                        <th>Quick Adjust</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Quick Adjust Stock Modal -->
<div id="stock-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm hidden p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-lg w-full shadow-2xl overflow-hidden animate-fadeIn relative">
        <div class="h-2 bg-amber-500 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/80 dark:bg-slate-950/60">
            <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white text-lg">Manage Product Inventory & Limits</h3>
                <p id="modal-product-title" class="text-xs text-slate-400 font-semibold truncate max-w-xs mt-0.5">Product Name</p>
            </div>
            <button type="button" id="close-stock-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold p-1"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <form id="stock-form" class="p-6 space-y-5">
            <input type="hidden" id="modal-product-id">

            <!-- Stock Count & Direct Action -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Available Stock Quantity (Units)</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" class="btn-quick-adjust-val py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl text-xs font-extrabold text-slate-700 dark:text-slate-200" data-val="10">+10 Units</button>
                    <button type="button" class="btn-quick-adjust-val py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl text-xs font-extrabold text-slate-700 dark:text-slate-200" data-val="50">+50 Units</button>
                    <button type="button" class="btn-quick-adjust-val py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl text-xs font-extrabold text-slate-700 dark:text-slate-200" data-val="100">+100 Units</button>
                </div>
                <div class="relative mt-1">
                    <input type="number" id="modal-stock-qty" min="0" required class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 text-base font-extrabold">
                </div>
            </div>

            <!-- In Stock Switch -->
            <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-950/50 rounded-xl border border-slate-200/80 dark:border-slate-800">
                <div>
                    <span class="text-xs font-bold text-slate-800 dark:text-white block">In-Stock Availability</span>
                    <span class="text-[11px] text-slate-400">Manual override to allow or pause customer purchases</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="modal-in-stock" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <!-- Purchase Limits (Min / Max Order Quantity) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-arrow-down-short-wide text-blue-500 mr-1"></i> Min Order Qty
                    </label>
                    <input type="number" id="modal-min-qty" min="1" placeholder="1" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl text-sm font-bold">
                    <span class="text-[10px] text-slate-400 mt-1 block">Min units user can buy</span>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-arrow-up-wide-short text-red-500 mr-1"></i> Max Order Qty
                    </label>
                    <input type="number" id="modal-max-qty" min="1" placeholder="10" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl text-sm font-bold">
                    <span class="text-[10px] text-slate-400 mt-1 block">Max units user can buy</span>
                </div>
            </div>

            <!-- Low Stock Threshold -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                    <i class="fa-solid fa-bell text-amber-500 mr-1"></i> Low Stock Alert Threshold
                </label>
                <input type="number" id="modal-low-threshold" min="0" placeholder="5" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl text-sm font-bold">
                <span class="text-[10px] text-slate-400 mt-1 block">Trigger warning alert when stock drops below this number</span>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" id="btn-cancel-stock" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800">Cancel</button>
                <button type="submit" id="btn-save-stock" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-extrabold shadow-md transition-transform active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div id="bulk-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm hidden p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-md w-full shadow-2xl overflow-hidden animate-fadeIn relative">
        <div class="h-2 bg-slate-800 dark:bg-slate-700 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/80 dark:bg-slate-950/60">
            <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white text-lg">Bulk Stock Actions</h3>
                <p id="bulk-selected-count" class="text-xs text-slate-400 font-semibold mt-0.5">0 products selected</p>
            </div>
            <button type="button" id="close-bulk-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold p-1"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <form id="bulk-form" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Action to Perform</label>
                <select id="bulk-action" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold">
                    <option value="add_stock">Add Stock (+X units to all selected)</option>
                    <option value="set_stock">Set Fixed Stock (Assign X units to all selected)</option>
                    <option value="mark_in_stock">Mark Selected as In Stock</option>
                    <option value="mark_out_of_stock">Mark Selected as Out of Stock</option>
                    <option value="set_limits">Set Min / Max Purchase Limits</option>
                </select>
            </div>

            <div id="bulk-qty-group">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Stock Quantity</label>
                <input type="number" id="bulk-quantity" min="0" placeholder="e.g. 50" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold">
            </div>

            <div id="bulk-limits-group" class="grid grid-cols-2 gap-3 hidden">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Min Order Qty</label>
                    <input type="number" id="bulk-min-qty" min="1" placeholder="1" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Max Order Qty</label>
                    <input type="number" id="bulk-max-qty" min="1" placeholder="10" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" id="btn-cancel-bulk" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 rounded-xl text-xs font-extrabold shadow-md">Apply Bulk Action</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentFilterStatus = 'all';
    let currentCategory = '';

    // Initialize DataTable
    const table = $('#inventory-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ url('/admin/inventory') }}",
            type: 'GET',
            data: function(d) {
                d.status = currentFilterStatus !== 'all' ? currentFilterStatus : '';
                d.category_id = currentCategory;
            },
            dataSrc: function(json) {
                // Update KPI metrics
                if (json.stats) {
                    $('#stat-total-units').text(Number(json.stats.total_stock_units).toLocaleString());
                    $('#stat-in-stock').text(Number(json.stats.in_stock_count).toLocaleString());
                    $('#stat-low-stock').text(Number(json.stats.low_stock_count).toLocaleString());
                    $('#stat-out-stock').text(Number(json.stats.out_of_stock_count).toLocaleString());
                    $('.low-stock-badge-count').text(json.stats.low_stock_count);
                    $('.out-stock-badge-count').text(json.stats.out_of_stock_count);
                }
                return json.data;
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                render: function(id) {
                    return `<div class="text-center"><input type="checkbox" class="product-select rounded border-slate-300" value="${id}"></div>`;
                }
            },
            {
                data: 'product_code',
                defaultContent: 'N/A',
                render: function(code) {
                    return `<span class="font-mono text-xs font-bold text-slate-500">${code || 'N/A'}</span>`;
                }
            },
            {
                data: 'name',
                render: function(name, type, data) {
                    return `
                        <div class="flex items-center gap-3">
                            <img src="${data.image}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-800 bg-slate-100 shrink-0" onerror="this.src='https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=100&q=80'">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 dark:text-white line-clamp-1 max-w-[200px]">${name}</h4>
                                <span class="text-[11px] font-extrabold text-[#fc490f]">₹${data.price}</span>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: 'category',
                defaultContent: 'N/A',
                render: function(cat) {
                    return `<span class="text-xs font-medium text-slate-600 dark:text-slate-400">${cat || 'N/A'}</span>`;
                }
            },
            {
                data: 'stock_quantity',
                render: function(qty, type, row) {
                    return `
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black ${qty <= 0 ? 'text-red-500' : (qty <= row.low_stock_threshold ? 'text-amber-500' : 'text-slate-900 dark:text-white')}">
                                ${qty} units
                            </span>
                        </div>
                    `;
                }
            },
            {
                data: 'min_order_qty',
                orderable: false,
                render: function(minQty, type, row) {
                    return `
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] font-extrabold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-md">
                                Min: ${minQty}
                            </span>
                            <span class="text-[10px] font-extrabold bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 px-2 py-0.5 rounded-md">
                                Max: ${row.max_order_qty}
                            </span>
                        </div>
                    `;
                }
            },
            {
                data: 'stock_status',
                render: function(status, type, row) {
                    if (status === 'out_of_stock') {
                        return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-red-100 text-red-700 dark:bg-red-950/50 dark:text-red-400 border border-red-200 dark:border-red-900/50"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</span>`;
                    } else if (status === 'low_stock') {
                        return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 animate-pulse"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock</span>`;
                    } else {
                        return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50"><i class="fa-solid fa-circle-check"></i> In Stock</span>`;
                    }
                }
            },
            {
                data: null,
                orderable: false,
                defaultContent: '',
                render: function(data, type, row) {
                    return `
                        <div class="flex items-center gap-1">
                            <button type="button" class="btn-quick-step p-1.5 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors" data-id="${row.id}" data-action="subtract" data-qty="5" title="Subtract 5 units">
                                -5
                            </button>
                            <button type="button" class="btn-quick-step p-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-600 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors" data-id="${row.id}" data-action="add" data-qty="5" title="Add 5 units">
                                +5
                            </button>
                            <button type="button" class="btn-quick-step p-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-600 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors" data-id="${row.id}" data-action="add" data-qty="20" title="Add 20 units">
                                +20
                            </button>
                        </div>
                    `;
                }
            },
            {
                data: null,
                orderable: false,
                defaultContent: '',
                render: function(data, type, row) {
                    return `
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-edit-stock px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center gap-1"
                                data-id="${row.id}"
                                data-name="${row.name}"
                                data-qty="${row.stock_quantity}"
                                data-instock="${row.in_stock ? 1 : 0}"
                                data-low="${row.low_stock_threshold}"
                                data-min="${row.min_order_qty}"
                                data-max="${row.max_order_qty}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <button type="button" class="btn-toggle-stock p-1.5 rounded-lg text-xs font-bold transition-colors ${row.in_stock ? 'text-emerald-500 hover:bg-emerald-50' : 'text-slate-400 hover:bg-slate-100'}" data-id="${row.id}" title="${row.in_stock ? 'Toggle to Out of Stock' : 'Toggle to In Stock'}">
                                <i class="fa-solid ${row.in_stock ? 'fa-toggle-on text-lg text-emerald-500' : 'fa-toggle-off text-lg text-slate-400'}"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[4, 'asc']] // Show lowest stock first by default
    });

    // Filter Tabs Click
    $('.filter-tab, .stat-filter-card').on('click', function() {
        const filter = $(this).data('filter');
        currentFilterStatus = filter;
        
        $('.filter-tab').removeClass('bg-slate-900 text-white dark:bg-white dark:text-slate-900 active')
            .addClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300');
        
        $(`.filter-tab[data-filter="${filter}"]`)
            .removeClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300')
            .addClass('bg-slate-900 text-white dark:bg-white dark:text-slate-900 active');

        table.ajax.reload();
    });

    // Category Filter Change
    $('#filter-category').on('change', function() {
        currentCategory = $(this).val();
        table.ajax.reload();
    });

    $('#btn-refresh').on('click', function() {
        table.ajax.reload();
    });

    // Check all checkbox
    $('#check-all').on('change', function() {
        $('.product-select').prop('checked', $(this).prop('checked'));
    });

    // Quick Step (+/-) Stock
    $(document).on('click', '.btn-quick-step', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        const qty = $(this).data('qty');

        $.ajax({
            url: "{{ url('/admin/inventory/update-stock') }}",
            type: 'POST',
            data: { id: id, action: action, quantity: qty },
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Stock Updated',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                    table.ajax.reload(null, false);
                }
            },
            error: function(err) {
                Swal.fire('Error', 'Failed to adjust stock', 'error');
            }
        });
    });

    // Toggle In Stock status
    $(document).on('click', '.btn-toggle-stock', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "{{ url('/admin/inventory/toggle-stock') }}",
            type: 'POST',
            data: { id: id },
            success: function(res) {
                if (res.success) {
                    table.ajax.reload(null, false);
                }
            }
        });
    });

    // Open Edit Stock Modal
    $(document).on('click', '.btn-edit-stock', function() {
        const btn = $(this);
        $('#modal-product-id').val(btn.data('id'));
        $('#modal-product-title').text(btn.data('name'));
        $('#modal-stock-qty').val(btn.data('qty'));
        $('#modal-in-stock').prop('checked', parseInt(btn.data('instock')) === 1);
        $('#modal-low-threshold').val(btn.data('low'));
        $('#modal-min-qty').val(btn.data('min'));
        $('#modal-max-qty').val(btn.data('max'));

        $('#stock-modal').removeClass('hidden');
    });

    $('#close-stock-modal, #btn-cancel-stock').on('click', function() {
        $('#stock-modal').addClass('hidden');
    });

    // Quick add value buttons in modal
    $('.btn-quick-adjust-val').on('click', function() {
        const val = parseInt($(this).data('val'));
        const cur = parseInt($('#modal-stock-qty').val()) || 0;
        $('#modal-stock-qty').val(cur + val);
    });

    // Save Stock Modal Form
    $('#stock-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#modal-product-id').val();
        const qty = $('#modal-stock-qty').val();
        const inStock = $('#modal-in-stock').is(':checked') ? 1 : 0;
        const low = $('#modal-low-threshold').val();
        const min = $('#modal-min-qty').val();
        const max = $('#modal-max-qty').val();

        $('#btn-save-stock').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: "{{ url('/admin/inventory/update-stock') }}",
            type: 'POST',
            data: {
                id: id,
                action: 'set',
                quantity: qty,
                in_stock: inStock,
                low_stock_threshold: low,
                min_order_qty: min,
                max_order_qty: max
            },
            success: function(res) {
                $('#btn-save-stock').prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save Changes');
                if (res.success) {
                    $('#stock-modal').addClass('hidden');
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            },
            error: function(err) {
                $('#btn-save-stock').prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save Changes');
                Swal.fire('Error', 'Failed to save stock updates', 'error');
            }
        });
    });

    // Bulk Actions
    $('#btn-bulk-stock').on('click', function() {
        const checked = $('.product-select:checked');
        if (checked.length === 0) {
            Swal.fire('No Products Selected', 'Please select at least one product using the checkboxes.', 'info');
            return;
        }
        $('#bulk-selected-count').text(`${checked.length} products selected`);
        $('#bulk-modal').removeClass('hidden');
    });

    $('#close-bulk-modal, #btn-cancel-bulk').on('click', function() {
        $('#bulk-modal').addClass('hidden');
    });

    $('#bulk-action').on('change', function() {
        const act = $(this).val();
        if (act === 'set_limits') {
            $('#bulk-qty-group').addClass('hidden');
            $('#bulk-limits-group').removeClass('hidden');
        } else if (act === 'mark_in_stock' || act === 'mark_out_of_stock') {
            $('#bulk-qty-group').addClass('hidden');
            $('#bulk-limits-group').addClass('hidden');
        } else {
            $('#bulk-qty-group').removeClass('hidden');
            $('#bulk-limits-group').addClass('hidden');
        }
    });

    $('#bulk-form').on('submit', function(e) {
        e.preventDefault();
        const selectedIds = [];
        $('.product-select:checked').each(function() {
            selectedIds.push($(this).val());
        });

        const action = $('#bulk-action').val();
        const qty = $('#bulk-quantity').val();
        const minQty = $('#bulk-min-qty').val();
        const maxQty = $('#bulk-max-qty').val();

        $.ajax({
            url: "{{ url('/admin/inventory/bulk-update') }}",
            type: 'POST',
            data: {
                ids: selectedIds,
                action: action,
                quantity: qty,
                min_order_qty: minQty,
                max_order_qty: maxQty
            },
            success: function(res) {
                if (res.success) {
                    $('#bulk-modal').addClass('hidden');
                    $('#check-all').prop('checked', false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Bulk Update Successful',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
                }
            },
            error: function(err) {
                Swal.fire('Error', 'Failed to perform bulk update', 'error');
            }
        });
    });

});
</script>
@endsection
