import React, { useState, useEffect } from 'react';
import { ChevronLeft, ChevronRight, Copy, Check } from 'lucide-react';
import { useApp } from '../context/AppContext';
import { SkeletonHero } from './SkeletonLoader';

export const HeroSlider: React.FC = () => {
  const { slides, isLoadingSlides, activeHeroIndex, setActiveHeroIndex } = useApp();
  const currentIndex = activeHeroIndex;
  const setCurrentIndex = setActiveHeroIndex;
  const [copiedCode, setCopiedCode] = useState<string | null>(null);

  const slideList = slides && slides.length > 0 ? slides : [];

  // Auto-play interval
  useEffect(() => {
    if (slideList.length <= 1) return;
    const timer = setInterval(() => {
      setCurrentIndex(prev => (prev + 1) % slideList.length);
    }, 6000);
    return () => clearInterval(timer);
  }, [slideList.length]);

  if (isLoadingSlides) {
    return <SkeletonHero />;
  }

  if (slideList.length === 0) return null;

  const handlePrev = (e: React.MouseEvent) => {
    e.stopPropagation();
    setCurrentIndex(prev => (prev - 1 + slideList.length) % slideList.length);
  };

  const handleNext = (e: React.MouseEvent) => {
    e.stopPropagation();
    setCurrentIndex(prev => (prev + 1) % slideList.length);
  };

  const handleCopy = (code: string, e: React.MouseEvent) => {
    e.stopPropagation();
    navigator.clipboard.writeText(code);
    setCopiedCode(code);
    setTimeout(() => setCopiedCode(null), 2500);
  };

  const currentSlide = slideList[currentIndex] || slideList[0];

  return (
    <div className="relative w-full rounded-2xl overflow-hidden shadow-lg border border-gray-100/10 h-48 sm:h-56 md:h-64 lg:h-72 select-none group">
      
      {/* Background slide wrapper */}
      <div className="absolute inset-0 w-full h-full flex transition-transform duration-700 ease-out">
        <div 
          className={`w-full h-full flex items-center justify-between p-4 sm:p-8 md:p-10 bg-gradient-to-r ${currentSlide.bgGradient}`}
        >
          {/* Text Container */}
          <div className="flex-1 max-w-[60%] sm:max-w-[65%] text-white space-y-1.5 sm:space-y-3.5 animate-fadeIn">
            <span className="inline-block bg-white/20 text-[9px] sm:text-xs font-bold uppercase tracking-widest px-2 py-0.5 rounded-full backdrop-blur-xs">
              ⚡ Limited Period Offer
            </span>
            <h2 className="font-sans font-black text-sm sm:text-2xl md:text-3xl leading-tight tracking-tight">
              {currentSlide.title}
            </h2>
            <p className="text-[10px] sm:text-sm text-white/95 line-clamp-2 leading-normal">
              {currentSlide.subtitle}
            </p>
            
            {/* Promo code copy button */}
            <div className="flex items-center gap-2 pt-1">
              <button
                onClick={(e) => handleCopy(currentSlide.code, e)}
                className="flex items-center gap-1.5 bg-white text-gray-900 text-[10px] sm:text-xs font-bold px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-lg shadow-md hover:bg-gray-100 transition-colors active:scale-95"
                title="Copy promo code"
                id={`btn-copy-${currentSlide.code}`}
              >
                {copiedCode === currentSlide.code ? (
                  <>
                    <Check className="w-3 h-3 text-emerald-600 font-bold" />
                    <span className="text-emerald-600">Copied!</span>
                  </>
                ) : (
                  <>
                    <Copy className="w-3 h-3 text-gray-500" />
                    <span>Use Code: <span className="font-mono text-red-600">{currentSlide.code}</span></span>
                  </>
                )}
              </button>
            </div>
          </div>

          {/* Image Container */}
          <div className="w-[35%] sm:w-[30%] h-full relative flex items-center justify-center">
            <div className="w-20 h-20 sm:w-36 sm:h-36 md:w-44 md:h-44 rounded-full overflow-hidden border-4 border-white/20 shadow-2xl relative shrink-0">
              <img
                src={currentSlide.image || 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80'}
                alt={currentSlide.title}
                referrerPolicy="no-referrer"
                className="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500"
                loading="eager"
                onError={(e) => {
                  (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80';
                }}
              />
            </div>
          </div>
        </div>
      </div>

      {/* Navigation Arrows (Visible on hover on desktop) */}
      <button
        onClick={handlePrev}
        className="absolute left-3 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/30 hover:bg-black/50 text-white backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200"
        aria-label="Previous slide"
        id="btn-slider-prev"
      >
        <ChevronLeft className="w-5 h-5" />
      </button>
      <button
        onClick={handleNext}
        className="absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/30 hover:bg-black/50 text-white backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200"
        aria-label="Next slide"
        id="btn-slider-next"
      >
        <ChevronRight className="w-5 h-5" />
      </button>

      {/* Navigation Dots */}
      <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
        {slideList.map((_, idx) => (
          <button
            key={idx}
            onClick={() => setCurrentIndex(idx)}
            className={`w-2 h-2 rounded-full transition-all ${
              idx === currentIndex ? 'bg-white w-4' : 'bg-white/40 hover:bg-white/60'
            }`}
            aria-label={`Go to slide ${idx + 1}`}
          />
        ))}
      </div>

    </div>
  );
};
