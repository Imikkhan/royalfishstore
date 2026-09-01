<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
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
<body class="h-full flex items-center justify-center bg-slate-100/90 px-4">
    
    <div class="max-w-md w-full bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xl space-y-6 relative overflow-hidden">
        <!-- Brand Red Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-amber-500 to-red-600"></div>

        <div class="text-center pt-2">
            <img src="/logo.png" alt="Royal Fish Store Logo" class="h-16 mx-auto w-auto object-contain">
            <p class="text-xs text-slate-500 font-medium mt-1">Premium Meat & Seafood Admin Console</p>
        </div>

        <!-- Password Login Form -->
        <form id="login-form" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" id="email" required placeholder="admin@royalfish.com" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-600 text-sm transition-all font-medium">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" required placeholder="••••••••" class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-600 text-sm transition-all font-medium">
                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" title="Show/Hide Password">
                        <i class="fa-solid fa-eye" id="toggle-password-icon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" id="btn-submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 shadow-md transition-all active:scale-[0.98] cursor-pointer">
                <span>Log In to Dashboard</span>
            </button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Password toggle feature
            $('#toggle-password').on('click', function() {
                const passwordInput = $('#password');
                const icon = $('#toggle-password-icon');
                const isPassword = passwordInput.attr('type') === 'password';

                passwordInput.attr('type', isPassword ? 'text' : 'password');
                if (isPassword) {
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Password Login Submit
            $('#login-form').submit(function(e) {
                e.preventDefault();
                
                let btn = $('#btn-submit');
                let email = $('#email').val();
                let password = $('#password').val();
                let token = $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content');

                btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Loading...');

                $.ajax({
                    url: '/admin/login',
                    method: 'POST',
                    data: { 
                        email: email, 
                        password: password,
                        _token: token
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '/admin/dashboard';
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<span>Log In to Dashboard</span>');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Invalid credentials.';

                        if (xhr.status === 419 || (msg && msg.toLowerCase().includes('csrf'))) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Session Expired',
                                text: 'Refreshing page with fresh security token...',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.reload();
                            });
                            return;
                        }

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
