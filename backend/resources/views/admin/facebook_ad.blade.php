@extends('layouts.admin')

@section('title', 'Facebook Ad Page Manage')

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
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Facebook Ad Page Manage</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manage Hero bottom Category sections, Bangla headings, and featured 4 products.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}/onepager" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Preview Onepager</span>
            </a>

            <button type="button" id="btn-add-section" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>+ Add Category Section</span>
            </button>

            <button type="button" id="btn-save-all" class="px-5 py-2.5 bg-[#fc490f] hover:bg-orange-600 active:scale-95 text-white text-xs font-black rounded-xl shadow-md shadow-orange-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Sections</span>
            </button>
        </div>
    </div>

    <!-- Multi-Category Sections Container -->
    <div id="sections-container" class="space-y-6">
        <!-- Dynamically rendered via JS -->
    </div>

    <!-- Empty State Template -->
    <div id="empty-sections-placeholder" class="hidden p-12 text-center bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-800 space-y-3">
        <div class="w-16 h-16 rounded-full bg-orange-100 dark:bg-orange-950/50 text-[#fc490f] flex items-center justify-center mx-auto text-2xl">
            <i class="fa-solid fa-folder-plus"></i>
        </div>
        <h3 class="text-base font-bold text-slate-900 dark:text-white">No Category Sections Added Yet</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">Click "+ Add Category Section" to create your first featured section with custom Bangla title and 4 products.</p>
        <button type="button" onclick="document.getElementById('btn-add-section').click()" class="px-4 py-2 bg-[#fc490f] text-white text-xs font-bold rounded-xl">
            + Add First Section
        </button>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Server-provided categories & products catalogs
    const ALL_CATEGORIES = @json($categories);
    const ALL_PRODUCTS = @json($products);
    let SECTIONS_DATA = @json($sections);

    if (!Array.isArray(SECTIONS_DATA)) {
        SECTIONS_DATA = [];
    }

    // Render all section builder cards
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

            // Resolve Category
            const selectedCat = ALL_CATEGORIES.find(c => String(c.id) === String(sec.category_id));

            // Selected Products count
            const selProdIds = Array.isArray(sec.product_ids) ? sec.product_ids.map(Number) : [];

            card.innerHTML = `
                <!-- Section Header -->
                <div class="p-4 sm:p-5 bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-orange-100 text-[#fc490f] font-black text-xs flex items-center justify-center shadow-xs">
                            #${index + 1}
                        </span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white section-title-display">
                                ${sec.title || 'ক্যাটাগরি সেকশন (Category Section)'}
                            </h3>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                <span>Category: <strong class="text-slate-700 dark:text-slate-300">${selectedCat ? selectedCat.name : 'Not selected'}</strong></span>
                                <span>•</span>
                                <span><strong class="text-emerald-600">${selProdIds.length}</strong> Products Selected</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">
                            <input type="checkbox" class="rounded text-[#fc490f] focus:ring-[#fc490f] sec-active-toggle" ${sec.is_active !== false ? 'checked' : ''} onchange="updateSectionField(${index}, 'is_active', this.checked)">
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
                        
                        <!-- Badge & Category (Left 4 cols) -->
                        <div class="md:col-span-4 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Section Badge (বাংলা বা English)
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.badge || '🔥 আজকের স্পেশাল অফার')}" 
                                    oninput="updateSectionField(${index}, 'badge', this.value)"
                                    placeholder="e.g. 🔥 আজকের স্পেশাল অফার"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]/20 focus:border-[#fc490f]"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Target Category
                                </label>
                                <select 
                                    onchange="handleCategoryChange(${index}, this.value)"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]/20 focus:border-[#fc490f]"
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
                                    View All Button Label (বাংলা বা English)
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.view_all_label || 'সকল পণ্য দেখুন (View All)')}" 
                                    oninput="updateSectionField(${index}, 'view_all_label', this.value)"
                                    placeholder="e.g. সকল মাছের কালেকশন দেখুন"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]/20 focus:border-[#fc490f]"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    View All Button Link / Action
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.view_all_link || '#featured-products')}" 
                                    oninput="updateSectionField(${index}, 'view_all_link', this.value)"
                                    placeholder="e.g. #featured-products or /categories"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]/20 focus:border-[#fc490f]"
                                >
                            </div>
                        </div>

                        <!-- Title & Subtitle (Middle 8 cols) -->
                        <div class="md:col-span-8 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Section Title (বাংলায় হেডলাইন - Full Bangla Support) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    value="${escapeHtml(sec.title || '')}" 
                                    oninput="updateSectionField(${index}, 'title', this.value)"
                                    placeholder="e.g. তাজা পদ্মার ইলিশ ও মাছের স্পেশাল কালেকশন"
                                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]/20 focus:border-[#fc490f]"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Section Subtitle / Description (বাংলা বা English)
                                </label>
                                <textarea 
                                    rows="2"
                                    oninput="updateSectionField(${index}, 'subtitle', this.value)"
                                    placeholder="e.g. ১ কেজি+ সাইজের স্পেশাল ইলিশ ও তাজা মাছ—সরাসরি নদী থেকে আপনার ঘরে।"
                                    class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-[#fc490f]/20 focus:border-[#fc490f]"
                                >${escapeHtml(sec.subtitle || '')}</textarea>
                            </div>

                            <!-- Product Selection Box -->
                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Select Exactly 4 Featured Products (পণ্য নির্বাচন করুন):
                                    </label>
                                    <span class="text-[11px] font-bold ${selProdIds.length === 4 ? 'text-emerald-600' : (selProdIds.length > 4 ? 'text-amber-600' : 'text-slate-500')}">
                                        ${selProdIds.length} of 4 selected ${selProdIds.length === 4 ? '✓ Perfect' : ''}
                                    </span>
                                </div>

                                <!-- Filtered Products Grid -->
                                <div class="max-h-60 overflow-y-auto p-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    ${renderProductCheckboxes(index, sec.category_id, selProdIds)}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            `;

            container.appendChild(card);
        });
    }

    // Helper: render product checkboxes for a section
    function renderProductCheckboxes(sectionIndex, categoryId, selectedIds) {
        // Show matching products first, or all active products
        let relevantProducts = ALL_PRODUCTS;
        if (categoryId) {
            const cat = ALL_CATEGORIES.find(c => String(c.id) === String(categoryId));
            if (cat) {
                relevantProducts = ALL_PRODUCTS.filter(p => 
                    String(p.category_id) === String(categoryId) || 
                    (p.sub_category && p.sub_category.toLowerCase() === cat.name.toLowerCase())
                );
                // If fewer than 4 found in exact category, append others
                if (relevantProducts.length < 4) {
                    const others = ALL_PRODUCTS.filter(p => !relevantProducts.some(rp => rp.id === p.id));
                    relevantProducts = [...relevantProducts, ...others];
                }
            }
        }

        if (relevantProducts.length === 0) {
            return `<div class="col-span-2 text-center p-4 text-xs text-slate-400">No products found in this category.</div>`;
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

    // Toggle product in section
    function toggleProductSelection(sectionIndex, productId, isChecked) {
        productId = Number(productId);
        let ids = SECTIONS_DATA[sectionIndex].product_ids || [];
        ids = ids.map(Number);

        if (isChecked) {
            if (!ids.includes(productId)) {
                ids.push(productId);
            }
        } else {
            ids = ids.filter(id => id !== productId);
        }

        SECTIONS_DATA[sectionIndex].product_ids = ids;
        renderSections();
    }

    // Update section field
    function updateSectionField(index, field, value) {
        if (!SECTIONS_DATA[index]) return;
        SECTIONS_DATA[index][field] = value;
        if (field === 'title') {
            const display = document.querySelector(`.section-card[data-section-index="${index}"] .section-title-display`);
            if (display) display.textContent = value || 'ক্যাটাগরি সেকশন (Category Section)';
        }
    }

    // Handle Category change
    function handleCategoryChange(index, newCatId) {
        SECTIONS_DATA[index].category_id = newCatId ? Number(newCatId) : '';
        
        // Auto-populate first 4 products of that category if currently empty
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

    // Move Section up or down
    function moveSection(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= SECTIONS_DATA.length) return;
        const temp = SECTIONS_DATA[index];
        SECTIONS_DATA[index] = SECTIONS_DATA[target];
        SECTIONS_DATA[target] = temp;
        renderSections();
    }

    // Delete section
    function deleteSection(index) {
        Swal.fire({
            title: 'Delete this category section?',
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
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Section removed (Click Save to persist)',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }

    // Add Section button
    document.getElementById('btn-add-section').addEventListener('click', () => {
        const defaultCat = ALL_CATEGORIES[0] || null;
        const autoProds = defaultCat 
            ? ALL_PRODUCTS.filter(p => String(p.category_id) === String(defaultCat.id)).slice(0, 4).map(p => Number(p.id))
            : ALL_PRODUCTS.slice(0, 4).map(p => Number(p.id));

        SECTIONS_DATA.push({
            id: 'sec_' + Date.now(),
            badge: '🔥 আজকের স্পেশাল কালেকশন',
            title: defaultCat ? `${defaultCat.name} স্পেশাল কালেকশন` : 'নতুন ক্যাটাগরি সেকশন',
            subtitle: 'তাজা ও হাইজেনিক সরাসরি আপনার দরজায় পৌঁছে যাবে।',
            category_id: defaultCat ? defaultCat.id : '',
            product_ids: autoProds,
            view_all_label: 'সকল পণ্য দেখুন (View All)',
            view_all_link: '#featured-products',
            is_active: true
        });

        renderSections();

        // Scroll to the newly created section
        setTimeout(() => {
            const cards = document.querySelectorAll('.section-card');
            if (cards.length > 0) {
                cards[cards.length - 1].scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    });

    // Save All Sections via AJAX
    document.getElementById('btn-save-all').addEventListener('click', function() {
        const btn = this;
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        $.ajax({
            url: '{{ url("/admin/facebook-ad/save") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                sections: SECTIONS_DATA
            },
            success: function(res) {
                btn.disabled = false;
                btn.innerHTML = origText;

                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: 'Facebook Ad Page sections have been updated and are live on the Onepager.',
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

    // Helper: HTML escape
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Initial render on load
    document.addEventListener('DOMContentLoaded', renderSections);
</script>
@endsection
