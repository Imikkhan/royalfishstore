@extends('layouts.admin')

@section('title', 'Media Manager')
@section('page_title', 'Media Manager')

@section('content')
<div class="space-y-6">
    
    <!-- Upload Dropzone Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <h3 class="font-bold text-slate-800 dark:text-white text-base mb-3">Upload Media Files</h3>
        <form action="{{ url('/admin/media/upload') }}" class="dropzone rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950/20" id="media-dropzone">
            @csrf
        </form>
    </div>

    <!-- Media Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4">Media Files Registry</h3>
        <div class="table-responsive">
            <table id="media-table" class="display responsive nowrap w-full border-b border-slate-100 dark:border-slate-800">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Preview</th>
                        <th>File Name</th>
                        <th>Extension</th>
                        <th>Size (KB)</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

@section('scripts')
<script>
    // Initialize Dropzone configuration
    Dropzone.options.mediaDropzone = {
        paramName: "file",
        maxFilesize: 10, // MB
        acceptedFiles: "image/*,application/pdf,video/*",
        success: function(file, response) {
            Swal.fire({
                icon: 'success',
                title: 'Uploaded!',
                text: response.message,
                timer: 1000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
            // Reload DataTable
            $('#media-table').DataTable().ajax.reload();
            setTimeout(() => {
                this.removeFile(file);
            }, 1500);
        },
        error: function(file, message) {
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: typeof message === 'object' ? message.message : message,
                confirmButtonColor: '#dc2626'
            });
        }
    };

    $(document).ready(function() {
        let table = $('#media-table').DataTable({
            ajax: {
                url: '/admin/media',
                type: 'GET'
            },
            columns: [
                { data: 'id' },
                {
                    data: 'file_path',
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        if (images.includes(row.file_type?.toLowerCase())) {
                            return `<img src="${data}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 mx-auto border border-slate-200">`;
                        } else if (row.file_type?.toLowerCase() === 'pdf') {
                            return `<div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center mx-auto text-lg"><i class="fa-solid fa-file-pdf"></i></div>`;
                        }
                        return `<div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center mx-auto text-lg"><i class="fa-solid fa-file"></i></div>`;
                    }
                },
                { data: 'file_name', className: 'font-semibold text-xs max-w-xs truncate' },
                { data: 'file_type', className: 'font-mono text-xs text-slate-400 uppercase' },
                { 
                    data: 'file_size', 
                    className: 'font-mono text-xs text-right',
                    render: size => (size / 1024).toFixed(1)
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
                                <button class="btn-copy p-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded transition-colors text-xs" data-link="${row.file_path}"><i class="fa-solid fa-copy"></i> Copy URL</button>
                                <button class="btn-delete p-1.5 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 hover:bg-red-100 rounded transition-colors text-xs" data-id="${row.id}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        // Copy url callback
        $('#media-table').on('click', '.btn-copy', function() {
            let link = $(this).attr('data-link');
            navigator.clipboard.writeText(link).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Link copied to clipboard.',
                    timer: 1000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            });
        });

        // Delete callback
        $('#media-table').on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Media File?',
                text: 'This file will be permanently deleted from server storage.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete file!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/media/delete/${id}`,
                        method: 'POST',
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
    });
</script>
@endsection
@endsection
