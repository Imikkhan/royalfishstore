import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { Order } from '../types';
import { User as UserIcon, MapPin, ShoppingBag, Phone, Mail, ShieldAlert, CheckCircle, Package, Truck, Compass, ChevronDown, ChevronUp, LogOut, KeyRound } from 'lucide-react';

export const Profile: React.FC = () => {
  const { orders, addresses, lastPlacedOrder, navigateTo, user, logout } = useApp();
  const [expandedOrderId, setExpandedOrderId] = useState<string | null>(null);

  const toggleOrderExpand = (orderId: string) => {
    setExpandedOrderId(prev => (prev === orderId ? null : orderId));
  };

  // Pre-seed a beautiful mock completed order if orders list is empty,
  // so the user has immediate rich tracking items to see.
  const displayOrders: Order[] = orders.length > 0 ? orders : [
    {
      id: 'ROYAL-598210',
      date: '17 Jul 2026, 02:40 PM',
      items: [
        {
          productId: 'fs-1',
          productName: 'Surmai / Seer King Fish Steaks',
          productImage: 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=400&q=80',
          price: 649,
          quantity: 1
        },
        {
          productId: 'ch-1',
          productName: 'Tender Chicken Curry Cut (Small)',
          productImage: 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=400&q=80',
          price: 169,
          quantity: 2
        }
      ],
      totalPrice: 987,
      paymentMethod: 'UPI',
      address: addresses[0] || {
        id: 'addr-1',
        name: 'Home (Default)',
        type: 'Home',
        addressLine: 'Flat 402, Royal Residency, Marine Drive',
        city: 'Mumbai',
        zipCode: '400002',
        phone: '+91 98765 43210'
      },
      status: 'Delivered',
      estimatedDelivery: 'Completed in 35 mins'
    }
  ];

  if (!user) {
    return (
      <div className="max-w-md mx-auto my-12 text-center p-8 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-xl space-y-6 animate-fadeIn">
        <div className="w-16 h-16 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto">
          <UserIcon className="w-8 h-8" />
        </div>
        <div className="space-y-2">
          <h3 className="text-xl font-black text-gray-900 dark:text-white tracking-tight">Login Required</h3>
          <p className="text-xs text-gray-500 dark:text-gray-400">
            Please log in to view your profile, manage addresses, track your fresh harvest deliveries, and see order history.
          </p>
        </div>
        <button
          onClick={() => navigateTo('login')}
          className="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all text-sm active:scale-98"
        >
          Proceed to Login
        </button>
      </div>
    );
  }

  return (
    <div className="space-y-8 pb-12 animate-fadeIn" id="profile-page">
      
      {/* 1. Profile Header / Welcome Banner */}
      <div className="bg-gradient-to-r from-red-600 to-red-800 text-white rounded-2xl p-6 shadow-md select-none">
        <div className="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
          {/* Avatar circular frame */}
          <div className="w-16 h-16 rounded-full bg-white/20 border-2 border-white flex items-center justify-center text-3xl font-extrabold shadow-inner">
            👤
          </div>
          
          <div className="flex-1 space-y-1">
            <h2 className="font-sans font-extrabold text-xl sm:text-2xl tracking-tight">
              {user.name}
            </h2>
            <div className="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs text-white/80">
              <span className="flex items-center gap-1">
                <Phone className="w-3.5 h-3.5" />
                <span>+91 {user.phone}</span>
              </span>
              <span>•</span>
              <span className="flex items-center gap-1">
                <Mail className="w-3.5 h-3.5" />
                <span>{user.email}</span>
              </span>
            </div>
          </div>

          <div className="flex flex-col sm:flex-row items-center gap-3">
            <div className="bg-white/10 px-3.5 py-1.5 rounded-xl border border-white/15 text-xs text-white text-center sm:text-left">
              <span className="font-bold block text-amber-300">👑 Royal VIP Club</span>
              <span className="text-[10px] opacity-90 mt-0.5">Free delivery on orders above ₹499</span>
            </div>
            
            <button
              onClick={() => {
                logout();
                navigateTo('home');
              }}
              className="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white flex items-center gap-1.5 text-xs font-bold transition-all"
            >
              <LogOut className="w-3.5 h-3.5" />
              <span>Logout</span>
            </button>
          </div>
        </div>
      </div>

      {/* 2. Main layout grid */}
      <div className="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        {/* Left Hand: Order History & Tracking visualizer */}
        <div className="md:col-span-8 space-y-6">
          
          <div className="space-y-1">
            <h3 className="font-sans font-extrabold text-gray-900 dark:text-white text-lg tracking-tight">
              Freshness Delivery Tracker & History
            </h3>
            <p className="text-xs text-gray-500 dark:text-gray-400">
              Track active orders in real-time. Sourced fresh, temperature monitored at all times.
            </p>
          </div>

          {/* Orders timeline listing */}
          <div className="space-y-4">
            {displayOrders.map(order => {
              const isExpanded = expandedOrderId === order.id || order.id === lastPlacedOrder?.id;
              
              // Get status progress code
              const statusSteps = ['Placed', 'Processing', 'Out for Delivery', 'Delivered'] as const;
              const currentStepIdx = statusSteps.indexOf(order.status as any);

              return (
                <div 
                  key={order.id}
                  className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-xs space-y-4 transition-all"
                  id={`order-container-${order.id}`}
                >
                  
                  {/* Order header bar */}
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-50 dark:border-slate-800/40 pb-3">
                    <div>
                      <div className="flex items-center gap-2">
                        <span className="font-extrabold text-sm text-gray-900 dark:text-white">
                          Order ID: {order.id}
                        </span>
                        
                        {/* Status Label Pill */}
                        <span className={`text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md ${
                          order.status === 'Delivered'
                            ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400'
                            : 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 animate-pulse'
                        }`}>
                          {order.status}
                        </span>
                      </div>
                      <span className="text-[10px] text-gray-400 font-mono mt-0.5 block">{order.date}</span>
                    </div>

                    <div className="flex items-center gap-4 justify-between sm:justify-end">
                      <div className="text-right">
                        <span className="text-[10px] text-gray-400 block">Total Cost</span>
                        <span className="font-extrabold text-sm text-red-600 dark:text-red-400 block">₹{order.totalPrice}</span>
                      </div>

                      {/* Expand Chevron */}
                      <button
                        onClick={() => toggleOrderExpand(order.id)}
                        className="p-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg text-gray-500"
                        id={`btn-expand-order-${order.id}`}
                      >
                        {isExpanded ? <ChevronUp className="w-4 h-4" /> : <ChevronDown className="w-4 h-4" />}
                      </button>
                    </div>
                  </div>

                  {/* Order Tracking Progress Visualizer (Only visible when active or expanded) */}
                  {order.status !== 'Delivered' && (
                    <div className="bg-gray-50/50 dark:bg-slate-800/10 rounded-xl border border-gray-100 dark:border-slate-800/60 p-4 space-y-4">
                      <div className="flex justify-between items-center text-xs">
                        <span className="text-gray-500">Estimated Arrival:</span>
                        <strong className="text-emerald-600 dark:text-emerald-400 font-extrabold">{order.estimatedDelivery}</strong>
                      </div>

                      {/* Visual Progress Steps */}
                      <div className="grid grid-cols-4 gap-1 text-center relative select-none pt-2">
                        {statusSteps.map((step, idx) => {
                          const isDone = idx <= currentStepIdx;
                          const isCurrent = idx === currentStepIdx;
                          return (
                            <div key={step} className="flex flex-col items-center gap-1.5 relative">
                              {/* Connector line */}
                              {idx < 3 && (
                                <div className={`absolute top-2.5 left-1/2 w-full h-[3px] -z-10 ${
                                  idx < currentStepIdx 
                                    ? 'bg-red-500' 
                                    : 'bg-gray-200 dark:bg-slate-700'
                                }`} />
                              )}
                              
                              {/* Ring/Circle */}
                              <div className={`w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all ${
                                isDone 
                                  ? 'bg-red-500 border-red-500 text-white' 
                                  : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700'
                              }`}>
                                {isDone && <CheckCircle className="w-3.5 h-3.5" />}
                              </div>

                              {/* Label text */}
                              <span className={`text-[9px] sm:text-[10px] font-bold ${
                                isCurrent 
                                  ? 'text-red-600 dark:text-red-400 font-extrabold scale-105' 
                                  : isDone 
                                  ? 'text-gray-800 dark:text-gray-200' 
                                  : 'text-gray-400 dark:text-gray-500'
                              }`}>
                                {step}
                              </span>
                            </div>
                          );
                        })}
                      </div>
                    </div>
                  )}

                  {/* Expanded Item Breakdown Details */}
                  {isExpanded && (
                    <div className="space-y-3.5 pt-1.5 border-t border-gray-50 dark:border-slate-800/20 animate-fadeIn">
                      <div className="space-y-2">
                        <span className="text-[10px] text-gray-400 font-bold uppercase block tracking-wider">
                          Portion Details
                        </span>
                        
                        <div className="space-y-2">
                          {order.items.map(item => (
                            <div key={item.productId} className="flex items-center justify-between text-xs text-gray-700 dark:text-gray-300 bg-gray-50/40 dark:bg-slate-800/20 p-2 rounded-xl border border-gray-100/40">
                              <div className="flex items-center gap-2">
                                <img
                                  src={item.productImage}
                                  alt={item.productName}
                                  referrerPolicy="no-referrer"
                                  className="w-10 h-10 rounded-lg object-cover"
                                />
                                <div>
                                  <span className="font-bold block">{item.productName}</span>
                                  <span className="text-[10px] text-gray-400">₹{item.price} x {item.quantity}</span>
                                </div>
                              </div>
                              <span className="font-bold text-gray-900 dark:text-white">
                                ₹{item.price * item.quantity}
                              </span>
                            </div>
                          ))}
                        </div>
                      </div>

                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2 border-t border-gray-50 dark:border-slate-800/40">
                        {/* Address */}
                        <div className="space-y-0.5">
                          <span className="text-[10px] text-gray-400 font-bold uppercase block tracking-wider">
                            Delivered To:
                          </span>
                          <span className="font-bold text-gray-800 dark:text-gray-200 block">
                            {order.address.name} ({order.address.type})
                          </span>
                          <p className="text-[10px] text-gray-500 dark:text-gray-400 leading-snug">
                            {order.address.addressLine}, {order.address.city} - {order.address.zipCode}
                          </p>
                        </div>

                        {/* Payment */}
                        <div className="space-y-0.5">
                          <span className="text-[10px] text-gray-400 font-bold uppercase block tracking-wider">
                            Payment info:
                          </span>
                          <span className="font-bold text-gray-800 dark:text-gray-200 block">
                            Method: {order.paymentMethod}
                          </span>
                          <span className="text-[10px] text-gray-500 dark:text-gray-400 block">
                            Total amount paid: <strong>₹{order.totalPrice}</strong>
                          </span>
                        </div>
                      </div>
                    </div>
                  )}

                </div>
              );
            })}
          </div>

        </div>

        {/* Right Hand: Address Book & Quality Metrics */}
        <div className="md:col-span-4 space-y-6">
          
          {/* Address Book Manager */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <div>
              <h3 className="font-sans font-bold text-gray-900 dark:text-white text-base">
                Address Book Manager
              </h3>
              <p className="text-[10px] text-gray-400 mt-0.5">
                Saved destinations for fast, single-click order processing.
              </p>
            </div>

            <div className="space-y-3.5">
              {addresses.map(addr => (
                <div 
                  key={addr.id}
                  className="p-3.5 rounded-xl border border-gray-100 dark:border-slate-800/80 bg-gray-50/50 dark:bg-slate-800/20 text-xs"
                >
                  <div className="flex items-center gap-1.5 mb-1.5">
                    <span className="text-base">
                      {addr.type === 'Home' ? '🏠' : addr.type === 'Work' ? '💼' : '📍'}
                    </span>
                    <span className="font-extrabold text-xs text-gray-800 dark:text-gray-100">
                      {addr.name}
                    </span>
                  </div>
                  <p className="text-[10px] text-gray-500 dark:text-gray-400 leading-snug mb-1">
                    {addr.addressLine}, {addr.city} - {addr.zipCode}
                  </p>
                  <span className="text-[9px] font-mono text-gray-400 font-medium block">
                    Mobile: {addr.phone}
                  </span>
                </div>
              ))}
            </div>

            <button
              onClick={() => navigateTo('cart')}
              className="w-full text-center text-xs font-bold py-2 bg-red-50 hover:bg-red-100 dark:bg-slate-800 dark:hover:bg-slate-700 border border-red-200 dark:border-slate-700 text-red-600 dark:text-red-400 rounded-lg transition-all"
            >
              + Create New Destination
            </button>
          </div>

          {/* Delivery Support Line Info */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4 text-xs">
            <h4 className="font-sans font-bold text-gray-900 dark:text-white text-sm flex items-center gap-1.5">
              <Package className="w-4 h-4 text-red-500" />
              <span>Royal Delivery Quality Helpline</span>
            </h4>
            <p className="text-gray-500 dark:text-gray-400 leading-relaxed text-[11px]">
              Our delivery boys are specially trained in cold-temperature logistics. If you notice any delay or packing compromise, call us directly.
            </p>
            <div className="space-y-2 border-t border-gray-50 dark:border-slate-800/40 pt-3 text-[11px] font-bold text-gray-800 dark:text-gray-200">
              <div className="flex items-center justify-between">
                <span>📞 Hotline support:</span>
                <span className="text-red-600 dark:text-red-400">1800-419-786</span>
              </div>
              <div className="flex items-center justify-between">
                <span>✉️ Email complaints:</span>
                <span className="text-red-600 dark:text-red-400">care@royalfish.com</span>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  );
};
