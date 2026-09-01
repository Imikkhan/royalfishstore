import React from 'react';
import { useApp } from '../context/AppContext';
import { HeroSlider } from '../components/HeroSlider';
import { TopCategoryTabs, CategoryList } from '../components/CategoryList';
import { ProductCard } from '../components/ProductCard';
import { SkeletonProductGrid } from '../components/SkeletonLoader';
import { Sparkles, ArrowRight, Star, Play, Gift } from 'lucide-react';

const VIDEOS = [
  {
    id: 'v1',
    title: 'Daily Fresh Catch From Local Waters',
    duration: '0:45',
    thumbnail: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=400&q=80'
  },
  {
    id: 'v2',
    title: 'Prawns Cleaning & Packing',
    duration: '0:38',
    thumbnail: 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=400&q=80'
  }
];

const CUSTOMER_REVIEWS = [
  {
    id: 'r1',
    name: 'Moumita Sen',
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
    rating: 5,
    quote: 'Prawns were very fresh and big size. Will order again for sure.',
    productTag: 'Golda Prawns - 500g',
    timeAgo: '3 days ago'
  },
  {
    id: 'r2',
    name: 'Rajesh Kumar',
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
    rating: 5,
    quote: 'Rohu fish cut was super clean, no smell at all. Delivered in 30 mins.',
    productTag: 'Rohu (Rui) Fish - 1kg',
    timeAgo: '1 day ago'
  }
];

