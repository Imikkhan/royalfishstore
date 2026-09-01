import React, { useState, useEffect, useRef } from 'react';
import { ChevronLeft, ChevronRight, ArrowRight } from 'lucide-react';
import { useApp } from '../context/AppContext';
import { SkeletonHero } from './SkeletonLoader';

export const HeroSlider: React.FC = () => {
  const { slides, isLoadingSlides, activeHeroIndex, setActiveHeroIndex } = useApp();
  const currentIndex = activeHeroIndex;
  const setCurrentIndex = setActiveHeroIndex;

  // Touch swipe refs
  const touchStartX = useRef<number>(0);
  const touchEndX = useRef<number>(0);

  const slideList = slides && slides.length > 0 ? slides : [
    {
      id: 'slide-default-1',
      titleLine1: 'FRESH FISH',
      titleLine2: 'HEALTHY LIFE',
      subtitleLine1: '100% Fresh | Cleaned & Hygienic',
      subtitleLine2: 'Home Delivery in 30 mins',
      bgGradient: 'from-[#fc490f] via-[#fc490f] to-[#e03e07]',
      image: 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80'
    },
    {
      id: 'slide-default-2',
      titleLine1: 'PRAWNS FESTIVAL',
      titleLine2: 'FLAT 20% OFF',
      subtitleLine1: 'Juicy White Tiger Prawns & Shrimps',
      subtitleLine2: 'Cleaned & Peeled ready for curry',
      bgGradient: 'from-[#e03e07] via-[#fc490f] to-[#fc490f]',
      image: 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=600&q=80'
    },
    {
      id: 'slide-default-3',
      titleLine1: 'TENDER CHICKEN',
      titleLine2: 'FARM FRESH',
      subtitleLine1: 'Antibiotic Free | Hand Cut Portions',
      subtitleLine2: 'Delivered Fresh in 30 Mins',
      bgGradient: 'from-[#fc490f] via-orange-600 to-amber-600',
      image: 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=600&q=80'
    }
  ];

  // Auto-play interval
  useEffect(() => {
    if (slideList.length <= 1) return;
    const timer = setInterval(() => {
      setCurrentIndex(prev => (prev + 1) % slideList.length);
    }, 4500);
    return () => clearInterval(timer);
  }, [slideList.length, setCurrentIndex]);

  const handlePrev = (e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setCurrentIndex(prev => (prev - 1 + slideList.length) % slideList.length);
  };

  const handleNext = (e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setCurrentIndex(prev => (prev + 1) % slideList.length);
  };

  // Touch events for mobile swiping
  const handleTouchStart = (e: React.TouchEvent) => {
    touchStartX.current = e.targetTouches[0].clientX;
  };

  const handleTouchMove = (e: React.TouchEvent) => {
    touchEndX.current = e.targetTouches[0].clientX;
  };

  const handleTouchEnd = () => {
    if (touchStartX.current - touchEndX.current > 50) {
      // Swiped Left -> Next slide
      handleNext();
    }
    if (touchStartX.current - touchEndX.current < -50) {
      // Swiped Right -> Prev slide
      handlePrev();
    }
  };

  if (isLoadingSlides && slides.length === 0) {
    return <SkeletonHero />;
  }

  return (
    <div 
      className="relative w-full rounded-none overflow-hidden shadow-none border-none h-44 sm:h-56 md:h-64 select-none group touch-pan-y"
      onTouchStart={handleTouchStart}
      onTouchMove={handleTouchMove}
      onTouchEnd={handleTouchEnd}
    >
      
      {/* Sliding Track (Flex container sliding via translateX) */}
      <div 
        className="w-full h-full flex transition-transform duration-500 ease-out"
        style={{ transform: `translateX(-${(currentIndex % slideList.length) * 100}%)` }}
      >
        {slideList.map((slide, idx) => (
          <div 
            key={slide.id || idx}
            className={`w-full h-full shrink-0 flex items-center justify-between pl-4 sm:pl-7 pr-0 py-0 bg-gradient-to-r ${slide.bgGradient || 'from-[#fc490f] via-[#fc490f] to-[#e03e07]'}`}
          >
            {/* Text Content Block */}
            <div className="flex-1 max-w-[55%] text-white space-y-1.5 sm:space-y-2.5 z-10 py-3 sm:py-5">
              <h2 className="font-sans font-black text-xl sm:text-3xl md:text-4xl leading-tight tracking-tight uppercase">
                <span className="block text-white">{slide.titleLine1 || slide.title || 'FRESH FISH'}</span>
                <span className="block text-[#FFEB3B]">{slide.titleLine2 || 'HEALTHY LIFE'}</span>
              </h2>
              
              <div className="text-[10px] sm:text-xs text-orange-50 leading-tight space-y-0.5">
                <p className="font-semibold">{slide.subtitleLine1 || slide.subtitle || '100% Fresh | Cleaned & Hygienic'}</p>
                <p>{slide.subtitleLine2 || 'Home Delivery in 30 mins'}</p>
              </div>
              
              {/* White Rounded SHOP NOW Button */}
              <div className="pt-1.5">
                <button
                  className="flex items-center gap-2 bg-white text-[#fc490f] hover:bg-orange-50 text-[11px] sm:text-xs font-black px-4 py-2 rounded-full shadow-md transition-all active:scale-95 uppercase cursor-pointer"
                  id={`btn-shop-now-${slide.id}`}
                >
                  <span>SHOP NOW</span>
                  <ArrowRight className="w-3.5 h-3.5 stroke-[3]" />
                </button>
              </div>
            </div>

            {/* Right Image Container - Flush top & bottom, left border-radius only */}
            <div className="w-[45%] h-full relative flex items-center justify-end overflow-hidden">
              <img
                src={slide.image || 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80'}
                alt="Fresh Fish Platter"
                referrerPolicy="no-referrer"
                className="w-full h-full object-cover object-center rounded-l-2xl sm:rounded-l-3xl transform group-hover:scale-105 transition-transform duration-500"
                loading="eager"
              />
            </div>
          </div>
        ))}
      </div>

      {/* Clickable Navigation Arrows */}
      <button
        onClick={handlePrev}
        className="absolute left-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/30 hover:bg-black/60 text-white backdrop-blur-xs transition-all active:scale-90 z-10"
        aria-label="Previous slide"
      >
        <ChevronLeft className="w-4 h-4 stroke-[3]" />
      </button>
      <button
        onClick={handleNext}
        className="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/30 hover:bg-black/60 text-white backdrop-blur-xs transition-all active:scale-90 z-10"
        aria-label="Next slide"
      >
        <ChevronRight className="w-4 h-4 stroke-[3]" />
      </button>

      {/* Bottom Center White Pagination Dots */}
      <div className="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10">
        {slideList.map((_, idx) => (
          <button
            key={idx}
            onClick={() => setCurrentIndex(idx)}
            className={`rounded-full transition-all ${
              idx === (currentIndex % slideList.length) ? 'bg-white w-3.5 h-2.5 shadow-sm' : 'bg-white/50 w-2 h-2 hover:bg-white/80'
            }`}
            aria-label={`Go to slide ${idx + 1}`}
          />
        ))}
      </div>

    </div>
  );
};



