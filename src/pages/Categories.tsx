import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { SUBCATEGORIES } from '../data/products';
import { Search, ChevronRight, Sparkles, ShieldCheck, Flame, Layers } from 'lucide-react';

const CATEGORY_IMAGES: Record<string, string> = {
  'fish-seafood': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
  'chicken': 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=600&q=80',
  'mutton': 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=600&q=80',
  'marinades': 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=600&q=80',
  'cold-cuts': 'https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=600&q=80',
  'combos': 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=600&q=80'
};

const CATEGORY_TAGLINES: Record<string, string> = {
  'fish-seafood': 'Fresh seawater & freshwater catch daily. 100% chemical free.',
  'chicken': 'Farm-fresh, tender cuts without growth hormones or antibiotics.',
  'mutton': 'Pasture-raised, rich-flavored tender goat meat.',
  'marinades': 'Ready-to-cook gourmet platters marinated in rich spices.',
  'cold-cuts': 'Premium smoked salamis, sausages & breakfast meats.',
  'combos': 'Curated value packs with extra savings on fresh meats.'
};

export const Categories: React.FC = () => {
  const { categories, setSelectedCategory, setSelectedSubCategory, navigateTo, products } = useApp();
  const [filterQuery, setFilterQuery] = useState('');

  const handleSelectCategory = (catId: string, subCat?: string) => {
    setSelectedCategory(catId);
    setSelectedSubCategory(subCat || null);
    navigateTo('category-view');
  };

  const filteredCategories = categories.filter(cat => {
    const name = cat.name || '';
    const desc = cat.description || '';
    const query = filterQuery.toLowerCase();
    return name.toLowerCase().includes(query) || desc.toLowerCase().includes(query);
  });

  return (
    <div className="space-y-6 pb-12 animate-fadeIn" id="categories-page">
      
      {/* 1. Header Title & Subtitle */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2">
            <span className="p-2 bg-red-100 dark:bg-red-950/50 text-red-600 dark:text-red-400 rounded-xl">
              <Layers className="w-5 h-5" />
            </span>
            <h1 className="font-sans font-black text-2xl sm:text-3xl text-gray-900 dark:text-white tracking-tight">
              All Categories
            </h1>
          </div>
          <p className="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
            Explore fresh seafood, tender chicken, rich mutton & ready-to-cook platters
          </p>
        </div>

        {/* Category search bar */}
        <div className="relative w-full sm:w-72">
          <Search className="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            placeholder="Search category..."
            value={filterQuery}
            onChange={(e) => setFilterQuery(e.target.value)}
            className="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all text-gray-800 dark:text-white"
          />
          {filterQuery && (
            <button
              onClick={() => setFilterQuery('')}
              className="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
            >
              ✕
            </button>
          )}
        </div>
      </div>

      {/* 2. Top Banner / Quality Callout */}
      <div className="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-amber-600 text-white p-5 sm:p-6 shadow-md select-none">
        <div className="relative z-10 max-w-xl space-y-2">
          <div className="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md text-white text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full border border-white/20">
            <Sparkles className="w-3 h-3 text-amber-300" /> 100% Cold-Chain Preserved (0-4°C)
          </div>
          <h2 className="text-lg sm:text-xl font-black tracking-tight leading-snug">
            Freshness Guaranteed from Shore & Farm to Doorstep
          </h2>
          <p className="text-xs text-white/90 font-medium">
            Every category is rigorously selected, 150-point quality verified, vacuum packed, and delivered express in 45 minutes.
          </p>
        </div>
        <div className="absolute right-[-20px] bottom-[-20px] opacity-20 pointer-events-none text-9xl font-black text-white select-none">
          🥩
        </div>
      </div>

      {/* 3. Categories Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredCategories.map((cat) => {
          const catId = cat.slug || cat.id;
          const imgUrl = cat.image || CATEGORY_IMAGES[catId] || 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80';
          const tagline = CATEGORY_TAGLINES[catId] || cat.description || 'Fresh & high quality meat';
          const subcats = (SUBCATEGORIES[catId] || []).filter(s => s !== 'All');
          const count = products.filter(p => {
            const pCat = typeof p.category === 'object' && p.category !== null 
              ? (p.category as any).slug || (p.category as any).id || (p.category as any).name || ''
              : String(p.category || '');
            return pCat === catId || String(pCat) === String(catId);
          }).length;

          return (
            <div
              key={cat.id}
              className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group"
            >
              {/* Category Header Image */}
              <div 
                className="relative h-44 w-full overflow-hidden cursor-pointer"
                onClick={() => handleSelectCategory(catId)}
              >
                <img
                  src={imgUrl}
                  alt={cat.name}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  referrerPolicy="no-referrer"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent" />
                
                {/* Category Icon Badge */}
                <div className="absolute top-3 left-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-gray-900 dark:text-white shadow-sm flex items-center gap-1.5 border border-white/20">
                  <span className="text-base">{cat.icon || '🍖'}</span>
                  <span>{cat.name}</span>
                </div>

                {/* Items count badge */}
                <div className="absolute top-3 right-3 bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-sm">
                  {count > 0 ? `${count} Items` : 'Fresh Cut'}
                </div>

                {/* Title & Tagline overlay */}
                <div className="absolute bottom-3 left-3 right-3 text-white">
                  <h3 className="font-sans font-black text-lg sm:text-xl tracking-tight leading-tight flex items-center justify-between">
                    <span>{cat.name}</span>
                    <ChevronRight className="w-5 h-5 group-hover:translate-x-1 transition-transform text-white/80" />
                  </h3>
                  <p className="text-[11px] text-gray-200 font-medium line-clamp-1 mt-0.5 opacity-90">
                    {tagline}
                  </p>
                </div>
              </div>

              {/* Subcategories Chips & Action */}
              <div className="p-4 space-y-3 bg-white dark:bg-slate-900">
                {subcats.length > 0 && (
                  <div>
                    <span className="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1.5">
                      Popular Cut Styles:
                    </span>
                    <div className="flex flex-wrap gap-1.5">
                      {subcats.map(sub => (
                        <button
                          key={sub}
                          onClick={() => handleSelectCategory(catId, sub)}
                          className="bg-gray-50 hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-gray-100 dark:border-slate-800 transition-colors"
                        >
                          {sub}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Primary CTA button */}
                <button
                  onClick={() => handleSelectCategory(catId)}
                  className="w-full py-2.5 bg-red-50 dark:bg-red-950/30 hover:bg-red-600 dark:hover:bg-red-600 text-red-600 dark:text-red-400 hover:text-white font-bold text-xs rounded-xl transition-all flex items-center justify-center gap-2 border border-red-200/50 dark:border-red-900/30 group-hover:bg-red-600 group-hover:text-white"
                >
                  <span>Explore {cat.name}</span>
                  <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            </div>
          );
        })}
      </div>

      {filteredCategories.length === 0 && (
        <div className="text-center py-16 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 space-y-3">
          <span className="text-4xl block">🔍</span>
          <h3 className="font-sans font-bold text-gray-800 dark:text-gray-200">No categories found</h3>
          <p className="text-xs text-gray-500 dark:text-gray-400">
            No matching category found for &ldquo;{filterQuery}&rdquo;. Try another search term.
          </p>
          <button
            onClick={() => setFilterQuery('')}
            className="bg-red-600 text-white font-bold text-xs px-4 py-2 rounded-xl"
          >
            Clear Search
          </button>
        </div>
      )}

      {/* 4. Bottom Trust Badges */}
      <div className="pt-6 border-t border-gray-100 dark:border-slate-800/80 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div className="p-3.5 bg-gray-50/70 dark:bg-slate-800/20 rounded-xl border border-gray-100 dark:border-slate-800 flex items-center gap-3">
          <ShieldCheck className="w-6 h-6 text-emerald-500 shrink-0" />
          <div>
            <h4 className="text-xs font-bold text-gray-900 dark:text-white">100% ISO Certified Packing</h4>
            <p className="text-[10px] text-gray-500 dark:text-gray-400">Vacuum sealed & chilled under 4°C</p>
          </div>
        </div>

        <div className="p-3.5 bg-gray-50/70 dark:bg-slate-800/20 rounded-xl border border-gray-100 dark:border-slate-800 flex items-center gap-3">
          <Flame className="w-6 h-6 text-red-500 shrink-0" />
          <div>
            <h4 className="text-xs font-bold text-gray-900 dark:text-white">Antibiotic & Hormone Free</h4>
            <p className="text-[10px] text-gray-500 dark:text-gray-400">Pure natural feeding, healthy livestock</p>
          </div>
        </div>

        <div className="p-3.5 bg-gray-50/70 dark:bg-slate-800/20 rounded-xl border border-gray-100 dark:border-slate-800 flex items-center gap-3 sm:col-span-2 lg:col-span-1">
          <Sparkles className="w-6 h-6 text-amber-500 shrink-0" />
          <div>
            <h4 className="text-xs font-bold text-gray-900 dark:text-white">45-Min Express Home Delivery</h4>
            <p className="text-[10px] text-gray-500 dark:text-gray-400">Insulated thermal dispatch bags</p>
          </div>
        </div>
      </div>

    </div>
  );
};
