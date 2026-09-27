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

// Robust extractor for YouTube video ID (supports Shorts, short links youtu.be, watch?v=, embed, etc.)
const extractYoutubeId = (urlOrId: string | undefined | null): string => {
  if (!urlOrId) return 'LXb3EKWsInQ';
  const str = String(urlOrId).trim();
  if (/^[a-zA-Z0-9_-]{11}$/.test(str)) return str;
  const shortsMatch = str.match(/(?:youtube\.com|youtu\.be)\/shorts\/([a-zA-Z0-9_-]{11})/i);
  if (shortsMatch) return shortsMatch[1];
  const youtuBeMatch = str.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/i);
  if (youtuBeMatch) return youtuBeMatch[1];
  const watchMatch = str.match(/[?&]v=([a-zA-Z0-9_-]{11})/i);
  if (watchMatch) return watchMatch[1];
  const embedMatch = str.match(/(?:embed|v|live)\/([a-zA-Z0-9_-]{11})/i);
  if (embedMatch) return embedMatch[1];
  const generic = str.match(/([a-zA-Z0-9_-]{11})/);
  return generic ? generic[1] : 'LXb3EKWsInQ';
};

export const Home: React.FC = () => {
  const { searchQuery, navigateTo, products, isLoadingProducts, videos, reviews } = useApp();
  const [activeVideoModal, setActiveVideoModal] = React.useState<any | null>(null);

  const displayVideos = videos && videos.length > 0 ? videos : VIDEOS;
  const displayReviews = reviews && reviews.length > 0 ? reviews : CUSTOMER_REVIEWS;
  const videoScrollRef = React.useRef<HTMLDivElement>(null);
  const [isVideoPaused, setIsVideoPaused] = React.useState(false);

  // Build repeated list for seamless infinite loop
  const repeatedVideos = React.useMemo(() => {
    if (!displayVideos || displayVideos.length === 0) return [];
    let list = [...displayVideos];
    while (list.length < 8) {
      list = [...list, ...displayVideos];
    }
    // Duplicate to form seamless continuous loop
    return [...list, ...list];
  }, [displayVideos]);

  // Smooth continuous auto-scroll loop (pauses on hover or touch)
  React.useEffect(() => {
    const el = videoScrollRef.current;
    if (!el || repeatedVideos.length === 0) return;

    let animId: number;
    const speed = 0.85; // smooth gentle auto-scroll speed

    const scrollStep = () => {
      if (!isVideoPaused && el) {
        el.scrollLeft += speed;
        const halfWidth = el.scrollWidth / 2;
        if (el.scrollLeft >= halfWidth) {
          el.scrollLeft -= halfWidth;
        }
      }
      animId = requestAnimationFrame(scrollStep);
    };

    animId = requestAnimationFrame(scrollStep);
    return () => cancelAnimationFrame(animId);
  }, [isVideoPaused, repeatedVideos.length]);

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

      {/* 4. Special Promotional Catch Banner */}
      <section className="mt-2.5">
        <div 
          onClick={() => navigateTo('onepager')}
          className="cursor-pointer bg-gradient-to-r from-red-600 via-[#fc490f] to-amber-600 rounded-2xl p-3 sm:p-4 text-white shadow-md hover:shadow-lg transition-all flex items-center justify-between gap-2.5 sm:gap-3 group relative overflow-hidden"
        >
          {/* Subtle ambient light glow */}
          <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none" />

          <div className="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
            <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/20 flex items-center justify-center text-xl sm:text-2xl shrink-0 backdrop-blur-xs group-hover:scale-110 transition-transform">
              🔥
            </div>
            <div className="min-w-0 flex-1">
              <div className="flex items-center gap-1.5 flex-wrap">
                <span className="bg-amber-300 text-slate-950 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                  TODAY&apos;S SPECIAL
                </span>
                <span className="text-[10px] text-amber-100 font-bold">
                  Use Code: <strong className="text-white font-black">ROYAL20</strong>
                </span>
              </div>
              <h4 className="text-xs sm:text-sm font-black text-white mt-1 leading-snug line-clamp-1 sm:line-clamp-none">
                Flat 20% OFF on Fresh Catch &amp; Seafood Deals!
              </h4>
              <p className="text-[10px] sm:text-[11px] text-orange-100 font-medium hidden xs:block truncate mt-0.5">
                Premium cut Padma Hilsa &amp; daily catches delivered fresh.
              </p>
            </div>
          </div>

          <div className="flex items-center gap-1 text-[11px] sm:text-xs font-black bg-white text-[#fc490f] px-3 sm:px-3.5 py-2 rounded-xl shrink-0 group-hover:bg-amber-300 group-hover:text-slate-950 transition-colors shadow-xs active:scale-95 whitespace-nowrap">
            <span>View Offers</span>
            <ArrowRight className="w-3.5 h-3.5 stroke-[3]" />
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

            {/* 7. Our Videos Section - Vertical Shorts Auto-scroll Carousel (Inspired by Moral Jewels) */}
            {displayVideos.length > 0 && (
            <div className="space-y-3.5 select-none">
              <div className="flex items-center justify-between">
                <div>
                  <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-base sm:text-lg flex items-center gap-2">
                    <span>Our Videos</span>
                    <span className="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                  </h3>
                  <p className="text-[11px] text-gray-500 dark:text-gray-400">
                    Watch fresh catch stories & behind the scenes
                  </p>
                </div>
                
                <div className="flex items-center gap-2">
                  <span className="text-xs font-bold text-[#fc490f] inline-flex items-center gap-1.5 bg-orange-50 dark:bg-orange-950/40 px-3 py-1 rounded-full border border-orange-200/50 dark:border-orange-800/40">
                    <Play className="w-3 h-3 fill-current" />
                    <span>Watch Shorts</span>
                  </span>
                </div>
              </div>

              {/* Horizontal Infinite Auto-scroll Container */}
              <div 
                ref={videoScrollRef}
                onMouseEnter={() => setIsVideoPaused(true)}
                onMouseLeave={() => setIsVideoPaused(false)}
                onTouchStart={() => setIsVideoPaused(true)}
                onTouchEnd={() => setTimeout(() => setIsVideoPaused(false), 2500)}
                className="flex flex-row overflow-x-auto gap-3.5 sm:gap-4 pb-3 pt-1 scrollbar-none cursor-grab active:cursor-grabbing"
              >
                {repeatedVideos.map((vid, idx) => {
                  const yId = extractYoutubeId(vid.youtubeId || vid.youtube_id || vid.youtubeUrl || vid.youtube_url);
                  const thumb = (vid.thumbnail && !vid.thumbnail.includes('img.youtube.com/vi/http'))
                    ? vid.thumbnail
                    : `https://img.youtube.com/vi/${yId}/hqdefault.jpg`;

                  return (
                    <div 
                      key={`${vid.id || yId}-${idx}`} 
                      onClick={() => setActiveVideoModal(vid)}
                      className="w-[155px] sm:w-[195px] md:w-[220px] aspect-[9/16] shrink-0 rounded-3xl overflow-hidden relative shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 group cursor-pointer bg-slate-950 border border-slate-200/80 dark:border-slate-800"
                    >
                      {/* Full-bleed Thumbnail Image */}
                      <img 
                        src={thumb} 
                        alt="Royal Fish Video" 
                        onError={(e) => {
                          (e.target as HTMLImageElement).src = `https://img.youtube.com/vi/${yId}/mqdefault.jpg`;
                        }}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                      />

                      {/* Top Dark Vignette Gradient (No title text clutter) */}
                      <div className="absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-black/80 via-black/30 to-transparent pointer-events-none p-3.5 flex items-center justify-between">
                        <span className="inline-flex items-center gap-1 bg-red-600/90 text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full shadow-xs">
                          <span className="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                          Shorts
                        </span>
                        <span className="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-full border border-white/20">
                          {vid.duration || '0:45'}
                        </span>
                      </div>

                      {/* Center Play Button Pulse on Hover */}
                      <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <div className="w-12 h-12 rounded-full bg-[#fc490f]/90 text-white flex items-center justify-center shadow-xl scale-90 group-hover:scale-100 transition-transform">
                          <Play className="w-5 h-5 fill-current ml-0.5" />
                        </div>
                      </div>

                      {/* Bottom Dark Vignette Gradient + "Tap to view" Glass Pill */}
                      <div className="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-black/90 via-black/40 to-transparent pointer-events-none flex items-end justify-center pb-4 px-3">
                        <div className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-black/60 backdrop-blur-md text-white text-[11px] font-semibold border border-white/25 shadow-lg group-hover:bg-[#fc490f] group-hover:border-[#fc490f] group-hover:scale-105 transition-all duration-300 pointer-events-auto">
                          <Play className="w-3 h-3 fill-current text-white ml-0.5" />
                          <span>Tap to view</span>
                        </div>
                      </div>
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
                  onClick={() => navigateTo('reviews')}
                  className="text-xs font-bold text-[#fc490f] hover:underline cursor-pointer flex items-center gap-1"
                >
                  View All &rarr;
                </button>
              </div>

              <div className="space-y-3">
                {displayReviews.slice(0, 4).map((rev: any) => {
                  const imgs: string[] = rev.images && Array.isArray(rev.images) ? rev.images : [];
                  const vUrl = rev.video_url || rev.videoUrl;

                  return (
                    <div key={rev.id} className="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xs space-y-2.5">
                      <div className="flex items-start justify-between">
                        <div className="flex items-center gap-3">
                          <img 
                            src={rev.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80'} 
                            alt={rev.name} 
                            className="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-slate-700 shrink-0" 
                          />
                          <div>
                            <div className="flex items-center gap-1.5">
                              <h4 className="text-xs font-bold text-gray-900 dark:text-white">{rev.name}</h4>
                              <span className="text-[10px] text-emerald-600 font-bold">✓ Verified</span>
                            </div>
                            <div className="flex items-center gap-0.5">
                              {[...Array(rev.rating || 5)].map((_, i) => (
                                <Star key={i} className="w-3 h-3 text-amber-400 fill-amber-400" />
                              ))}
                            </div>
                          </div>
                        </div>
                        <span className="text-[10px] text-gray-400">{rev.timeAgo || 'Recently'}</span>
                      </div>

                      <p className="text-xs text-gray-700 dark:text-gray-300 italic leading-relaxed">
                        &ldquo;{rev.quote || rev.comment}&rdquo;
                      </p>

                      {/* Photo / Video chips if media is present */}
                      {(imgs.length > 0 || vUrl) && (
                        <div className="flex items-center gap-2 pt-1">
                          {imgs.slice(0, 3).map((img: string, i: number) => (
                            <div 
                              key={i} 
                              onClick={() => navigateTo('reviews')}
                              className="w-12 h-12 rounded-xl overflow-hidden border border-gray-200 dark:border-slate-800 cursor-pointer hover:opacity-90"
                            >
                              <img src={img} alt="review media" className="w-full h-full object-cover" />
                            </div>
                          ))}
                          {vUrl && (
                            <span 
                              onClick={() => navigateTo('reviews')}
                              className="px-2.5 py-1 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 text-[10px] font-bold flex items-center gap-1 border border-red-200/50 cursor-pointer"
                            >
                              <Play className="w-3 h-3 fill-current" /> Video
                            </span>
                          )}
                        </div>
                      )}

                      <div className="flex items-center justify-between text-[10px] text-gray-400 pt-1.5 border-t border-gray-50 dark:border-slate-800">
                        <span className="font-bold text-gray-600 dark:text-gray-400">🏷️ {rev.productTag || rev.product_tag || 'Fresh Catch'}</span>
                        <button 
                          onClick={() => navigateTo('reviews')} 
                          className="text-[#fc490f] font-bold hover:underline cursor-pointer"
                        >
                          Read full story &rarr;
                        </button>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>

          </>
        )}
      </div>

      {/* YouTube Video Player Modal (Vertical Shorts & Reel Format) */}
      {activeVideoModal && (
        <div 
          onClick={() => setActiveVideoModal(null)}
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 animate-fadeIn"
        >
          <div 
            onClick={(e) => e.stopPropagation()}
            className="bg-slate-900 border border-slate-800 rounded-3xl max-w-xs sm:max-w-sm w-full overflow-hidden shadow-2xl relative my-auto flex flex-col"
          >
            <div className="p-3.5 bg-slate-950 flex items-center justify-between border-b border-slate-800">
              <div className="flex items-center gap-2 min-w-0">
                <span className="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse shrink-0"></span>
                <h3 className="font-extrabold text-white text-xs sm:text-sm truncate pr-2">
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
            <div className="relative aspect-[9/16] max-h-[75vh] w-full bg-black mx-auto">
              {(() => {
                const modalYId = extractYoutubeId(activeVideoModal.youtubeId || activeVideoModal.youtube_id || activeVideoModal.youtubeUrl || activeVideoModal.youtube_url);
                return (
                  <iframe
                    src={`https://www.youtube.com/embed/${modalYId}?autoplay=1&rel=0`}
                    title={activeVideoModal.title}
                    className="w-full h-full border-0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                  />
                );
              })()}
            </div>
          </div>
        </div>
      )}

    </div>
  );
};

