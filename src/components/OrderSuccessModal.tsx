import React, { useEffect } from 'react';
import { useApp } from '../context/AppContext';
import { CheckCircle2, Clock, MapPin, PackageCheck, ShoppingBag, ArrowRight } from 'lucide-react';
import { trackPurchase } from '../utils/tracking';

export const OrderSuccessModal: React.FC = () => {
  const {
    showOrderSuccessModal,
    setShowOrderSuccessModal,
    lastPlacedOrder,
    navigateTo
  } = useApp();

  useEffect(() => {
    if (showOrderSuccessModal && lastPlacedOrder) {
      trackPurchase(lastPlacedOrder);
    }
  }, [showOrderSuccessModal, lastPlacedOrder?.id]);

  if (!showOrderSuccessModal || !lastPlacedOrder) return null;

  const handleTrackOrder = () => {
    setShowOrderSuccessModal(false);
    navigateTo('profile');
  };

  const handleContinueShopping = () => {
    setShowOrderSuccessModal(false);
    navigateTo('home');
  };

  return (
    <div className="fixed inset-0 z-50 bg-black/70 backdrop-blur-xs flex items-center justify-center p-4 animate-fadeIn select-none">
      <div className="w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden transform animate-scaleUp">
        
        {/* Top Celebration Banner */}
        <div className="bg-gradient-to-br from-[#fc490f] via-orange-500 to-[#F97316] p-6 text-white text-center relative overflow-hidden">
          <div className="absolute -right-6 -bottom-6 opacity-15 pointer-events-none">
            <PackageCheck className="w-36 h-36" />
          </div>
          
          <div className="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg animate-bounce">
            <CheckCircle2 className="w-10 h-10 text-[#fc490f]" />
          </div>

          <h3 className="font-sans font-black text-2xl tracking-tight text-white">
            Order Confirmed! 🎉
          </h3>
          <p className="text-xs text-orange-100 font-medium mt-1">
            Your fresh catch is being packed & prepared.
          </p>
        </div>

        {/* Order Details Body */}
        <div className="p-5 space-y-4">
          
          {/* Order ID & Estimated Delivery Card */}
          <div className="bg-orange-50/60 dark:bg-slate-800/50 rounded-2xl p-4 border border-orange-100 dark:border-slate-800 space-y-2.5">
            <div className="flex items-center justify-between text-xs pb-2 border-b border-orange-100 dark:border-slate-700/60">
              <span className="text-gray-500 dark:text-gray-400 font-medium">Order Reference ID</span>
              <span className="font-mono font-black text-[#fc490f] text-sm">{lastPlacedOrder.id}</span>
            </div>

            <div className="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-200 pt-0.5">
              <Clock className="w-4 h-4 text-[#fc490f] shrink-0" />
              <div>
                <span className="font-bold block">Estimated Express Delivery</span>
                <span className="text-[11px] text-gray-500 dark:text-gray-400">Dispatching in 30 - 45 Mins</span>
              </div>
            </div>

            {lastPlacedOrder.address && (
              <div className="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-200 pt-1">
                <MapPin className="w-4 h-4 text-[#fc490f] shrink-0" />
                <div className="truncate">
                  <span className="font-bold block">Delivery Address</span>
                  <span className="text-[11px] text-gray-500 dark:text-gray-400 truncate block">
                    {lastPlacedOrder.address.addressLine || lastPlacedOrder.address.name}, {lastPlacedOrder.address.city || ''}
                  </span>
                </div>
              </div>
            )}
          </div>

          {/* Payment & Amount Summary */}
          <div className="flex items-center justify-between px-3 py-2 bg-gray-50 dark:bg-slate-800/30 rounded-xl border border-gray-100 dark:border-slate-800 text-xs">
            <div>
              <span className="text-gray-400 text-[10px] uppercase font-bold block">Payment Mode</span>
              <span className="font-bold text-gray-800 dark:text-gray-200 uppercase">{lastPlacedOrder.paymentMethod || 'COD'}</span>
            </div>
            <div className="text-right">
              <span className="text-gray-400 text-[10px] uppercase font-bold block">Total Amount Paid</span>
              <span className="font-black text-gray-900 dark:text-white text-base">₹{lastPlacedOrder.totalPrice}</span>
            </div>
          </div>

          {/* Actions */}
          <div className="space-y-2 pt-2">
            <button
              onClick={handleTrackOrder}
              className="w-full bg-[#fc490f] hover:bg-orange-600 text-white font-bold text-sm py-3 rounded-xl flex items-center justify-center gap-2 shadow-lg transition-all active:scale-95 cursor-pointer"
              id="btn-modal-track-order"
            >
              <span>TRACK ORDER STATUS</span>
              <ArrowRight className="w-4 h-4" />
            </button>

            <button
              onClick={handleContinueShopping}
              className="w-full bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs py-2.5 rounded-xl transition-colors cursor-pointer"
              id="btn-modal-continue-shopping"
            >
              Continue Shopping
            </button>
          </div>

        </div>

      </div>
    </div>
  );
};
