<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Partner Login - Royal Fish Store</title>
    
    <!-- PWA Manifest & Mobile App Meta Tags -->
    <link rel="manifest" href="/rider-manifest.json">
    <meta name="theme-color" content="#FF5722">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="RoyalRider">
    <link rel="apple-touch-icon" href="/images/rider-icon-192.png">

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
<body class="h-full flex flex-col items-center justify-center bg-slate-100 px-4 py-8 relative">

    <!-- PWA App Install Prompt Banner -->
    <div id="pwa-install-banner" class="hidden max-w-md w-full mb-4 bg-gradient-to-r from-red-600 to-amber-600 border border-red-500 rounded-2xl p-3.5 text-white flex items-center justify-between shadow-xl">
        <div class="flex items-center gap-3">
            <img src="/images/rider-icon-192.png" alt="Rider App Icon" class="w-10 h-10 rounded-xl shadow-md border border-white/20">
            <div>
                <h4 class="text-xs font-bold leading-tight">Install Rider Mobile App</h4>
                <p class="text-[10px] text-red-100">Fast 1-click home screen access</p>
            </div>
        </div>
        <button id="btn-install-pwa" class="px-3.5 py-1.5 bg-white hover:bg-slate-100 text-red-600 text-xs font-extrabold rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
            <i class="fa-solid fa-download mr-1"></i> Install
        </button>
    </div>
    
    <div class="max-w-md w-full bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xl space-y-6 relative overflow-hidden">
        <!-- Top Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-amber-500 to-red-600"></div>

        <div class="text-center space-y-2 pt-2">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-red-600 to-amber-500 shadow-md text-white">
                <i class="fa-solid fa-motorcycle text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Rider Console</h2>
            <p class="text-xs text-slate-500">Royal Fish Express Delivery Partner Portal</p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex p-1 bg-slate-100 rounded-2xl">
            <button type="button" id="tab-rider-pass" class="flex-1 py-2 text-xs font-bold rounded-xl transition-all text-slate-800 bg-white shadow-xs cursor-pointer">
                <i class="fa-solid fa-lock mr-1.5 text-red-600"></i> Password
            </button>
            <button type="button" id="tab-rider-otp" class="flex-1 py-2 text-xs font-bold rounded-xl transition-all text-slate-500 hover:text-slate-900 cursor-pointer">
                <i class="fa-brands fa-whatsapp mr-1.5 text-emerald-500"></i> WhatsApp OTP
            </button>
        </div>

        <!-- Mode 1: Password Form -->
        <form id="rider-login-form" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Rider Email or Phone Number</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" id="email" required placeholder="ramesh@royalfish.com or 9820198201" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-sm transition-all font-medium">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" required placeholder="••••••••" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-sm transition-all font-medium">
                </div>
            </div>

            <button type="submit" id="btn-submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 shadow-md transition-all active:scale-[0.98] cursor-pointer">
                <span>Log In to Rider Console</span>
            </button>
        </form>

        <!-- Mode 2: WhatsApp OTP Form -->
        <div id="rider-otp-container" class="space-y-4 hidden">
            <!-- Step 1: Send OTP -->
            <form id="rider-send-otp-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Rider WhatsApp Number</label>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-700 font-bold text-xs border-r border-slate-200 pr-2 pointer-events-none">
                            🇮🇳 +91
                        </span>
                        <input type="tel" id="rider-otp-phone" maxlength="10" required placeholder="Enter 10-digit number" class="block w-full pl-16 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm transition-all font-bold tracking-wider">
                    </div>
                </div>

                <button type="submit" id="btn-rider-send-otp" class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all active:scale-[0.98] cursor-pointer">
                    <i class="fa-brands fa-whatsapp text-lg mr-2"></i>
                    <span>Get OTP on WhatsApp</span>
                </button>
            </form>

            <!-- Step 2: Verify OTP (Hidden initially) -->
            <form id="rider-verify-otp-form" class="space-y-4 hidden">
                @csrf
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-3 text-xs text-emerald-800 flex items-center justify-between">
                    <span><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i> OTP sent to <strong id="display-rider-phone">+91</strong></span>
                    <button type="button" id="btn-rider-change-num" class="text-red-600 font-bold underline cursor-pointer text-[11px]">Edit</button>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Enter 4-Digit OTP</label>
                    <input type="text" id="rider-otp-code" maxlength="4" required placeholder="••••" class="block w-full py-3 text-center text-2xl tracking-[0.5em] font-black bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all">
                </div>

                <button type="submit" id="btn-rider-verify-otp" class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all active:scale-[0.98] cursor-pointer">
                    <span>Verify &amp; Enter Rider Console</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Tab Toggle
            $('#tab-rider-pass').on('click', function() {
                $(this).addClass('bg-white text-slate-800 shadow-xs').removeClass('text-slate-500');
                $('#tab-rider-otp').removeClass('bg-white text-slate-800 shadow-xs').addClass('text-slate-500');
                $('#rider-login-form').removeClass('hidden');
                $('#rider-otp-container').addClass('hidden');
            });

            $('#tab-rider-otp').on('click', function() {
                $(this).addClass('bg-white text-slate-800 shadow-xs').removeClass('text-slate-500');
                $('#tab-rider-pass').removeClass('bg-white text-slate-800 shadow-xs').addClass('text-slate-500');
                $('#rider-login-form').addClass('hidden');
                $('#rider-otp-container').removeClass('hidden');
            });

            // Password Login Submit
            $('#rider-login-form').submit(function(e) {
                e.preventDefault();
                
                let btn = $('#btn-submit');
                let email = $('#email').val();
                let password = $('#password').val();
                let token = $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content');

                btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Authenticating...');

                $.ajax({
                    url: '/rider/login',
                    method: 'POST',
                    data: { 
                        email: email, 
                        password: password,
                        _token: token
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome Back!',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '/rider/dashboard';
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<span>Log In to Rider Console</span>');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Invalid rider credentials.';

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

            // Send Rider OTP Submit
            $('#rider-send-otp-form').submit(function(e) {
                e.preventDefault();
                let btn = $('#btn-rider-send-otp');
                let phone = $('#rider-otp-phone').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Sending OTP via WhatsApp...');

                $.ajax({
                    url: '/rider/send-otp',
                    method: 'POST',
                    data: { phone: phone, _token: token },
                    success: function(res) {
                        btn.prop('disabled', false).html('<i class="fa-brands fa-whatsapp text-lg mr-2"></i> <span>Get OTP on WhatsApp</span>');
                        $('#display-rider-phone').text('+91 ' + phone);
                        $('#rider-send-otp-form').addClass('hidden');
                        $('#rider-verify-otp-form').removeClass('hidden');
                        $('#rider-otp-code').focus();
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i class="fa-brands fa-whatsapp text-lg mr-2"></i> <span>Get OTP on WhatsApp</span>');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to send OTP.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#dc2626' });
                    }
                });
            });

            // Change Rider Phone
            $('#btn-rider-change-num').on('click', function() {
                $('#rider-verify-otp-form').addClass('hidden');
                $('#rider-send-otp-form').removeClass('hidden');
                $('#rider-otp-code').val('');
            });

            // Verify Rider OTP Submit
            $('#rider-verify-otp-form').submit(function(e) {
                e.preventDefault();
                let btn = $('#btn-rider-verify-otp');
                let phone = $('#rider-otp-phone').val();
                let otp = $('#rider-otp-code').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Verifying...');

                $.ajax({
                    url: '/rider/verify-otp',
                    method: 'POST',
                    data: { phone: phone, otp: otp, _token: token },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified!',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '/rider/dashboard';
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<span>Verify &amp; Enter Rider Console</span>');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Invalid OTP code.';
                        Swal.fire({ icon: 'error', title: 'Verification Failed', text: msg, confirmButtonColor: '#dc2626' });
                    }
                });
            });
        });

        // Register PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/rider-sw.js').then(function(reg) {
                    console.log('Rider PWA Service Worker registered');
                });
            });
        }

        // PWA Install Prompt Listener
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            $('#pwa-install-banner').removeClass('hidden');
        });

        $('#btn-install-pwa').click(function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        $('#pwa-install-banner').addClass('hidden');
                    }
                    deferredPrompt = null;
                });
            }
        });
    </script>
</body>
</html>
