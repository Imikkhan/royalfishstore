@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
        <div class="flex items-center gap-2">
            <button id="btn-add-category" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm active:scale-98">
                <i class="fa-solid fa-plus"></i> Add Category
            </button>
            <button id="btn-bulk-delete" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Bulk Delete
            </button>
        </div>
        <span class="text-xs text-slate-400">Total Categories: <strong id="categories-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive">
            <table id="categories-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>ID</th>
                        <th>Category Image</th>
                        <th>Category Name</th>
                        <th>Level / Parent</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Create/Edit Category Modal -->
<div id="category-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-md w-full shadow-2xl overflow-hidden animate-fadeIn relative">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 id="modal-title" class="font-bold text-slate-800 dark:text-white text-base">Add Category</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="category-form" class="p-6 space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="category-id">
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Category Name</label>
                <input type="text" id="category-name" required placeholder="Fish & Seafood" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Parent Category</label>
                <select id="category-parent" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                    <option value="">None (Main Category)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Description</label>
                <textarea id="category-description" rows="2" placeholder="Fresh catch from seawater and freshwater..." class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm"></textarea>
            </div>

            <!-- Image Upload Section -->
            <div class="grid grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Category Image</label>
                    <input type="file" id="category-image" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 hover:file:bg-slate-200 cursor-pointer">
                </div>
                <div class="flex items-center gap-3">
                    <img id="category-image-preview" src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80" alt="Preview" class="w-14 h-14 rounded-xl border border-slate-200 object-cover bg-slate-100">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" id="btn-cancel" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Save Category</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#category-parent').select2({
            placeholder: "None (Main Category)",
            dropdownParent: $('#category-modal'),
            width: '100%'
        });

        let table = $('#categories-table').DataTable({
            ajax: {
                url: '/admin/categories',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="category-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'id' },
                {
                    data: 'image',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data) {
                            return `<img src="${data}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-200 mx-auto">`;
                        }
                        return `<div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-850/50 text-slate-400 dark:text-slate-500 flex items-center justify-center text-[10px] font-extrabold mx-auto border border-dashed border-slate-300 dark:border-slate-800">NO IMG</div>`;
                    }
                },
                { data: 'name', className: 'font-bold' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.parent ? `<span class="bg-red-50 dark:bg-red-950/30 px-2 py-0.5 rounded text-[10px] font-bold text-red-600 dark:text-red-400">Sub of <strong>${row.parent.name}</strong></span>` : '<span class="text-xs text-slate-400 font-semibold">Main Category</span>';
                    }
                },
                { data: 'slug', className: 'font-mono text-xs text-slate-400' },
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
                $('#categories-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Toggle category status AJAX
        $('#categories-table').on('change', '.status-toggle', function() {
            let id = $(this).attr('data-id');
            let checkbox = $(this);
            $.ajax({
                url: '/admin/categories/toggle-status',
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
                    checkbox.prop('checked', !checkbox.prop('checked')); // Reset
                }
            });
        });

        // Preview uploaded image
        $('#category-image').change(function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#category-image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Toggle modal
        function openModal(title) {
            $('#modal-title').text(title);
            $('#category-modal').removeClass('hidden');
        }

        function closeModal() {
            $('#category-modal').addClass('hidden');
            $('#category-form')[0].reset();
            $('#category-id').val('');
            $('#category-parent').val('').trigger('change');
            $('#category-image-preview').attr('src', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80');
        }

        $('#btn-add-category').click(function() {
            openModal('Add Category');
        });

        $('#close-modal, #btn-cancel').click(closeModal);

        // Edit button click
        $('#categories-table').on('click', '.btn-edit', function() {
            let row = JSON.parse($(this).attr('data-row'));
            $('#category-id').val(row.id);
            $('#category-name').val(row.name);
            $('#category-parent').val(row.parent_id || '').trigger('change');
            $('#category-description').val(row.description || '');
            
            if (row.image) {
                $('#category-image-preview').attr('src', row.image);
            } else {
                $('#category-image-preview').attr('src', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80');
            }
            openModal('Edit Category');
        });

        // Form Submit via AJAX (Multipart FormData for image uploads)
        $('#category-form').submit(function(e) {
            e.preventDefault();
            
            let id = $('#category-id').val();
            let formData = new FormData();
            formData.append('parent_id', $('#category-parent').val() || '');
            formData.append('name', $('#category-name').val());
            formData.append('description', $('#category-description').val());

            let imageFile = $('#category-image')[0].files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            let url = id ? `/admin/categories/update/${id}` : '/admin/categories/store';

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
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    let msg = errors ? Object.values(errors).flat().join('<br>') : 'Could not save category.';
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
        $('#categories-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Category?',
                text: 'All subcategories and products inside this category will remain, but the category will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/categories/delete',
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
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                    });
                }
            });
        });

        // Check All checkboxes
        $('#check-all').click(function() {
            $('.category-checkbox').prop('checked', this.checked);
        });

        // Bulk Delete Callback
        $('#btn-bulk-delete').click(function() {
            let selectedIds = [];
            $('.category-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more categories to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Categories?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/categories/delete',
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
                            }).then(function() {
                                window.location.reload();
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
