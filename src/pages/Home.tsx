import React from 'react';
import { useApp } from '../context/AppContext';
import { SUBCATEGORIES, PROMO_SLIDES } from '../data/products';
import { HeroSlider } from '../components/HeroSlider';
import { CategoryList } from '../components/CategoryList';
import { ProductCard } from '../components/ProductCard';
import { SkeletonProductGrid } from '../components/SkeletonLoader';
import { Sparkles, ArrowRight, ShieldCheck, Truck, RefreshCcw, Star, ChevronLeft, ChevronRight } from 'lucide-react';

const TESTIMONIALS = [
  {
    name: 'Anjali Sharma',
    city: 'Mumbai',
    quote: 'Absolutely fresh! The Tiger Prawns are beautifully cleaned and tails kept on. Saved me 30 minutes of kitchen prep.',
    avatar: '👩‍🦰',
    rating: 5
  },
  {
    name: 'Karan Mehra',
    city: 'Delhi',
    quote: 'Never seen meat cut so perfectly. No blood clots, no fat clumps. The Goat Curry Cut is incredibly juicy slow-cooked.',
    avatar: '👨',
    rating: 5
  },
  {
    name: 'Priya Sen',
    city: 'Bengaluru',
    quote: 'Express delivery was literally inside 40 minutes! Packing was super cool with gel-ice layers. Highly recommended.',
    avatar: '👩',
    rating: 4.8
  }
];

