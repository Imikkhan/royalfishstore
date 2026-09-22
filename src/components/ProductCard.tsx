import React from 'react';
import { Product } from '../types';
import { useApp } from '../context/AppContext';
import { Snowflake, Plus, Minus, ShoppingCart, Truck } from 'lucide-react';

interface ProductCardProps {
  product: Product;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  const { navigateTo, addToCart, removeFromCart, getCartQuantity, activePincode, showToast } = useApp();
  const quantity = getCartQuantity(product.id);
  const isDeliverable = (product.isDeliverable !== false) && (
    !activePincode || 
    !product.servicedPincodes || 
    product.servicedPincodes.length === 0 || 
    product.servicedPincodes.includes('*') || 
    product.servicedPincodes.includes(activePincode)
  );
  const isOutOfStock = Boolean(product.isOutOfStock || (product.stockQuantity !== undefined && product.stockQuantity <= 0) || product.inStock === false || (product as any).in_stock === false);
  const stockQty = product.stockQuantity !== undefined ? Number(product.stockQuantity) : 50;
  const lowThreshold = Number(product.lowStockThreshold || (product as any).low_stock_threshold || 5);
  const isLowStock = !isOutOfStock && (Boolean(product.isLowStock) || (stockQty > 0 && stockQty <= lowThreshold));
  const minQty = Math.max(1, Number(product.minOrderQty || (product as any).min_order_qty || 1));
  const maxQty = Math.max(1, Number(product.maxOrderQty || (product as any).max_order_qty || 10));

  const originalPrice = Number(product.originalPrice || (product as any).original_price || 0);
  const price = Number(product.price || 0);
  const hasDiscount = originalPrice > price && originalPrice > 0;
  const discountPercent = hasDiscount
    ? Math.round(((originalPrice - price) / originalPrice) * 100)
    : 0;

  const handleCardClick = () => {
    navigateTo('product-details', product.id, product);
  };

