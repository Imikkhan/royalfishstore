import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { PincodeModal } from './PincodeModal';
import { Menu, Search, ShoppingBag, MapPin, Bell, SlidersHorizontal, User } from 'lucide-react';

interface HeaderProps {
  onOpenSidebar: () => void;
}

export const Header: React.FC<HeaderProps> = ({ onOpenSidebar }) => {
  const {
    currentPage,
    navigateTo,
    cartCount,
    searchQuery,
    setSearchQuery,
    activePincode,
    isPincodeModalOpen,
    setIsPincodeModalOpen,
    user
  } = useApp();

  return (
    <header className="sticky top-0 z-40 w-full shadow-none select-none transition-colors duration-200">
      
      {/* Pincode Location Selector Modal */}
      <PincodeModal 
        isOpen={isPincodeModalOpen} 
        onClose={() => setIsPincodeModalOpen(false)} 
      />

      {/* Primary Header Bar (Solid Vibrant Royal Orange #fc490f) */}
      <div className="bg-[#fc490f] text-white px-3.5 sm:px-6 pt-1.5 pb-2.5 space-y-1 shadow-sm">
        
        {/* Top Row: Hamburger | Logo | Location | Bell | Cart */}
        <div className="flex items-center justify-between gap-2 max-w-7xl mx-auto">
          
          {/* Left: Hamburger & Logo */}
          <div className="flex items-center gap-2">
            <button
              onClick={onOpenSidebar}
              className="p-1 -ml-1 rounded-lg hover:bg-white/10 text-white transition-colors"
              aria-label="Open Menu"
              id="btn-hamburger"
            >
              <Menu className="w-6 h-6 sm:w-7 sm:h-7 stroke-[2.5]" />
            </button>
            
            <div 
              onClick={() => navigateTo('home')} 
              className="flex items-center cursor-pointer group"
            >
              <img 
                src="/logo.png" 
                alt="Royal Fish Store" 
                className="h-16 sm:h-20 w-auto object-contain transform group-hover:scale-105 transition-transform" 
              />
            </div>
          </div>

          {/* Middle-Right: Deliver to Location Selector */}
          <div 
            onClick={() => setIsPincodeModalOpen(true)}
            className="flex items-center gap-1.5 text-xs text-white/95 hover:text-white cursor-pointer py-1 px-2 rounded-full bg-white/15 hover:bg-white/25 border border-white/20 transition-all"
            id="btn-select-pincode"
          >
            <MapPin className="w-3.5 h-3.5 text-white shrink-0 fill-white/20" />
            <div className="flex flex-col text-[10px] leading-tight">
              <span className="text-[8px] text-orange-100">Deliver to</span>
              <span className="font-bold truncate max-w-[90px] sm:max-w-none">{activePincode || 'Select Pincode'} ▾</span>
            </div>
          </div>

          {/* Right: Notifications Bell & Shopping Cart & User Profile */}
          <div className="flex items-center gap-2">

            {/* Notification / Orders Bell Icon */}
            <button
              onClick={() => navigateTo(user ? 'profile' : 'login')}
              className="p-1 rounded-full hover:bg-white/10 text-white relative transition-colors"
              aria-label="Notifications"
              title="Order Updates & Notifications"
            >
              <Bell className="w-5 h-5 sm:w-6 sm:h-6" />
            </button>

            {/* User Account / Profile Button */}
            <button
              onClick={() => navigateTo(user ? 'profile' : 'login')}
              className="p-1 rounded-full hover:bg-white/10 text-white relative transition-colors flex items-center gap-1"
              aria-label={user ? 'My Profile' : 'Login'}
              id="btn-header-user"
              title={user ? `${user.name} (Account)` : 'Login / Register'}
            >
              <User className="w-5 h-5 sm:w-6 sm:h-6" />
              {user && (
                <span className="hidden md:inline text-[11px] font-bold bg-white/20 px-2 py-0.5 rounded-full max-w-[90px] truncate">
                  {user.name.split(' ')[0]}
                </span>
              )}
            </button>

            {/* Shopping Cart Icon with Dynamic Badge */}
            <button
              onClick={() => navigateTo('cart')}
              className="p-1 rounded-full hover:bg-white/10 text-white relative transition-all"
              aria-label="View Cart"
              id="btn-cart-header"
            >
              <ShoppingBag className="w-5 h-5 sm:w-6 sm:h-6" />
              {cartCount > 0 && (
                <span className="absolute -top-1 -right-1 bg-white text-[#fc490f] text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center border border-white shadow-sm">
                  {cartCount}
                </span>
              )}
            </button>

          </div>

        </div>

        {/* Embedded White Search Input Bar with Filter Icon */}
        <div className="max-w-7xl mx-auto relative pt-0">
          <div className="relative flex items-center w-full">
            <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
              <Search className="w-4 h-4 text-gray-400" />
            </div>
            <input
              type="text"
              placeholder="Search for fish, chicken, prawns, mutton..."
              value={searchQuery}
              onClick={() => {
                if (currentPage !== 'search') navigateTo('search');
              }}
              onFocus={() => {
                if (currentPage !== 'search') navigateTo('search');
              }}
              onChange={(e) => {
                setSearchQuery(e.target.value);
                if (currentPage !== 'search') navigateTo('search');
              }}
              className="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm bg-white text-gray-900 placeholder-gray-400 rounded-2xl shadow-inner focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all font-medium"
            />
            {/* Filter icon inside search bar on right */}
            <button 
              onClick={() => {
                if (searchQuery) setSearchQuery('');
              }}
              className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
              aria-label="Filter"
            >
              {searchQuery ? (
                <span className="text-xs font-bold bg-gray-200 rounded-full w-4 h-4 flex items-center justify-center">✕</span>
              ) : (
                <SlidersHorizontal className="w-4 h-4 text-gray-600" />
              )}
            </button>
          </div>
        </div>

      </div>

    </header>
  );
};


