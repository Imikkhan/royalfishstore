import React from 'react';
import { useApp } from '../context/AppContext';
import { SUBCATEGORIES } from '../data/products';
import { ProductCard } from '../components/ProductCard';
import { ChevronLeft, Sparkles, Filter, ShieldCheck, HelpCircle, ArrowUpDown } from 'lucide-react';

// Premium stock food images for category sub-filters to simulate Licious app experience perfectly
const SUBCATEGORY_IMAGES: Record<string, string> = {
  // Fish & Seafood
  'All-fish-seafood': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80',
  'Seawater Fish': 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=180&q=80',
  'Freshwater Fish': 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=180&q=80',
  'Prawns': 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=180&q=80',
  'Exotic Catch': 'https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?auto=format&fit=crop&w=180&q=80',

  // Chicken
  'All-chicken': 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=180&q=80',
  'Curry Cuts': 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?auto=format&fit=crop&w=180&q=80',
  'Boneless & Mince': 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=180&q=80',

  // Mutton
  'All-mutton': 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=180&q=80',
  'Keema & Minced': 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=180&q=80',

  // Marinades
  'All-marinades': 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=180&q=80',
  'Chicken Marinades': 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=180&q=80',
  'Fish Marinades': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80',

  // Cold Cuts
  'All-cold-cuts': 'https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=180&q=80',
  'Salami & Sausages': 'https://images.unsplash.com/photo-1541048619-2c7094f117a6?auto=format&fit=crop&w=180&q=80',

  // Combos
  'All-combos': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80',
  'Combo Packs': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=180&q=80'
};

const CATEGORY_SLOGANS: Record<string, string> = {
  'fish-seafood': 'Daily fresh seawater & freshwater catch. 100% chemical free.',
  'chicken': 'No growth hormones or steroids. Tender farm-fresh chicken.',
  'mutton': 'Ideal blend of meat & fat for rich taste. Tender pasture-raised goat.',
  'marinades': 'Ready-to-cook pre-marinated gourmet meat and fish platters.',
  'cold-cuts': 'Premium spiced smoked salamis, sausages, and breakfast meats.',
  'combos': 'Butcher-curated value bundles. Extra savings on daily cuts.'
};

