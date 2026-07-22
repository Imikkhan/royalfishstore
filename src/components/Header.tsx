import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { PincodeModal } from './PincodeModal';
import { Menu, Search, ShoppingBag, User, Sun, Moon, MapPin, ChevronDown, Check, Wifi, WifiOff } from 'lucide-react';

interface HeaderProps {
  onOpenSidebar: () => void;
}

export const Header: React.FC<HeaderProps> = ({ onOpenSidebar }) => {
  const {
    theme,
    toggleTheme,
    currentPage,
    navigateTo,
    cartCount,
    cartTotal,
    searchQuery,
    setSearchQuery,
    addresses,
    selectedAddressId,
    user,
    activeHeroIndex,
    activePincode,
    isPincodeModalOpen,
    setIsPincodeModalOpen
  } = useApp();

  const [addressDropdownOpen, setAddressDropdownOpen] = useState(false);

  const selectedAddress = addresses.find(a => a.id === selectedAddressId) || addresses[0];

  const mobileHeaderGradients = [
    'from-blue-700 via-blue-600 to-blue-600',
    'from-amber-800 via-amber-700 to-amber-700',
    'from-emerald-700 via-emerald-600 to-emerald-600'
  ];
  const currentHeaderGradient = theme === 'dark' ? 'from-slate-950 to-slate-900' : (mobileHeaderGradients[activeHeroIndex] || mobileHeaderGradients[0]);

  return (
    <header className="relative md:sticky md:top-0 z-40 w-full transition-colors duration-200 shadow-xs">
      
      {/* Pincode Location Selector Modal */}
      <PincodeModal 
        isOpen={isPincodeModalOpen} 
        onClose={() => setIsPincodeModalOpen(false)} 
      />

      {/* Desktop Header (MD size and up) */}
      <div className="hidden md:block bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16 gap-4">
            
            {/* Left: Hamburger & Logo */}
            <div className="flex items-center gap-3">
              <button
                onClick={onOpenSidebar}
                className="p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800 transition-colors"
                aria-label="Open Menu"
                id="btn-hamburger"
              >
                <Menu className="w-6 h-6" />
              </button>
              
              <div 
                onClick={() => navigateTo('home')} 
                className="flex items-center gap-3 cursor-pointer select-none group"
              >
                <div className="relative flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#7e0a0a] via-[#a80e0e] to-[#c21818] shadow-md border border-amber-400/25 transform group-hover:scale-105 transition-all duration-300 shrink-0 overflow-hidden">
                  {/* Subtle gold outer glow */}
                  <div className="absolute inset-0 rounded-2xl border border-amber-400/10" />
                  <svg className="w-6.5 h-6.5 text-amber-400 drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                    {/* Delivery Circular Ring showing speed/motion */}
                    <path d="M12 2a10 10 0 0 1 8 16" stroke="currentColor" strokeDasharray="3 3" />
                    <path d="M20 18a10 10 0 0 1-16-6" stroke="currentColor" />
                    {/* Express delivery arrow */}
                    <path d="M2 12l2-2 2 2" stroke="currentColor" />
                    {/* Beautiful Fish Swimming through the center */}
                    <path d="M8 12c1-2 3.5-3.5 6.5-3.5 2.5 0 4.5 1.5 5.5 3-1 1.5-3 3-5.5 3-3 0-5.5-1.5-6.5-3z" fill="currentColor" stroke="none" />
                    <path d="M8 12l-3-2v4z" fill="currentColor" stroke="none" />
                    {/* Fish eye */}
                    <circle cx="16" cy="11.5" r="0.75" fill="#7e0a0a" />
                  </svg>
                </div>
                <div className="flex flex-col">
                  <div className="flex items-baseline">
                    <span className="font-sans font-black tracking-tight text-lg sm:text-xl text-[#a80e0e] dark:text-[#f87171] leading-none uppercase">
                      Royal
                    </span>
                    <span className="font-sans font-black tracking-tight text-lg sm:text-xl text-slate-900 dark:text-white leading-none uppercase ml-0.5">
                      Fish
                    </span>
                    <span className="font-sans font-black tracking-tight text-lg sm:text-xl text-slate-900 dark:text-white leading-none uppercase ml-0.5">
                      Store
                    </span>
                  </div>
                  <span className="text-[8px] sm:text-[9px] font-mono tracking-[0.20em] text-[#a80e0e]/75 dark:text-amber-400 font-extrabold uppercase leading-none mt-1">
                    Online Fish Delivery
                  </span>
                </div>
              </div>
            </div>

            {/* Middle: Delivery Location (Pincode Trigger) */}
            <div className="hidden md:flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300 relative">
              <MapPin className="w-4 h-4 text-red-500 shrink-0 animate-bounce" />
              <button
                onClick={() => setIsPincodeModalOpen(true)}
                className="flex items-center gap-1.5 font-medium hover:text-red-600 dark:hover:text-red-400 bg-gray-50 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-slate-700 transition-all text-xs"
                id="btn-select-pincode"
              >
                <span>Delivering to: <strong className="text-gray-900 dark:text-white font-extrabold">{activePincode}</strong></span>
                <ChevronDown className="w-3.5 h-3.5 shrink-0" />
              </button>

              {addressDropdownOpen && (
                <div className="absolute top-full left-0 mt-2 w-72 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 py-2 z-50 animate-fadeIn">
                  <div className="px-4 py-2 border-b border-gray-100 dark:border-slate-700 font-semibold text-xs text-gray-400 uppercase tracking-wider">
                    Deliver To
                  </div>
                  {addresses.map(addr => (
                    <button
                      key={addr.id}
                      onClick={() => {
                        setAddressDropdownOpen(false);
                      }}
                      className="w-full text-left px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-slate-700 flex items-start gap-2.5 transition-colors"
                    >
                      <span className="text-lg mt-0.5">
                        {addr.type === 'Home' ? '🏠' : addr.type === 'Work' ? '💼' : '📍'}
                      </span>
                      <div className="flex-1 min-w-0">
                        <div className="font-semibold text-sm text-gray-800 dark:text-gray-100 flex items-center justify-between">
                          {addr.name}
                          {addr.id === selectedAddressId && <Check className="w-4 h-4 text-emerald-500" />}
                        </div>
                        <p className="text-xs text-gray-500 dark:text-gray-400 truncate">{addr.addressLine}</p>
                      </div>
                    </button>
                  ))}
                  <div className="p-2 border-t border-gray-100 dark:border-slate-700">
                    <button
                      onClick={() => {
                        navigateTo('profile');
                        setAddressDropdownOpen(false);
                      }}
                      className="w-full text-center text-xs font-semibold py-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-colors"
                    >
                      + Manage Addresses
                    </button>
                  </div>
                </div>
              )}
            </div>

            {/* Middle: Live Search (Tablet & Desktop only) */}
            <div className="hidden md:block flex-1 max-w-md mx-4 relative group">
              <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Search className="w-4 h-4 text-gray-400 group-focus-within:text-red-500 transition-colors" />
              </div>
              <input
                type="text"
                placeholder="Search fish, chicken, prawns, mutton..."
                value={searchQuery}
                onChange={(e) => {
                  setSearchQuery(e.target.value);
                  if (currentPage !== 'home') navigateTo('home');
                }}
                className="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 border border-transparent focus:border-red-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl focus:outline-none transition-all"
              />
            </div>

            {/* Right: Quick Controls */}
            <div className="flex items-center gap-1 sm:gap-3">
              {/* Dark Mode toggle */}
              <button
                onClick={toggleTheme}
                className="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800 transition-colors"
                aria-label="Toggle Theme"
                id="btn-theme-toggle"
              >
                {theme === 'dark' ? <Sun className="w-5 h-5 text-amber-400" /> : <Moon className="w-5 h-5" />}
              </button>

              {/* Cart Shortcut Widget */}
              <button
                onClick={() => navigateTo('cart')}
                className="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800 relative transition-all flex items-center gap-1 sm:gap-2 group"
                id="btn-cart-header"
              >
                <div className="relative">
                  <ShoppingBag className="w-5 h-5 text-gray-700 dark:text-gray-300 group-hover:scale-105 transition-transform" />
                  {cartCount > 0 && (
                    <span className="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none flex items-center justify-center animate-bounce min-w-[16px] h-[16px]">
                      {cartCount}
                    </span>
                  )}
                </div>
                <span className="text-xs font-bold text-gray-700 dark:text-gray-200 hidden sm:inline">
                  {cartCount > 0 ? `₹${cartTotal}` : '₹0'}
                </span>
              </button>

              {/* Profile Quick Link */}
              <button
                onClick={() => navigateTo(user ? 'profile' : 'login')}
                className="p-2 py-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800 transition-colors hidden sm:flex items-center gap-1.5 border border-transparent hover:border-gray-100 dark:hover:border-slate-800"
                aria-label="View Profile"
                id="btn-profile-header"
              >
                <User className="w-5 h-5 text-gray-600 dark:text-gray-300" />
                <span className="text-xs font-bold text-gray-700 dark:text-gray-200">
                  {user ? `Hi, ${user.name.split(' ')[0]}` : 'Login'}
                </span>
              </button>
            </div>

          </div>
        </div>
      </div>

      {/* Mobile Header (Highly integrated design like Blinkit) */}
      <div className={`block md:hidden bg-gradient-to-b ${currentHeaderGradient} dark:from-slate-950 dark:to-slate-900 text-white px-4 pt-3 pb-3.5 space-y-3.5 select-none transition-all duration-500`}>
        
        {/* Row 1: Left (Menu & Logo) / Right (Profile & Cart) */}
        <div className="flex items-center justify-between gap-3">
          
          <div className="flex items-center gap-2">
            <button
              onClick={onOpenSidebar}
              className="p-1.5 -ml-1 rounded-lg hover:bg-white/10 text-white transition-colors"
              aria-label="Open Menu"
            >
              <Menu className="w-6 h-6" />
            </button>
            
            <div 
              onClick={() => navigateTo('home')} 
              className="flex items-center gap-2.5 cursor-pointer select-none group"
            >
              <div className="relative flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-tr from-[#7e0a0a] via-[#a80e0e] to-[#c21818] shadow-md border border-amber-400/30 transform group-hover:scale-105 transition-all shrink-0 overflow-hidden">
                <svg className="w-5.5 h-5.5 text-amber-300 drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                  {/* Delivery Circular Ring showing speed/motion */}
                  <path d="M12 2a10 10 0 0 1 8 16" stroke="currentColor" strokeDasharray="3 3" />
                  <path d="M20 18a10 10 0 0 1-16-6" stroke="currentColor" />
                  {/* Express delivery arrow */}
                  <path d="M2 12l2-2 2 2" stroke="currentColor" />
                  {/* Beautiful Fish Swimming through the center */}
                  <path d="M8 12c1-2 3.5-3.5 6.5-3.5 2.5 0 4.5 1.5 5.5 3-1 1.5-3 3-5.5 3-3 0-5.5-1.5-6.5-3z" fill="currentColor" stroke="none" />
                  <path d="M8 12l-3-2v4z" fill="currentColor" stroke="none" />
                  {/* Fish eye */}
                  <circle cx="16" cy="11.5" r="0.75" fill="#7e0a0a" />
                </svg>
              </div>
              <div className="flex flex-col">
                <div className="flex items-baseline">
                  <span className="font-sans font-black tracking-tight text-sm text-white leading-none uppercase">
                    Royal
                  </span>
                  <span className="font-sans font-black tracking-tight text-sm text-yellow-300 leading-none uppercase ml-0.5">
                    Fish
                  </span>
                  <span className="font-sans font-black tracking-tight text-sm text-white leading-none uppercase ml-0.5">
                    Store
                  </span>
                </div>
                <span className="text-[7.5px] font-mono tracking-[0.25em] text-yellow-200/90 font-extrabold uppercase leading-none mt-1">
                  ONLINE FISH DELIVERY
                </span>
              </div>
            </div>
          </div>

          <div className="flex items-center gap-1">
            {/* Quick Theme Toggle */}
            <button
              onClick={toggleTheme}
              className="p-2 rounded-full hover:bg-white/10 text-white transition-colors"
              aria-label="Toggle Theme"
            >
              {theme === 'dark' ? <Sun className="w-4.5 h-4.5 text-amber-300" /> : <Moon className="w-4.5 h-4.5 text-white" />}
            </button>

            {/* Quick Profile */}
            <button
              onClick={() => navigateTo(user ? 'profile' : 'login')}
              className="p-2 rounded-full hover:bg-white/10 text-white transition-colors"
              aria-label="View Profile"
            >
              <User className="w-4.5 h-4.5" />
            </button>

            {/* Quick Cart */}
            <button
              onClick={() => navigateTo('cart')}
              className="p-2 rounded-full hover:bg-white/10 text-white relative transition-all"
              aria-label="View Cart"
            >
              <ShoppingBag className="w-4.5 h-4.5" />
              {cartCount > 0 && (
                <span className="absolute -top-0.5 -right-0.5 bg-yellow-400 text-slate-900 text-[9px] font-black w-4.5 h-4.5 rounded-full flex items-center justify-center animate-bounce shadow-sm">
                  {cartCount}
                </span>
              )}
            </button>
          </div>

        </div>

        {/* Row 2: Location delivery address (Blinkit style selector) */}
        <div 
          onClick={() => setIsPincodeModalOpen(true)}
          className="flex items-center gap-1.5 text-xs text-white/90 hover:text-white cursor-pointer py-1.5 bg-white/10 hover:bg-white/20 px-3 rounded-xl transition-all border border-white/10"
          id="btn-mobile-pincode"
        >
          <MapPin className="w-3.5 h-3.5 text-yellow-300 shrink-0 animate-bounce" />
          <span className="truncate font-medium flex-1 text-red-50 text-[11px]">
            Deliver to Pincode: <strong className="text-white font-black tracking-wide text-xs">{activePincode}</strong>
          </span>
          <span className="text-[10px] font-black text-yellow-300 tracking-wider uppercase shrink-0 bg-black/20 px-2 py-0.5 rounded-full border border-yellow-300/30">
            Change ▾
          </span>
        </div>

        {/* Row 3: Full-width search bar embedded directly inside gradient background */}
        <div className="relative w-full">
          <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <Search className="w-4 h-4 text-gray-400 dark:text-gray-500" />
          </div>
          <input
            type="text"
            placeholder="Search fresh fish, chicken, prawns, mutton cuts..."
            value={searchQuery}
            onChange={(e) => {
              setSearchQuery(e.target.value);
              if (currentPage !== 'home') navigateTo('home');
            }}
            className="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 border border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400/30 transition-all font-medium"
          />
          {searchQuery && (
            <button 
              onClick={() => setSearchQuery('')}
              className="absolute inset-y-0 right-0 pr-3 flex items-center text-[10px] font-bold text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
            >
              ✕
            </button>
          )}
        </div>

      </div>

    </header>
  );
};
