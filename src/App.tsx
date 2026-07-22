import React, { useState } from 'react';
import { AppProvider, useApp } from './context/AppContext';
import { Header } from './components/Header';
import { BottomNav } from './components/BottomNav';
import { Sidebar } from './components/Sidebar';
import { Home } from './pages/Home';
import { ProductDetails } from './pages/ProductDetails';
import { Cart } from './pages/Cart';
import { Profile } from './pages/Profile';
import { Login } from './pages/Login';
import { CategoryView } from './pages/CategoryView';
import { Categories } from './pages/Categories';
import { ShieldCheck, Flame, Compass, Award, Heart } from 'lucide-react';

const CITIES = [
  'Bengaluru', 'NCR Delhi', 'Hyderabad', 'Chandigarh', 'Panchkula', 'Mohali', 
  'Mumbai', 'Pune', 'Chennai', 'Coimbatore', 'Jaipur', 'Cochin', 'Vijayawada', 
  'Visakhapatnam', 'Kolkata', 'Lucknow', 'Kanpur', 'Nagpur'
];

const SEARCHES = [
  'Chicken Curry Cut', 'Boneless Chicken Breast', 'Surmai King Fish Steaks', 
  'White Tiger Prawns', 'Norwegian Salmon Fillet', 'Rohu Bengali Cut', 
  'Goat Curry Cut', 'Premium Mutton Keema', 'Tandoori Tikka Marinade', 
  'Chicken Salami Slices', 'Fish Fry & Curry Combo', 'Ready to Cook Platters'
];