export const CategoryView: React.FC = () => {
  const { 
    selectedCategory, 
    setSelectedCategory,
    selectedSubCategory, 
    setSelectedSubCategory, 
    navigateTo,
    searchQuery,
    categories,
    products
  } = useApp();

  // Redirect to home if no category is active
  if (!selectedCategory) {
    setTimeout(() => navigateTo('home'), 0);
    return null;
  }

  const categoryDetails = categories.find(c => (c.slug || c.id) === selectedCategory);
  const categoryName = categoryDetails ? categoryDetails.name : 'Premium Meats';
  const slogan = CATEGORY_SLOGANS[selectedCategory] || 'Sourced fresh daily and traceably delivered.';

  // Filter products based on selected category, subcategory and search query
  const filteredProducts = products.filter(product => {
    const prodCatSlug = typeof product.category === 'object' && product.category !== null 
      ? (product.category as any).slug || (product.category as any).name || ''
      : String(product.category || '');
      
    const matchesCategory = prodCatSlug === selectedCategory || 
      prodCatSlug.toLowerCase().replace(/[^a-z0-9]/g, '') === selectedCategory.toLowerCase().replace(/[^a-z0-9]/g, '');
    
    const subCat = product.subCategory || product.sub_category;
    const matchesSubCategory = selectedSubCategory && selectedSubCategory !== 'All'
      ? subCat === selectedSubCategory
      : true;

    const matchesSearch = searchQuery
      ? (product.name || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
        (product.description || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
        (subCat && subCat.toLowerCase().includes(searchQuery.toLowerCase()))
      : true;

    return matchesCategory && matchesSubCategory && matchesSearch;
  });

  const subcats = SUBCATEGORIES[selectedCategory] || ['All'];

  const getSubcategoryImage = (sub: string) => {
    if (sub === 'All') {
      return SUBCATEGORY_IMAGES[`All-${selectedCategory}`] || SUBCATEGORY_IMAGES['All-chicken'];
    }
    // Fallback search
    return SUBCATEGORY_IMAGES[sub] || SUBCATEGORY_IMAGES[`All-${selectedCategory}`] || 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=150&q=80';
  };

  return (
    <div className="space-y-6 pb-12 animate-fadeIn" id="category-view-page">
      
      {/* 1. Header Navigation & Breadcrumbs */}
      <div className="space-y-2">
        <div className="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 select-none">
          <button 
            onClick={() => {
              setSelectedCategory(null);
              setSelectedSubCategory(null);
              navigateTo('home');
            }} 
            className="hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium"
          >
            Home
          </button>
          <span>/</span>
          <span className="font-bold text-red-600 dark:text-red-400 capitalize">
            {selectedCategory.replace('-', ' & ')}
          </span>
        </div>

        <div className="flex items-center gap-3">
          <button
            onClick={() => {
              setSelectedCategory(null);
              setSelectedSubCategory(null);
              navigateTo('home');
            }}
            className="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-300 transition-colors"
            aria-label="Back to Home"
          >
            <ChevronLeft className="w-6 h-6" />
          </button>
          <div>
            <h1 className="font-sans font-black text-2xl sm:text-3xl text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
              <span>{categoryName}</span>
              <span className="text-xl sm:text-2xl">{categoryDetails?.icon}</span>
            </h1>
          </div>
        </div>
      </div>

      {/* 2. Stylish Premium Promotion Banner (Licious Red/Pink Tinted Banner) */}
      <div className="relative overflow-hidden rounded-2xl bg-gradient-to-r from-rose-500/10 via-pink-500/5 to-rose-600/5 dark:from-red-950/20 dark:to-slate-900 border border-red-100/40 dark:border-slate-800/80 p-5 sm:p-6 flex items-center justify-between select-none">
        <div className="space-y-1.5 max-w-lg">
          <div className="inline-flex items-center gap-1 bg-red-100 dark:bg-red-950/50 text-red-700 dark:text-red-400 text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full">
            <Sparkles className="w-3 h-3 animate-pulse" /> Certified Fresh
          </div>
          <h2 className="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight">
            {slogan}
          </h2>
          <p className="text-xs text-gray-500 dark:text-gray-400 font-medium">
            Strict 150-point quality check, double-chilled transit, 100% fresh meat.
          </p>
        </div>
        <div className="hidden md:block">
          <img 
            src="https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=150&q=80"
            alt="Sealed Premium Meat Pack"
            className="w-20 h-20 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-md transform rotate-6"
            referrerPolicy="no-referrer"
          />
        </div>
      </div>

      {/* 3. Subcategories Circular Row (Exact Licious Design!) */}
      <div className="space-y-3.5 py-2">
        <div className="flex items-center justify-between px-1">
          <span className="text-[11px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
            Select Your Preferred Cut Style
          </span>
          {selectedSubCategory && selectedSubCategory !== 'All' && (
            <button 
              onClick={() => setSelectedSubCategory(null)}
              className="text-xs font-bold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
            >
              Show All cuts
            </button>
          )}
        </div>

        {/* Circular Subcategories Grid / List */}
        <div className="flex gap-4 sm:gap-6 overflow-x-auto pb-2 scrollbar-none -mx-4 px-4 sm:mx-0 sm:px-0">
          {subcats.map(sub => {
            const isActive = (selectedSubCategory === sub) || (!selectedSubCategory && sub === 'All');
            const imgUrl = getSubcategoryImage(sub);
            
            return (
              <button
                key={sub}
                onClick={() => setSelectedSubCategory(sub === 'All' ? null : sub)}
                className="flex flex-col items-center gap-2 group focus:outline-none shrink-0 w-[76px] sm:w-[90px]"
                id={`subcat-${sub.replace(/\s+/g, '-').toLowerCase()}`}
              >
                {/* Circular image with active ring */}
                <div 
                  className={`w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-2 transition-all duration-300 relative shadow-sm ${
                    isActive 
                      ? 'border-red-600 ring-4 ring-red-100 dark:ring-red-950/30 scale-105' 
                      : 'border-gray-100 dark:border-slate-800 group-hover:border-red-300 group-hover:scale-105'
                  }`}
                >
                  <img 
                    src={imgUrl} 
                    alt={sub} 
                    referrerPolicy="no-referrer"
                    className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                  />
                  {isActive && (
                    <div className="absolute inset-0 bg-red-600/10 dark:bg-red-500/10 mix-blend-multiply" />
                  )}
                </div>

                {/* Subcategory Label */}
                <span className={`text-[11px] sm:text-xs font-bold text-center leading-snug tracking-tight break-words max-w-full truncate-2-lines transition-colors ${
                  isActive 
                    ? 'text-red-600 dark:text-red-400 font-extrabold' 
                    : 'text-gray-700 dark:text-gray-300 group-hover:text-red-600 dark:group-hover:text-red-400'
                }`}>
                  {sub}
                </span>
              </button>
            );
          })}
        </div>
      </div>

      {/* 4. Controls & Filters Indicator bar */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-b border-gray-100 dark:border-slate-800/60 py-3 select-none">
        <div className="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
          <span className="font-extrabold text-gray-800 dark:text-white">{filteredProducts.length} Items</span>
          <span>available in this category</span>
        </div>

        <div className="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
          <button className="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-slate-800/50 dark:hover:bg-slate-800 text-[11px] font-bold text-gray-700 dark:text-gray-300 border border-gray-150 dark:border-slate-800 transition-all">
            <Filter className="w-3 h-3" />
            <span>Filters</span>
          </button>
          
          <button className="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-slate-800/50 dark:hover:bg-slate-800 text-[11px] font-bold text-gray-700 dark:text-gray-300 border border-gray-150 dark:border-slate-800 transition-all">
            <ArrowUpDown className="w-3 h-3" />
            <span>Price: Low to High</span>
          </button>

          <div className="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 border border-emerald-100/40 dark:border-emerald-950/30">
            <span>⚡ Express delivery (45m)</span>
          </div>
        </div>
      </div>

      {/* 5. Products List Grid */}
      <div className="space-y-6">
        {filteredProducts.length === 0 ? (
          <div className="text-center py-16 space-y-4 rounded-3xl bg-gray-50 dark:bg-slate-900 border border-dashed border-gray-200 dark:border-slate-800">
            <span className="text-5xl block">🥩</span>
            <div className="max-w-md mx-auto space-y-2 px-4">
              <h3 className="font-sans font-bold text-gray-900 dark:text-white text-lg">
                No cuts found in this filter
              </h3>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                We don't currently have products matching the subcategory &ldquo;{selectedSubCategory}&rdquo;. Try browsing other cuts or check our standard selections!
              </p>
              <button
                onClick={() => setSelectedSubCategory(null)}
                className="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-md"
              >
                Show All Cuts
              </button>
            </div>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredProducts.map(product => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </div>

      {/* 6. Premium Trust Badging */}
      <div className="pt-8 mt-4 border-t border-gray-100 dark:border-slate-800/60 grid grid-cols-1 sm:grid-cols-3 gap-4 select-none">
        <div className="bg-gray-50/50 dark:bg-slate-800/10 p-3.5 rounded-xl border border-gray-100/40 dark:border-slate-800 flex items-start gap-2.5">
          <ShieldCheck className="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" />
          <div className="space-y-0.5">
            <h4 className="text-xs font-bold text-gray-900 dark:text-white">Freshness Guaranteed</h4>
            <p className="text-[10px] text-gray-400 dark:text-gray-500">Double-chilled transit under 4°C to lock in the flavor.</p>
          </div>
        </div>

        <div className="bg-gray-50/50 dark:bg-slate-800/10 p-3.5 rounded-xl border border-gray-100/40 dark:border-slate-800 flex items-start gap-2.5">
          <Sparkles className="w-5 h-5 text-red-500 shrink-0 mt-0.5" />
          <div className="space-y-0.5">
            <h4 className="text-xs font-bold text-gray-900 dark:text-white">Expert Butcher Cuts</h4>
            <p className="text-[10px] text-gray-400 dark:text-gray-500">Perfect cut consistency with zero waste bone portions.</p>
          </div>
        </div>

        <div className="bg-gray-50/50 dark:bg-slate-800/10 p-3.5 rounded-xl border border-gray-100/40 dark:border-slate-800 flex items-start gap-2.5">
          <HelpCircle className="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
          <div className="space-y-0.5">
            <h4 className="text-xs font-bold text-gray-900 dark:text-white">Why cold-chain?</h4>
            <p className="text-[10px] text-gray-400 dark:text-gray-500">We never freeze meat. Cold-chain keeps natural juices intact.</p>
          </div>
        </div>
      </div>

    </div>
  );
};
