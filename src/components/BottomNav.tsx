import React from 'react';
import { useApp } from '../context/AppContext';
import { Home, LayoutGrid, ShoppingBag, Tag, User, Package } from 'lucide-react';

export const BottomNav: React.FC = () => {
  const { currentPage, navigateTo, cartCount, user } = useApp();

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
      label: 'Categories',
      icon: LayoutGrid,
      isActive: currentPage === 'categories' || currentPage === 'category-view',
      onClick: () => navigateTo('categories')
    },
    {
      id: 'orders',
      label: 'Orders',
      icon: Package,
      isActive: false,
      onClick: () => navigateTo(user ? 'profile' : 'login')
    },
    {
      id: 'cart',
      label: 'Cart',
      icon: ShoppingBag,
      isActive: currentPage === 'cart',
      onClick: () => navigateTo('cart'),
      badgeCount: cartCount
    },
    {
      id: 'profile',
      label: user ? 'Account' : 'Login',
      icon: User,
      isActive: currentPage === 'profile' || currentPage === 'login',
      onClick: () => navigateTo(user ? 'profile' : 'login')
    }
  ];

  if (currentPage === 'onepager') {
    return null;
  }

  return (
    <div className="md:hidden fixed bottom-0 left-0 right-0 w-full z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-gray-100 dark:border-slate-800 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] select-none transition-colors duration-200">
      <div className="grid grid-cols-5 h-14 max-w-md mx-auto">
        {navItems.map((item) => {
          const Icon = item.icon;
          const active = item.isActive;
          const isCart = item.id === 'cart';

          return (
            <button
              key={item.id}
              onClick={item.onClick}
              id={`btn-nav-${item.id}`}
              className={`relative flex flex-col items-center justify-center transition-all duration-200 active:scale-95 group ${
                active
                  ? 'text-[#fc490f] font-black'
                  : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 font-medium'
              }`}
            >
              {/* Top Active Bar Highlight Indicator */}
              {active && (
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-1 bg-[#fc490f] rounded-b-full shadow-sm animate-fadeIn" />
              )}

              {/* Icon Container with Badge */}
              <div className="relative flex items-center justify-center">
                <Icon className={`w-5 h-5 transition-transform duration-200 ${
                  active ? 'stroke-[2.5px] text-[#fc490f]' : 'stroke-[1.8px] group-hover:scale-105'
                }`} />

                {/* Cart Badge */}
                {isCart && item.badgeCount !== undefined && item.badgeCount > 0 && (
                  <span className="absolute -top-1.5 -right-2.5 bg-[#fc490f] text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-sm animate-bounce border-2 border-white dark:border-slate-900 leading-none">
                    {item.badgeCount}
                  </span>
                )}
              </div>

              {/* Label */}
              <span className={`text-[10px] tracking-tight mt-0.5 transition-colors ${
                active ? 'font-bold text-[#fc490f]' : 'text-gray-500 dark:text-gray-400'
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