  const handleAdd = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (isOutOfStock) return;
    if (!isDeliverable) {
      showToast(`${product.name} is not deliverable to pincode ${activePincode}.`, 'warning');
      return;
    }
    addToCart(product);
  };

  const handleSubtract = (e: React.MouseEvent) => {
    e.stopPropagation();
    removeFromCart(product.id);
  };

  return (
    <div 
      onClick={handleCardClick}
      className={`w-full h-full bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden cursor-pointer group select-none ${isOutOfStock ? 'opacity-85' : ''}`}
      id={`product-card-${product.id}`}
    >
      {/* Top Image Container */}
      <div className="relative aspect-4/3 w-full overflow-hidden bg-gray-50 dark:bg-slate-800 shrink-0">
        <img
          src={product.image}
          alt={product.name}
          referrerPolicy="no-referrer"
          className={`w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ${isOutOfStock ? 'grayscale-[40%]' : ''}`}
          loading="lazy"
        />
        
        {/* Top-Left: Snowflake Freshness Badge or Out of Stock / Undeliverable Tag */}
        {isOutOfStock ? (
          <div className="absolute top-2.5 left-2.5 bg-slate-950/90 text-white text-[10px] font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></span>
            <span>Sold Out</span>
          </div>
        ) : !isDeliverable ? (
          <div className="absolute top-2.5 left-2.5 bg-amber-600 text-white text-[9.5px] font-black px-2 py-0.5 rounded-md shadow-md uppercase tracking-wider">
            Not Deliverable
          </div>
        ) : (
          <div className="absolute top-2.5 left-2.5 bg-[#fc490f] text-white p-1.5 rounded-full shadow-md flex items-center justify-center">
            <Snowflake className="w-4 h-4 text-white animate-spin-slow" />
          </div>
        )}

        {/* Top-Right: Discount Badge or Low Stock Urgency */}
        {isOutOfStock ? (
          <span className="absolute top-2.5 right-2.5 bg-red-600/90 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
            Out of Stock
          </span>
        ) : isLowStock ? (
          <span className="absolute top-2.5 right-2.5 bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider animate-pulse">
            Only {stockQty} Left!
          </span>
        ) : discountPercent > 0 ? (
          <span className="absolute top-2.5 right-2.5 bg-[#fc490f] text-white text-xs font-black px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
            {discountPercent}% OFF
          </span>
        ) : null}
      </div>

      {/* Product Content Details */}
      <div className="p-3.5 flex-1 flex flex-col justify-between gap-2.5 overflow-hidden">
        
        {/* Title & Weight Specs (Fixed height blocks to prevent content overflow) */}
        <div className="space-y-1.5 overflow-hidden">
          <h4 className="font-sans font-extrabold text-gray-900 dark:text-white text-[15px] sm:text-[17px] line-clamp-2 leading-snug group-hover:text-[#fc490f] transition-colors min-h-[2.6rem] overflow-hidden">
            {product.name}
          </h4>
          
          {/* Metadata Specs (Short Description) */}
          {(product.shortDescription || product.short_description) && (
            <div className="text-[12.5px] sm:text-xs text-gray-700 dark:text-gray-300 leading-snug min-h-[2.4rem] overflow-hidden font-semibold">
              <div className="whitespace-pre-line line-clamp-2">
                {product.shortDescription || product.short_description}
              </div>
            </div>
          )}
        </div>

        {/* Bottom Price & Add to Cart Button (Pinned at bottom) */}
        <div className="space-y-2.5 pt-2 border-t border-gray-100 dark:border-slate-800 mt-auto shrink-0">
          <div className="flex items-baseline justify-between gap-1.5 flex-wrap min-h-[1.5rem] overflow-hidden">
            <div className="flex items-baseline gap-1.5">
              <span className="text-lg font-black text-gray-900 dark:text-white leading-none">
                ₹{product.price}
              </span>
              {hasDiscount && (
                <>
                  <span className="text-xs text-gray-400 line-through font-semibold leading-none">
                    ₹{originalPrice}
                  </span>
                  <span className="text-xs font-black text-[#fc490f] leading-none">
                    {discountPercent}% OFF
                  </span>
                </>
              )}
            </div>

            {/* Min / Max purchase limit tag if custom */}
            {(minQty > 1 || maxQty < 10) && !isOutOfStock && (
              <span className="text-[10px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">
                {minQty > 1 ? `Min ${minQty}` : ''}{minQty > 1 && maxQty < 10 ? ' | ' : ''}{maxQty < 10 ? `Max ${maxQty}` : ''}
              </span>
            )}
          </div>

          {/* Full Width Button / Controller */}
          <div>
            {isOutOfStock ? (
              <div 
                className="w-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-extrabold text-xs sm:text-sm py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 uppercase tracking-wide cursor-not-allowed select-none border border-slate-200/50 dark:border-slate-700/50"
                id={`sold-out-btn-${product.id}`}
              >
                <span>OUT OF STOCK</span>
              </div>
            ) : !isDeliverable ? (
              <div 
                className="w-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-bold text-xs py-2 px-2.5 rounded-xl flex items-center justify-center gap-1 uppercase tracking-wide cursor-not-allowed select-none border border-amber-200 dark:border-amber-800/60"
                title={`Not deliverable to ${activePincode}`}
              >
                <span>NOT DELIVERABLE</span>
              </div>
            ) : quantity === 0 ? (
              <button
                onClick={handleAdd}
                className="w-full bg-[#fc490f] hover:bg-orange-600 text-white font-bold text-xs sm:text-sm py-2 px-3 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95 shadow-xs uppercase tracking-wide cursor-pointer"
                id={`add-btn-${product.id}`}
              >
                <ShoppingCart className="w-4 h-4" />
                <span>ADD TO CART</span>
              </button>
            ) : (
              <div 
                className="w-full bg-[#fc490f] text-white font-bold text-xs sm:text-sm rounded-xl flex items-center justify-between shadow-xs overflow-hidden py-1 px-2"
                id={`qty-ctrl-${product.id}`}
              >
                <button
                  onClick={handleSubtract}
                  className="p-1 hover:bg-orange-600 rounded-lg transition-colors"
                  aria-label="Decrease quantity"
                >
                  <Minus className="w-4 h-4" />
                </button>
                <span className="font-bold text-xs sm:text-sm px-1">
                  {quantity} in Cart
                </span>
                <button
                  onClick={handleAdd}
                  disabled={quantity >= stockQty || quantity >= maxQty}
                  className={`p-1 rounded-lg transition-colors ${quantity >= stockQty || quantity >= maxQty ? 'opacity-40 cursor-not-allowed' : 'hover:bg-orange-600'}`}
                  aria-label="Increase quantity"
                >
                  <Plus className="w-4 h-4" />
                </button>
              </div>
            )}
          </div>

          {/* Delivery Time Slot Footer */}
          {(product.deliveryTime || product.delivery_time) && (
            <div className="pt-2 border-t border-gray-100 dark:border-slate-800 flex items-center justify-center gap-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300">
              <Truck className="w-4 h-4 text-[#00a6c0] dark:text-[#0da1b9] shrink-0" />
              <span>{product.deliveryTime || product.delivery_time}</span>
            </div>
          )}
        </div>

      </div>
    </div>
  );
};