const AppContent: React.FC = () => {
  const { currentPage } = useApp();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  // Render the current active view page
  const renderPage = () => {
    switch (currentPage) {
      case 'home':
        return <Home />;
      case 'product-details':
        return <ProductDetails />;
      case 'cart':
        return <Cart />;
      case 'profile':
        return <Profile />;
      case 'login':
        return <Login />;
      case 'category-view':
        return <CategoryView />;
      case 'categories':
        return <Categories />;
      default:
        return <Home />;
    }
  };

  return (
    <div className="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-200">
      
      {/* 1. Hamburger Left Drawer Sidebar */}
      <Sidebar isOpen={sidebarOpen} onClose={() => setSidebarOpen(false)} />

      {/* 2. Unified Header navigation bar */}
      <Header onOpenSidebar={() => setSidebarOpen(true)} />

      {/* 4. Main viewport context frame */}
      <main className="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-12">
        {renderPage()}
      </main>

      {/* 5. Desktop-Optimized Footer (Inspired by Licious visual hierarchy) */}
      <footer className="w-full bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 py-10 text-xs text-gray-500 dark:text-gray-400 select-none pb-24 md:pb-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
          
          {/* Brand Introduction Section */}
          <div className="grid grid-cols-1 md:grid-cols-12 gap-8 items-start border-b border-gray-50 dark:border-slate-800/60 pb-8">
            <div className="md:col-span-4 space-y-3 text-center md:text-left">
              <div className="flex items-center gap-3 justify-center md:justify-start">
                <div className="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-[#7e0a0a] via-[#a80e0e] to-[#c21818] shadow-md border border-amber-400/25 shrink-0 overflow-hidden">
                  <div className="absolute inset-0 rounded-xl border border-amber-400/10" />
                  <svg className="w-6 h-6 text-amber-400 drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M12 2a10 10 0 0 1 8 16" stroke="currentColor" strokeDasharray="3 3" />
                    <path d="M20 18a10 10 0 0 1-16-6" stroke="currentColor" />
                    <path d="M2 12l2-2 2 2" stroke="currentColor" />
                    <path d="M8 12c1-2 3.5-3.5 6.5-3.5 2.5 0 4.5 1.5 5.5 3-1 1.5-3 3-5.5 3-3 0-5.5-1.5-6.5-3z" fill="currentColor" stroke="none" />
                    <path d="M8 12l-3-2v4z" fill="currentColor" stroke="none" />
                    <circle cx="16" cy="11.5" r="0.75" fill="#7e0a0a" />
                  </svg>
                </div>
                <span className="font-sans font-black text-[#a80e0e] dark:text-[#f87171] tracking-tight text-lg">
                  ROYAL FISH STORE
                </span>
              </div>
              <p className="text-[11px] leading-relaxed">
                Royal Fish Store (royalfishstore.com) is your premium meat and seafood home delivery companion, heavily inspired by the standards of Licious. We offer freshly-caught seafood, pasture-raised country chicken, and selected cuts of mutton vacuum-sealed and delivered cooled under 4°C directly to your doorstep.
              </p>
            </div>

            {/* Quick stats / guarantees */}
            <div className="md:col-span-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
              <div className="p-3 bg-red-50/20 dark:bg-slate-800/20 rounded-xl border border-red-100/10">
                <span className="text-xl block mb-1">🧼</span>
                <strong className="text-gray-800 dark:text-gray-200 block text-xs">Sanitized Packing</strong>
                <span className="text-[10px] opacity-85 block">ISO 22000 certified</span>
              </div>
              <div className="p-3 bg-red-50/20 dark:bg-slate-800/20 rounded-xl border border-red-100/10">
                <span className="text-xl block mb-1">🧊</span>
                <strong className="text-gray-800 dark:text-gray-200 block text-xs">Cold-chain Preserved</strong>
                <span className="text-[10px] opacity-85 block">Cooled strictly 0-4°C</span>
              </div>
              <div className="p-3 bg-red-50/20 dark:bg-slate-800/20 rounded-xl border border-red-100/10">
                <span className="text-xl block mb-1">⏰</span>
                <strong className="text-gray-800 dark:text-gray-200 block text-xs">45 Min Express</strong>
                <span className="text-[10px] opacity-85 block">Fast local dispatch</span>
              </div>
              <div className="p-3 bg-red-50/20 dark:bg-slate-800/20 rounded-xl border border-red-100/10">
                <span className="text-xl block mb-1">🍗</span>
                <strong className="text-gray-800 dark:text-gray-200 block text-xs">100% Antibiotic Free</strong>
                <span className="text-[10px] opacity-85 block">Pure natural feed only</span>
              </div>
            </div>
          </div>

          {/* Cities We Serve Grid */}
          <div className="space-y-2.5">
            <span className="font-sans font-bold text-gray-800 dark:text-gray-200 block text-xs uppercase tracking-wider">
              🌆 Cities We Serve (Super-Fast Home Delivery)
            </span>
            <div className="flex flex-wrap gap-2 text-[11px] font-medium">
              {CITIES.map(city => (
                <span 
                  key={city} 
                  className="bg-gray-50 hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-slate-700/60 text-gray-600 dark:text-gray-400 px-2.5 py-1 rounded-md transition-colors"
                >
                  {city}
                </span>
              ))}
            </div>
          </div>

          {/* Popular Searches */}
          <div className="space-y-2.5">
            <span className="font-sans font-bold text-gray-800 dark:text-gray-200 block text-xs uppercase tracking-wider">
              🔎 Popular fresh meats searched online
            </span>
            <div className="flex flex-wrap gap-1.5 text-[10px]">
              {SEARCHES.map(term => (
                <span 
                  key={term} 
                  className="border border-gray-100 dark:border-slate-800 text-gray-500 px-2 py-0.5 rounded-full"
                >
                  {term}
                </span>
              ))}
            </div>
          </div>

          {/* Bottom security certificate bar */}
          <div className="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-50 dark:border-slate-800/40 text-[11px] text-gray-400">
            <div className="flex items-center gap-1">
              <span>Made with premium quality standards for</span>
              <a href="https://royalfishstore.com" target="_blank" rel="noopener noreferrer" className="text-red-600 font-bold hover:underline">
                royalfishstore.com
              </a>
            </div>

            {/* Payment security icons layout */}
            <div className="flex items-center gap-3">
              <span className="font-mono text-[9px] uppercase tracking-widest text-gray-400 dark:text-gray-500">
                100% Secure Checkout:
              </span>
              <div className="flex gap-2">
                <span className="bg-gray-50 dark:bg-slate-800 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-100 dark:border-slate-700">
                  VISA Secure
                </span>
                <span className="bg-gray-50 dark:bg-slate-800 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-100 dark:border-slate-700">
                  MasterCard ID Check
                </span>
                <span className="bg-gray-50 dark:bg-slate-800 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-100 dark:border-slate-700">
                  RuPay JCB
                </span>
              </div>
            </div>
          </div>

        </div>
      </footer>

      {/* 6. Sticky bottom mobile navigation bar (Only active on md:hidden mobile screen layout) */}
      <BottomNav />

    </div>
  );
};

export default function App() {
  return (
    <AppProvider>
      <AppContent />
    </AppProvider>
  );
}
