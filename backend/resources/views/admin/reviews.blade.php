@extends('layouts.admin')

@section('title', 'Customer Reviews Management - Royal Fish Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-star text-amber-500"></i> Customer Reviews Management
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage customer reviews with ratings, photos, and video attachments shown across the website and reviews page.</p>
        </div>
        <div>
            <button id="btn-add-review" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Add Customer Review
            </button>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Reviews</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $reviews->count() }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Average Rating</span>
            <div class="text-2xl font-black text-amber-500 mt-1">
                ★ {{ $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 5.0 }}
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-500">Active / Live</span>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $reviews->where('is_active', true)->count() }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-500">With Photos / Video</span>
            <div class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1">
                {{ $reviews->filter(function($r) { return !empty($r->video_url) || (!empty($r->images) && is_array($r->images) && count($r->images) > 0); })->count() }}
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden p-6">
        <div class="table-responsive">
            <table id="reviews-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Reviewer</th>
                        <th class="py-3 px-4">Rating</th>
                        <th class="py-3 px-4">Review Text</th>
                        <th class="py-3 px-4">Product Tag</th>
                        <th class="py-3 px-4">Media (Photos/Video)</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @foreach($reviews as $rev)
                    @php
                        $imgs = is_array($rev->images) ? $rev->images : (json_decode($rev->images, true) ?: []);
                    @endphp
                    <tr data-id="{{ $rev->id }}">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $rev->avatar ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80' }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1">
                                        {{ $rev->name }}
                                        @if($rev->is_verified)
                                            <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]" title="Verified Buyer"></i>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400">{{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-bold text-amber-500 whitespace-nowrap">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }} text-xs"></i>
                            @endfor
                            <span class="text-[11px] text-slate-600 dark:text-slate-400 ml-1">({{ $rev->rating }}/5)</span>
                        </td>
                        <td class="py-3 px-4 max-w-xs">
                            <p class="line-clamp-2 text-slate-600 dark:text-slate-300 italic">"{{ $rev->quote }}"</p>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                🏷️ {{ $rev->product_tag ?: 'General' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @foreach(array_slice($imgs, 0, 3) as $img)
                                    <a href="{{ $img }}" target="_blank" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shrink-0 block">
                                        <img src="{{ $img }}" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                                @if(count($imgs) > 3)
                                    <span class="text-[10px] font-bold text-slate-400">+{{ count($imgs) - 3 }}</span>
                                @endif
                                @if(!empty($rev->video_url))
                                    <a href="{{ $rev->video_url }}" target="_blank" class="px-2 py-0.5 rounded-md bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 font-bold text-[10px] flex items-center gap-1">
                                        <i class="fa-solid fa-circle-play"></i> Video
                                    </a>
                                @endif
                                @if(count($imgs) === 0 && empty($rev->video_url))
                                    <span class="text-slate-400 text-[10px]">Text Only</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer toggle-status" data-id="{{ $rev->id }}" {{ $rev->is_active ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500"></div>
                            </label>
                        </td>
                        <td class="py-3 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button class="btn-edit text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" 
                                    data-json="{{ json_encode($rev) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-delete text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" data-id="{{ $rev->id }}">
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

<!-- Add / Edit Modal -->
<div id="review-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs hidden p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <form id="review-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="review-id" name="id">

            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 id="modal-title" class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-500"></i> Add Customer Review
                </h3>
                <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Customer Name *</label>
                        <input type="text" id="review-name" name="name" required class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" placeholder="e.g. Moumita Sen">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Star Rating (1-5) *</label>
                        <select id="review-rating" name="rating" required class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-bold">
                            <option value="5">★★★★★ (5 Stars - Excellent)</option>
                            <option value="4">★★★★☆ (4 Stars - Good)</option>
                            <option value="3">★★★☆☆ (3 Stars - Average)</option>
                            <option value="2">★★☆☆☆ (2 Stars - Poor)</option>
                            <option value="1">★☆☆☆☆ (1 Star - Terrible)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Product Purchased / Tag</label>
                    <input type="text" id="review-product-tag" name="product_tag" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" placeholder="e.g. Fresh Hilsa 1kg Cut / Golda Prawns">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Review Feedback / Comment *</label>
                    <textarea id="review-quote" name="quote" rows="3" required class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" placeholder="Write the customer's review feedback..."></textarea>
                </div>

                <!-- Media Uploads: Images & Video -->
                <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-4 bg-slate-50/50 dark:bg-slate-950/50 space-y-3">
                    <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                        <i class="fa-solid fa-photo-film text-purple-500"></i> Media Attachments (Photos & Video)
                    </span>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Upload Customer Photos (Images)</label>
                        <input type="file" id="review-image-files" name="image_files[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Upload Customer Video File (MP4, MOV, WebM max 50MB)</label>
                        <input type="file" id="review-video-file" name="video_file" accept="video/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-1">Upload real unboxing, cooking or fresh catch video directly from your device.</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700 dark:text-slate-300">
                        <input type="checkbox" id="review-verified" name="is_verified" value="1" checked class="w-4 h-4 rounded text-amber-500">
                        <span>Verified Buyer Badge</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700 dark:text-slate-300">
                        <input type="checkbox" id="review-active" name="is_active" value="1" checked class="w-4 h-4 rounded text-emerald-500">
                        <span>Active / Visible on Store</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-100 dark:border-slate-800">
                <button type="button" class="btn-close-modal px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800">
                    Cancel
                </button>
                <button type="submit" id="btn-save-review" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-md transition-all active:scale-95 cursor-pointer">
                    Save Review
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if ($('#reviews-table').length) {
            $('#reviews-table').DataTable({
                responsive: true,
                order: [[1, 'desc']]
            });
        }

        // Open Add Modal
        $('#btn-add-review').on('click', function() {
            $('#review-form')[0].reset();
            $('#review-id').val('');
            $('#modal-title').html('<i class="fa-solid fa-star text-amber-500"></i> Add Customer Review');
            $('#review-modal').removeClass('hidden');
        });

        // Close Modal
        $('.btn-close-modal').on('click', function() {
            $('#review-modal').addClass('hidden');
        });

        // Edit Button
        $(document).on('click', '.btn-edit', function() {
            const data = $(this).data('json');
            $('#review-id').val(data.id);
            $('#review-name').val(data.name);
            $('#review-rating').val(data.rating);
            $('#review-quote').val(data.quote);
            $('#review-product-tag').val(data.product_tag);
            $('#review-video-url').val(data.video_url || '');
            $('#review-verified').prop('checked', !!data.is_verified);
            $('#review-active').prop('checked', !!data.is_active);

            $('#modal-title').html('<i class="fa-solid fa-pen-to-square text-blue-500"></i> Edit Customer Review');
            $('#review-modal').removeClass('hidden');
        });

        // Form Submit (AJAX with FormData for File Uploads)
        $('#review-form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#review-id').val();
            const url = id ? `/admin/reviews/update/${id}` : '/admin/reviews/store';
            const formData = new FormData(this);

            $('#btn-save-review').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.message || 'Saved successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(err) {
                    const msg = err.responseJSON?.message || 'Error saving review.';
                    Swal.fire({ icon: 'error', title: 'Oops!', text: msg });
                },
                complete: function() {
                    $('#btn-save-review').prop('disabled', false).text('Save Review');
                }
            });
        });

        // Toggle Status
        $(document).on('change', '.toggle-status', function() {
            const id = $(this).data('id');
            $.ajax({
                url: '/admin/reviews/toggle-status',
                type: 'POST',
                data: { id: id, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        });

        // Delete Review
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Delete this review?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/reviews/delete',
                        type: 'POST',
                        data: { id: id, _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
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
