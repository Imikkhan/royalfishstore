@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
        <div class="flex items-center gap-2">
            <button id="btn-add-role" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm active:scale-98">
                <i class="fa-solid fa-plus"></i> Add New Role
            </button>
            <button id="btn-bulk-delete" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Bulk Delete
            </button>
        </div>
        <span class="text-xs text-slate-400">Total Roles: <strong id="roles-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive">
            <table id="roles-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Slug</th>
                        <th>Permissions Granted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Create/Edit Role Modal -->
<div id="role-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-lg w-full shadow-2xl overflow-hidden animate-fadeIn relative">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 id="modal-title" class="font-bold text-slate-800 dark:text-white text-base">Add New Role</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="role-form" class="p-6 space-y-4">
            <input type="hidden" id="role-id">
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Role Name</label>
                <input type="text" id="role-name" required placeholder="Manager" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Permissions</label>
                <div class="grid grid-cols-2 gap-3 max-h-52 overflow-y-auto p-2 bg-slate-50 dark:bg-slate-950/40 rounded-xl border border-slate-200/40 dark:border-slate-800">
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="*" class="rounded border-slate-300 text-red-600"> Full Access (*)
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="dashboard" class="rounded border-slate-300 text-red-600"> Dashboard
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="roles" class="rounded border-slate-300 text-red-600"> Roles Admin
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="users" class="rounded border-slate-300 text-red-600"> User Admin
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="categories" class="rounded border-slate-300 text-red-600"> Categories
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="products" class="rounded border-slate-300 text-red-600"> Products
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="orders" class="rounded border-slate-300 text-red-600"> Orders
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="media" class="rounded border-slate-300 text-red-600"> Media Manager
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="settings" class="rounded border-slate-300 text-red-600"> Settings
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" id="btn-cancel" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Save Role</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        let table = $('#roles-table').DataTable({
            ajax: {
                url: '/admin/roles',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (row.slug === 'super-admin') return '';
                        return `<input type="checkbox" class="role-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'id' },
                { data: 'name', className: 'font-bold' },
                { data: 'slug', className: 'font-mono text-xs text-slate-400' },
                {
                    data: 'permissions',
                    render: function(data) {
                        try {
                            let perms = JSON.parse(data) || [];
                            return perms.map(p => `<span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mr-1 mb-1">${p}</span>`).join('');
                        } catch(e) {
                            return `<span class="text-slate-400">${data}</span>`;
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        if (row.slug === 'super-admin') {
                            return `<span class="text-xs font-semibold text-slate-400"><i class="fa-solid fa-lock"></i> Protected</span>`;
                        }
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
                $('#roles-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Toggle modal
        function openModal(title) {
            $('#modal-title').text(title);
            $('#role-modal').removeClass('hidden');
        }

        function closeModal() {
            $('#role-modal').addClass('hidden');
            $('#role-form')[0].reset();
            $('#role-id').val('');
        }

        $('#btn-add-role').click(function() {
            openModal('Add New Role');
        });

        $('#close-modal, #btn-cancel').click(closeModal);

        // Edit button callback
        $('#roles-table').on('click', '.btn-edit', function() {
            let row = JSON.parse($(this).attr('data-row'));
            $('#role-id').val(row.id);
            $('#role-name').val(row.name);

            let perms = [];
            try {
                perms = JSON.parse(row.permissions) || [];
            } catch(e) {}

            $('input[name="permissions[]"]').prop('checked', false);
            perms.forEach(p => {
                $(`input[name="permissions[]"][value="${p}"]`).prop('checked', true);
            });

            openModal('Edit Role');
        });

        // Form Submit
        $('#role-form').submit(function(e) {
            e.preventDefault();
            
            let id = $('#role-id').val();
            let name = $('#role-name').val();
            let permissions = [];
            $('input[name="permissions[]"]:checked').each(function() {
                permissions.push($(this).val());
            });

            let url = id ? `/admin/roles/update/${id}` : '/admin/roles/store';

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    name: name,
                    permissions: permissions
                },
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
                    let msg = errors ? Object.values(errors).flat().join('<br>') : 'Could not save role.';
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
        $('#roles-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'All users with this role will lose their permission mappings!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/roles/delete',
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
            $('.role-checkbox').prop('checked', this.checked);
        });

        // Bulk Delete Callback
        $('#btn-bulk-delete').click(function() {
            let selectedIds = [];
            $('.role-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more roles to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Roles?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/roles/delete',
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
