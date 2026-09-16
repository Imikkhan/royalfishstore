@extends('layouts.admin')

@section('title', 'Settings Module')
@section('page_title', 'Settings Module')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Settings Form -->
    <form id="settings-form" class="space-y-6" enctype="multipart/form-data">
        @csrf
        
        <!-- General Store Settings -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-store text-red-500"></i> General Information
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Store / Website Name</label>
                    <input type="text" name="website_name" value="{{ $settings['website_name'] ?? '' }}" required class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tagline</label>
                    <input type="text" name="website_tagline" value="{{ $settings['website_tagline'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Contact Email</label>
                    <input type="email" name="website_email" value="{{ $settings['website_email'] ?? '' }}" required class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Contact Phone</label>
                    <input type="text" name="website_phone" value="{{ $settings['website_phone'] ?? '' }}" required class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Physical Address</label>
                <textarea name="website_address" rows="2" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">{{ $settings['website_address'] ?? '' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Update Logo</label>
                    <input type="file" name="logo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 hover:file:bg-slate-200 cursor-pointer">
                    @if(!empty($settings['website_logo']))
                        <img src="{{ $settings['website_logo'] }}" class="h-10 mt-2 rounded bg-slate-100 border p-1 object-contain">
                    @endif
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Update Favicon</label>
                    <input type="file" name="favicon" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 hover:file:bg-slate-200 cursor-pointer">
                    @if(!empty($settings['website_favicon']))
                        <img src="{{ $settings['website_favicon'] }}" class="h-8 w-8 mt-2 rounded bg-slate-100 border p-1 object-contain">
                    @endif
                </div>
            </div>
        </div>

        <!-- Order & Checkout Rules -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-emerald-500"></i> Order & Checkout Rules
            </h3>
            <p class="text-xs text-slate-400">Configure global basket restrictions and minimum checkout order value required from customers.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-indian-rupee-sign text-emerald-500 mr-1"></i> Minimum Order Amount (₹)
                    </label>
                    <input type="number" name="min_order_amount" value="{{ $settings['min_order_amount'] ?? '199' }}" min="0" required placeholder="199" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-sm font-extrabold">
                    <span class="text-[10px] text-slate-400 mt-1 block">Customer cannot checkout if cart total is below this amount</span>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-truck text-blue-500 mr-1"></i> Free Delivery Above (₹)
                    </label>
                    <input type="number" name="free_delivery_threshold" value="{{ $settings['free_delivery_threshold'] ?? '499' }}" min="0" placeholder="499" class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm font-extrabold">
                    <span class="text-[10px] text-slate-400 mt-1 block">Orders equal or above this get free express shipping</span>
                </div>
            </div>
        </div>

        <!-- Delivery Time Slots Configuration -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-clock text-amber-500"></i> Delivery Time Slots Management
            </h3>
            <p class="text-xs text-slate-400">Manage global delivery time slot displays. Admin can select these slots when creating or editing products.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">🌅 Morning Slot Display Text</label>
                    <input type="text" name="morning_delivery_slot" value="{{ $settings['morning_delivery_slot'] ?? 'Today 07:00 am - 12:00 pm' }}" required placeholder="e.g. Today 07:00 am - 12:00 pm" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">🌆 Evening Slot Display Text</label>
                    <input type="text" name="evening_delivery_slot" value="{{ $settings['evening_delivery_slot'] ?? 'Today 4:00pm - 08:30 pm' }}" required placeholder="e.g. Today 4:00pm - 08:30 pm" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>
        </div>

        <!-- SEO Management -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-blue-500"></i> SEO Metadata Config
            </h3>
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Meta Title Tag</label>
                <input type="text" name="seo_meta_title" value="{{ $settings['seo_meta_title'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Meta Description</label>
                <textarea name="seo_meta_description" rows="2" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">{{ $settings['seo_meta_description'] ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Keywords</label>
                <input type="text" name="seo_meta_keywords" value="{{ $settings['seo_meta_keywords'] ?? '' }}" placeholder="fresh fish, sea harvest, seafood" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
            </div>
        </div>

        <!-- Mail SMTP Configuration -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-purple-500"></i> SMTP Server Settings (Mailing)
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">SMTP Hostname</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1 font-mono">Port</label>
                    <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] ?? '' }}" placeholder="587" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" name="smtp_user" value="{{ $settings['smtp_user'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>
        </div>

        <!-- WhatsApp Notifications Configuration -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-emerald-500 text-lg"></i>
                    <span>WhatsApp Order & Logistics Notifications</span>
                </div>
                <span class="text-[10px] bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 font-extrabold px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
                    Codebey & Meta Active
                </span>
            </h3>
            <p class="text-xs text-slate-400">Automated WhatsApp alerts sent to Admin, Customers, and Delivery Boys (Riders).</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-phone text-emerald-500 mr-1"></i> Admin WhatsApp Phone Number
                    </label>
                    <input type="text" name="admin_whatsapp_phone" value="{{ $settings['admin_whatsapp_phone'] ?? ($settings['website_phone'] ?? '') }}" placeholder="e.g. 9198308XXXXX" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-sm font-bold font-mono">
                    <span class="text-[10px] text-slate-400 mt-1 block">New order alerts will be instantly sent to this WhatsApp number</span>
                </div>

                <div class="space-y-2.5 pt-1 sm:pt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="notify_admin_whatsapp" value="0">
                        <input type="checkbox" name="notify_admin_whatsapp" value="1" {{ (!isset($settings['notify_admin_whatsapp']) || $settings['notify_admin_whatsapp'] == '1') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">🚨 Alert Admin on New Orders</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="notify_customer_order_placed" value="0">
                        <input type="checkbox" name="notify_customer_order_placed" value="1" {{ (!isset($settings['notify_customer_order_placed']) || $settings['notify_customer_order_placed'] == '1') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">🎉 Send Order Confirmation to Customer</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="notify_customer_status_change" value="0">
                        <input type="checkbox" name="notify_customer_status_change" value="1" {{ (!isset($settings['notify_customer_status_change']) || $settings['notify_customer_status_change'] == '1') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">📦 Notify Customer on Status Change</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="notify_rider_assignment" value="0">
                        <input type="checkbox" name="notify_rider_assignment" value="1" {{ (!isset($settings['notify_rider_assignment']) || $settings['notify_rider_assignment'] == '1') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">🛵 Notify Delivery Boy on Order Assignment</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Social Media Connections -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-share-nodes text-emerald-500"></i> Social Media Links
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1"><i class="fa-brands fa-facebook"></i> Facebook Link</label>
                    <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1"><i class="fa-brands fa-instagram"></i> Instagram Link</label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1"><i class="fa-brands fa-twitter"></i> Twitter Link</label>
                    <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" class="block w-full px-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 text-sm">
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex justify-end gap-2 bg-white dark:bg-slate-900 p-4 border border-slate-200/60 dark:border-slate-800 rounded-2xl shadow-xs">
            <button type="submit" id="btn-save-settings" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm active:scale-98">
                <i class="fa-solid fa-circle-check"></i> Save Store Configurations
            </button>
        </div>

    </form>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#settings-form').submit(function(e) {
            e.preventDefault();
            
            let btn = $('#btn-save-settings');
            let formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch animate-spin mr-1.5"></i> Saving...');

            $.ajax({
                url: '/admin/settings/save',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-circle-check"></i> Save Store Configurations');
                    Swal.fire({
                        icon: 'success',
                        title: 'Settings Saved',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-circle-check"></i> Save Store Configurations');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Failed to save settings. Please try again.',
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        });
    });
</script>
@endsection
@endsection