export const Home: React.FC = () => {
  const { searchQuery, navigateTo, products, isLoadingProducts, videos } = useApp();
  const [activeVideoModal, setActiveVideoModal] = React.useState<any | null>(null);

  const displayVideos = videos && videos.length > 0 ? videos : VIDEOS;

  // Filter products based on search query
  const filteredProducts = products.filter(product => {
    const catName = typeof product.category === 'object' && product.category !== null
      ? (product.category as any).name || ''
      : String(product.category || '');

    const matchesSearch = searchQuery
      ? (product.name || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      (product.description || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      catName.toLowerCase().includes(searchQuery.toLowerCase())
      : true;

    return matchesSearch;
  });

  const bestSellers = filteredProducts.filter(p => p.isBestSeller || p.rating >= 4.8);
  const hitsToShow = bestSellers.length > 0 ? bestSellers : filteredProducts;
  const hilsaSpecial = filteredProducts.filter(p => (p.name || '').toLowerCase().includes('hilsa') || (p.name || '').toLowerCase().includes('rohu'));
  const seafoodCategory = filteredProducts.filter(p => (
    p.category === 'fresh-fish' || 
    p.category === 'fish-seafood' || 
    p.category === 'seafood' || 
    p.category === 'wholesale-fish' ||
    (p.subCategory || '').toLowerCase().includes('fish') ||
    (p.subCategory || '').toLowerCase().includes('prawn')
  ));

  return (
    <div className="space-y-4 pb-16 animate-fadeIn select-none" id="home-page">

      {/* 1. Top Quick Filter Tabs Bar (Placed ABOVE Hero Slider, flush with header) */}
      <section className="-mx-4 -mt-6 md:mx-0 md:mt-0">
        <TopCategoryTabs />
      </section>

      {/* 2. Hero Offers Carousel Slider (Placed flush below TopCategoryTabs) */}
      <section className="-mx-4 sm:mx-0 -mt-4 sm:mt-0">
        <HeroSlider />
      </section>

      {/* 3. Shop by Category Circles (Directly on page background matching reference) */}
      <section className="px-0 -mt-1.5">
        <CategoryList />
      </section>

      {/* 4. Special Facebook Onepager Promotion Banner */}
      <section className="mt-2">
        <div 
          onClick={() => navigateTo('onepager')}
          className="cursor-pointer bg-gradient-to-r from-red-600 via-[#fc490f] to-amber-600 rounded-2xl p-3.5 sm:p-4 text-white shadow-md hover:shadow-lg transition-all flex items-center justify-between gap-3 group"
        >
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl shrink-0 backdrop-blur-xs group-hover:scale-110 transition-transform">
              🔥
            </div>
            <div>
              <div className="flex items-center gap-2">
                <span className="bg-amber-300 text-slate-950 text-[9px] font-black px-2 py-0.5 rounded-full uppercase">
                  Facebook Exclusive
                </span>
                <span className="text-[10px] text-amber-100 font-bold">Use Code: ROYAL20</span>
              </div>
              <h4 className="text-xs sm:text-sm font-extrabold text-white mt-0.5">
                Flat 20% OFF + 45-Min Express Delivery Onepager!
              </h4>
            </div>
          </div>
          <div className="flex items-center gap-1 text-xs font-black bg-white text-[#fc490f] px-3 py-1.5 rounded-xl shrink-0 group-hover:bg-amber-300 group-hover:text-slate-950 transition-colors shadow-xs">
            <span>Explore Onepager</span>
            <ArrowRight className="w-3.5 h-3.5" />
          </div>
        </div>
      </section>


      {/* 5. Filter Summary Indicator (if active) */}
      {searchQuery && (
        <div className="flex items-center gap-3 bg-orange-50 p-3 rounded-xl border border-orange-200 text-sm">
          <Sparkles className="w-4 h-4 text-[#fc490f]" />
          <span className="text-gray-700 dark:text-gray-300">
            Showing <strong className="text-[#fc490f] font-extrabold">{filteredProducts.length}</strong> fresh products matching &ldquo;<span className="italic font-bold">{searchQuery}</span>&rdquo;
          </span>
        </div>
      )}

      {/* 5. Our Current Hits Section */}
      <div id="products-section" className="space-y-6">
        {isLoadingProducts ? (
          <SkeletonProductGrid count={4} />
        ) : (
          <>
            {/* 6a. 🔥 Our Current Hits (Horizontally Scrollable - 1.5 Cards Visible on Mobile with clean left space) */}
            {hitsToShow.length > 0 && (
            <div className="space-y-3">
              <div className="flex items-center justify-between">
                <h3 className="font-sans font-extrabold text-gray-900 text-base sm:text-lg tracking-tight flex items-center gap-1.5">
                  <span>🔥 Our Current Hits</span>
                </h3>
                <button
                  onClick={() => navigateTo('categories')}
                  className="text-xs font-bold text-[#fc490f] hover:underline cursor-pointer"
                >
                  View All &rarr;
                </button>
              </div>

              {/* Flex horizontal scroll track sitting inside page margins */}
              <div className="flex flex-row items-stretch overflow-x-auto gap-3.5 pb-2.5 scrollbar-none snap-x snap-mandatory sm:grid sm:grid-cols-3 lg:grid-cols-4 sm:overflow-x-visible">
                {hitsToShow.slice(0, 6).map(product => (
                  <div key={product.id} className="w-[66vw] max-w-[270px] sm:w-auto shrink-0 snap-start h-full flex flex-col">
                    <ProductCard product={product} />
                  </div>
                ))}
              </div>
            </div>
            )}

            {/* 6b. Special Hilsa Fish Category Section */}
            {hilsaSpecial.length > 0 && (
            <div className="space-y-3 pt-1">
              <div className="flex items-center justify-between">
                <h3 className="font-sans font-extrabold text-gray-900 text-base sm:text-lg tracking-tight">
                  Special Hilsa Fish Category
                </h3>
                <button
                  onClick={() => navigateTo('categories')}
                  className="text-xs font-bold text-[#fc490f] hover:underline cursor-pointer"
                >
                  View All &rarr;
                </button>
              </div>

              <div className="flex flex-row items-stretch overflow-x-auto gap-3.5 pb-2.5 scrollbar-none snap-x snap-mandatory sm:grid sm:grid-cols-3 lg:grid-cols-4 sm:overflow-x-visible">
                {hilsaSpecial.slice(0, 6).map(product => (
                  <div key={product.id} className="w-[66vw] max-w-[270px] sm:w-auto shrink-0 snap-start h-full flex flex-col">
                    <ProductCard product={product} />
                  </div>
                ))}
              </div>
            </div>
            )}

            {/* 6. Prawns Festival Promotional Banner */}
            <div className="relative w-full rounded-2xl overflow-hidden bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white p-5 sm:p-7 shadow-lg flex items-center justify-between border border-blue-800/40">
              <div className="space-y-1.5 max-w-[65%]">
                <span className="text-[10px] font-bold uppercase tracking-widest text-amber-300">
                  PRAWNS FESTIVAL
                </span>
                <h3 className="font-sans font-black text-lg sm:text-2xl text-amber-400 uppercase leading-tight">
                  UP TO 20% OFF
                </h3>
                <p className="text-xs text-blue-100 line-clamp-1">
                  Fresh Prawns & Shrimps Limited Time Offer
                </p>
                <button
                  onClick={() => navigateTo('categories')}
                  className="mt-2 inline-flex items-center gap-1.5 bg-white text-slate-900 text-xs font-black px-3.5 py-1.5 rounded-xl shadow-md hover:bg-gray-100 transition-all active:scale-95 uppercase"
                >
                  <span>SHOP NOW</span>
                  <ArrowRight className="w-3.5 h-3.5 text-[#fc490f]" />
                </button>
              </div>
              <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden border-2 border-white/20 shadow-xl shrink-0">
                <img
                  src="https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=400&q=80"
                  alt="Prawns Festival"
                  className="w-full h-full object-cover"
                />
              </div>
            </div>

            {/* 7. Our Videos Section */}
            {displayVideos.length > 0 && (
            <div className="space-y-3">
              <div className="flex items-center justify-between">
                <h3 className="font-sans font-extrabold text-gray-900 text-base sm:text-lg">
                  Our Videos
                </h3>
                <span className="text-xs font-bold text-[#fc490f]">
                  {displayVideos.length} Videos
                </span>
              </div>

              <div className="flex flex-row overflow-x-auto gap-3.5 pb-2.5 scrollbar-none snap-x snap-mandatory sm:grid sm:grid-cols-4 sm:overflow-x-visible">
                {displayVideos.map(vid => {
                  const yId = vid.youtubeId || vid.youtube_id || 'LXb3EKWsInQ';
                  const thumb = vid.thumbnail || `https://img.youtube.com/vi/${yId}/hqdefault.jpg`;
                  return (
                    <div 
                      key={vid.id} 
                      onClick={() => setActiveVideoModal(vid)}
                      className="w-[70vw] max-w-[280px] sm:w-auto shrink-0 snap-start space-y-1.5 group cursor-pointer"
                    >
                      <div className="relative aspect-video w-full rounded-2xl overflow-hidden shadow-xs bg-gray-900 border border-gray-100">
                        <img src={thumb} alt={vid.title} className="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-300" />
                        {/* Play icon overlay */}
                        <div className="absolute inset-0 flex items-center justify-center">
                          <div className="w-10 h-10 rounded-full bg-red-600/90 text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <Play className="w-5 h-5 fill-current ml-0.5" />
                          </div>
                        </div>
                        {/* Duration badge */}
                        <span className="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                          {vid.duration || '1:00'}
                        </span>
                      </div>
                      <h4 className="text-xs font-bold text-gray-800 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors">
                        {vid.title}
                      </h4>
                    </div>
                  );
                })}
              </div>
            </div>
            )}

            {/* 8. Cashback / Referral Banner */}
            <div className="bg-amber-50 p-4 rounded-2xl border border-amber-200/60 flex items-center justify-between gap-3">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-orange-100 text-[#fc490f] flex items-center justify-center shrink-0">
                  <Gift className="w-6 h-6" />
                </div>
                <div>
                  <h4 className="text-xs font-extrabold text-gray-900 uppercase">10% CASHBACK</h4>
                  <p className="text-[11px] text-gray-600">Pay via Amazon Pay & get instant rewards.</p>
                </div>
              </div>
              <button
                onClick={() => navigateTo('categories')}
                className="bg-[#fc490f] hover:bg-orange-600 text-white text-xs font-bold px-3 py-1.5 rounded-xl uppercase shrink-0 shadow-sm cursor-pointer"
              >
                ORDER NOW
              </button>
            </div>

            {/* 9. Seafood Category Section */}
            {seafoodCategory.length > 0 && (
            <div className="space-y-3 pt-1">
              <div className="flex items-center justify-between">
                <h3 className="font-sans font-extrabold text-gray-900 text-base sm:text-lg tracking-tight">
                  Seafood Category
                </h3>
                <button
                  onClick={() => navigateTo('categories')}
                  className="text-xs font-bold text-[#fc490f] hover:underline cursor-pointer"
                >
                  View All &rarr;
                </button>
              </div>

              <div className="flex flex-row items-stretch overflow-x-auto gap-3.5 pb-2.5 scrollbar-none snap-x snap-mandatory sm:grid sm:grid-cols-3 lg:grid-cols-4 sm:overflow-x-visible">
                {seafoodCategory.slice(0, 6).map(product => (
                  <div key={product.id} className="w-[66vw] max-w-[270px] sm:w-auto shrink-0 snap-start h-full flex flex-col">
                    <ProductCard product={product} />
                  </div>
                ))}
              </div>
            </div>
            )}

            {/* 10. Customer Reviews Section */}
            <div className="space-y-3 pt-2">
              <div className="flex items-center justify-between">
                <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-base sm:text-lg">
                  Customer Reviews
                </h3>
                <button
                  onClick={() => navigateTo('categories')}
                  className="text-xs font-bold text-[#fc490f] hover:underline cursor-pointer"
                >
                  View All &rarr;
                </button>
              </div>

              <div className="space-y-3">
                {CUSTOMER_REVIEWS.map(rev => (
                  <div key={rev.id} className="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xs space-y-2">
                    <div className="flex items-center gap-3">
                      <img src={rev.avatar} alt={rev.name} className="w-10 h-10 rounded-full object-cover border border-gray-200" />
                      <div>
                        <h4 className="text-xs font-bold text-gray-900 dark:text-white">{rev.name}</h4>
                        <div className="flex items-center gap-0.5">
                          {[...Array(rev.rating)].map((_, i) => (
                            <Star key={i} className="w-3 h-3 text-amber-400 fill-amber-400" />
                          ))}
                        </div>
                      </div>
                    </div>
                    <p className="text-xs text-gray-700 dark:text-gray-300 italic">
                      &ldquo;{rev.quote}&rdquo;
                    </p>
                    <div className="flex items-center justify-between text-[10px] text-gray-400 pt-1 border-t border-gray-50 dark:border-slate-800">
                      <span className="font-bold text-gray-600 dark:text-gray-400">🏷️ {rev.productTag}</span>
                      <span>{rev.timeAgo}</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>

          </>
        )}
      </div>

      {/* YouTube Video Player Modal */}
      {activeVideoModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 animate-fadeIn">
          <div className="bg-slate-900 border border-slate-800 rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl relative my-auto">
            <div className="p-4 bg-slate-950 flex items-center justify-between border-b border-slate-800">
              <div className="flex items-center gap-2 min-w-0">
                <span className="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
                <h3 className="font-extrabold text-white text-sm sm:text-base truncate pr-2">
                  {activeVideoModal.title}
                </h3>
              </div>
              <button
                onClick={() => setActiveVideoModal(null)}
                className="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-white flex items-center justify-center font-bold text-sm transition-colors cursor-pointer shrink-0"
              >
                ✕
              </button>
            </div>
            <div className="relative aspect-video w-full bg-black">
              <iframe
                src={`https://www.youtube.com/embed/${activeVideoModal.youtubeId || activeVideoModal.youtube_id || 'LXb3EKWsInQ'}?autoplay=1`}
                title={activeVideoModal.title}
                className="w-full h-full border-0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
              />
            </div>
          </div>
        </div>
      )}

    </div>
  );
};