export const Home: React.FC = () => {
  const { searchQuery, setSelectedCategory, navigateTo, activeHeroIndex, products, isLoadingProducts, slides } = useApp();

  const [activeTestimonial, setActiveTestimonial] = React.useState(0);

  React.useEffect(() => {
    const timer = setInterval(() => {
      setActiveTestimonial((prev) => (prev + 1) % TESTIMONIALS.length);
    }, 4000);
    return () => clearInterval(timer);
  }, []);

  const activeSlide = slides[activeHeroIndex] || slides[0] || { bgGradient: 'from-red-600 to-rose-500' };
  const startColorClass = activeSlide.bgGradient.split(' ')[0] || 'from-red-600';

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

  const bestSellers = filteredProducts.filter(p => p.isBestSeller);
  const todaySpecials = filteredProducts.filter(p => p.isTodaySpecial || p.rating >= 4.8);
  const otherProducts = filteredProducts.filter(p => !p.isBestSeller && !p.isTodaySpecial && p.rating < 4.8);

  return (
    <div className="space-y-8 pb-10 animate-fadeIn" id="home-page">
      
      {/* 1. Dynamic Hero Offers Carousel Slider with unified background */}
      <div className={`-mx-4 -mt-6 md:mx-0 md:mt-0 px-4 md:px-0 pt-5 md:pt-2 pb-6 md:pb-0 bg-gradient-to-b ${startColorClass} to-slate-50 dark:from-slate-900 dark:to-slate-950 md:from-transparent md:to-transparent md:bg-none transition-all duration-500`}>
        <section className="relative">
          <HeroSlider />
        </section>
      </div>

      {/* 2. Circular Categories Navigation */}
      <section className="bg-gray-50/50 dark:bg-slate-800/20 py-4 px-3 sm:px-6 rounded-2xl border border-gray-100/40 dark:border-slate-800">
        <CategoryList />
      </section>

      {/* 3. Filter Summary Indicator (if active) */}
      {searchQuery && (
        <div className="flex items-center gap-3 bg-red-50/40 dark:bg-red-950/20 p-3.5 rounded-xl border border-red-100/40 dark:border-slate-800 text-sm">
          <Sparkles className="w-4 h-4 text-red-500" />
          <span className="text-gray-700 dark:text-gray-300">
            Showing <strong className="text-red-600 dark:text-red-400 font-extrabold">{filteredProducts.length}</strong> fresh products 
            matching &ldquo;<span className="italic font-bold">{searchQuery}</span>&rdquo;
          </span>
        </div>
      )}

      {/* 4. Products Section Wrapper */}
      <div id="products-section" className="space-y-10 scroll-mt-20">
        {isLoadingProducts ? (
          <SkeletonProductGrid count={6} />
        ) : (
          <>
            {/* If no products found */}
            {filteredProducts.length === 0 && (
          <div className="text-center py-16 space-y-4 rounded-2xl bg-gray-50 dark:bg-slate-900 border border-dashed border-gray-200 dark:border-slate-800">
            <span className="text-5xl block">🎣</span>
            <div className="max-w-md mx-auto space-y-2">
              <h3 className="font-sans font-bold text-gray-900 dark:text-white text-lg">
                No matching fresh catch found
              </h3>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                We couldn't find what you were searching for. Try checking out our best-selling fresh Salmon, White Prawns, or juicy Mutton!
              </p>
              <button
                onClick={() => {
                  setSelectedCategory(null);
                  // Context handles resetting searchQuery via binding
                  window.location.reload(); // Quick reset
                }}
                className="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md"
              >
                Reset All Filters
              </button>
            </div>
          </div>
        )}

        {/* 4a. Best Sellers (Licious style 'Our Current Hits') */}
        {bestSellers.length > 0 && (
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-lg sm:text-xl tracking-tight flex items-center gap-2">
                  <span>🔥 Our Current Hits</span>
                  <span className="bg-red-100 dark:bg-red-950/50 text-red-600 dark:text-red-400 text-[10px] font-bold uppercase px-2 py-0.5 rounded-md">
                    Trending
                  </span>
                </h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  What everyone is eating right now! Sourced and cleaned fresh daily.
                </p>
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {bestSellers.map(product => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        )}

        {/* 4b. Chef's Daily Recommendations */}
        {todaySpecials.length > 0 && (
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-lg sm:text-xl tracking-tight flex items-center gap-2">
                  <span>🧑‍🍳 Royal Daily Specials</span>
                  <span className="bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase px-2 py-0.5 rounded-md">
                    Freshly Cut
                  </span>
                </h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  Carefully processed by our master butchers for optimal flavor.
                </p>
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {todaySpecials.map(product => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        )}

        {/* 4c. Remaining Products */}
        {otherProducts.length > 0 && (
          <div className="space-y-4">
            <div>
              <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-lg sm:text-xl tracking-tight">
                Fresh Marine & Farm Range
              </h3>
              <p className="text-xs text-gray-500 dark:text-gray-400">
                100% traceably sourced, direct to your kitchen door.
              </p>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {otherProducts.map(product => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        )}
          </>
        )}
      </div>

      {/* 5. Brand Value Pillars (Licious Style Quality Checks) */}
      <section className="bg-gradient-to-br from-red-500/5 to-amber-500/5 dark:from-slate-800/40 dark:to-slate-800/20 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 sm:p-8 space-y-6 select-none">
        <div className="text-center max-w-xl mx-auto space-y-1.5">
          <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-base sm:text-lg">
            The Royal Quality Pledge
          </h3>
          <p className="text-xs text-gray-500 dark:text-gray-400">
            We maintain rigid cold-chain standards and precision cuts for ultimate taste and safety.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
          
          <div className="space-y-2">
            <div className="w-10 h-10 rounded-full bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-500 mx-auto md:mx-0">
              <ShieldCheck className="w-5 h-5" />
            </div>
            <h4 className="text-sm font-bold text-gray-800 dark:text-gray-200">
              150+ Stringent Quality Checks
            </h4>
            <p className="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
              Every fish is individually inspected, descaled, and vacuum packed. Free of chemicals, hormones, and artificial preservatives.
            </p>
          </div>

          <div className="space-y-2">
            <div className="w-10 h-10 rounded-full bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-500 mx-auto md:mx-0">
              <Truck className="w-5 h-5" />
            </div>
            <h4 className="text-sm font-bold text-gray-800 dark:text-gray-200">
              End-to-End Cold Chain Care
            </h4>
            <p className="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
              We preserve natural juices by keeping products cooled strictly between 0°C and 4°C from dock to your kitchen drawer.
            </p>
          </div>

          <div className="space-y-2">
            <div className="w-10 h-10 rounded-full bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-500 mx-auto md:mx-0">
              <RefreshCcw className="w-5 h-5" />
            </div>
            <h4 className="text-sm font-bold text-gray-800 dark:text-gray-200">
              Zero-Risk Freshness Refund
            </h4>
            <p className="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
              Not completely satisfied with the texture or cutting style? Contact our support line for an immediate, no-questions refund.
            </p>
          </div>

        </div>
      </section>

      {/* 6. Customer Testimonials (Licious Style 'What our customers say') */}
      <section className="space-y-4 select-none">
        <div className="flex items-center justify-between">
          <div>
            <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-base sm:text-lg">
              Hear from our Fresh Food Lovers
            </h3>
            <p className="text-xs text-gray-500 dark:text-gray-400">
              Real reviews from registered families who order daily
            </p>
          </div>
          <div className="flex items-center gap-1.5">
            <button
              onClick={() => setActiveTestimonial((prev) => (prev - 1 + TESTIMONIALS.length) % TESTIMONIALS.length)}
              className="p-1.5 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all active:scale-95"
              aria-label="Previous review"
            >
              <ChevronLeft className="w-4 h-4" />
            </button>
            <button
              onClick={() => setActiveTestimonial((prev) => (prev + 1) % TESTIMONIALS.length)}
              className="p-1.5 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all active:scale-95"
              aria-label="Next review"
            >
              <ChevronRight className="w-4 h-4" />
            </button>
          </div>
        </div>

        <div className="relative overflow-hidden bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-6 sm:p-8 rounded-2xl shadow-xs transition-all duration-350">
          {/* Decorative quotes badge */}
          <div className="absolute right-6 top-6 text-7xl text-red-500/10 dark:text-red-500/5 font-serif pointer-events-none select-none">
            “
          </div>

          <div className="min-h-[140px] flex flex-col justify-between">
            <div className="space-y-3.5">
              {/* Rating */}
              <div className="flex items-center gap-0.5">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="w-4 h-4 text-amber-400 fill-amber-400 shrink-0" />
                ))}
              </div>

              {/* Quote text with dynamic fade animation on active index change */}
              <p 
                key={activeTestimonial} 
                className="text-xs sm:text-sm text-gray-700 dark:text-gray-300 italic font-medium leading-relaxed animate-fadeIn animate-duration-300"
              >
                &ldquo;{TESTIMONIALS[activeTestimonial].quote}&rdquo;
              </p>
            </div>

            {/* Author details */}
            <div className="flex items-center gap-3 mt-5 pt-4 border-t border-gray-100/60 dark:border-slate-800">
              <span className="text-3xl select-none">{TESTIMONIALS[activeTestimonial].avatar}</span>
              <div>
                <h4 className="text-xs font-bold text-gray-900 dark:text-white">
                  {TESTIMONIALS[activeTestimonial].name}
                </h4>
                <span className="text-[10px] text-gray-400 dark:text-gray-500 font-mono font-medium">
                  Verified Gourmet, {TESTIMONIALS[activeTestimonial].city}
                </span>
              </div>
            </div>
          </div>

          {/* Indicators / Dots */}
          <div className="flex justify-center gap-1.5 mt-5">
            {TESTIMONIALS.map((_, idx) => (
              <button
                key={idx}
                onClick={() => setActiveTestimonial(idx)}
                className={`w-1.5 h-1.5 rounded-full transition-all duration-300 ${
                  idx === activeTestimonial 
                    ? 'bg-red-600 w-4' 
                    : 'bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600'
                }`}
                aria-label={`Go to slide ${idx + 1}`}
              />
            ))}
          </div>
        </div>
      </section>

    </div>
  );
};
