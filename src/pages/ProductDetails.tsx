import React, { useEffect } from 'react';
import { useApp } from '../context/AppContext';
import { ChevronLeft, Star, Clock, Heart, Award, Sparkles, ChefHat, Check, ShieldCheck, ShoppingBag, ArrowLeft, Snowflake } from 'lucide-react';
import { trackViewItem } from '../utils/tracking';

export const ProductDetails: React.FC = () => {
  const { 
    selectedProduct, 
    goBack, 
    addToCart, 
    removeFromCart, 
    getCartQuantity, 
    navigateTo, 
    cartTotal,
    activePincode,
    setIsPincodeModalOpen,
  } = useApp();

  useEffect(() => {
    if (selectedProduct) {
      trackViewItem(selectedProduct);
    }
  }, [selectedProduct?.id]);

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
  const isDeliverable = (selectedProduct.isDeliverable !== false) && (
    !activePincode || 
    !selectedProduct.servicedPincodes || 
    selectedProduct.servicedPincodes.length === 0 || 
    selectedProduct.servicedPincodes.includes('*') || 
    selectedProduct.servicedPincodes.includes(activePincode)
  );
  const isOutOfStock = Boolean(selectedProduct.isOutOfStock || (selectedProduct.stockQuantity !== undefined && selectedProduct.stockQuantity <= 0) || selectedProduct.inStock === false || (selectedProduct as any).in_stock === false);
  const stockQty = selectedProduct.stockQuantity !== undefined ? Number(selectedProduct.stockQuantity) : 50;
  const lowThreshold = Number(selectedProduct.lowStockThreshold || (selectedProduct as any).low_stock_threshold || 5);
  const isLowStock = !isOutOfStock && (Boolean(selectedProduct.isLowStock) || (stockQty > 0 && stockQty <= lowThreshold));
  const minQty = Math.max(1, Number(selectedProduct.minOrderQty || (selectedProduct as any).min_order_qty || 1));
  const maxQty = Math.max(1, Number(selectedProduct.maxOrderQty || (selectedProduct as any).max_order_qty || 10));

  const originalPrice = Number(selectedProduct.originalPrice || (selectedProduct as any).original_price || 0);
  const hasDiscount = originalPrice > selectedProduct.price && originalPrice > 0;
  const discountPercent = hasDiscount
    ? Math.round(((originalPrice - selectedProduct.price) / originalPrice) * 100)
    : 0;

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">
      
      {/* Breadcrumb back */}
      <button
        onClick={() => goBack()}
        className="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white mb-6 font-semibold transition-colors cursor-pointer group"
      >
        <ArrowLeft className="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" />
        Back to listings
      </button>

      {/* Main Grid */}
      <div className="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">
        
        {/* Left Column: Image Showcase */}
        <div className="md:col-span-6 space-y-4">
          <div className="relative aspect-4/3 rounded-3xl overflow-hidden bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-800 shadow-sm">
            <img
              src={selectedProduct.image}
              alt={selectedProduct.name}
              referrerPolicy="no-referrer"
              className={`w-full h-full object-cover ${isOutOfStock ? 'grayscale-[40%]' : ''}`}
            />
            <div className="absolute top-4 left-4 bg-[#fc490f] text-white p-2 rounded-full shadow-lg">
              <Snowflake className="w-5 h-5 animate-spin-slow" />
            </div>

            {isOutOfStock ? (
              <span className="absolute top-4 right-4 bg-red-600 text-white text-xs font-black px-3 py-1 rounded-lg shadow-md uppercase tracking-wider">
                Out of Stock
              </span>
            ) : isLowStock ? (
              <span className="absolute top-4 right-4 bg-amber-500 text-white text-xs font-black px-3 py-1 rounded-lg shadow-md uppercase tracking-wider animate-pulse">
                Only {stockQty} Left!
              </span>
            ) : hasDiscount ? (
              <span className="absolute top-4 right-4 bg-[#fc490f] text-white text-xs font-black px-3 py-1 rounded-lg shadow-md uppercase tracking-wider">
                SAVE {discountPercent}% NOW
              </span>
            ) : null}
          </div>
        </div>

        {/* Right Column: Title, Metadata, Dynamic checkout addition, details */}
        <div className="md:col-span-6 space-y-6">
          
          {/* Header */}
          <div className="space-y-2">
            <div className="flex flex-wrap items-center gap-2">
              <span className="bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider">
                👑 100% Traceable Catch
              </span>
              <span className="bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider">
                🔬 Chemical-free
              </span>

              {/* Live Inventory Status Pill */}
              {isOutOfStock ? (
                <span className="bg-red-100 dark:bg-red-950/50 text-red-700 dark:text-red-300 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider flex items-center gap-1">
                  <span className="w-1.5 h-1.5 rounded-full bg-red-500"></span> Currently Unavailable
                </span>
              ) : isLowStock ? (
                <span className="bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider flex items-center gap-1 animate-pulse">
                  <span className="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Low Stock: Only {stockQty} units
                </span>
              ) : (
                <span className="bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider flex items-center gap-1">
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> In Stock ({stockQty} units)
                </span>
              )}
            </div>
            
            <h2 className="font-sans font-black text-xl sm:text-2xl md:text-3xl text-gray-950 dark:text-white leading-tight tracking-tight">
              {selectedProduct.name}
            </h2>

            {/* Short description */}
            {(selectedProduct.shortDescription || selectedProduct.short_description) && (
              <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                {selectedProduct.shortDescription || selectedProduct.short_description}
              </p>
            )}

            {/* Purchase Limits Banner */}
            {(minQty > 1 || maxQty < 10) && (
              <div className="bg-slate-100 dark:bg-slate-800/60 p-2.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-3">
                {minQty > 1 && <span>📦 Min Order Limit: <strong className="text-slate-900 dark:text-white">{minQty} units</strong></span>}
                {minQty > 1 && maxQty < 10 && <span className="text-slate-400">|</span>}
                {maxQty < 10 && <span>⛔ Max Allowed: <strong className="text-slate-900 dark:text-white">{maxQty} units/order</strong></span>}
              </div>
            )}
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
                  {hasDiscount && (
                    <>
                      <span className="text-sm text-gray-400 dark:text-gray-500 line-through font-semibold">
                        ₹{originalPrice}
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
                {isOutOfStock ? (
                  <div className="w-full sm:w-auto bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-extrabold text-xs sm:text-sm px-6 py-3 rounded-xl flex items-center justify-center gap-2 cursor-not-allowed uppercase tracking-wider border border-slate-300/50 dark:border-slate-700/50">
                    <span>OUT OF STOCK</span>
                  </div>
                ) : !isDeliverable ? (
                  <div className="flex flex-col sm:flex-row items-center gap-2">
                    <div className="w-full sm:w-auto bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-bold text-xs sm:text-sm px-4 py-3 rounded-xl flex items-center justify-center gap-1.5 cursor-not-allowed border border-amber-200 dark:border-amber-800/60 uppercase tracking-wide">
                      <span>NOT DELIVERABLE TO {activePincode}</span>
                    </div>
                    <button
                      type="button"
                      onClick={() => setIsPincodeModalOpen(true)}
                      className="text-xs text-red-600 dark:text-red-400 underline font-bold hover:no-underline cursor-pointer py-1"
                    >
                      Change Pincode
                    </button>
                  </div>
                ) : quantity === 0 ? (
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
                      disabled={quantity >= stockQty || quantity >= maxQty}
                      className={`px-4 py-2.5 transition-colors font-extrabold text-base ${quantity >= stockQty || quantity >= maxQty ? 'opacity-40 cursor-not-allowed' : 'hover:bg-[#8f0a0a] active:bg-[#7a0808]'}`}
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
