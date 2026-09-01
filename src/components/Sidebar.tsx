import React from 'react';
import { useApp } from '../context/AppContext';
// Dynamic categories imported via context
import { X, Home, ShoppingBag, User, Phone, MapPin, Award, ShieldAlert, Heart, Star, Layers } from 'lucide-react';

interface SidebarProps {
  isOpen: boolean;
  onClose: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({ isOpen, onClose }) => {
  const {
    navigateTo,
    setSelectedCategory,
    theme,
    toggleTheme,
    cartCount,
    user,
    categories
  } = useApp();

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-hidden">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity duration-300"
        onClick={onClose}
      />

      {/* Drawer content */}
      <div className="absolute inset-y-0 left-0 max-w-xs w-full bg-white dark:bg-slate-900 shadow-2xl flex flex-col justify-between py-6 px-4 z-50 transform transition-transform duration-300 ease-out">
        <div>
          {/* Header */}
          <div className="flex items-center justify-between pb-6 border-b border-gray-100 dark:border-slate-800">
            <div className="flex items-center gap-2.5">
              <img src="/logo.png" alt="Royal Fish Store Logo" className="h-10 w-auto object-contain" />
            </div>
            <button
              onClick={onClose}
              className="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
              aria-label="Close menu"
              id="btn-close-sidebar"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Quick Stats/Badge */}
          <div className="my-5 p-3 rounded-xl bg-red-50/50 dark:bg-slate-800/50 border border-red-100/40 dark:border-slate-800">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="text-xl">💯</span>
                <div>
                  <h4 className="text-xs font-bold text-gray-800 dark:text-gray-100">
                    100% Fresh Guarantee
                  </h4>
                  <p className="text-[10px] text-gray-500 dark:text-gray-400">
                    No frozen stock, direct catch daily
                  </p>
                </div>
              </div>
            </div>
          </div>

          {/* Main Navigation links */}
          <nav className="space-y-1.5">
            <button
              onClick={() => {
                navigateTo('home');
                setSelectedCategory(null);
                onClose();
              }}
              className="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 rounded-xl transition-colors"
              id="sidebar-link-home"
            >
              <Home className="w-4 h-4 text-gray-400" />
              <span>Home Screen</span>
            </button>

            <button
              onClick={() => {
                navigateTo('onepager');
                onClose();
              }}
              className="w-full flex items-center justify-between px-3 py-2 text-sm font-bold text-[#fc490f] bg-orange-50/70 dark:bg-orange-950/30 hover:bg-orange-100 rounded-xl transition-colors border border-orange-200/50"
              id="sidebar-link-onepager"
            >
              <div className="flex items-center gap-3">
                <span className="text-base">🔥</span>
                <span>FB Special Deals (Onepager)</span>
              </div>
              <span className="bg-red-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full">
                20% OFF
              </span>
            </button>

            <button
              onClick={() => {
                navigateTo('categories');
                onClose();
              }}
              className="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 rounded-xl transition-colors"
              id="sidebar-link-categories"
            >
              <Layers className="w-4 h-4 text-gray-400" />
              <span>All Categories</span>
            </button>

            <button
              onClick={() => {
                navigateTo('cart');
                onClose();
              }}
              className="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 rounded-xl transition-colors"
              id="sidebar-link-cart"
            >
              <div className="flex items-center gap-3">
                <ShoppingBag className="w-4 h-4 text-gray-400" />
                <span>My Shopping Cart</span>
              </div>
              {cartCount > 0 && (
                <span className="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                  {cartCount}
                </span>
              )}
            </button>

            <button
              onClick={() => {
                navigateTo(user ? 'profile' : 'login');
                onClose();
              }}
              className="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 rounded-xl transition-colors"
              id="sidebar-link-profile"
            >
              <div className="flex items-center gap-3">
                <User className="w-4 h-4 text-gray-400" />
                <span>{user ? 'My Profile & Orders' : 'Login / Register'}</span>
              </div>
              {!user && (
                <span className="bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 text-[9px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">
                  New
                </span>
              )}
            </button>
          </nav>

          {/* Category quick filters */}
          <div className="mt-6">
            <h3 className="px-3 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
              Browse Seafood & Meat
            </h3>
            <div className="space-y-1">
              {categories.map(cat => {
                const catId = cat.slug || cat.id;
                return (
                <button
                  key={cat.id}
                  onClick={() => {
                    navigateTo('home');
                    setSelectedCategory(catId);
                    onClose();
                    // Smooth scroll down to products section
                    const el = document.getElementById('products-section');
                    if (el) el.scrollIntoView({ behavior: 'smooth' });
                  }}
                  className="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50/30 dark:hover:bg-slate-800/40 rounded-xl transition-colors"
                >
                  <span className="text-base">{cat.icon}</span>
                  <span className="font-medium text-xs">{cat.name}</span>
                </button>
              );
              })}
            </div>
          </div>
        </div>

        {/* Footer info & Offline Toggle */}
        <div className="space-y-4 pt-6 border-t border-gray-100 dark:border-slate-800">
          <div className="text-xs text-gray-400 dark:text-gray-500 px-2 space-y-2">
            <div className="flex items-center gap-2">
              <Phone className="w-3.5 h-3.5 text-gray-400" />
              <span>Support: 1800-419-786</span>
            </div>
            <div className="flex items-center gap-2">
              <MapPin className="w-3.5 h-3.5 text-gray-400" />
              <span>royalfishstore.com</span>
            </div>
          </div>

          <div className="text-center text-[10px] text-gray-400 px-2">
            © 2026 Royal Fish Store Pvt Ltd.
            <div className="mt-1 font-mono text-[9px] text-gray-500">
              Inspired by Licious Quality
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
