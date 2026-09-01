import React, { useState } from 'react';
import { AppProvider, useApp } from './context/AppContext';
import { Header } from './components/Header';
import { BottomNav } from './components/BottomNav';
import { Sidebar } from './components/Sidebar';
import { OrderSuccessModal } from './components/OrderSuccessModal';
import { Home } from './pages/Home';
import { ProductDetails } from './pages/ProductDetails';
import { Cart } from './pages/Cart';
import { Profile } from './pages/Profile';
import { Login } from './pages/Login';
import { CategoryView } from './pages/CategoryView';
import { Categories } from './pages/Categories';
import { SearchPage } from './pages/Search';
import { Onepager } from './pages/Onepager';
import { ShieldCheck, Snowflake, Zap, Leaf } from 'lucide-react';

const AppContent: React.FC = () => {
  const { currentPage } = useApp();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  // Render the current active view page
  const renderPage = () => {
    switch (currentPage) {
      case 'home':
        return <Home />;
      case 'onepager':
        return <Onepager />;
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
      case 'search':
        return <SearchPage />;
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
      <main className={`flex-1 w-full mx-auto ${
        currentPage === 'onepager'
          ? 'p-0 max-w-none'
          : 'max-w-7xl px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-12'
      }`}>
        {renderPage()}
      </main>

      {/* 5. Desktop-Optimized Footer (Inspired by Licious visual hierarchy) */}
      <footer className="w-full bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 py-10 text-xs text-gray-500 dark:text-gray-400 select-none pb-24 md:pb-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
          
          {/* Brand Introduction Section */}
          <div className="grid grid-cols-1 md:grid-cols-12 gap-8 items-start border-b border-gray-50 dark:border-slate-800/60 pb-8">
            <div className="md:col-span-4 space-y-3 text-center md:text-left">
              <div className="flex items-center justify-center md:justify-start">
                <img src="/logo.png" alt="Royal Fish Store Logo" className="h-12 w-auto object-contain" />
              </div>
              <p className="text-[11px] leading-relaxed">
                Royal Fish Store (royalfishstore.com) is your premium meat and seafood home delivery companion, heavily inspired by the standards of Licious. We offer freshly-caught seafood, pasture-raised country chicken, and selected cuts of mutton vacuum-sealed and delivered cooled under 4°C directly to your doorstep.
              </p>
            </div>

            {/* Quick stats / guarantees */}
            <div className="md:col-span-8 grid grid-cols-2 sm:grid-cols-4 gap-3.5 text-center">
              <div className="p-3.5 bg-gray-50/80 dark:bg-slate-800/50 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 rounded-2xl border border-gray-100 dark:border-slate-800 transition-all duration-300 group">
                <div className="w-10 h-10 mx-auto rounded-xl bg-emerald-100/70 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2 shadow-xs group-hover:scale-110 transition-transform">
                  <ShieldCheck className="w-5 h-5 stroke-[2.5]" />
                </div>
                <strong className="text-gray-900 dark:text-white block text-xs font-extrabold">Sanitized Packing</strong>
                <span className="text-[10px] text-gray-500 dark:text-gray-400 block mt-0.5 font-medium">ISO 22000 certified</span>
              </div>

              <div className="p-3.5 bg-gray-50/80 dark:bg-slate-800/50 hover:bg-blue-50/50 dark:hover:bg-blue-950/20 rounded-2xl border border-gray-100 dark:border-slate-800 transition-all duration-300 group">
                <div className="w-10 h-10 mx-auto rounded-xl bg-blue-100/70 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2 shadow-xs group-hover:scale-110 transition-transform">
                  <Snowflake className="w-5 h-5 stroke-[2.5]" />
                </div>
                <strong className="text-gray-900 dark:text-white block text-xs font-extrabold">Cold-chain Preserved</strong>
                <span className="text-[10px] text-gray-500 dark:text-gray-400 block mt-0.5 font-medium">Cooled strictly 0-4°C</span>
              </div>

              <div className="p-3.5 bg-gray-50/80 dark:bg-slate-800/50 hover:bg-amber-50/50 dark:hover:bg-amber-950/20 rounded-2xl border border-gray-100 dark:border-slate-800 transition-all duration-300 group">
                <div className="w-10 h-10 mx-auto rounded-xl bg-amber-100/70 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2 shadow-xs group-hover:scale-110 transition-transform">
                  <Zap className="w-5 h-5 stroke-[2.5]" />
                </div>
                <strong className="text-gray-900 dark:text-white block text-xs font-extrabold">45 Min Express</strong>
                <span className="text-[10px] text-gray-500 dark:text-gray-400 block mt-0.5 font-medium">Fast local dispatch</span>
              </div>

              <div className="p-3.5 bg-gray-50/80 dark:bg-slate-800/50 hover:bg-orange-50/50 dark:hover:bg-orange-950/20 rounded-2xl border border-gray-100 dark:border-slate-800 transition-all duration-300 group">
                <div className="w-10 h-10 mx-auto rounded-xl bg-orange-100/70 dark:bg-orange-950/50 text-[#fc490f] flex items-center justify-center mb-2 shadow-xs group-hover:scale-110 transition-transform">
                  <Leaf className="w-5 h-5 stroke-[2.5]" />
                </div>
                <strong className="text-gray-900 dark:text-white block text-xs font-extrabold">100% Antibiotic Free</strong>
                <span className="text-[10px] text-gray-500 dark:text-gray-400 block mt-0.5 font-medium">Pure natural feed only</span>
              </div>
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

      {/* 6. Instant Order Placed Celebration Modal */}
      <OrderSuccessModal />

      {/* 7. Sticky bottom mobile navigation bar (Only active on md:hidden mobile screen layout) */}
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
