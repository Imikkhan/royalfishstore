@extends('layouts.admin')

@section('title', 'Hero Banners Management')
@section('page_title', 'Hero Banners Management')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
        <div class="flex items-center gap-2">
            <button id="btn-add-slide" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm active:scale-98">
                <i class="fa-solid fa-plus"></i> Add Hero Banner
            </button>
            <button id="btn-bulk-delete" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Bulk Delete
            </button>
        </div>
        <span class="text-xs text-slate-400">Total Banners: <strong id="slides-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive overflow-x-auto">
            <table id="slides-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>ID</th>
                        <th>Banner Preview</th>
                        <th>Title & Subtitle</th>
                        <th>Promo Code</th>
                        <th>Gradient Theme</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Create/Edit Slide Modal -->
<div id="slide-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-lg w-full shadow-2xl overflow-hidden animate-fadeIn relative my-8">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 id="modal-title" class="font-bold text-slate-800 dark:text-white text-base">Add Hero Banner</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="slide-form" class="p-6 space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="slide-id">
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Banner Title</label>
                <input type="text" id="slide-title" required placeholder="Flat ₹150 OFF on King Fish" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Subtitle / Description</label>
                <textarea id="slide-subtitle" rows="2" placeholder="Valid on orders above ₹799. Express 45-min delivery..." class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Promo Code</label>
                    <input type="text" id="slide-code" placeholder="ROYAL150" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Display Order</label>
                    <input type="number" id="slide-order" value="1" min="1" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Background Gradient Tailwind Class</label>
                <select id="slide-gradient" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                    <option value="from-red-600 to-rose-500">Royal Red (from-red-600 to-rose-500)</option>
                    <option value="from-blue-600 to-indigo-600">Ocean Blue (from-blue-600 to-indigo-600)</option>
                    <option value="from-amber-600 to-orange-600">Golden Amber (from-amber-600 to-orange-600)</option>
                    <option value="from-emerald-600 to-teal-600">Emerald Mint (from-emerald-600 to-teal-600)</option>
                    <option value="from-purple-600 to-pink-600">Purple Velvet (from-purple-600 to-pink-600)</option>
                </select>
            </div>

            <!-- Image Upload Section -->
            <div class="grid grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Banner Image</label>
                    <input type="file" id="slide-image" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 hover:file:bg-slate-200 cursor-pointer">
                </div>
                <div class="flex items-center justify-center">
                    <img id="slide-image-preview" src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80" alt="Preview" class="w-20 h-16 rounded-xl border border-slate-200 object-cover bg-slate-100 shadow-sm">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" id="btn-cancel" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Save Banner</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        let table = $('#slides-table').DataTable({
            ajax: {
                url: '/admin/slides',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="slide-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'id', className: 'font-mono text-xs text-slate-400' },
                {
                    data: 'image',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<img src="${data}" class="w-16 h-12 rounded-lg object-cover bg-slate-100 border border-slate-200 mx-auto">`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div>
                                <div class="font-bold text-slate-800 dark:text-white leading-snug">${row.title}</div>
                                <div class="text-[10px] text-slate-400 line-clamp-1">${row.subtitle || ''}</div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'code',
                    render: function(data) {
                        return `<span class="bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 px-2 py-0.5 rounded font-mono font-bold text-xs">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'bg_gradient',
                    render: function(data) {
                        return `<span class="text-[10px] font-mono text-slate-400">${data || 'Standard'}</span>`;
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
                $('#slides-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Toggle Status AJAX
        $('#slides-table').on('change', '.status-toggle', function() {
            let id = $(this).attr('data-id');
            let checkbox = $(this);
            $.ajax({
                url: '/admin/slides/toggle-status',
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

        // Toggle Modal
        function openModal(title) {
            $('#modal-title').text(title);
            $('#slide-modal').removeClass('hidden');
        }

        function closeModal() {
            $('#slide-modal').addClass('hidden');
            $('#slide-form')[0].reset();
            $('#slide-id').val('');
            $('#slide-image-preview').attr('src', 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80');
        }

        $('#btn-add-slide').click(function() {
            openModal('Add Hero Banner');
        });

        $('#close-modal, #btn-cancel').click(closeModal);

        // Image Preview
        $('#slide-image').change(function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#slide-image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Edit button click callback
        $('#slides-table').on('click', '.btn-edit', function() {
            let row = JSON.parse($(this).attr('data-row'));
            $('#slide-id').val(row.id);
            $('#slide-title').val(row.title);
            $('#slide-subtitle').val(row.subtitle || '');
            $('#slide-code').val(row.code || '');
            $('#slide-order').val(row.sort_order || 1);
            $('#slide-gradient').val(row.bg_gradient || 'from-red-600 to-rose-500');
            $('#slide-image-preview').attr('src', row.image);
            openModal('Edit Hero Banner');
        });

        // Form Submit via AJAX
        $('#slide-form').submit(function(e) {
            e.preventDefault();
            
            let id = $('#slide-id').val();
            let formData = new FormData();
            formData.append('title', $('#slide-title').val());
            formData.append('subtitle', $('#slide-subtitle').val());
            formData.append('code', $('#slide-code').val());
            formData.append('sort_order', $('#slide-order').val());
            formData.append('bg_gradient', $('#slide-gradient').val());
            
            let imageFile = $('#slide-image')[0].files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            let url = id ? `/admin/slides/update/${id}` : '/admin/slides/store';

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
                    let msg = errors ? Object.values(errors).flat().join('<br>') : 'Could not save banner.';
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
        $('#slides-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Hero Banner?',
                text: 'This banner will be removed from the main homepage carousel.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/slides/delete',
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
            $('.slide-checkbox').prop('checked', this.checked);
        });

        // Bulk Delete Callback
        $('#btn-bulk-delete').click(function() {
            let selectedIds = [];
            $('.slide-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more banners to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Banners?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/slides/delete',
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
