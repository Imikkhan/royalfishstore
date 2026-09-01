import React, { useState, useEffect } from 'react';
import { useApp } from '../context/AppContext';
import { PRODUCTS } from '../data/products';
import { API_BASE_URL } from '../config';

interface OnepagerCustomSection {
  id: string;
  badge: string;
  title: string;
  subtitle: string;
  category_id: number | string;
  category_name: string;
  category_slug: string;
  category_image: string;
  category_icon: string;
  view_all_label: string;
  view_all_link: string;
  products: any[];
}

import {
  Truck,
  MessageCircle,
  ShoppingBag,
  Star,
  ChevronDown,
  ChevronUp,
  Check,
  ArrowRight,
  Phone,
  Zap,
  Plus,
  Minus
} from 'lucide-react';

export const Onepager: React.FC = () => {
  const {
    products,
    addToCart,
    removeFromCart,
    getCartQuantity,
    navigateTo,
    showToast
  } = useApp();

  const displayProducts = (products && products.length > 0) ? products : PRODUCTS;

  // Dynamic Admin-configured Category & Products Sections
  const [customSections, setCustomSections] = useState<OnepagerCustomSection[]>([]);
  const [isLoadingSections, setIsLoadingSections] = useState(true);

  useEffect(() => {
    fetch(`${API_BASE_URL}/onepager-settings`)
      .then(res => res.ok ? res.json() : null)
      .then(data => {
        if (data && data.success && Array.isArray(data.sections) && data.sections.length > 0) {
          setCustomSections(data.sections);
        }
        setIsLoadingSections(false);
      })
      .catch(() => {
        setIsLoadingSections(false);
      });
  }, []);

  // FAQ Accordion State (open by default to match landing page showcase)
  const [openFaqs, setOpenFaqs] = useState<Record<number, boolean>>({ 0: true, 1: true, 2: true, 3: true });

  const toggleFaq = (idx: number) => {
    setOpenFaqs(prev => ({ ...prev, [idx]: !prev[idx] }));
  };

  const faqs = [
    {
      q: 'কোন কোন PIN code-এ Delivery হবে?',
      content: (
        <div className="space-y-2 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
          <p>
            <strong className="text-slate-900 dark:text-white font-bold">Newtown:</strong> 700156, 700157, 700136, 700135, 700160, 700161, 700162, 700163, 700132, 700152, 700059, 700101
          </p>
          <p>
            <strong className="text-slate-900 dark:text-white font-bold">Salt Lake:</strong> 700091, 700106, 700107, 700102, 700064, 700010, 700046, 700101, 700100, 700105
          </p>
        </div>
      )
    },
    {
      q: 'কাটা ইলিশে Delivery Charge কত?',
      content: (
        <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
          3 পিস অর্ডারে ₹100 delivery charge যোগ হবে। 4 পিস বা তার বেশি অর্ডার করলে delivery সম্পূর্ণ FREE।
        </p>
      )
    },
    {
      q: 'কত সময়ের মধ্যে Delivery হবে?',
      content: (
        <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
          অর্ডার Confirm হওয়ার পর সাধারণত 24 ঘণ্টার মধ্যে Delivery করা হবে।
        </p>
      )
    },
    {
      q: 'Cash on Delivery আছে?',
      content: (
        <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
          হ্যাঁ, মাছ হাতে পাওয়ার সময় Cash on Delivery-তে মূল্য দিতে পারবেন।
        </p>
      )
    }
  ];

  return (
    <div className="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 select-none pb-28">
      {/* 1. HERO BANNER SECTION */}
      <section className="relative overflow-hidden bg-gradient-to-b from-amber-50/70 via-orange-50/30 to-white pt-3 sm:pt-10 pb-8 sm:pb-10 px-3 sm:px-6 lg:px-8 border-b border-orange-100/80">
        
        {/* Soft background ambient glow */}
        <div className="absolute top-0 right-0 w-96 h-96 bg-amber-200/25 rounded-full blur-3xl pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-orange-200/25 rounded-full blur-3xl pointer-events-none" />

        <div className="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center relative z-10">
          
          {/* Left Column: Attention-Grabbing Hook & Offer */}
          <div className="lg:col-span-7 space-y-4 text-center lg:text-left order-2 lg:order-1">
            
            {/* Offer Tag */}
            <div className="inline-flex items-center gap-1.5 bg-orange-100/90 border border-orange-200/80 text-[#fc490f] text-xs font-black px-3.5 py-1.5 rounded-full shadow-xs">
              <span className="animate-pulse">🔥</span>
              <span>আজকের স্পেশাল ইলিশ অফার</span>
            </div>

            {/* Main Catchy Headline */}
            <h1 className="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-[1.2]">
              কলকাতায় এবার ঘরে বসেই উপভোগ করুন <span className="text-[#fc490f]">তেলতেলে রাজকীয় ইলিশ</span>
            </h1>

            {/* Description */}
            <p className="text-sm sm:text-base text-slate-600 font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
              ১ কেজি+ সাইজের স্পেশাল ইলিশ—কাটিং, পরিষ্কার ও হাইজেনিক প্যাকেজিংসহ পৌঁছে যাবে আপনার রান্নাঘরে।
            </p>

            {/* 2 Price Spec Boxes */}
            <div className="grid grid-cols-2 gap-3 pt-1 max-w-md mx-auto lg:mx-0">
              <div className="bg-white rounded-2xl p-3.5 sm:p-4 border border-orange-200/80 shadow-xs hover:border-[#fc490f] transition-colors text-left">
                <span className="text-[11px] sm:text-xs font-bold text-slate-500 block">১ কেজি+ সম্পূর্ণ ইলিশ</span>
                <div className="flex items-baseline gap-1 mt-1">
                  <span className="text-xl sm:text-2xl font-black text-[#fc490f]">₹1,399</span>
                  <span className="text-[11px] text-slate-400 font-bold">/কেজি</span>
                </div>
              </div>

              <div className="bg-white rounded-2xl p-3.5 sm:p-4 border border-orange-200/80 shadow-xs hover:border-[#fc490f] transition-colors text-left">
                <span className="text-[11px] sm:text-xs font-bold text-slate-500 block">৭০-৮০ গ্রাম কাটা পিস</span>
                <div className="flex items-baseline gap-1 mt-1">
                  <span className="text-xl sm:text-2xl font-black text-[#fc490f]">₹149</span>
                  <span className="text-[11px] text-slate-400 font-bold">/পিস</span>
                </div>
              </div>
            </div>

            {/* Action Buttons */}
            <div className="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 pt-2">
              <a
                href="#featured-products"
                className="w-full sm:w-auto px-7 py-3.5 bg-gradient-to-r from-[#fc490f] to-orange-600 hover:from-orange-600 hover:to-red-600 active:scale-95 text-white font-extrabold rounded-2xl shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2 text-sm sm:text-base transition-all group"
              >
                <span>🐟 এখনই অর্ডার করুন</span>
              </a>

              <a
                href="https://wa.me/919876543210?text=Hi%20Royal%20Fish%20Store%2C%20আমি%20আজকের%20স্পেশাল%20পদ্মার%20ইলিশ%20অর্ডার%20করতে%20চাই।"
                target="_blank"
                rel="noopener noreferrer"
                className="w-full sm:w-auto px-6 py-3.5 bg-[#25D366] hover:bg-[#20ba59] active:scale-95 text-white font-extrabold rounded-2xl shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 text-sm sm:text-base transition-all"
              >
                <MessageCircle className="w-5 h-5 fill-current" />
                <span>WhatsApp-এ কথা বলুন</span>
              </a>
            </div>

            {/* Trust Checklist */}
            <div className="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-5 pt-2 text-xs font-bold text-slate-700">
              <div className="flex items-center gap-1.5 text-emerald-700 bg-emerald-50/70 px-2.5 py-1 rounded-lg border border-emerald-200/50">
                <Check className="w-3.5 h-3.5 text-emerald-600 stroke-[3]" />
                <span>Cash on Delivery</span>
              </div>
              <div className="flex items-center gap-1.5 text-emerald-700 bg-emerald-50/70 px-2.5 py-1 rounded-lg border border-emerald-200/50">
                <Check className="w-3.5 h-3.5 text-emerald-600 stroke-[3]" />
                <span>২৪ ঘণ্টার মধ্যে Delivery</span>
              </div>
              <div className="flex items-center gap-1.5 text-emerald-700 bg-emerald-50/70 px-2.5 py-1 rounded-lg border border-emerald-200/50">
                <Check className="w-3.5 h-3.5 text-emerald-600 stroke-[3]" />
                <span>কলকাতাজুড়ে Service</span>
              </div>
            </div>

          </div>

          {/* Right Column: Hero Visual Showcase Card */}
          <div className="lg:col-span-5 flex justify-center order-1 lg:order-2 w-full">
            <div className="relative w-full max-w-[340px] sm:max-w-md bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl border border-orange-100/80 group">
              <img
                src="/hilsa_hero.png"
                alt="তাজা পদ্মার ইলিশ - Royal Fish Store"
                className="w-full h-auto object-cover transform group-hover:scale-[1.02] transition-transform duration-500 block"
              />

              {/* Floating Bottom-Left Delivery Badge */}
              <div className="absolute bottom-3 left-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-slate-100 flex items-center gap-2 text-slate-900">
                <div className="w-7 h-7 rounded-lg bg-orange-100 text-[#fc490f] flex items-center justify-center shrink-0">
                  <Truck className="w-3.5 h-3.5" />
                </div>
                <div className="leading-tight text-left">
                  <span className="text-[10px] font-black block text-slate-900">২৪ ঘণ্টার মধ্যে</span>
                  <span className="text-[8px] font-bold text-slate-500 block">আপনার দরজায় Delivery</span>
                </div>
              </div>

              {/* Floating Top-Right Fresh Seal */}
              <div className="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-full shadow-md border border-slate-100 text-center">
                <span className="text-[9px] font-black text-emerald-600 tracking-wide block">100% FRESH</span>
              </div>
            </div>
          </div>

        </div>
      </section>

      {/* Category Quick Jump Bar (when multiple custom sections exist) */}
      {customSections.length > 1 && (
        <div className="sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-orange-100 dark:border-slate-800 py-2.5 px-4 overflow-x-auto shadow-xs">
          <div className="max-w-6xl mx-auto flex items-center gap-2 text-xs font-bold whitespace-nowrap">
            {customSections.map((sec, idx) => (
              <a
                key={sec.id || idx}
                href={`#sec-${sec.id || idx}`}
                className="px-3.5 py-1.5 rounded-full bg-orange-50 dark:bg-slate-800 hover:bg-[#fc490f] hover:text-white text-slate-700 dark:text-slate-300 border border-orange-200 dark:border-slate-700 transition-all flex items-center gap-1.5 shrink-0 shadow-2xs"
              >
                <span>{sec.category_icon || '🐟'}</span>
                <span>{sec.title}</span>
              </a>
            ))}
          </div>
        </div>
      )}

      {/* 2. DYNAMIC ADMIN CATEGORY & 4 PRODUCTS SECTIONS */}
      <div id="featured-products" className="scroll-mt-14">
        {((customSections.length > 0 ? customSections : [
          {
            id: 'sec_default_1',
            badge: '🔥 আজকের স্পেশাল অফার',
            title: 'তাজা পদ্মার ইলিশ ও মাছের স্পেশাল কালেকশন',
            subtitle: '১ কেজি+ সাইজের স্পেশাল ইলিশ ও তাজা মাছ—সরাসরি নদী থেকে আপনার ঘরে।',
            category_id: '',
            category_name: 'Fish & Seafood',
            category_slug: 'fish-seafood',
            category_image: '',
            category_icon: '🐟',
            view_all_label: 'সকল মাছের কালেকশন দেখুন (View All Fish)',
            view_all_link: '#featured-products',
            products: displayProducts.slice(0, 4)
          }
        ]).map((sec, secIdx) => {
          const prods = (sec.products && sec.products.length > 0) 
            ? sec.products 
            : displayProducts.slice(0, 4);

          return (
            <section 
              key={sec.id || secIdx} 
              id={`sec-${sec.id || secIdx}`} 
              className="py-10 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto border-b border-orange-100/60"
            >
              {/* Header with full Bangla support */}
              <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 text-center sm:text-left">
                <div className="space-y-1.5 max-w-2xl">
                  {sec.badge && (
                    <div className="inline-flex items-center gap-1.5 text-xs font-black text-[#fc490f] bg-orange-100/80 px-3 py-1 rounded-full shadow-xs">
                      <span>{sec.badge}</span>
                    </div>
                  )}
                  <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                    {sec.title}
                  </h2>
                  {sec.subtitle && (
                    <p className="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                      {sec.subtitle}
                    </p>
                  )}
                </div>

                {/* View All Button on Header (Desktop) */}
                <a
                  href={sec.view_all_link || "#featured-products"}
                  onClick={(e) => {
                    if (sec.view_all_link && sec.view_all_link.startsWith('/') && !sec.view_all_link.startsWith('/#')) {
                      e.preventDefault();
                      navigateTo('categories');
                    }
                  }}
                  className="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-orange-50 hover:bg-orange-100 dark:bg-slate-800 dark:hover:bg-slate-700 border border-orange-200 dark:border-slate-700 text-[#fc490f] text-xs font-bold rounded-xl transition-all shadow-xs"
                >
                  <span>{sec.view_all_label || 'সকল পণ্য দেখুন (View All)'}</span>
                  <ArrowRight className="w-3.5 h-3.5" />
                </a>
              </div>

              {/* 4 Products Responsive Grid */}
              <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                {prods.slice(0, 4).map((p: any) => {
                  const quantity = getCartQuantity(p.id);
                  const savePercent = p.originalPrice > p.price
                    ? Math.round(((p.originalPrice - p.price) / p.originalPrice) * 100)
                    : 20;

                  return (
                    <div 
                      key={p.id}
                      className="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 hover:border-orange-300 dark:hover:border-orange-500/50 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden group"
                    >
                      {/* Image & Badges Container */}
                      <div className="relative aspect-4/3 overflow-hidden bg-slate-50 dark:bg-slate-800">
                        <img
                          src={p.image || '/logo.png'}
                          alt={p.name}
                          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                          loading="lazy"
                        />
                        
                        {/* Discount Seal */}
                        {savePercent > 0 && (
                          <div className="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm">
                            SAVE {savePercent}%
                          </div>
                        )}

                        {/* Weight Tag */}
                        <div className="absolute bottom-2 right-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                          {p.weight || '500g'}
                        </div>
                      </div>

                      {/* Content Box */}
                      <div className="p-3 sm:p-4 flex-1 flex flex-col justify-between space-y-2.5">
                        <div>
                          <h3 className="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white line-clamp-2 leading-snug group-hover:text-[#fc490f] transition-colors">
                            {p.name}
                          </h3>
                          {p.pieces && (
                            <span className="text-[10px] text-slate-400 font-medium block mt-0.5">
                              {p.pieces}
                            </span>
                          )}
                        </div>

                        {/* Price row */}
                        <div className="flex items-baseline gap-1.5 pt-1">
                          <span className="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                            ₹{p.price}
                          </span>
                          {p.originalPrice > p.price && (
                            <span className="text-xs text-slate-400 line-through">
                              ₹{p.originalPrice}
                            </span>
                          )}
                        </div>

                        {/* Action Buttons: Add to Cart with +/- Stepper & WhatsApp (Stacked for zero breakage on 2-col mobile cards) */}
                        <div className="space-y-1.5 pt-1">
                          {quantity === 0 ? (
                            <button
                              type="button"
                              onClick={() => {
                                addToCart(p);
                                showToast(`${p.name} added to cart!`, 'success');
                              }}
                              className="w-full py-2 px-2 rounded-xl text-xs font-black bg-gradient-to-r from-[#fc490f] to-orange-600 hover:from-orange-600 hover:to-red-600 text-white active:scale-95 transition-all flex items-center justify-center gap-1.5 shadow-xs"
                              title="Add to Cart"
                            >
                              <Plus className="w-3.5 h-3.5 stroke-[3]" />
                              <span>Add to Cart</span>
                            </button>
                          ) : (
                            <div className="w-full bg-[#fc490f] text-white font-black rounded-xl flex items-center justify-between shadow-xs overflow-hidden py-1 px-1.5 border border-orange-600">
                              <button
                                type="button"
                                onClick={() => {
                                  removeFromCart(p.id);
                                  showToast(`Updated ${p.name} in cart`, 'info');
                                }}
                                className="w-7 h-7 flex items-center justify-center bg-white/20 hover:bg-white/30 active:scale-90 rounded-lg transition-all text-white font-black"
                                aria-label="Decrease quantity"
                              >
                                <Minus className="w-3.5 h-3.5 stroke-[3]" />
                              </button>

                              <span className="font-black text-xs px-1 text-white">
                                {quantity} in Cart
                              </span>

                              <button
                                type="button"
                                onClick={() => {
                                  addToCart(p);
                                  showToast(`Added 1 more ${p.name}`, 'success');
                                }}
                                className="w-7 h-7 flex items-center justify-center bg-white/20 hover:bg-white/30 active:scale-90 rounded-lg transition-all text-white font-black"
                                aria-label="Increase quantity"
                              >
                                <Plus className="w-3.5 h-3.5 stroke-[3]" />
                              </button>
                            </div>
                          )}

                          <a
                            href={`https://wa.me/919876543210?text=Hi%20Royal%20Fish%20Store%2C%20আমি%20${encodeURIComponent(p.name)}%20${quantity > 0 ? `(${quantity} প্যাক)` : ''}%20অর্ডার%20করতে%20চাই।`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="w-full py-1.5 px-2 rounded-xl text-[11px] font-bold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 active:scale-95 transition-all flex items-center justify-center gap-1.5 shadow-2xs"
                            title="Order on WhatsApp"
                          >
                            <MessageCircle className="w-3.5 h-3.5 fill-current text-emerald-600 dark:text-emerald-400" />
                            <span>WhatsApp Order{quantity > 0 ? ` (${quantity})` : ''}</span>
                          </a>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>

              {/* Mobile View All Button */}
              <div className="mt-5 text-center sm:hidden">
                <a
                  href={sec.view_all_link || "#featured-products"}
                  onClick={(e) => {
                    if (sec.view_all_link && sec.view_all_link.startsWith('/') && !sec.view_all_link.startsWith('/#')) {
                      e.preventDefault();
                      navigateTo('categories');
                    }
                  }}
                  className="w-full py-3 px-4 bg-orange-50 dark:bg-slate-800 hover:bg-orange-100 dark:hover:bg-slate-700 border border-orange-200 dark:border-slate-700 text-[#fc490f] text-xs font-black rounded-2xl transition-all flex items-center justify-center gap-2 shadow-xs"
                >
                  <span>{sec.view_all_label || 'সকল পণ্য দেখুন (View All)'}</span>
                  <ArrowRight className="w-3.5 h-3.5" />
                </a>
              </div>
            </section>
          );
        }))}
      </div>

      {/* 3. FRESHNESS & QUALITY JOURNEY (4 STEPS) */}
      <section className="py-10 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
        <div className="text-center space-y-2 mb-8">
          <span className="text-[#fc490f] text-xs font-black uppercase tracking-wider bg-orange-100 dark:bg-orange-950 px-3 py-1 rounded-full">
            OUR PROMISE
          </span>
          <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
            Samandar Se Kitchen Tak: 4-Step Fresh Process
          </h2>
          <p className="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
            Harr order ke peeche hamara uncompromised hygiene aur quality standard.
          </p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 text-center space-y-2 shadow-xs">
            <div className="w-12 h-12 rounded-2xl bg-orange-100 dark:bg-orange-950/60 text-[#fc490f] flex items-center justify-center text-2xl mx-auto font-black">
              🌅
            </div>
            <h4 className="font-extrabold text-sm text-slate-900 dark:text-white">1. Dawn Fresh Catch</h4>
            <p className="text-xs text-slate-500 leading-relaxed">
              Sourced every morning directly from Diamond Harbour & coastal landing centers.
            </p>
          </div>

          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 text-center space-y-2 shadow-xs">
            <div className="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center text-2xl mx-auto font-black">
              ❄️
            </div>
            <h4 className="font-extrabold text-sm text-slate-900 dark:text-white">2. 0-4°C Cold Transit</h4>
            <p className="text-xs text-slate-500 leading-relaxed">
              Maintained strictly in refrigerated vans without freezing or cell damage.
            </p>
          </div>

          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 text-center space-y-2 shadow-xs">
            <div className="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center text-2xl mx-auto font-black">
              🧼
            </div>
            <h4 className="font-extrabold text-sm text-slate-900 dark:text-white">3. RO Washed & Cut</h4>
            <p className="text-xs text-slate-500 leading-relaxed">
              Sanitized RO water wash, expert master butchery, customized Bengali/Steak cuts.
            </p>
          </div>

          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 text-center space-y-2 shadow-xs">
            <div className="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center text-2xl mx-auto font-black">
              ⚡
            </div>
            <h4 className="font-extrabold text-sm text-slate-900 dark:text-white">4. 45-Min Express Drop</h4>
            <p className="text-xs text-slate-500 leading-relaxed">
              Thermal vacuum sealed boxes delivered directly to your kitchen in 45 mins.
            </p>
          </div>
        </div>
      </section>

      {/* 4. REAL CUSTOMER REVIEWS & SOCIAL PROOF */}
      <section className="py-10 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
        <div className="text-center space-y-2 mb-8">
          <div className="inline-flex items-center gap-1.5 text-amber-500 text-xs font-black">
            <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
            <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
            <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
            <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
            <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
            <span className="text-slate-800 dark:text-slate-200 ml-1">4.9 / 5.0 (15,420+ Reviews)</span>
          </div>
          <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
            What Real Seafood Lovers Say
          </h2>
          <p className="text-xs text-slate-500">Facebook par ad dekh kar order karne wale customer ke asli reviews</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div className="flex items-center gap-3">
              <img
                src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80"
                alt="Sunita Roy"
                className="w-10 h-10 rounded-full object-cover border border-[#fc490f]"
              />
              <div>
                <h4 className="font-extrabold text-xs text-slate-900 dark:text-white">Sunita Roy</h4>
                <div className="text-[10px] text-slate-400">Kolkata • Verified Buyer</div>
              </div>
            </div>
            <div className="flex text-amber-400 text-xs">★★★★★</div>
            <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
              "Diamond Harbour Hilsa order kiya tha Facebook ad dekh ke. Fish ekdum fresh thi, koi smell nahi aur tel bohot accha nikla curry me! Ab har weekend yahin se lenge."
            </p>
            <div className="text-[10px] font-bold text-[#fc490f] bg-orange-50 dark:bg-orange-950/40 p-1.5 rounded-lg inline-block">
              Ordered: Fresh Hilsa 1kg Cut
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div className="flex items-center gap-3">
              <img
                src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80"
                alt="Vikramaditya Rao"
                className="w-10 h-10 rounded-full object-cover border border-[#fc490f]"
              />
              <div>
                <h4 className="font-extrabold text-xs text-slate-900 dark:text-white">Vikramaditya Rao</h4>
                <div className="text-[10px] text-slate-400">Mumbai • Verified Buyer</div>
              </div>
            </div>
            <div className="flex text-amber-400 text-xs">★★★★★</div>
            <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
              "Surmai steaks and Jumbo tiger prawns were delivered in just 35 minutes! Cleaned so well that I just had to marinate and fry. 10/10 packing."
            </p>
            <div className="text-[10px] font-bold text-[#fc490f] bg-orange-50 dark:bg-orange-950/40 p-1.5 rounded-lg inline-block">
              Ordered: Surmai Steaks & Tiger Prawns
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div className="flex items-center gap-3">
              <img
                src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80"
                alt="Anand Verma"
                className="w-10 h-10 rounded-full object-cover border border-[#fc490f]"
              />
              <div>
                <h4 className="font-extrabold text-xs text-slate-900 dark:text-white">Anand Verma</h4>
                <div className="text-[10px] text-slate-400">Delhi NCR • Verified Buyer</div>
              </div>
            </div>
            <div className="flex text-amber-400 text-xs">★★★★★</div>
            <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
              "Mutton curry cut and country chicken both were super tender. Cash on delivery option made it very reliable to test for the first time."
            </p>
            <div className="text-[10px] font-bold text-[#fc490f] bg-orange-50 dark:bg-orange-950/40 p-1.5 rounded-lg inline-block">
              Ordered: Goat Curry Cut & Farm Chicken
            </div>
          </div>
        </div>
      </section>

      {/* 5. WHOLESALE & BULK ORDERS BANNER (B2B HOTELS & CATERING) */}
      <section className="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
        <div className="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-700">
          <div className="space-y-2 text-center md:text-left">
            <span className="bg-amber-400 text-slate-950 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">
              WHOLESALE & PARTY ORDERS
            </span>
            <h3 className="text-xl sm:text-2xl font-black">
              Hotels, Caterers & Party Bulk Supply (10kg+)
            </h3>
            <p className="text-xs sm:text-sm text-slate-300 max-w-xl">
              Get special bulk discount rates with daily scheduled temperature-controlled delivery and customized butchery.
            </p>
          </div>

          <div className="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full md:w-auto">
            <a
              href="https://wa.me/919876543210?text=Hi%20Royal%20Fish%20Store%2C%20I%20need%20Wholesale%2FB2B%20Pricing%20for%20Bulk%20Fish%20and%20Meat."
              target="_blank"
              rel="noopener noreferrer"
              className="w-full sm:w-auto px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl flex items-center justify-center gap-2 text-xs transition-all"
            >
              <MessageCircle className="w-4 h-4" />
              <span>Get Wholesale Price List</span>
            </a>
            <a
              href="tel:+919876543210"
              className="w-full sm:w-auto px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl flex items-center justify-center gap-2 text-xs transition-all border border-white/20"
            >
              <Phone className="w-4 h-4" />
              <span>Call B2B Desk</span>
            </a>
          </div>
        </div>
      </section>

      {/* 6. 100% MONEY BACK FRESHNESS GUARANTEE BADGE */}
      <section className="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div className="bg-amber-50 dark:bg-slate-900/80 border-2 border-amber-300 dark:border-amber-700/60 rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
          <div className="w-14 h-14 bg-amber-400 text-slate-950 rounded-2xl flex items-center justify-center text-3xl shrink-0 shadow-md">
            🛡️
          </div>
          <div>
            <h4 className="font-black text-slate-900 dark:text-white text-base">
              100% No-Risk Freshness & Taste Guarantee
            </h4>
            <p className="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
              Agar aapko delivery ke waqt fish ya meat ki freshness me koi bhi shaq ho, toh rider ko wapas de dein ya hamare helpline par call karein. Hum bina kisi jhanjhat ke <strong>Instant Replacement ya 100% Refund</strong> karenge!
            </p>
          </div>
        </div>
      </section>

      {/* 7. FREQUENTLY ASKED QUESTIONS (ACCORDION) */}
      <section className="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div className="text-center space-y-2 mb-8">
          <span className="text-[#fc490f] text-xs sm:text-sm font-black tracking-wider block">
            প্রয়োজনীয় তথ্য
          </span>
          <h2 className="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white">
            সাধারণ কিছু প্রশ্নের উত্তর
          </h2>
        </div>

        <div className="space-y-4">
          {faqs.map((faq, idx) => {
            const isOpen = openFaqs[idx] !== false;
            return (
              <div
                key={idx}
                className="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-orange-100/90 dark:border-slate-800 p-5 sm:p-6 shadow-xs hover:border-orange-200 transition-all"
              >
                <button
                  type="button"
                  onClick={() => toggleFaq(idx)}
                  className="w-full text-left flex items-center justify-between gap-3 group cursor-pointer"
                >
                  <h3 className="font-extrabold text-sm sm:text-base text-slate-900 dark:text-white flex items-center gap-2.5 group-hover:text-[#fc490f] transition-colors leading-snug">
                    <span className="text-xs text-slate-700 dark:text-slate-300 font-bold shrink-0">
                      {isOpen ? '▼' : '▶'}
                    </span>
                    <span>{faq.q}</span>
                  </h3>
                </button>

                {isOpen && (
                  <div className="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/80 pl-5 sm:pl-6">
                    {faq.content}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </section>

      {/* 8. BOTTOM STICKY CONVERSION BAR (OPTIMIZED FOR BOTH MOBILE & DESKTOP) */}
      <div className="fixed bottom-0 left-0 right-0 w-full z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-orange-200 dark:border-slate-800 py-2.5 sm:py-3 px-4 sm:px-6 shadow-[0_-4px_25px_rgba(0,0,0,0.12)]">
        <div className="max-w-6xl mx-auto flex items-center justify-between gap-4">
          
          {/* Desktop Left Info */}
          <div className="hidden md:flex items-center gap-3.5">
            <div className="w-10 h-10 rounded-2xl bg-orange-100 dark:bg-slate-800 text-[#fc490f] flex items-center justify-center text-xl shadow-xs shrink-0">
              🐟
            </div>
            <div className="leading-tight">
              <div className="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2">
                <span>আজকের স্পেশাল পদ্মার তাজা ইলিশ ও ফ্রেশ সিফুড</span>
                <span className="text-[10px] font-black text-emerald-600 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                  100% FRESH
                </span>
              </div>
              <p className="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                ২৪ ঘণ্টার মধ্যে ডেলিভারি • Cash on Delivery • কোনো কেমিক্যাল বা ফরমালিন নেই
              </p>
            </div>
          </div>

          {/* Action Buttons: Full width on mobile, sleek inline buttons on desktop */}
          <div className="flex items-center gap-2 sm:gap-3 w-full md:w-auto">
            <a
              href="https://wa.me/919876543210?text=Hi%20Royal%20Fish%20Store%2C%20আমি%20আজকের%20স্পেশাল%20পদ্মার%20ইলিশ%20অর্ডার%20করতে%20চাই।"
              target="_blank"
              rel="noopener noreferrer"
              className="flex-1 md:flex-initial px-3 sm:px-7 py-2.5 sm:py-3 bg-[#25D366] hover:bg-[#20ba59] active:scale-95 text-white font-extrabold text-xs sm:text-sm rounded-xl sm:rounded-2xl shadow-md shadow-emerald-500/20 flex items-center justify-center gap-1.5 sm:gap-2 transition-all whitespace-nowrap"
              title="Chat & Order on WhatsApp"
            >
              <MessageCircle className="w-4 h-4 sm:w-5 sm:h-5 fill-current shrink-0" />
              <span className="sm:hidden">WhatsApp</span>
              <span className="hidden sm:inline">WhatsApp-এ অর্ডার করুন</span>
            </a>

            <a
              href="#featured-products"
              className="flex-1 md:flex-initial px-4 sm:px-8 py-2.5 sm:py-3 bg-gradient-to-r from-[#fc490f] to-orange-600 hover:from-orange-600 hover:to-red-600 active:scale-95 text-white font-extrabold text-xs sm:text-sm rounded-xl sm:rounded-2xl shadow-lg shadow-orange-500/25 flex items-center justify-center gap-1.5 sm:gap-2 transition-all whitespace-nowrap"
            >
              <Zap className="w-4 h-4 fill-white shrink-0" />
              <span className="sm:hidden">Order Now</span>
              <span className="hidden sm:inline">এখনই অর্ডার করুন</span>
            </a>
          </div>

        </div>
      </div>

    </div>
  );
};
