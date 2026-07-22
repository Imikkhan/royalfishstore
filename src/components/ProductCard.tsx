import React from 'react';
import { Product } from '../types';
import { useApp } from '../context/AppContext';
import { Star, Clock, Plus, Minus, ShoppingCart, Truck } from 'lucide-react';

interface ProductCardProps {
  product: Product;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  const { navigateTo, addToCart, removeFromCart, getCartQuantity, activePincode } = useApp();
  const quantity = getCartQuantity(product.id);
  const isDeliverable = product.isDeliverable !== false;

  // Calculate percentage off
  const discountPercent = Math.round(
    ((product.originalPrice - product.price) / product.originalPrice) * 100
  );

  const handleCardClick = () => {
    navigateTo('product-details', product.id);
  };

  const handleAdd = (e: React.MouseEvent) => {
    e.stopPropagation();
    addToCart(product);
  };

  const handleSubtract = (e: React.MouseEvent) => {
    e.stopPropagation();
    removeFromCart(product.id);
  };

  return (
    <div 
      onClick={handleCardClick}
      className="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden cursor-pointer group"
      id={`product-card-${product.id}`}
    >
      {/* Top Image Container */}
      <div className="relative aspect-video w-full overflow-hidden bg-gray-50 dark:bg-slate-800">
        <img
          src={product.image}
          alt={product.name}
          referrerPolicy="no-referrer"
          className="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
          loading="lazy"
        />
        
        {/* Rating overlay */}
        <div className="absolute bottom-2.5 left-2.5 bg-white/90 dark:bg-slate-900/95 backdrop-blur-xs px-2 py-0.5 rounded-md flex items-center gap-1 text-[10px] font-bold text-gray-800 dark:text-gray-100 shadow-sm">
          <Star className="w-3 h-3 text-amber-500 fill-amber-500" />
          <span>{product.rating}</span>
          <span className="text-gray-400 font-normal">({product.reviewsCount})</span>
        </div>

        {/* Tags Overlay */}
        {(() => {
          let tagsArray: string[] = [];
          if (product.tags) {
            if (Array.isArray(product.tags)) {
              tagsArray = product.tags;
            } else if (typeof product.tags === 'string') {
              try {
                const parsed = JSON.parse(product.tags);
                if (Array.isArray(parsed)) {
                  tagsArray = parsed;
                } else {
                  tagsArray = product.tags.split(',').map(t => t.trim()).filter(Boolean);
                }
              } catch (e) {
                tagsArray = product.tags.split(',').map(t => t.trim()).filter(Boolean);
              }
            }
          }
          if (tagsArray.length === 0) return null;
          return (
            <div className="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
              {tagsArray.slice(0, 1).map((tag, idx) => (
                <span 
                  key={idx}
                  className="bg-red-600 text-white text-[9px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-md shadow-sm"
                >
                  {tag}
                </span>
              ))}
            </div>
          );
        })()}

        {/* Discount Tag Overlay */}
        {discountPercent > 0 && (
          <span className="absolute top-2.5 right-2.5 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
            {discountPercent}% OFF
          </span>
        )}
      </div>

      {/* Product Content Details */}
      <div className="p-4 flex-1 flex flex-col justify-between gap-3.5">
        
        {/* Title, Quantities */}
        <div className="space-y-2">
          {/* Larger Font for product title */}
          <h4 className="font-sans font-extrabold text-gray-950 dark:text-white text-base sm:text-lg line-clamp-2 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors leading-snug">
            {product.name}
          </h4>
          
          {/* Metadata Specs (Weight, pieces, servings with larger size and explicit fields like Gross & Net weight) */}
          <div className="flex flex-col gap-1 text-xs sm:text-[13px] text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
            <div>
              <span className="font-bold text-gray-700 dark:text-gray-300">Gross : </span>
              <span>{product.grossWeight || `${product.weight}`}</span>
              <span className="mx-1.5">•</span>
              <span className="font-bold text-gray-700 dark:text-gray-300">Net weight </span>
              <span>{product.netWeight || (product.weight.includes('500g') ? '450g-480g' : product.weight.includes('250g') ? '220g-240g' : product.weight)}</span>
            </div>
            <div>
              <span>{product.piecesAfterCutting || (product.pieces !== '1 Fillet' && product.pieces !== 'Finely Minced' ? `${product.pieces} after cutting.` : product.pieces)}</span>
            </div>
          </div>
          
          <p className="text-xs text-gray-500 dark:text-gray-500 line-clamp-2 leading-relaxed">
            {product.description}
          </p>
        </div>

        {/* Bottom Price and Addition Trigger row */}
        <div className="space-y-3">
          <div className="flex items-center justify-between pt-2.5 border-t border-gray-100 dark:border-slate-800">
            <div className="flex flex-wrap items-baseline gap-2">
              {/* Turquoise-ish / teal price as in the reference photo */}
              <span className="text-xl sm:text-2xl font-black text-[#00a6c0] dark:text-[#0da1b9]">
                ₹{product.price}
              </span>
              {product.originalPrice > product.price && (
                <>
                  <span className="text-sm sm:text-base text-gray-400 dark:text-gray-500 line-through font-semibold">
                    ₹{product.originalPrice}
                  </span>
                  <span className="bg-[#0da1b9] text-white text-[10px] sm:text-[11px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider shadow-2xs">
                    {discountPercent}% OFF
                  </span>
                </>
              )}
            </div>

            {/* Add / Subtract Cart Quantity Control */}
            <div className="relative shrink-0">
              {quantity === 0 ? (
                <button
                  onClick={handleAdd}
                  className="bg-[#a80e0e] hover:bg-[#8f0a0a] text-white font-extrabold text-xs sm:text-sm px-4 py-2 rounded-xl flex items-center gap-1.5 transition-all active:scale-95 shadow-sm uppercase tracking-wide cursor-pointer"
                  id={`add-btn-${product.id}`}
                >
                  <span>ADD</span>
                  <ShoppingCart className="w-4 h-4" />
                </button>
              ) : (
                <div 
                  className="bg-[#a80e0e] text-white font-bold text-xs sm:text-sm rounded-xl flex items-center shadow-md overflow-hidden border border-[#a80e0e]"
                  id={`qty-ctrl-${product.id}`}
                >
                  <button
                    onClick={handleSubtract}
                    className="px-2.5 py-2 hover:bg-[#8f0a0a] active:bg-[#7a0808] transition-colors"
                    aria-label="Decrease quantity"
                  >
                    <Minus className="w-3.5 h-3.5" />
                  </button>
                  <span className="px-3 font-sans font-bold select-none min-w-[24px] text-center">
                    {quantity}
                  </span>
                  <button
                    onClick={handleAdd}
                    className="px-2.5 py-2 hover:bg-[#8f0a0a] active:bg-[#7a0808] transition-colors"
                    aria-label="Increase quantity"
                  >
                    <Plus className="w-3.5 h-3.5" />
                  </button>
                </div>
              )}
            </div>
          </div>

          {/* Delivery Box & Pincode Status */}
          <div className={`flex items-center justify-between gap-2 px-3 py-2 rounded-xl border text-xs font-medium ${
            isDeliverable 
              ? 'bg-gray-50 dark:bg-slate-800/80 border-gray-100/60 dark:border-slate-800 text-gray-500 dark:text-gray-400' 
              : 'bg-amber-50 dark:bg-amber-950/30 border-amber-200/50 text-amber-700 dark:text-amber-400'
          }`}>
            <div className="flex items-center gap-1.5 truncate">
              <Truck className={`w-4 h-4 shrink-0 ${isDeliverable ? 'text-emerald-500' : 'text-amber-500'}`} />
              <span className="truncate">{isDeliverable ? (product.deliveryTime || '30-45 Mins Express') : `Not in ${activePincode}`}</span>
            </div>
            <span className="text-[10px] font-bold text-gray-400 dark:text-gray-500 font-mono shrink-0">
              📍 {activePincode}
            </span>
          </div>
        </div>

      </div>
    </div>
  );
};
