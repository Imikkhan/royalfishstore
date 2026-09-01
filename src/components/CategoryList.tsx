import React from 'react';
import { useApp } from '../context/AppContext';
import { SkeletonCategories } from './SkeletonLoader';
import { LayoutGrid } from 'lucide-react';

// Top Horizontal Category Filter Tabs Bar (Main Categories)
export const TopCategoryTabs: React.FC = () => {
  const { currentPage, selectedCategory, setSelectedCategory, setSelectedSubCategory, navigateTo, categories } = useApp();

  const handleCategoryClick = (catId: string | null) => {
    if (catId === 'all') {
      setSelectedCategory(null);
      setSelectedSubCategory(null);
      navigateTo('categories');
    } else {
      setSelectedCategory(catId);
      setSelectedSubCategory(null);
      navigateTo('category-view');
    }
  };

  // Dynamically filter Main Categories (parent_id === null or undefined or 0)
  const mainCategories = categories.filter(cat => !cat.parent_id || cat.parent_id === null || cat.parent_id === 0);

  const getCategoryIcon = (cat: any) => {
    if (cat.icon) return cat.icon;
    const slug = cat.slug || '';
    if (slug.includes('chicken')) return '🍗';
    if (slug.includes('mutton')) return '🥩';
    if (slug.includes('marinade')) return '🍢';
    if (slug.includes('cold') || slug.includes('salami')) return '🥓';
    if (slug.includes('combo')) return '🎁';
    return '🐟';
  };

  const tabs = [
    { id: 'all', name: 'All', icon: <LayoutGrid className="w-4 h-4 sm:w-5 sm:h-5 text-[#fc490f]" /> },
    ...mainCategories.map(cat => ({
      id: cat.slug || cat.id,
      name: cat.name.replace(' & Seafood', '').replace('Fresh ', '').replace('Rich ', ''),
      fullName: cat.name,
      icon: getCategoryIcon(cat)
    }))
  ];

  return (
    <div className="w-full bg-white py-2 px-2 sm:px-4 border-none shadow-none select-none">
      <div className="flex items-center justify-between sm:justify-start gap-3 sm:gap-6 overflow-x-auto pb-1 scrollbar-none snap-x">
        {tabs.map(tab => {
          const isSelected = tab.id === 'all' ? (currentPage === 'categories' || (!selectedCategory && currentPage === 'home')) : selectedCategory === tab.id;
          return (
            <button
              key={tab.id}
              onClick={() => handleCategoryClick(tab.id)}
              className="flex flex-col items-center justify-center shrink-0 min-w-[50px] sm:min-w-[60px] snap-start group focus:outline-none cursor-pointer"
              id={`top-cat-tab-${tab.id}`}
            >
              <div className={`w-9 h-9 sm:w-11 sm:h-11 rounded-full flex items-center justify-center text-lg transition-all overflow-hidden ${isSelected ? 'bg-orange-100/80 scale-105' : 'bg-gray-50 hover:bg-orange-50'
                }`}>
                {typeof tab.icon === 'string' && (tab.icon.startsWith('http') || tab.icon.startsWith('/')) ? (
                  <img src={tab.icon} alt={tab.name} className="w-full h-full object-cover rounded-full" />
                ) : (
                  tab.icon
                )}
              </div>
              <span className={`text-[11px] sm:text-xs font-bold mt-1 ${isSelected ? 'text-[#fc490f]' : 'text-gray-700'}`}>
                {tab.name}
              </span>
              {isSelected && (
                <div className="w-6 h-0.5 bg-[#fc490f] mt-1 rounded-full animate-fadeIn" />
              )}
            </button>
          );
        })}
      </div>
    </div>
  );
};

