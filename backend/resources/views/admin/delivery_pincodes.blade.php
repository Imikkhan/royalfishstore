@extends('layouts.admin')

@section('title', 'Delivery Pincodes & Serviceability - Royal Fish Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-emerald-600"></i> Delivery Pincodes &amp; Serviceability
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Control which delivery pincodes customers can order to. This list is the single source of truth for the website pincode selector, checkout and order placement.
            </p>
        </div>
        <div>
            <button id="btn-add-pincode" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Add New Pincode
            </button>
        </div>
    </div>

    <!-- Info strip -->
    <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/70 dark:border-emerald-900/60 rounded-2xl px-5 py-4 flex items-start gap-3">
        <i class="fa-solid fa-circle-info text-emerald-600 dark:text-emerald-400 mt-0.5"></i>
        <div class="text-[11.5px] leading-relaxed text-emerald-800 dark:text-emerald-300 font-medium">
            <p><strong>{{ $pincodes->where('is_active', true)->count() }}</strong> active delivery pincode(s) out of <strong>{{ $pincodes->count() }}</strong> configured.</p>
            <p class="mt-0.5">Disabling a pincode immediately hides it from the website and blocks customers from placing orders to that pincode. If the list is empty the website falls back to per-product delivery zones.</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden p-6">
        <div class="table-responsive">
            <table id="pincodes-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Pincode</th>
                        <th class="py-3 px-4">Area / Locality</th>
                        <th class="py-3 px-4">Sort Order</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Added On</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @forelse($pincodes as $pincode)
                    <tr data-id="{{ $pincode->id }}">
                        <td class="py-3 px-4">
                            <span class="font-mono font-extrabold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-md tracking-wider">
                                {{ $pincode->pincode }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                            {{ $pincode->area_name ?: '—' }}
                        </td>
                        <td class="py-3 px-4 font-mono font-semibold">
                            {{ $pincode->sort_order }}
                        </td>
                        <td class="py-3 px-4">
                            <button class="btn-toggle-status px-3 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition-all {{ $pincode->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}" data-id="{{ $pincode->id }}">
                                {{ $pincode->is_active ? '● Serviceable' : '○ Not Serviceable' }}
                            </button>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                            {{ $pincode->created_at ? $pincode->created_at->format('d M Y') : '—' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="btn-edit-pincode text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 p-2 rounded-lg transition-colors cursor-pointer"
                                    data-id="{{ $pincode->id }}"
                                    data-pincode="{{ $pincode->pincode }}"
                                    data-area="{{ $pincode->area_name }}"
                                    data-order="{{ $pincode->sort_order }}"
                                    data-active="{{ $pincode->is_active ? '1' : '0' }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-delete-pincode text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 p-2 rounded-lg transition-colors cursor-pointer" data-id="{{ $pincode->id }}" data-pincode="{{ $pincode->pincode }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-slate-400 dark:text-slate-500 font-semibold">
                            <i class="fa-solid fa-map-pin text-2xl block mb-2 text-slate-300 dark:text-slate-700"></i>
                            No delivery pincodes configured yet. The website is currently using per-product delivery zones.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add / Edit Modal -->
<div id="pincode-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm hidden p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-lg w-full shadow-2xl overflow-hidden animate-fadeIn relative my-auto">
        <div class="h-2 bg-emerald-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-950/40">
            <h3 id="modal-title" class="font-extrabold text-slate-900 dark:text-white text-lg">Add Delivery Pincode</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold p-1"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="pincode-form" class="p-6 space-y-4">
            <input type="hidden" id="pincode-id">

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">6-Digit Pincode</label>
                <input type="text" id="pin-code" required maxlength="6" inputmode="numeric" placeholder="700135" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-mono font-bold tracking-widest">
                <p class="text-[11px] text-slate-400 mt-1">Only 6 digit Indian pincodes are allowed. Duplicate pincodes are rejected.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Area / Locality Name</label>
                <input type="text" id="pin-area" placeholder="Rajarhat / DLF 1 &amp; 2" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
                <p class="text-[11px] text-slate-400 mt-1">Shown to customers in the website pincode selector (e.g. "Action Area I (New Town)").</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sort Order</label>
                <input type="number" id="pin-order" placeholder="0" min="0" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
                <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first in the customer pincode list.</p>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="pin-active" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600">
                <label for="pin-active" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Serviceable (deliver to this pincode)</label>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" id="btn-cancel" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold rounded-xl transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-7 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-extrabold rounded-xl transition-colors shadow-md active:scale-98">Save Pincode</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#pincodes-table').DataTable({
            responsive: true,
            pageLength: 25,
            order: []
        });

        const modal = $('#pincode-modal');

        $('#btn-add-pincode').click(function() {
            $('#modal-title').text('Add Delivery Pincode');
            $('#pincode-id').val('');
            $('#pincode-form')[0].reset();
            $('#pin-active').prop('checked', true);
            modal.removeClass('hidden');
        });

        $('#close-modal, #btn-cancel').click(function() {
            modal.addClass('hidden');
        });

        // Edit Pincode
        $(document).on('click', '.btn-edit-pincode', function() {
            const btn = $(this);
            $('#modal-title').text('Edit Delivery Pincode');
            $('#pincode-id').val(btn.data('id'));
            $('#pin-code').val(btn.data('pincode'));
            $('#pin-area').val(btn.data('area') || '');
            $('#pin-order').val(btn.data('order'));
            $('#pin-active').prop('checked', btn.data('active') == 1);
            modal.removeClass('hidden');
        });

        // Submit Form
        $('#pincode-form').submit(function(e) {
            e.preventDefault();

            const id = $('#pincode-id').val();
            const url = id ? `{{ url('/admin/delivery-pincodes/update') }}/${id}` : `{{ url('/admin/delivery-pincodes/store') }}`;

            const data = {
                _token: '{{ csrf_token() }}',
                pincode: $('#pin-code').val().replace(/\D/g, ''),
                area_name: $('#pin-area').val(),
                sort_order: $('#pin-order').val(),
                is_active: $('#pin-active').is(':checked') ? 1 : 0
            };

            $.post(url, data, function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                }
            }).fail(function(err) {
                const errors = err.responseJSON?.errors;
                const firstError = errors ? Object.values(errors)[0]?.[0] : null;
                Swal.fire('Error', firstError || err.responseJSON?.message || 'Something went wrong.', 'error');
            });
        });
        // Toggle Serviceability
        $(document).on('click', '.btn-toggle-status', function() {
            const id = $(this).data('id');
            $.post(`{{ url('/admin/delivery-pincodes/toggle-status') }}`, {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(res) {
                if(res.success) window.location.reload();
            }).fail(function(err) {
                Swal.fire('Error', err.responseJSON?.message || 'Unable to change the status.', 'error');
            });
        });

        // Delete Pincode
        $(document).on('click', '.btn-delete-pincode', function() {
            const id = $(this).data('id');
            const pin = $(this).data('pincode');
            Swal.fire({
                title: 'Remove ' + pin + '?',
                text: "Customers will no longer be able to select or order to this pincode.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/admin/delivery-pincodes/delete') }}`, {
                        _token: '{{ csrf_token() }}',
                        id: id
                    }, function(res) {
                        if(res.success) {
                            Swal.fire('Removed!', res.message, 'success').then(() => window.location.reload());
                        }
                    }).fail(function(err) {
                        Swal.fire('Error', err.responseJSON?.message || 'Unable to remove the pincode.', 'error');
                    });
                }
            });
        });
    });
</script>
@endsection

