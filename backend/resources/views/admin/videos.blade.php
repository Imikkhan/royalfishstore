@extends('layouts.admin')

@section('title', 'YouTube Videos Management - Royal Fish Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-circle-play text-red-600"></i> YouTube Videos Management
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage YouTube videos displayed in the "Our Videos" section on the website homepage.</p>
        </div>
        <div>
            <button id="btn-add-video" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Add New YouTube Video
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden p-6">
        <div class="table-responsive">
            <table id="videos-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Thumbnail</th>
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">YouTube ID / URL</th>
                        <th class="py-3 px-4">Duration</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @foreach($videos as $video)
                    <tr data-id="{{ $video->id }}">
                        <td class="py-3 px-4">
                            <div class="relative w-24 aspect-video rounded-xl overflow-hidden bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xs">
                                <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                    <i class="fa-solid fa-play text-white text-xs"></i>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                            {{ $video->title }}
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ $video->youtube_url }}" target="_blank" class="text-red-600 dark:text-red-400 hover:underline font-mono font-medium flex items-center gap-1">
                                <i class="fa-brands fa-youtube"></i> {{ $video->youtube_id }}
                            </a>
                        </td>
                        <td class="py-3 px-4 font-mono font-semibold">
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-md">{{ $video->duration }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <button class="btn-toggle-status px-3 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition-all {{ $video->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}" data-id="{{ $video->id }}">
                                {{ $video->is_active ? '● Active' : '○ Inactive' }}
                            </button>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="btn-edit-video text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 p-2 rounded-lg transition-colors cursor-pointer" 
                                    data-id="{{ $video->id }}"
                                    data-title="{{ $video->title }}"
                                    data-url="{{ $video->youtube_url }}"
                                    data-thumbnail="{{ $video->thumbnail }}"
                                    data-duration="{{ $video->duration }}"
                                    data-order="{{ $video->sort_order }}"
                                    data-active="{{ $video->is_active ? '1' : '0' }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-delete-video text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 p-2 rounded-lg transition-colors cursor-pointer" data-id="{{ $video->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="video-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm hidden p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 max-w-xl w-full shadow-2xl overflow-hidden animate-fadeIn relative my-auto">
        <div class="h-2 bg-red-600 w-full"></div>
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-950/40">
            <h3 id="modal-title" class="font-extrabold text-slate-900 dark:text-white text-lg">Add YouTube Video</h3>
            <button id="close-modal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold p-1"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="video-form" class="p-6 space-y-4">
            <input type="hidden" id="video-id">
            
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Video Title</label>
                <input type="text" id="video-title" required placeholder="Daily Fresh Catch From Local Waters" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5"><i class="fa-brands fa-youtube text-red-600 mr-1"></i> YouTube Video Link / ID</label>
                <input type="text" id="video-url" required placeholder="https://www.youtube.com/watch?v=LXb3EKWsInQ or https://youtu.be/LXb3EKWsInQ" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
                <p class="text-[11px] text-slate-400 mt-1">Paste any YouTube URL or Video ID. Thumbnail will be auto-generated.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Duration (e.g. 0:45)</label>
                    <input type="text" id="video-duration" placeholder="0:45" value="1:00" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sort Order</label>
                    <input type="number" id="video-order" placeholder="0" value="0" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Custom Thumbnail URL (Optional)</label>
                <input type="text" id="video-thumbnail" placeholder="Leave empty for auto YouTube thumbnail" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm font-medium">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="video-active" checked class="w-4 h-4 rounded border-slate-300 text-red-600">
                <label for="video-active" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Active (Show on Homepage)</label>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" id="btn-cancel" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold rounded-xl transition-colors">Cancel</button>
                <button type="submit" id="btn-save" class="px-7 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-extrabold rounded-xl transition-colors shadow-md active:scale-98">Save Video</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#videos-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: []
        });

        const modal = $('#video-modal');

        $('#btn-add-video').click(function() {
            $('#modal-title').text('Add YouTube Video');
            $('#video-id').val('');
            $('#video-form')[0].reset();
            $('#video-active').prop('checked', true);
            modal.removeClass('hidden');
        });

        $('#close-modal, #btn-cancel').click(function() {
            modal.addClass('hidden');
        });

        // Edit Video
        $(document).on('click', '.btn-edit-video', function() {
            const btn = $(this);
            $('#modal-title').text('Edit YouTube Video');
            $('#video-id').val(btn.data('id'));
            $('#video-title').val(btn.data('title'));
            $('#video-url').val(btn.data('url'));
            $('#video-thumbnail').val(btn.data('thumbnail'));
            $('#video-duration').val(btn.data('duration'));
            $('#video-order').val(btn.data('order'));
            $('#video-active').prop('checked', btn.data('active') == 1);
            modal.removeClass('hidden');
        });

        // Submit Form
        $('#video-form').submit(function(e) {
            e.preventDefault();
            const id = $('#video-id').val();
            const url = id ? `{{ url('/admin/videos/update') }}/${id}` : `{{ url('/admin/videos/store') }}`;

            const data = {
                _token: '{{ csrf_token() }}',
                title: $('#video-title').val(),
                youtube_url: $('#video-url').val(),
                thumbnail: $('#video-thumbnail').val(),
                duration: $('#video-duration').val(),
                sort_order: $('#video-order').val(),
                is_active: $('#video-active').is(':checked') ? 1 : 0
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
                Swal.fire('Error', err.responseJSON?.message || 'Something went wrong.', 'error');
            });
        });

        // Toggle Status
        $(document).on('click', '.btn-toggle-status', function() {
            const id = $(this).data('id');
            $.post(`{{ url('/admin/videos/toggle-status') }}`, {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(res) {
                if(res.success) window.location.reload();
            });
        });

        // Delete Video
        $(document).on('click', '.btn-delete-video', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Delete YouTube Video?',
                text: "This video will be removed from homepage 'Our Videos' section.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/admin/videos/delete') }}`, {
                        _token: '{{ csrf_token() }}',
                        id: id
                    }, function(res) {
                        if(res.success) {
                            Swal.fire('Deleted!', res.message, 'success').then(() => window.location.reload());
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
