<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Royal Fish Store</title>
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- jQuery & SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-br from-slate-900 via-[#5b0808] to-slate-950 px-4">
    
    <div class="max-w-md w-full bg-slate-900/40 backdrop-blur-xl border border-slate-700/50 rounded-3xl p-8 shadow-2xl space-y-6 relative overflow-hidden">
        <!-- Brand Red Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-amber-500 to-red-600"></div>

        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-red-800 to-red-600 border border-amber-400/25 shadow-lg">
                <i class="fa-solid fa-crown text-amber-400 text-xl"></i>
            </div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tight">Royal Fish Store</h2>
            <p class="text-xs text-slate-400">Premium Meat & Seafood Admin Console</p>
        </div>

        <form id="login-form" class="space-y-4">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" id="email" required placeholder="admin@royalfish.com" class="block w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border border-slate-800 text-white placeholder-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500 text-sm transition-all font-medium">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" required placeholder="••••••••" class="block w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border border-slate-800 text-white placeholder-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500 text-sm transition-all font-medium">
                </div>
            </div>

            <button type="submit" id="btn-submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 shadow-md transition-all active:scale-[0.98]">
                <span>Log In to Dashboard</span>
            </button>
        </form>
    </div>

    <script>
        $(document).ready(function() {

            $('#login-form').submit(function(e) {
                e.preventDefault();
                
                let btn = $('#btn-submit');
                let email = $('#email').val();
                let password = $('#password').val();

                btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Loading...');

                $.ajax({
                    url: '/admin/login',
                    method: 'POST',
                    data: { 
                        email: email, 
                        password: password,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '/admin/dashboard';
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<span>Log In to Dashboard</span>');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Invalid credentials.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: msg,
                            confirmButtonColor: '#dc2626'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
