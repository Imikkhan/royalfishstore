import React from 'react';
import { useApp } from '../context/AppContext';
import { ProductCard } from '../components/ProductCard';
import { SkeletonProductGrid } from '../components/SkeletonLoader';
import { Search as SearchIcon, ChevronLeft, Sparkles, Filter, X } from 'lucide-react';

const QUICK_TAGS = ['Rohu', 'Hilsa', 'Prawns', 'Chicken', 'Salmon', 'Surmai', 'Mutton', 'Crab'];

export const SearchPage: React.FC = () => {
  const {
    searchQuery,
    setSearchQuery,
    products,
    isLoadingProducts,
    navigateTo,
    goBack
  } = useApp();

  // Filter products based on searchQuery
  const filteredProducts = products.filter(product => {
    if (!searchQuery.trim()) return true;

    const query = searchQuery.toLowerCase().trim();
    const catName = typeof product.category === 'object' && product.category !== null
      ? (product.category as any).name || ''
      : String(product.category || '');

    return (
      (product.name || '').toLowerCase().includes(query) ||
      (product.description || '').toLowerCase().includes(query) ||
      (product.shortDescription || product.short_description || '').toLowerCase().includes(query) ||
      (product.subCategory || product.sub_category || '').toLowerCase().includes(query) ||
      catName.toLowerCase().includes(query)
    );
  });

  return (
    <div className="space-y-6 pb-20 animate-fadeIn select-none" id="search-page">
      
      {/* 1. Header Navigation Bar */}
      <div className="flex items-center justify-between gap-3">
        <button
          onClick={goBack}
          className="flex items-center gap-1.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:text-[#fc490f] transition-colors py-1.5 px-3 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 shadow-xs"
          id="btn-search-back"
        >
          <ChevronLeft className="w-4 h-4" />
          <span>Back</span>
        </button>

        <div className="text-right">
          <span className="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Search Results</span>
          <span className="text-xs font-black text-gray-900 dark:text-white">
            {filteredProducts.length} Items Found
          </span>
        </div>
      </div>

      {/* 2. Quick Tags Bar */}
      <div className="space-y-2">
        <div className="flex items-center justify-between">
          <span className="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1">
            <Sparkles className="w-3.5 h-3.5 text-[#fc490f]" />
            Popular Searches:
          </span>
          {searchQuery && (
            <button
              onClick={() => setSearchQuery('')}
              className="text-xs text-[#fc490f] font-bold hover:underline flex items-center gap-1"
            >
              Clear Search <X className="w-3 h-3" />
            </button>
          )}
        </div>

        <div className="flex flex-wrap gap-2">
          {QUICK_TAGS.map(tag => {
            const isSelected = searchQuery.toLowerCase().includes(tag.toLowerCase());
            return (
              <button
                key={tag}
                onClick={() => setSearchQuery(tag)}
                className={`text-xs font-bold px-3 py-1.5 rounded-full transition-all cursor-pointer border ${
                  isSelected
                    ? 'bg-[#fc490f] text-white border-[#fc490f] shadow-sm'
                    : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-slate-800 hover:border-[#fc490f]/50'
                }`}
              >
                {tag}
              </button>
            );
          })}
        </div>
      </div>

      {/* 3. Search Results Heading */}
      {searchQuery ? (
        <div className="bg-orange-50/60 dark:bg-slate-800/40 p-4 rounded-2xl border border-orange-100 dark:border-slate-800 flex items-center justify-between">
          <div>
            <h2 className="font-sans font-black text-base sm:text-lg text-gray-900 dark:text-white">
              Results for "<span className="text-[#fc490f]">{searchQuery}</span>"
            </h2>
            <p className="text-xs text-gray-500 font-medium mt-0.5">
              Showing freshly sourced items matching your query.
            </p>
          </div>
        </div>
      ) : (
        <div className="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">
          <h2 className="font-sans font-black text-base text-gray-900 dark:text-white">
            All Products Catalogue
          </h2>
          <p className="text-xs text-gray-500 font-medium mt-0.5">
            Type in the search bar above to filter by name, category, or cut.
          </p>
        </div>
      )}

      {/* 4. Products Grid or Skeletons or Empty State */}
      {isLoadingProducts ? (
        <SkeletonProductGrid count={6} />
      ) : filteredProducts.length === 0 ? (
        <div className="py-16 text-center space-y-4 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-xs p-6">
          <div className="w-16 h-16 bg-orange-50 dark:bg-slate-800 text-[#fc490f] rounded-full flex items-center justify-center mx-auto">
            <SearchIcon className="w-8 h-8" />
          </div>
          
          <div className="space-y-1">
            <h3 className="font-sans font-extrabold text-lg text-gray-900 dark:text-white">
              No products found matching "{searchQuery}"
            </h3>
            <p className="text-xs text-gray-500 max-w-sm mx-auto">
              We couldn't find any items matching your exact search. Try searching for "Rohu", "Prawns", "Chicken", or "Salmon".
            </p>
          </div>

          <button
            onClick={() => {
              setSearchQuery('');
              navigateTo('home');
            }}
            className="bg-[#fc490f] hover:bg-orange-600 text-white font-bold text-xs py-2.5 px-5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer"
          >
            Browse All Products
          </button>
        </div>
      ) : (
        <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
          {filteredProducts.map(product => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      )}

    </div>
  );
};