// Shop by Category Circular Grid Component (Sub Categories)
export const CategoryList: React.FC = () => {
  const { selectedSubCategory, setSelectedCategory, setSelectedSubCategory, navigateTo, categories, isLoadingCategories } = useApp();

  // Dynamically filter Sub Categories (parent_id is set)
  const subCategories = categories.filter(cat => cat.parent_id !== null && cat.parent_id !== undefined && cat.parent_id !== 0);

  const handleSubCategoryClick = (subCat: any) => {
    // Find parent category to set selectedCategory properly
    const parentCat = categories.find(p => p.id === subCat.parent_id || p.slug === subCat.parent_id || (subCat.parent_slug && (p.slug === subCat.parent_slug || p.id === subCat.parent_slug)));
    const parentSlug = parentCat ? (parentCat.slug || parentCat.id) : (subCat.parent_slug || (typeof subCat.parent_id === 'string' ? subCat.parent_id : 'fish-seafood'));

    setSelectedCategory(parentSlug);
    setSelectedSubCategory(subCat.name);
    navigateTo('category-view');
  };

  return (
    <div className="w-full space-y-2.5 select-none">
      {/* Header */}
      <div className="flex items-center justify-between">
        <h3 className="font-sans font-extrabold text-gray-900 text-base sm:text-lg">
          Shop by Category
        </h3>

        <button
          onClick={() => navigateTo('categories')}
          className="text-xs font-bold text-[#fc490f] hover:underline shrink-0 cursor-pointer"
        >
          View All
        </button>
      </div>

      {isLoadingCategories ? (
        <SkeletonCategories />
      ) : subCategories.length === 0 ? (
        <div className="py-4 text-center text-xs text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
          No sub-categories available
        </div>
      ) : (
        <div className="grid grid-rows-3 grid-flow-col auto-cols-[calc((100vw-2rem)/4)] sm:flex sm:flex-row sm:flex-nowrap sm:auto-cols-auto gap-x-1 gap-y-2 overflow-x-auto pb-1.5 scrollbar-none snap-x snap-mandatory -mx-4 px-4">
          {subCategories.map(cat => {
            const isActive = selectedSubCategory === cat.name;
            const getSubCatImg = (item: any) => {
              if (item.image) return item.image;
              const name = (item.name || '').toLowerCase();
              const slug = (item.slug || '').toLowerCase();
              if (name.includes('chicken') || slug.includes('chicken')) return 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?auto=format&fit=crop&w=200&q=80';
              if (name.includes('mutton') || slug.includes('mutton') || name.includes('keema')) return 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=200&q=80';
              if (name.includes('prawn') || slug.includes('prawn')) return 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=200&q=80';
              if (name.includes('marinade') || slug.includes('marinade')) return 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=200&q=80';
              if (name.includes('salami') || name.includes('sausage') || slug.includes('salami')) return 'https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=200&q=80';
              if (name.includes('combo') || slug.includes('combo')) return 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=200&q=80';
              return 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=200&q=80';
            };
            return (
              <button
                key={cat.id || cat.slug}
                onClick={() => handleSubCategoryClick(cat)}
                className="flex flex-col items-center text-center shrink-0 w-full sm:w-[108px] snap-start group focus:outline-none cursor-pointer"
                id={`subcat-item-${cat.id}`}
              >
                <div
                  className={`w-[72px] h-[72px] sm:w-[108px] sm:h-[108px] rounded-full p-0.5 bg-white border border-gray-200/80 shadow-sm flex items-center justify-center overflow-hidden transition-transform duration-200 group-hover:scale-105 ${isActive ? 'ring-2 ring-[#fc490f] border-[#fc490f]' : ''
                    }`}
                >
                  <img
                    src={getSubCatImg(cat)}
                    alt={cat.name}
                    className="w-full h-full object-cover rounded-full"
                  />
                </div>

                <span className="text-[10px] sm:text-xs font-bold text-gray-800 leading-tight mt-1 whitespace-pre-line text-center line-clamp-2 max-w-[76px] sm:max-w-none">
                  {cat.name}
                </span>
              </button>
            );
          })}
        </div>
      )}

    </div>
  );
};
