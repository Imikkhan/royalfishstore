@extends('layouts.admin')

@section('title', 'Products Management')
@section('page_title', 'Products Management')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
        <div class="flex items-center gap-2">
            <button id="btn-add-product" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm active:scale-98">
                <i class="fa-solid fa-plus"></i> Add Product
            </button>
            <button id="btn-bulk-delete" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Bulk Delete
            </button>
        </div>
        <span class="text-xs text-slate-400">Total Products: <strong id="products-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive overflow-x-auto">
            <table id="products-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Sub-Category</th>
                        <th>Price</th>
                        <th>Original</th>
                        <th>Weight</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Create/Edit Product Modal -->
<div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-2xl w-full shadow-2xl overflow-hidden animate-fadeIn relative my-8">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 id="modal-title" class="font-bold text-slate-800 dark:text-white text-base">Add Product</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="product-form" class="p-6 space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="product-id">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Product Name</label>
                    <input type="text" id="product-name" required placeholder="Surmai King Fish Steaks" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Category</label>
                        <select id="product-category" required class="block w-full text-sm">
                            <option value="">Select</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Sub-Category</label>
                        <select id="product-subcategory" class="block w-full text-sm">
                            <option value="">Select Category First</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Price (₹)</label>
                    <input type="number" id="product-price" required placeholder="499" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Original Price (₹)</label>
                    <input type="number" id="product-original-price" placeholder="599" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Weight Metric (e.g. 500g)</label>
                    <input type="text" id="product-weight" required placeholder="500g" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Pieces Detail</label>
                    <input type="text" id="product-pieces" placeholder="5-7 Steaks" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Servings Count</label>
                    <input type="text" id="product-servings" placeholder="Serves 2-3" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tags (Comma Separated)</label>
                    <input type="text" id="product-tags" placeholder="Best Seller, Fresh Catch" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Serviced Pincodes (Comma Separated, e.g. 400001, 400002 or * for All)</label>
                <input type="text" id="product-pincodes" placeholder="400001, 400002, 110001 (or * for All locations)" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Description</label>
                <textarea id="product-description" rows="3" placeholder="Sourced daily, fresh, cleaned and vacuum packed..." class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm"></textarea>
            </div>

            <!-- Image Upload with Preview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Product Image File</label>
                    <input type="file" id="product-image" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 hover:file:bg-slate-200 cursor-pointer">
                </div>
                <div class="flex items-center gap-3">
                    <img id="image-preview" src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80" alt="Preview" class="w-16 h-16 rounded-xl border border-slate-200 object-cover bg-slate-100">
                    <div class="space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" id="product-bestseller" class="rounded border-slate-300 text-red-600"> Best Seller
                        </label>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" id="product-special" class="rounded border-slate-300 text-red-600"> Today's Special
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" id="btn-cancel" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Save Product</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Embed list of subcategories from the backend
        const subCategoriesList = @json($subCategories);

        // Initialize Select2 dropdown parent elements so they mount inside modal overlays
        $('#product-category').select2({
            placeholder: "Select Category",
            dropdownParent: $('#product-modal'),
            width: '100%'
        });

        $('#product-subcategory').select2({
            placeholder: "Select Subcategory",
            dropdownParent: $('#product-modal'),
            width: '100%'
        });

        // Filter and update subcategories dropdown when category is changed
        $('#product-category').on('change', function() {
            let parentId = $(this).val();
            let subSelect = $('#product-subcategory');
            subSelect.empty().append('<option value="">None / Select Subcategory</option>');
            
            let filtered = subCategoriesList.filter(s => s.parent_id == parentId);
            filtered.forEach(s => {
                subSelect.append(`<option value="${s.name}">${s.name}</option>`);
            });
            
            subSelect.trigger('change');
        });

        let table = $('#products-table').DataTable({
            ajax: {
                url: '/admin/products',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="product-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'product_code', className: 'font-mono text-xs text-slate-400' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center gap-3">
                                <img src="${row.image}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 shrink-0">
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white line-clamp-1">${row.name}</div>
                                    <div class="text-[9px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                        ${row.is_best_seller ? '<span class="px-1.5 py-0.5 rounded bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 font-bold text-[8px] uppercase">Best Seller</span>' : ''}
                                        ${row.is_today_special ? '<span class="px-1.5 py-0.5 rounded bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 font-bold text-[8px] uppercase">Today Special</span>' : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
                { data: 'category.name', className: 'font-semibold text-xs' },
                { data: 'sub_category', className: 'text-xs text-slate-400' },
                { data: 'price', className: 'font-extrabold text-red-600 dark:text-red-400 text-xs', render: p => '₹' + p },
                { data: 'original_price', className: 'text-slate-400 line-through text-xs', render: op => op ? '₹' + op : '-' },
                { data: 'weight', className: 'font-mono text-xs' },
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
                                <button class="btn-edit p-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded transition-colors text-xs" data-row='${JSON.stringify(row)}'><i class="fa-solid fa-pen-to-square"></i></button>
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
                $('#products-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Toggle Active Status AJAX
        $('#products-table').on('change', '.status-toggle', function() {
            let id = $(this).attr('data-id');
            let checkbox = $(this);
            $.ajax({
                url: '/admin/products/toggle-status',
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

        // Toggle modal
        function openModal(title) {
            $('#modal-title').text(title);
            $('#product-modal').removeClass('hidden');
        }

        function closeModal() {
            $('#product-modal').addClass('hidden');
            $('#product-form')[0].reset();
            $('#product-id').val('');
            $('#product-category').val('').trigger('change');
            $('#product-subcategory').val('').trigger('change');
            $('#product-pincodes').val('*');
            $('#image-preview').attr('src', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80');
        }

        $('#btn-add-product').click(function() {
            openModal('Add Product');
        });

        $('#close-modal, #btn-cancel').click(closeModal);

        // Preview uploaded image
        $('#product-image').change(function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Edit button click callback
        $('#products-table').on('click', '.btn-edit', function() {
            let row = JSON.parse($(this).attr('data-row'));
            $('#product-id').val(row.id);
            $('#product-name').val(row.name);
            
            // Set category and trigger change event to build subcategories options
            $('#product-category').val(row.category_id).trigger('change');
            
            // Wait slightly for reactive change options load, then set subcategory value
            setTimeout(function() {
                $('#product-subcategory').val(row.sub_category || '').trigger('change');
            }, 100);

            $('#product-price').val(row.price);
            $('#product-original-price').val(row.original_price || '');
            $('#product-weight').val(row.weight || '');
            $('#product-pieces').val(row.pieces || '');
            $('#product-servings').val(row.servings || '');
            $('#product-description').val(row.description || '');

            let tags = [];
            try {
                tags = typeof row.tags === 'string' ? JSON.parse(row.tags) : row.tags;
                if (!tags) tags = [];
            } catch(e) {}
            $('#product-tags').val(tags.join(', '));

            let pincodes = [];
            try {
                pincodes = typeof row.serviced_pincodes === 'string' ? JSON.parse(row.serviced_pincodes) : row.serviced_pincodes;
                if (!pincodes) pincodes = ['*'];
            } catch(e) { pincodes = ['*']; }
            $('#product-pincodes').val(pincodes.join(', '));

            $('#image-preview').attr('src', row.image);
            $('#product-bestseller').prop('checked', row.is_best_seller);
            $('#product-special').prop('checked', row.is_today_special);

            openModal('Edit Product');
        });

        // Form Submit via AJAX (Multipart FormData for image uploads)
        $('#product-form').submit(function(e) {
            e.preventDefault();
            
            let id = $('#product-id').val();
            let formData = new FormData();
            formData.append('name', $('#product-name').val());
            formData.append('category_id', $('#product-category').val());
            formData.append('sub_category', $('#product-subcategory').val() || '');
            formData.append('price', $('#product-price').val());
            formData.append('original_price', $('#product-original-price').val());
            formData.append('weight', $('#product-weight').val());
            formData.append('pieces', $('#product-pieces').val());
            formData.append('servings', $('#product-servings').val());
            formData.append('description', $('#product-description').val());
            formData.append('tags', $('#product-tags').val());
            formData.append('serviced_pincodes', $('#product-pincodes').val() || '*');
            
            let imageFile = $('#product-image')[0].files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            formData.append('is_best_seller', $('#product-bestseller').is(':checked') ? '1' : '0');
            formData.append('is_today_special', $('#product-special').is(':checked') ? '1' : '0');

            let url = id ? `/admin/products/update/${id}` : '/admin/products/store';

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    closeModal();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    let msg = errors ? Object.values(errors).flat().join('<br>') : 'Could not save product.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: msg,
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        });

        // Single delete callback
        $('#products-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Product?',
                text: 'This product listing will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/products/delete',
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
            $('.product-checkbox').prop('checked', this.checked);
        });

        // Bulk Delete Callback
        $('#btn-bulk-delete').click(function() {
            let selectedIds = [];
            $('.product-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more products to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Products?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/products/delete',
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
