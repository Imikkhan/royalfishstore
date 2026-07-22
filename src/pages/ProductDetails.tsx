import React from 'react';
import { useApp } from '../context/AppContext';
import { ChevronLeft, Star, Clock, Heart, Award, Sparkles, ChefHat, Check, ShieldCheck, ShoppingBag } from 'lucide-react';

export const ProductDetails: React.FC = () => {
  const { 
    selectedProduct, 
    goBack, 
    addToCart, 
    removeFromCart, 
    getCartQuantity, 
    navigateTo, 
    cartTotal 
  } = useApp();

  if (!selectedProduct) {
    return (
      <div className="text-center py-20 space-y-4">
        <span className="text-5xl block">🔎</span>
        <h3 className="font-sans font-bold text-gray-900 dark:text-white text-lg">
          Product Not Found
        </h3>
        <p className="text-sm text-gray-500 dark:text-gray-400">
          The seafood item you are looking for does not exist or has been sold out.
        </p>
        <button
          onClick={goBack}
          className="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md"
        >
          Go Back Home
        </button>
      </div>
    );
  }

  const quantity = getCartQuantity(selectedProduct.id);
  const discountPercent = Math.round(
    ((selectedProduct.originalPrice - selectedProduct.price) / selectedProduct.originalPrice) * 100
  );

  return (
    <div className="space-y-6 pb-12 animate-fadeIn" id="product-details-page">
      
      {/* 1. Back button navigation */}
      <button
        onClick={goBack}
        className="flex items-center gap-1.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors py-1 px-3 bg-gray-100 dark:bg-slate-800 rounded-lg self-start w-fit"
        id="btn-details-back"
      >
        <ChevronLeft className="w-4 h-4" />
        <span>Back to Products</span>
      </button>

      {/* 2. Main 2-column details split */}
      <div className="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        {/* Left Column: Image with overlays */}
        <div className="md:col-span-6 relative rounded-2xl overflow-hidden border border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-900 shadow-sm aspect-video sm:aspect-square">
          <img
            src={selectedProduct.image}
            alt={selectedProduct.name}
            referrerPolicy="no-referrer"
            className="w-full h-full object-cover"
            loading="eager"
          />

          {/* Rating tag */}
          <div className="absolute bottom-4 left-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs px-3 py-1 rounded-lg flex items-center gap-1 text-xs font-bold text-gray-800 dark:text-gray-100 shadow-md">
            <Star className="w-3.5 h-3.5 text-amber-500 fill-amber-500 animate-pulse" />
            <span>{selectedProduct.rating} Stars Rating</span>
            <span className="text-gray-400 font-normal">({selectedProduct.reviewsCount} customer reviews)</span>
          </div>

          {/* Discount Overlay */}
          {discountPercent > 0 && (
            <span className="absolute top-4 right-4 bg-amber-500 text-white text-xs font-extrabold px-3 py-1 rounded-lg shadow-md">
              SAVE {discountPercent}% NOW
            </span>
          )}
        </div>

        {/* Right Column: Title, Metadata, Dynamic checkout addition, details */}
        <div className="md:col-span-6 space-y-6">
          
          {/* Header */}
          <div className="space-y-2">
            <div className="flex flex-wrap gap-2">
              <span className="bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider">
                👑 100% Traceable Catch
              </span>
              <span className="bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider">
                🔬 Chemical-free
              </span>
            </div>
            
            <h2 className="font-sans font-black text-xl sm:text-2xl md:text-3xl text-gray-950 dark:text-white leading-tight tracking-tight">
              {selectedProduct.name}
            </h2>

            {/* Spec cards with detailed metadata */}
            <div className="grid grid-cols-2 gap-2 text-[11px] sm:text-xs font-bold text-gray-700 dark:text-gray-300">
              <div className="bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-800 px-3 py-2 rounded-xl flex flex-col gap-0.5">
                <span className="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider">Gross Weight</span>
                <span className="font-extrabold text-gray-950 dark:text-white">{selectedProduct.grossWeight || selectedProduct.weight}</span>
              </div>
              <div className="bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-800 px-3 py-2 rounded-xl flex flex-col gap-0.5">
                <span className="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider">Net Weight</span>
                <span className="font-extrabold text-gray-950 dark:text-white">
                  {selectedProduct.netWeight || (selectedProduct.weight.includes('500g') ? '450g-480g' : selectedProduct.weight.includes('250g') ? '220g-240g' : selectedProduct.weight)}
                </span>
              </div>
              <div className="bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-800 px-3 py-2 rounded-xl flex flex-col gap-0.5">
                <span className="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider">No. of Pieces</span>
                <span className="font-extrabold text-gray-950 dark:text-white">
                  {selectedProduct.pieces}
                </span>
              </div>
              <div className="bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-800 px-3 py-2 rounded-xl flex flex-col gap-0.5">
                <span className="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider">Servings</span>
                <span className="font-extrabold text-gray-950 dark:text-white">{selectedProduct.servings}</span>
              </div>
            </div>
          </div>

          <hr className="border-gray-100 dark:border-slate-800" />

          {/* Price & Quantity Adder Card */}
          <div className="bg-gray-50/50 dark:bg-slate-800/20 rounded-2xl border border-gray-100 dark:border-slate-800 p-5 space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div className="space-y-1">
                <p className="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wide">Special Club Price</p>
                <div className="flex items-baseline gap-2">
                  <span className="text-2xl sm:text-3xl font-black text-[#00a6c0] dark:text-[#0da1b9]">
                    ₹{selectedProduct.price}
                  </span>
                  {selectedProduct.originalPrice > selectedProduct.price && (
                    <>
                      <span className="text-sm text-gray-400 dark:text-gray-500 line-through font-semibold">
                        ₹{selectedProduct.originalPrice}
                      </span>
                      <span className="bg-[#0da1b9] text-white text-[10px] font-extrabold px-2 py-0.5 rounded uppercase tracking-wider">
                        {discountPercent}% OFF
                      </span>
                    </>
                  )}
                </div>
                <p className="text-[10px] text-gray-400 dark:text-gray-500">Inclusive of all local food safety taxes.</p>
              </div>

              {/* Add item controller */}
              <div className="w-full sm:w-auto">
                {quantity === 0 ? (
                  <button
                    onClick={() => addToCart(selectedProduct)}
                    className="w-full sm:w-auto justify-center bg-[#a80e0e] hover:bg-[#8f0a0a] text-white font-extrabold text-xs sm:text-sm px-6 py-3 rounded-xl flex items-center gap-2 transition-all active:scale-95 shadow-md shadow-[#a80e0e]/15 uppercase tracking-wider cursor-pointer"
                    id="btn-details-add"
                  >
                    <span>ADD TO CART</span>
                    <ShoppingBag className="w-4 h-4" />
                  </button>
                ) : (
                  <div className="w-full sm:w-auto justify-between bg-[#a80e0e] text-white font-bold rounded-xl flex items-center shadow-md border border-[#a80e0e] select-none overflow-hidden">
                    <button
                      onClick={() => removeFromCart(selectedProduct.id)}
                      className="px-4 py-2.5 hover:bg-[#8f0a0a] active:bg-[#7a0808] transition-colors font-extrabold text-base"
                      aria-label="Decrease quantity"
                    >
                      -
                    </button>
                    <span className="px-4 font-sans font-extrabold text-sm min-w-[36px] text-center">
                      {quantity}
                    </span>
                    <button
                      onClick={() => addToCart(selectedProduct)}
                      className="px-4 py-2.5 hover:bg-[#8f0a0a] active:bg-[#7a0808] transition-colors font-extrabold text-base"
                      aria-label="Increase quantity"
                    >
                      +
                    </button>
                  </div>
                )}
              </div>
            </div>

            {/* Instant feedback when items in cart */}
            {quantity > 0 && (
              <div className="text-xs text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/20 px-3 py-2 rounded-lg flex items-center justify-between">
                <span>Added to cart! (Subtotal: ₹{selectedProduct.price * quantity})</span>
                <button 
                  onClick={() => navigateTo('cart')}
                  className="underline hover:no-underline font-extrabold text-[10px]"
                >
                  View Cart
                </button>
              </div>
            )}
          </div>

          {/* Delivery estimate */}
          <div className="flex items-center gap-3 text-xs bg-emerald-500/10 dark:bg-emerald-950/10 border border-emerald-500/20 p-4 rounded-xl text-emerald-800 dark:text-emerald-400">
            <Clock className="w-5 h-5 text-emerald-500 animate-bounce" />
            <div>
              <span className="font-extrabold block">Guaranteed Express Delivery in 45-60 mins!</span>
              <span className="text-[10px] text-gray-500 dark:text-gray-400 block mt-0.5">
                Packaged inside sterile insulated boxes with gel-ice cold packs.
              </span>
            </div>
          </div>

          {/* Description */}
          <div className="space-y-2">
            <h3 className="font-sans font-bold text-gray-900 dark:text-white text-base">
              Product Overview & Source
            </h3>
            <p className="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
              {selectedProduct.description}
            </p>
          </div>

          {/* Food preparation safety check list */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs text-gray-700 dark:text-gray-300">
            <div className="flex items-start gap-2">
              <ShieldCheck className="w-4.5 h-4.5 text-red-500 mt-0.5 shrink-0" />
              <span><strong>Antibiotic-free:</strong> Farm reared with natural feeds.</span>
            </div>
            <div className="flex items-start gap-2">
              <ShieldCheck className="w-4.5 h-4.5 text-red-500 mt-0.5 shrink-0" />
              <span><strong>No added hormones:</strong> 100% natural development.</span>
            </div>
            <div className="flex items-start gap-2">
              <ShieldCheck className="w-4.5 h-4.5 text-red-500 mt-0.5 shrink-0" />
              <span><strong>Hygienically Packed:</strong> Double-sealed food-grade packs.</span>
            </div>
            <div className="flex items-start gap-2">
              <ShieldCheck className="w-4.5 h-4.5 text-red-500 mt-0.5 shrink-0" />
              <span><strong>Custom Cuts:</strong> Perfect portion sizes, zero wastage.</span>
            </div>
          </div>

          {/* Chef Hat Tips */}
          <div className="bg-amber-500/5 border border-amber-500/10 rounded-2xl p-4 flex gap-3 text-xs text-amber-800 dark:text-amber-400 select-none">
            <ChefHat className="w-6 h-6 text-amber-500 shrink-0 mt-0.5" />
            <div className="space-y-1">
              <span className="font-bold block">Chef&apos;s Cooking Suggestion:</span>
              <p className="text-gray-500 dark:text-gray-400 leading-relaxed text-[11px]">
                {selectedProduct.category === 'fish-seafood' 
                  ? 'Pat dry before placing on pan. Shallow fry with mustard oil, curry leaves, and a pinch of turmeric for a perfect crispy coastal finish.'
                  : selectedProduct.category === 'chicken' 
                  ? 'Let it rest at room temp for 5 mins after unpackaging. Slow-cook with onions and ground masalas to retain natural succulent juices.'
                  : 'Avoid pressure cooking. Slow-stew for 45 minutes on medium flame with cloves and cardamoms for tender, melt-in-your-mouth texture.'}
              </p>
            </div>
          </div>

        </div>

      </div>

    </div>
  );
};
