@extends('layouts.admin')

@section('title', 'Customers & Staff')
@section('page_title', 'Customers & Staff')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
        <div class="flex items-center gap-2">
            <button id="btn-add-user" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm active:scale-98">
                <i class="fa-solid fa-plus"></i> Add Staff/User
            </button>
            <button id="btn-bulk-delete" class="px-4 py-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-slate-800 text-slate-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Bulk Delete
            </button>
        </div>
        <span class="text-xs text-slate-400">Total Registered: <strong id="users-count" class="text-slate-700 dark:text-slate-300 font-extrabold">0</strong></span>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="table-responsive">
            <table id="users-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="check-all" class="rounded border-slate-300"></th>
                        <th>ID</th>
                        <th>User Details</th>
                        <th>Phone Number</th>
                        <th>Role / Level</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Create/Edit User Modal -->
<div id="user-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-xs hidden p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-md w-full shadow-2xl overflow-hidden animate-fadeIn relative">
        <div class="h-1.5 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 id="modal-title" class="font-bold text-slate-800 dark:text-white text-base">Add Staff User</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="user-form" class="p-6 space-y-4">
            <input type="hidden" id="user-id">
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Full Name</label>
                <input type="text" id="user-name" required placeholder="John Doe" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" id="user-email" required placeholder="john@example.com" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                    <input type="tel" id="user-phone" required placeholder="9876543210" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="user-password" placeholder="••••••••" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                    <p id="password-help" class="text-[9px] text-slate-400 mt-0.5 hidden">Leave empty to keep existing password.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Role / Permission level</label>
                    <select id="user-role" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                        <option value="">Customer (No dashboard access)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" id="btn-cancel" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Save User</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#user-role').select2({
            placeholder: "Select Role",
            dropdownParent: $('#user-modal'),
            width: '100%'
        });

        let table = $('#users-table').DataTable({
            ajax: {
                url: '/admin/users',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (row.id === {{ Auth::id() }}) return ''; // Prev self bulk delete
                        return `<input type="checkbox" class="user-checkbox rounded border-slate-300" value="${row.id}">`;
                    }
                },
                { data: 'id' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div>
                                <div class="font-bold text-slate-800 dark:text-white">${row.name}</div>
                                <div class="text-[10px] text-slate-400 font-mono">${row.email}</div>
                            </div>
                        `;
                    }
                },
                { data: 'phone', className: 'font-semibold font-mono text-xs' },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (row.role) {
                            let badgeColor = row.role.slug === 'super-admin' ? 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400';
                            return `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${badgeColor}"><i class="fa-solid fa-user-shield text-[9px] mr-1"></i>${row.role.name}</span>`;
                        }
                        return `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400"><i class="fa-solid fa-shopping-bag text-[9px] mr-1"></i>Customer</span>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        let date = new Date(data);
                        return `<span class="text-xs text-slate-400 font-medium">${date.toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'})}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="flex gap-1.5">
                                <button class="btn-edit p-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded transition-colors text-xs" data-row='${JSON.stringify(row)}'><i class="fa-solid fa-pen-to-square"></i></button>
                                ${row.id === {{ Auth::id() }} ? '' : `
                                <button class="btn-delete p-1.5 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 hover:bg-red-100 rounded transition-colors text-xs" data-id="${row.id}"><i class="fa-solid fa-trash"></i></button>
                                `}
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            drawCallback: function(settings) {
                $('#users-count').text(settings.json ? settings.json.data.length : 0);
            }
        });

        // Toggle modal
        function openModal(title) {
            $('#modal-title').text(title);
            $('#user-modal').removeClass('hidden');
        }

        function closeModal() {
            $('#user-modal').addClass('hidden');
            $('#user-form')[0].reset();
            $('#user-role').val('').trigger('change');
            $('#user-id').val('');
            $('#user-password').prop('required', true);
            $('#password-help').addClass('hidden');
        }

        $('#btn-add-user').click(function() {
            openModal('Add Staff User');
        });

        $('#close-modal, #btn-cancel').click(closeModal);

        // Edit user event
        $('#users-table').on('click', '.btn-edit', function() {
            let row = JSON.parse($(this).attr('data-row'));
            $('#user-id').val(row.id);
            $('#user-name').val(row.name);
            $('#user-email').val(row.email);
            $('#user-phone').val(row.phone);
            $('#user-role').val(row.role_id || '').trigger('change');
            
            // Password not required during update
            $('#user-password').prop('required', false);
            $('#password-help').removeClass('hidden');

            openModal('Edit User Details');
        });

        // Form Submit AJAX
        $('#user-form').submit(function(e) {
            e.preventDefault();
            
            let id = $('#user-id').val();
            let name = $('#user-name').val();
            let email = $('#user-email').val();
            let phone = $('#user-phone').val();
            let password = $('#user-password').val();
            let role_id = $('#user-role').val();

            let url = id ? `/admin/users/update/${id}` : '/admin/users/store';

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    name: name,
                    email: email,
                    phone: phone,
                    password: password,
                    role_id: role_id
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
                    let msg = errors ? Object.values(errors).flat().join('<br>') : 'Could not save user.';
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
        $('#users-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete User?',
                text: 'This user account will be permanently deactivated.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/delete',
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
            $('.user-checkbox').prop('checked', this.checked);
        });

        // Bulk Delete Callback
        $('#btn-bulk-delete').click(function() {
            let selectedIds = [];
            $('.user-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Selection',
                    text: 'Please select one or more users to delete.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Selected Users?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete bulk!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/delete',
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
