import React from 'react';
import { useApp } from '../context/AppContext';
import { SkeletonCategories } from './SkeletonLoader';

export const CategoryList: React.FC = () => {
  const { selectedCategory, setSelectedCategory, navigateTo, categories, isLoadingCategories } = useApp();

  const handleCategoryClick = (catId: string) => {
    setSelectedCategory(catId);
    navigateTo('category-view');
  };

  return (
    <div className="w-full space-y-4">
      <div className="flex items-center justify-between">
        <div>
          <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-base sm:text-lg leading-tight">
            What's cooking today?
          </h3>
          <p className="text-xs text-gray-500 dark:text-gray-400">
            Select a category to explore fresh, hand-cut options
          </p>
        </div>
        
        {selectedCategory && (
          <button
            onClick={() => setSelectedCategory(null)}
            className="text-xs font-bold text-red-600 dark:text-red-400 hover:underline"
            id="btn-clear-category"
          >
            Clear Filter
          </button>
        )}
      </div>

      {isLoadingCategories ? (
        <SkeletonCategories />
      ) : (
        /* Horizontal scroll container for categories on mobile, wrapping grid on desktop */
        <div className="flex flex-row flex-nowrap items-start gap-5 overflow-x-auto pb-3 scrollbar-none -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-6 sm:overflow-x-visible snap-x snap-mandatory">
        {categories.map(cat => {
          const catId = cat.slug || cat.id;
          const isActive = selectedCategory === catId;
          return (
            <button
              key={cat.id}
              onClick={() => handleCategoryClick(catId)}
              className="flex flex-col items-center gap-2 text-center shrink-0 w-[84px] sm:w-auto snap-start group focus:outline-none focus:ring-0"
              id={`cat-${cat.id}`}
            >
              {/* Circular bubble */}
              <div 
                className={`w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center text-2xl sm:text-3xl shadow-sm border transition-all duration-200 overflow-hidden ${
                  isActive 
                    ? 'bg-red-50 dark:bg-red-950/40 border-red-500 scale-105 shadow-md ring-4 ring-red-100 dark:ring-red-950/20' 
                    : 'bg-gray-50 dark:bg-slate-800 border-gray-100 dark:border-slate-700 hover:border-red-200 hover:bg-white dark:hover:bg-slate-700 group-hover:scale-105'
                }`}
              >
                {cat.image ? (
                  <img src={cat.image} alt={cat.name} className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                ) : (
                  <span className="transform group-hover:scale-110 transition-transform font-bold text-red-600 dark:text-red-400 text-lg uppercase select-none">{cat.name.charAt(0)}</span>
                )}
              </div>
              
              {/* Labels */}
              <div className="w-full flex flex-col items-center justify-start min-h-[3rem]">
                <span className={`text-[11px] sm:text-xs block font-bold leading-tight break-words text-center ${
                  isActive ? 'text-red-600 dark:text-red-400 font-extrabold' : 'text-gray-700 dark:text-gray-300'
                }`}>
                  {cat.name}
                </span>
                <span className="text-[9px] text-gray-400 dark:text-gray-500 block leading-tight text-center mt-0.5 break-words">
                  {cat.description}
                </span>
              </div>
            </button>
          );
        })}
      </div>
      )}
    </div>
  );
};
