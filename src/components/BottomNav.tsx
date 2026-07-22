import React from 'react';
import { useApp } from '../context/AppContext';
import { Home, LayoutGrid, ShoppingBag, User } from 'lucide-react';

export const BottomNav: React.FC = () => {
  const { currentPage, navigateTo, cartCount, cartTotal, user } = useApp();

  const navItems = [
    {
      id: 'home',
      label: 'Home',
      icon: Home,
      isActive: currentPage === 'home',
      onClick: () => navigateTo('home')
    },
    {
      id: 'categories',
      label: 'Category',
      icon: LayoutGrid,
      isActive: currentPage === 'categories' || currentPage === 'category-view',
      onClick: () => navigateTo('categories')
    },
    {
      id: 'cart',
      label: cartCount > 0 ? `₹${cartTotal}` : 'Cart',
      icon: ShoppingBag,
      isActive: currentPage === 'cart',
      onClick: () => navigateTo('cart'),
      badgeCount: cartCount
    },
    {
      id: 'profile',
      label: user ? 'Account' : 'Profile',
      icon: User,
      isActive: currentPage === 'profile' || currentPage === 'login',
      onClick: () => navigateTo(user ? 'profile' : 'login')
    }
  ];

  return (
    <div className="md:hidden fixed bottom-0 left-0 right-0 w-full z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-gray-100 dark:border-slate-800/80 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] select-none transition-colors duration-200">
      <div className="grid grid-cols-4 h-16 max-w-md mx-auto">
        {navItems.map((item) => {
          const Icon = item.icon;
          const active = item.isActive;
          const isCart = item.id === 'cart';
          const isCategory = item.id === 'categories';

          return (
            <button
              key={item.id}
              onClick={item.onClick}
              id={`btn-nav-${item.id}`}
              className={`relative flex flex-col items-center justify-center transition-all duration-200 active:scale-95 group ${
                active
                  ? 'text-red-600 dark:text-red-400 font-extrabold'
                  : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 font-medium'
              }`}
            >
              {/* Top Active Bar Highlight Indicator */}
              {active && (
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-9 h-1 bg-gradient-to-r from-red-600 via-rose-600 to-amber-500 rounded-b-full shadow-[0_2px_8px_rgba(220,38,38,0.5)] animate-fadeIn" />
              )}

              {/* Icon Container with Badge */}
              <div className="relative flex items-center justify-center mt-1">
                <div className={`p-1 rounded-xl transition-all duration-200 ${
                  active ? 'bg-red-50 dark:bg-red-950/50 scale-110' : ''
                }`}>
                  <Icon className={`w-5 h-5 transition-transform duration-200 ${
                    active ? 'stroke-[2.5px]' : 'stroke-[1.8px] group-hover:scale-105'
                  }`} />
                </div>

                {/* Cart Badge */}
                {isCart && item.badgeCount !== undefined && item.badgeCount > 0 && (
                  <span className="absolute -top-1 -right-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-sm animate-pulse min-w-[16px] text-center border-2 border-white dark:border-slate-900 leading-none">
                    {item.badgeCount}
                  </span>
                )}

                {/* Category indicator dot when inactive */}
                {isCategory && !active && (
                  <span className="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900" />
                )}
              </div>

              {/* Label */}
              <span className={`text-[10px] tracking-tight mt-0.5 transition-colors ${
                active ? 'font-bold text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'
              }`}>
                {item.label}
              </span>
            </button>
          );
        })}
      </div>
    </div>
  );
};
