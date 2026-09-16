@extends('layouts.admin')

@section('title', 'Facebook Ad Page & Onepager Manage')

@section('content')
<div class="space-y-6 select-none max-w-7xl mx-auto pb-16">
    
    <!-- Top Action & Info Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                    <i class="fa-brands fa-facebook text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Facebook Ad Page & Onepager Manager</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manage Hero banner, Single Product Showcase, Headings, Category Sections, Reviews, and FAQs.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ url('/') }}/onepager" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Preview Onepager</span>
            </a>

            <button type="button" id="btn-save-all" class="px-5 py-2.5 bg-[#fc490f] hover:bg-orange-600 active:scale-95 text-white text-xs font-black rounded-xl shadow-md shadow-orange-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Settings</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2 overflow-x-auto">
        <button type="button" onclick="switchTab('hero')" id="tab-btn-hero" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-[#fc490f] text-white shadow-sm">
            <i class="fa-solid fa-star"></i>
            <span>1. Hero Banner</span>
        </button>
        <button type="button" onclick="switchTab('sections')" id="tab-btn-sections" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>2. Products & Sections</span>
        </button>
        <button type="button" onclick="switchTab('reviews')" id="tab-btn-reviews" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
            <i class="fa-solid fa-comments"></i>
            <span>3. Customer Reviews</span>
        </button>
        <button type="button" onclick="switchTab('faqs')" id="tab-btn-faqs" class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
            <i class="fa-solid fa-circle-question"></i>
            <span>4. FAQs</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: HERO BANNER SETTINGS                -->
    <!-- ========================================== -->
    <div id="tab-content-hero" class="tab-content space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#fc490f]"></span>
                    Hero Banner Headline & Visuals
                </h3>
                <p class="text-xs text-slate-500">Top attention-grabbing hook shown when customers click from Facebook Ads.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Offer Badge -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Offer Badge Text</label>
                    <input type="text" id="hero-badge" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold" placeholder="e.g. 🔥 আজকের স্পেশাল ইলিশ অফার">
                </div>

                <!-- Hero Image URL -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Hero Visual Image URL</label>
                    <input type="text" id="hero-image" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono" placeholder="e.g. /hilsa_hero.png or full image URL">
                </div>

                <!-- Main Catchy Headline -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Main Headline (Bangla / English) - Full HTML & Color span supported
                    </label>
                    <input type="text" id="hero-title" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-black" placeholder="e.g. কলকাতায় এবার ঘরে বসেই উপভোগ করুন তেলতেলে রাজকীয় ইলিশ">
                </div>

                <!-- Description / Subtitle -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Subtitle / Description</label>
                    <textarea id="hero-subtitle" rows="2" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium" placeholder="e.g. ১ কেজি+ সাইজের স্পেশাল ইলিশ—কাটিং, পরিষ্কার ও হাইজেনিক প্যাকেজিংসহ পৌঁছে যাবে আপনার রান্নাঘরে।"></textarea>
                </div>
            </div>

            <!-- 2 Offer Spec Boxes -->
            <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                <h4 class="text-xs font-black uppercase text-slate-400 mb-4 tracking-wider">Hero 2 Price / Spec Feature Boxes</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Box 1 -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                        <span class="text-xs font-extrabold text-[#fc490f]">Spec Box 1</span>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Title</label>
                            <input type="text" id="hero-pbox1-title" class="w-full px-3 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-bold" placeholder="e.g. ১ কেজি+ সম্পূর্ণ ইলিশ">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Price</label>
                                <input type="text" id="hero-pbox1-price" class="w-full px-3 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-black text-[#fc490f]" placeholder="e.g. ₹1,399">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Unit</label>
                                <input type="text" id="hero-pbox1-unit" class="w-full px-3 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg" placeholder="e.g. /কেজি">
                            </div>
                        </div>
                    </div>

                    <!-- Box 2 -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                        <span class="text-xs font-extrabold text-[#fc490f]">Spec Box 2</span>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Title</label>
                            <input type="text" id="hero-pbox2-title" class="w-full px-3 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-bold" placeholder="e.g. ৭০-৮০ গ্রাম কাটা পিস">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Price</label>
                                <input type="text" id="hero-pbox2-price" class="w-full px-3 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-black text-[#fc490f]" placeholder="e.g. ₹149">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Unit</label>
                                <input type="text" id="hero-pbox2-unit" class="w-full px-3 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg" placeholder="e.g. /পিস">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons & Badges -->
            <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                <h4 class="text-xs font-black uppercase text-slate-400 mb-4 tracking-wider">CTA Buttons & Floating Badges</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Order Button Label</label>
                        <input type="text" id="hero-btn-order" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-bold" placeholder="e.g. 🐟 এখনই অর্ডার করুন">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">WhatsApp Button Label</label>
                        <input type="text" id="hero-btn-whatsapp" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-bold" placeholder="e.g. WhatsApp-এ কথা বলুন">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">WhatsApp Number</label>
                        <input type="text" id="hero-whatsapp-num" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-mono" placeholder="e.g. 919876543210">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Delivery Badge Text</label>
                        <input type="text" id="hero-delivery-badge" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg" placeholder="e.g. ২৪ ঘণ্টার মধ্যে আপনার দরজায় Delivery">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: PRODUCTS & CATEGORY SECTIONS        -->
    <!-- ========================================== -->
    <div id="tab-content-sections" class="tab-content hidden space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white">Product Display Sections</h3>
                <p class="text-xs text-slate-500">Choose between 4-Products Grid or Single Featured Product Showcase (with custom headline above each).</p>
            </div>
            <button type="button" id="btn-add-section" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>+ Add Section</span>
            </button>
        </div>

        <div id="sections-container" class="space-y-6">
            <!-- Dynamically populated via JS -->
        </div>

        <!-- Empty State Placeholder -->
        <div id="empty-sections-placeholder" class="hidden p-12 text-center bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-800 space-y-3">
            <div class="w-16 h-16 rounded-full bg-orange-100 dark:bg-orange-950/50 text-[#fc490f] flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-folder-plus"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">No Sections Added Yet</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">Click "+ Add Section" to create your first featured section with custom Bangla title and products.</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: CUSTOMER REVIEWS                    -->
    <!-- ========================================== -->
    <div id="tab-content-reviews" class="tab-content hidden space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        Customer Reviews & Testimonials
                    </h3>
                    <p class="text-xs text-slate-500">Manage real customer quotes and photos shown on the Onepager.</p>
                </div>
                <button type="button" onclick="addReviewItem()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Add Review</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Section Title</label>
                    <input type="text" id="reviews-title" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold" placeholder="e.g. What Real Seafood Lovers Say">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Section Subtitle</label>
                    <input type="text" id="reviews-subtitle" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium" placeholder="e.g. Facebook par ad dekh kar order karne wale customer ke asli reviews">
                </div>
            </div>

            <!-- Reviews List Container -->
            <div id="reviews-container" class="space-y-4 pt-2">
                <!-- Populated via JS -->
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: FAQS SECTION                        -->
    <!-- ========================================== -->
    <div id="tab-content-faqs" class="tab-content hidden space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        Frequently Asked Questions (FAQs)
                    </h3>
                    <p class="text-xs text-slate-500">Questions & answers shown to clarify PIN codes, delivery time, COD, etc.</p>
                </div>
                <button type="button" onclick="addFaqItem()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Add FAQ</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Section Title (বাংলায় হেডলাইন)</label>
                    <input type="text" id="faqs-title" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold" placeholder="e.g. সাধারণ কিছু প্রশ্নের উত্তর">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Section Subtitle / Tag</label>
                    <input type="text" id="faqs-subtitle" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium" placeholder="e.g. প্রয়োজনীয় তথ্য">
                </div>
            </div>

            <!-- FAQs List Container -->
            <div id="faqs-container" class="space-y-4 pt-2">
                <!-- Populated via JS -->
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Initial data from server
    const ALL_CATEGORIES = @json($categories);
    const ALL_PRODUCTS = @json($products);
    let SECTIONS_DATA = @json($sections);
    let HERO_DATA = @json($hero);
    let REVIEWS_DATA = @json($reviews);
    let FAQS_DATA = @json($faqs);

    if (!Array.isArray(SECTIONS_DATA)) SECTIONS_DATA = [];
    if (!HERO_DATA || typeof HERO_DATA !== 'object') HERO_DATA = {};
    if (!REVIEWS_DATA || typeof REVIEWS_DATA !== 'object') REVIEWS_DATA = { items: [] };
    if (!Array.isArray(REVIEWS_DATA.items)) REVIEWS_DATA.items = [];
    if (!FAQS_DATA || typeof FAQS_DATA !== 'object') FAQS_DATA = { items: [] };
    if (!Array.isArray(FAQS_DATA.items)) FAQS_DATA.items = [];

    // Switch Tab function
    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#fc490f]', 'text-white', 'shadow-sm');
            btn.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        const activeBtn = document.getElementById(`tab-btn-${tabId}`);
        const activeContent = document.getElementById(`tab-content-${tabId}`);

        if (activeBtn) {
            activeBtn.classList.add('bg-[#fc490f]', 'text-white', 'shadow-sm');
            activeBtn.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
        }
        if (activeContent) {
            activeContent.classList.remove('hidden');
        }
    }

    // =========================================================================
    // 1. HERO TAB POPULATION
    // =========================================================================
    function populateHeroFields() {
        document.getElementById('hero-badge').value = HERO_DATA.badge || '🔥 আজকের স্পেশাল ইলিশ অফার';
        document.getElementById('hero-image').value = HERO_DATA.hero_image || '/hilsa_hero.png';
        document.getElementById('hero-title').value = HERO_DATA.title || 'কলকাতায় এবার ঘরে বসেই উপভোগ করুন তেলতেলে রাজকীয় ইলিশ';
        document.getElementById('hero-subtitle').value = HERO_DATA.subtitle || '১ কেজি+ সাইজের স্পেশাল ইলিশ—কাটিং, পরিষ্কার ও হাইজেনিক প্যাকেজিংসহ পৌঁছে যাবে আপনার রান্নাঘরে।';

        document.getElementById('hero-pbox1-title').value = HERO_DATA.price_box_1_title || '১ কেজি+ সম্পূর্ণ ইলিশ';
        document.getElementById('hero-pbox1-price').value = HERO_DATA.price_box_1_price || '₹1,399';
        document.getElementById('hero-pbox1-unit').value = HERO_DATA.price_box_1_unit || '/কেজি';

        document.getElementById('hero-pbox2-title').value = HERO_DATA.price_box_2_title || '৭০-৮০ গ্রাম কাটা পিস';
        document.getElementById('hero-pbox2-price').value = HERO_DATA.price_box_2_price || '₹149';
        document.getElementById('hero-pbox2-unit').value = HERO_DATA.price_box_2_unit || '/পিস';

        document.getElementById('hero-btn-order').value = HERO_DATA.btn_order_text || '🐟 এখনই অর্ডার করুন';
        document.getElementById('hero-btn-whatsapp').value = HERO_DATA.btn_whatsapp_text || 'WhatsApp-এ কথা বলুন';
        document.getElementById('hero-whatsapp-num').value = HERO_DATA.whatsapp_number || '919876543210';
        document.getElementById('hero-delivery-badge').value = HERO_DATA.delivery_badge || '২৪ ঘণ্টার মধ্যে আপনার দরজায় Delivery';
    }

    function collectHeroData() {
        return {
            badge: document.getElementById('hero-badge').value,
            hero_image: document.getElementById('hero-image').value,
            title: document.getElementById('hero-title').value,
            subtitle: document.getElementById('hero-subtitle').value,
            price_box_1_title: document.getElementById('hero-pbox1-title').value,
            price_box_1_price: document.getElementById('hero-pbox1-price').value,
            price_box_1_unit: document.getElementById('hero-pbox1-unit').value,
            price_box_2_title: document.getElementById('hero-pbox2-title').value,
            price_box_2_price: document.getElementById('hero-pbox2-price').value,
            price_box_2_unit: document.getElementById('hero-pbox2-unit').value,
            btn_order_text: document.getElementById('hero-btn-order').value,
            btn_whatsapp_text: document.getElementById('hero-btn-whatsapp').value,
            whatsapp_number: document.getElementById('hero-whatsapp-num').value,
            delivery_badge: document.getElementById('hero-delivery-badge').value,
            fresh_badge: HERO_DATA.fresh_badge || '100% FRESH'
        };
    }

    // =========================================================================
    // 2. SECTIONS TAB (GRID VS SINGLE PRODUCT SHOWCASE WITH HEADING ABOVE EACH)
    // =========================================================================
    function renderSections() {
        const container = document.getElementById('sections-container');
        const emptyState = document.getElementById('empty-sections-placeholder');

        if (SECTIONS_DATA.length === 0) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        container.innerHTML = '';

        SECTIONS_DATA.forEach((sec, index) => {
            const card = document.createElement('div');
            card.className = 'bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-all section-card';
            card.dataset.sectionIndex = index;

            const selectedCat = ALL_CATEGORIES.find(c => String(c.id) === String(sec.category_id));
            const selProdIds = Array.isArray(sec.product_ids) ? sec.product_ids.map(Number) : [];
            const layoutType = sec.layout_type || 'grid';
            if (!sec.promo_headings) sec.promo_headings = {};

            card.innerHTML = `
                <!-- Section Header -->
                <div class="p-4 sm:p-5 bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-orange-100 text-[#fc490f] font-black text-xs flex items-center justify-center shadow-xs">
                            #${index + 1}
                        </span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white section-title-display">
                                ${sec.title || 'ক্যাটাগরি সেকশন'}
                            </h3>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <span>Category: <strong class="text-slate-700 dark:text-slate-300">${selectedCat ? selectedCat.name : 'Not selected'}</strong></span>
                                <span>•</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold ${layoutType === 'single_showcase' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'}">
                                    ${layoutType === 'single_showcase' ? '⭐ Single Product Showcase' : '📦 4 Products Grid'}
                                </span>
                                <span>•</span>
                                <span><strong class="text-emerald-600">${selProdIds.length}</strong> Products Selected</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">
                            <input type="checkbox" class="rounded text-[#fc490f] focus:ring-[#fc490f]" ${sec.is_active !== false ? 'checked' : ''} onchange="updateSectionField(${index}, 'is_active', this.checked)">
                            <span>Active</span>
                        </label>

                        ${index > 0 ? `
                        <button type="button" onclick="moveSection(${index}, -1)" class="p-2 bg-white dark:bg-slate-800 hover:bg-slate-100 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 text-xs" title="Move Up">
                            <i class="fa-solid fa-arrow-up"></i>
                        </button>
                        ` : ''}

                        ${index < SECTIONS_DATA.length - 1 ? `
                        <button type="button" onclick="moveSection(${index}, 1)" class="p-2 bg-white dark:bg-slate-800 hover:bg-slate-100 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 text-xs" title="Move Down">
                            <i class="fa-solid fa-arrow-down"></i>
                        </button>
                        ` : ''}

                        <button type="button" onclick="deleteSection(${index})" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl border border-red-200 text-xs transition-colors" title="Delete Section">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <!-- Section Body -->
                <div class="p-5 sm:p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        
                        <!-- Left 4 cols: Layout, Badge & Category -->
                        <div class="md:col-span-4 space-y-4">
                            <!-- Layout Switcher -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Display Layout Style (লেআউট স্টাইল)
                                </label>
                                <select 
                                    onchange="updateSectionField(${index}, 'layout_type', this.value); renderSections();"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]"
                                >
                                    <option value="grid" ${layoutType === 'grid' ? 'selected' : ''}>📦 4 Products Grid (Standard Collection)</option>
                                    <option value="single_showcase" ${layoutType === 'single_showcase' ? 'selected' : ''}>⭐ Single Product Showcase (Headings above each product)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Section Badge (বাংলা বা English)
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.badge || '🔥 আজকের স্পেশাল অফার')}" 
                                    oninput="updateSectionField(${index}, 'badge', this.value)"
                                    placeholder="e.g. 🔥 আজকের স্পেশাল অফার"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Target Category
                                </label>
                                <select 
                                    onchange="handleCategoryChange(${index}, this.value)"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white"
                                >
                                    <option value="">-- Choose Category --</option>
                                    ${ALL_CATEGORIES.map(c => `
                                        <option value="${c.id}" ${String(c.id) === String(sec.category_id) ? 'selected' : ''}>
                                            ${c.icon || '📦'} ${c.name} ${c.parent_id ? '(Subcategory)' : ''}
                                        </option>
                                    `).join('')}
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    View All Button Label
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.view_all_label || 'সকল পণ্য দেখুন (View All)')}" 
                                    oninput="updateSectionField(${index}, 'view_all_label', this.value)"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold"
                                >
                            </div>
                        </div>

                        <!-- Right 8 cols: Title, Subtitle & Product Selector -->
                        <div class="md:col-span-8 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Section Title (বাংলায় হেডলাইন) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.title || '')}" 
                                    oninput="updateSectionField(${index}, 'title', this.value)"
                                    placeholder="e.g. তাজা পদ্মার ইলিশ ও মাছের স্পেশাল কালেকশন"
                                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Section Subtitle / Description
                                </label>
                                <textarea 
                                    rows="2" 
                                    oninput="updateSectionField(${index}, 'subtitle', this.value)"
                                    class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium"
                                >${escapeHtml(sec.subtitle || '')}</textarea>
                            </div>

                            <!-- Product Selection Box -->
                            <div class="pt-1">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Select Products to Feature (${layoutType === 'single_showcase' ? 'Shown individually with big cards' : 'Shown in 4-card grid'}):
                                    </label>
                                    <span class="text-[11px] font-bold text-slate-500">
                                        ${selProdIds.length} Selected
                                    </span>
                                </div>

                                <div class="max-h-52 overflow-y-auto p-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    ${renderProductCheckboxes(index, sec.category_id, selProdIds)}
                                </div>
                            </div>

                            <!-- NEW: Promotional Headings Above Each Selected Product -->
                            ${selProdIds.length > 0 ? `
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-[#fc490f]">✨ Custom Heading Above Each Product:</span>
                                    <span class="text-[10px] text-slate-400">(Aapke screenshot ke mutabiq har product ke upar alag pitch headline)</span>
                                </div>
                                <div class="space-y-3">
                                    ${selProdIds.map(pid => {
                                        const prod = ALL_PRODUCTS.find(p => Number(p.id) === Number(pid));
                                        if (!prod) return '';
                                        const currentHeading = (sec.promo_headings && (sec.promo_headings[pid] || sec.promo_headings[String(pid)])) || '';
                                        return `
                                            <div class="p-3 rounded-xl bg-orange-50/50 dark:bg-slate-800/80 border border-orange-200/60 dark:border-slate-700 space-y-1.5">
                                                <div class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-white">
                                                    <img src="${prod.image || '/logo.png'}" class="w-6 h-6 rounded object-cover">
                                                    <span class="truncate">${escapeHtml(prod.name)}</span>
                                                    <span class="text-[#fc490f] text-[11px] font-black shrink-0">₹${prod.price}</span>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    value="${escapeHtml(currentHeading)}" 
                                                    oninput="updateProductHeading(${index}, ${pid}, this.value)"
                                                    placeholder="e.g. Now you can buy just ONE piece of premium Hilsa from a whole fish — right from home!"
                                                    class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-white font-medium focus:ring-1 focus:ring-[#fc490f]"
                                                >
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                            ` : ''}

                        </div>

                    </div>
                </div>
            `;

            container.appendChild(card);
        });
    }

    function renderProductCheckboxes(sectionIndex, categoryId, selectedIds) {
        let relevantProducts = ALL_PRODUCTS;
        if (categoryId) {
            const cat = ALL_CATEGORIES.find(c => String(c.id) === String(categoryId));
            if (cat) {
                relevantProducts = ALL_PRODUCTS.filter(p => 
                    String(p.category_id) === String(categoryId) || 
                    (p.sub_category && p.sub_category.toLowerCase() === cat.name.toLowerCase())
                );
                if (relevantProducts.length < 4) {
                    const others = ALL_PRODUCTS.filter(p => !relevantProducts.some(rp => rp.id === p.id));
                    relevantProducts = [...relevantProducts, ...others];
                }
            }
        }

        if (relevantProducts.length === 0) {
            return `<div class="col-span-2 text-center p-4 text-xs text-slate-400">No products found.</div>`;
        }

        return relevantProducts.map(p => {
            const isChecked = selectedIds.includes(Number(p.id));
            const imgUrl = p.image || '/logo.png';

            return `
                <label class="flex items-center gap-3 p-2.5 rounded-xl border transition-all cursor-pointer ${
                    isChecked 
                        ? 'bg-orange-50/80 dark:bg-orange-950/40 border-[#fc490f] text-slate-900 dark:text-white shadow-xs' 
                        : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-orange-300'
                }">
                    <input 
                        type="checkbox" 
                        value="${p.id}" 
                        ${isChecked ? 'checked' : ''} 
                        onchange="toggleProductSelection(${sectionIndex}, ${p.id}, this.checked)"
                        class="rounded text-[#fc490f] focus:ring-[#fc490f]"
                    >
                    <img src="${imgUrl}" alt="${escapeHtml(p.name)}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 shrink-0">
                    <div class="min-w-0 flex-1 leading-tight">
                        <span class="text-xs font-bold block truncate">${escapeHtml(p.name)}</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[11px] font-black text-[#fc490f]">₹${p.price}</span>
                            <span class="text-[10px] text-slate-400 font-mono">${p.weight || '500g'}</span>
                        </div>
                    </div>
                </label>
            `;
        }).join('');
    }

    function toggleProductSelection(sectionIndex, productId, isChecked) {
        productId = Number(productId);
        let ids = SECTIONS_DATA[sectionIndex].product_ids || [];
        ids = ids.map(Number);

        if (isChecked) {
            if (!ids.includes(productId)) ids.push(productId);
        } else {
            ids = ids.filter(id => id !== productId);
        }

        SECTIONS_DATA[sectionIndex].product_ids = ids;
        renderSections();
    }

    function updateSectionField(index, field, value) {
        if (!SECTIONS_DATA[index]) return;
        SECTIONS_DATA[index][field] = value;
        if (field === 'title') {
            const display = document.querySelector(`.section-card[data-section-index="${index}"] .section-title-display`);
            if (display) display.textContent = value || 'ক্যাটাগরি সেকশন';
        }
    }

    function updateProductHeading(sectionIndex, productId, value) {
        if (!SECTIONS_DATA[sectionIndex]) return;
        if (!SECTIONS_DATA[sectionIndex].promo_headings) {
            SECTIONS_DATA[sectionIndex].promo_headings = {};
        }
        SECTIONS_DATA[sectionIndex].promo_headings[productId] = value;
    }

    function handleCategoryChange(index, newCatId) {
        SECTIONS_DATA[index].category_id = newCatId ? Number(newCatId) : '';
        if (newCatId && (!SECTIONS_DATA[index].product_ids || SECTIONS_DATA[index].product_ids.length === 0)) {
            const cat = ALL_CATEGORIES.find(c => String(c.id) === String(newCatId));
            if (cat) {
                const autoProds = ALL_PRODUCTS.filter(p => 
                    String(p.category_id) === String(newCatId) || 
                    (p.sub_category && p.sub_category.toLowerCase() === cat.name.toLowerCase())
                ).slice(0, 4).map(p => Number(p.id));
                SECTIONS_DATA[index].product_ids = autoProds;
            }
        }
        renderSections();
    }

    function moveSection(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= SECTIONS_DATA.length) return;
        const temp = SECTIONS_DATA[index];
        SECTIONS_DATA[index] = SECTIONS_DATA[target];
        SECTIONS_DATA[target] = temp;
        renderSections();
    }

    function deleteSection(index) {
        Swal.fire({
            title: 'Delete this section?',
            text: 'It will be removed from the Facebook Ad onepager.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fc490f',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                SECTIONS_DATA.splice(index, 1);
                renderSections();
            }
        });
    }

    document.getElementById('btn-add-section').addEventListener('click', () => {
        const defaultCat = ALL_CATEGORIES[0] || null;
        const autoProds = defaultCat 
            ? ALL_PRODUCTS.filter(p => String(p.category_id) === String(defaultCat.id)).slice(0, 4).map(p => Number(p.id))
            : ALL_PRODUCTS.slice(0, 4).map(p => Number(p.id));

        SECTIONS_DATA.push({
            id: 'sec_' + Date.now(),
            badge: '🔥 আজকের স্পেশাল অফার',
            title: defaultCat ? `${defaultCat.name} স্পেশাল কালেকশন` : 'নতুন কালেকশন',
            subtitle: 'তাজা ও হাইজেনিক সরাসরি আপনার দরজায় পৌঁছে যাবে।',
            layout_type: 'single_showcase', // Default to showcase for high conversion!
            category_id: defaultCat ? defaultCat.id : '',
            product_ids: autoProds,
            promo_headings: {},
            view_all_label: 'সকল পণ্য দেখুন (View All)',
            view_all_link: '#featured-products',
            is_active: true
        });

        renderSections();
    });

    // =========================================================================
    // 3. REVIEWS TAB
    // =========================================================================
    function renderReviews() {
        const container = document.getElementById('reviews-container');
        document.getElementById('reviews-title').value = REVIEWS_DATA.title || 'What Real Seafood Lovers Say';
        document.getElementById('reviews-subtitle').value = REVIEWS_DATA.subtitle || 'Facebook par ad dekh kar order karne wale customer ke asli reviews';

        container.innerHTML = '';
        if (!REVIEWS_DATA.items || REVIEWS_DATA.items.length === 0) {
            container.innerHTML = '<p class="text-xs text-slate-400 p-4 text-center">No customer reviews added yet. Click "+ Add Review".</p>';
            return;
        }

        REVIEWS_DATA.items.forEach((rev, idx) => {
            const row = document.createElement('div');
            row.className = 'p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3';
            row.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-amber-500">Review #${idx + 1}</span>
                    <button type="button" onclick="deleteReviewItem(${idx})" class="text-red-500 hover:text-red-700 text-xs font-bold">
                        <i class="fa-solid fa-trash-can"></i> Remove
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Customer Name</label>
                        <input type="text" value="${escapeHtml(rev.name || '')}" oninput="REVIEWS_DATA.items[${idx}].name = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-bold" placeholder="e.g. Sunita Roy">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Location / Tag</label>
                        <input type="text" value="${escapeHtml(rev.city || '')}" oninput="REVIEWS_DATA.items[${idx}].city = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg" placeholder="e.g. Kolkata • Verified Buyer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Star Rating (1-5)</label>
                        <select onchange="REVIEWS_DATA.items[${idx}].rating = Number(this.value)" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-bold">
                            <option value="5" ${Number(rev.rating) === 5 ? 'selected' : ''}>★★★★★ (5 Stars)</option>
                            <option value="4" ${Number(rev.rating) === 4 ? 'selected' : ''}>★★★★☆ (4 Stars)</option>
                            <option value="3" ${Number(rev.rating) === 3 ? 'selected' : ''}>★★★☆☆ (3 Stars)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Ordered Item Tag</label>
                        <input type="text" value="${escapeHtml(rev.ordered_item || '')}" oninput="REVIEWS_DATA.items[${idx}].ordered_item = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-semibold" placeholder="e.g. Ordered: Fresh Hilsa 1kg Cut">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Customer Photo URL</label>
                        <input type="text" value="${escapeHtml(rev.image || '')}" oninput="REVIEWS_DATA.items[${idx}].image = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-mono" placeholder="e.g. https://... or /uploads/...">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Review Text / Feedback</label>
                    <textarea rows="2" oninput="REVIEWS_DATA.items[${idx}].text = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-medium" placeholder="Review comment...">${escapeHtml(rev.text || '')}</textarea>
                </div>
            `;
            container.appendChild(row);
        });
    }

    function addReviewItem() {
        if (!REVIEWS_DATA.items) REVIEWS_DATA.items = [];
        REVIEWS_DATA.items.push({
            name: 'Happy Customer',
            city: 'Kolkata • Verified Buyer',
            rating: 5,
            text: 'Very fresh fish delivered right on time!',
            image: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
            ordered_item: 'Ordered: Fresh Ilish'
        });
        renderReviews();
    }

    function deleteReviewItem(idx) {
        REVIEWS_DATA.items.splice(idx, 1);
        renderReviews();
    }

    function collectReviewsData() {
        return {
            title: document.getElementById('reviews-title').value,
            subtitle: document.getElementById('reviews-subtitle').value,
            items: REVIEWS_DATA.items
        };
    }

    // =========================================================================
    // 4. FAQS TAB
    // =========================================================================
    function renderFaqs() {
        const container = document.getElementById('faqs-container');
        document.getElementById('faqs-title').value = FAQS_DATA.title || 'সাধারণ কিছু প্রশ্নের উত্তর';
        document.getElementById('faqs-subtitle').value = FAQS_DATA.subtitle || 'প্রয়োজনীয় তথ্য';

        container.innerHTML = '';
        if (!FAQS_DATA.items || FAQS_DATA.items.length === 0) {
            container.innerHTML = '<p class="text-xs text-slate-400 p-4 text-center">No FAQs added yet. Click "+ Add FAQ".</p>';
            return;
        }

        FAQS_DATA.items.forEach((faq, idx) => {
            const row = document.createElement('div');
            row.className = 'p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3';
            row.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-blue-500">FAQ #${idx + 1}</span>
                    <button type="button" onclick="deleteFaqItem(${idx})" class="text-red-500 hover:text-red-700 text-xs font-bold">
                        <i class="fa-solid fa-trash-can"></i> Remove
                    </button>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Question (প্রশ্ন - বাংলা বা English)</label>
                    <input type="text" value="${escapeHtml(faq.q || '')}" oninput="FAQS_DATA.items[${idx}].q = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-bold" placeholder="e.g. কোন কোন PIN code-এ Delivery হবে?">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Answer (উত্তর)</label>
                    <textarea rows="2" oninput="FAQS_DATA.items[${idx}].a = this.value" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-medium" placeholder="Answer details...">${escapeHtml(faq.a || '')}</textarea>
                </div>
            `;
            container.appendChild(row);
        });
    }

    function addFaqItem() {
        if (!FAQS_DATA.items) FAQS_DATA.items = [];
        FAQS_DATA.items.push({
            q: 'নতুন প্রশ্ন (New Question)?',
            a: 'প্রশ্নের উত্তর এখানে লিখুন।'
        });
        renderFaqs();
    }

    function deleteFaqItem(idx) {
        FAQS_DATA.items.splice(idx, 1);
        renderFaqs();
    }

    function collectFaqsData() {
        return {
            title: document.getElementById('faqs-title').value,
            subtitle: document.getElementById('faqs-subtitle').value,
            items: FAQS_DATA.items
        };
    }

    // =========================================================================
    // SAVE ALL SETTINGS (HERO + SECTIONS + REVIEWS + FAQS)
    // =========================================================================
    document.getElementById('btn-save-all').addEventListener('click', function() {
        const btn = this;
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving All...';

        const heroPayload = collectHeroData();
        const reviewsPayload = collectReviewsData();
        const faqsPayload = collectFaqsData();

        $.ajax({
            url: '{{ url("/admin/facebook-ad/save") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                sections: SECTIONS_DATA,
                hero: heroPayload,
                reviews: reviewsPayload,
                faqs: faqsPayload
            },
            success: function(res) {
                btn.disabled = false;
                btn.innerHTML = origText;

                Swal.fire({
                    icon: 'success',
                    title: 'All Onepager Settings Saved!',
                    text: 'Hero, Products Showcase, Reviews, and FAQs are now live on /onepager.',
                    confirmButtonColor: '#fc490f'
                });
            },
            error: function(xhr) {
                btn.disabled = false;
                btn.innerHTML = origText;

                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: xhr.responseJSON?.message || 'Error occurred while saving settings.',
                    confirmButtonColor: '#fc490f'
                });
            }
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Initialize all tabs
    document.addEventListener('DOMContentLoaded', () => {
        populateHeroFields();
        renderSections();
        renderReviews();
        renderFaqs();
    });
</script>
@endsection
