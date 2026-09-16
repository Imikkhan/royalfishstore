import React, { useState, useEffect } from 'react';
import { useApp } from '../context/AppContext';
import { Order } from '../types';
import { User as UserIcon, MapPin, ShoppingBag, Phone, Mail, ShieldAlert, CheckCircle, Package, Truck, Compass, ChevronDown, ChevronUp, LogOut, KeyRound, MessageSquare, Send, X } from 'lucide-react';
import { API_BASE_URL } from '../config';

export const Profile: React.FC = () => {
  const { orders, addresses, lastPlacedOrder, navigateTo, user, logout, refreshOrders } = useApp();
  const [expandedOrderId, setExpandedOrderId] = useState<string | null>(null);
  const [activeChatOrder, setActiveChatOrder] = useState<Order | null>(null);
  const [chatMessages, setChatMessages] = useState<any[]>([]);
  const [chatInput, setChatInput] = useState('');

  // Periodically refresh orders to sync status from admin updates in real-time
  useEffect(() => {
    refreshOrders();
    const interval = setInterval(() => {
      refreshOrders();
    }, 5000);
    return () => clearInterval(interval);
  }, []);

  const fetchChatMessages = async (orderId: string) => {
    try {
      const authToken = localStorage.getItem('royal-fish-token');
      const headers: Record<string, string> = {};
      if (authToken) headers['Authorization'] = `Bearer ${authToken}`;

      const res = await fetch(`${API_BASE_URL}/orders/${orderId}/chat`, { headers });
      if (res.ok) {
        const data = await res.json();
        setChatMessages(data);
      }
    } catch (e) {}
  };

  useEffect(() => {
    if (!activeChatOrder) return;
    fetchChatMessages(activeChatOrder.id);
    const interval = setInterval(() => {
      fetchChatMessages(activeChatOrder.id);
    }, 3000);
    return () => clearInterval(interval);
  }, [activeChatOrder]);

  const handleSendChat = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!chatInput.trim() || !activeChatOrder) return;

    const messageText = chatInput.trim();
    setChatInput('');

    try {
      const authToken = localStorage.getItem('royal-fish-token');
      const headers: Record<string, string> = { 'Content-Type': 'application/json' };
      if (authToken) headers['Authorization'] = `Bearer ${authToken}`;

      await fetch(`${API_BASE_URL}/orders/${activeChatOrder.id}/chat`, {
        method: 'POST',
        headers,
        body: JSON.stringify({ message: messageText })
      });
      fetchChatMessages(activeChatOrder.id);
    } catch (e) {}
  };

  const toggleOrderExpand = (orderId: string) => {
    setExpandedOrderId(prev => (prev === orderId ? null : orderId));
  };

  const displayOrders: Order[] = orders;

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
            {displayOrders.length === 0 ? (
              <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-8 text-center space-y-4 shadow-xs">
                <div className="w-16 h-16 rounded-full bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto text-2xl">
                  📦
                </div>
                <div className="space-y-1">
                  <h4 className="font-extrabold text-base text-gray-900 dark:text-white">No Orders Placed Yet</h4>
                  <p className="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                    You haven't placed any orders with us yet. Explore our fresh catch of seafood & meats!
                  </p>
                </div>
                <button
                  onClick={() => navigateTo('catalog')}
                  className="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all active:scale-95 inline-flex items-center gap-2"
                >
                  <ShoppingBag className="w-4 h-4" />
                  <span>Explore Fresh Catch</span>
                </button>
              </div>
            ) : (
              displayOrders.map(order => {
                const isExpanded = expandedOrderId === order.id || order.id === lastPlacedOrder?.id;
                
                const statusSteps = ['Placed', 'Processing', 'Out for Delivery', 'Delivered'] as const;
                
                const getStepIndex = (st: string) => {
                  switch (st) {
                    case 'Placed':
                    case 'Pending':
                    case 'Pending Assignment':
                      return 0;
                    case 'Processing':
                    case 'Dispatched':
                      return 1;
                    case 'Out for Delivery':
                      return 2;
                    case 'Delivered':
                      return 3;
                    default:
                      return 0;
                  }
                };

                const currentStepIdx = getStepIndex(order.status);

                const isCancelled = order.status?.toLowerCase() === 'cancelled';
                const isDelivered = order.status?.toLowerCase() === 'delivered';

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
                            isDelivered
                              ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50'
                              : isCancelled
                                ? 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50'
                                : 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 animate-pulse'
                          }`}>
                            {order.status}
                          </span>
                        </div>
                        <span className="text-[10px] text-gray-400 font-mono mt-0.5 block">{order.date}</span>
                      </div>

                      <div className="flex items-center gap-3 justify-between sm:justify-end">
                        {!isDelivered && !isCancelled && (
                          <button
                            onClick={() => setActiveChatOrder(order)}
                            className="px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shrink-0"
                          >
                            <MessageSquare className="w-3.5 h-3.5" />
                            <span>Chat Rider</span>
                          </button>
                        )}

                        <div className="text-right">
                          <span className="text-[10px] text-gray-400 block">Total Cost</span>
                          <span className="font-extrabold text-sm text-red-600 dark:text-red-400 block">₹{order.totalPrice}</span>
                        </div>

                        {/* Expand Chevron */}
                        <button
                          onClick={() => toggleOrderExpand(order.id)}
                          className="p-2 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 text-gray-600 dark:text-gray-300 transition-colors"
                          aria-label="Toggle details"
                        >
                          {isExpanded ? <ChevronUp className="w-4 h-4" /> : <ChevronDown className="w-4 h-4" />}
                        </button>
                      </div>
                    </div>

                    {/* Progress step bar or Cancelled Alert */}
                    {isCancelled ? (
                      <div className="py-2.5">
                        <div className="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/40 rounded-xl p-3 flex items-center gap-3">
                          <div className="w-7 h-7 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center font-black text-sm shrink-0">
                            ✕
                          </div>
                          <div>
                            <p className="text-xs font-bold text-red-700 dark:text-red-300">
                              Order Cancelled
                            </p>
                            <p className="text-[10px] text-red-600/80 dark:text-red-400/80">
                              This order has been cancelled. If you have questions, please contact our support team.
                            </p>
                          </div>
                        </div>
                      </div>
                    ) : (
                      <div className="py-2">
                        <div className="flex items-center justify-between text-[11px] font-bold mb-2">
                          {statusSteps.map((stepName, idx) => {
                            const isDone = idx <= currentStepIdx;
                            const isCurrent = idx === currentStepIdx;

                            return (
                              <div 
                                key={stepName} 
                                className={`flex flex-col items-center gap-1 ${
                                  isDelivered
                                    ? 'text-emerald-600 dark:text-emerald-400 font-extrabold'
                                    : isDone 
                                      ? 'text-red-600 dark:text-red-400 font-extrabold' 
                                      : 'text-gray-400 dark:text-slate-600 font-medium'
                                }`}
                              >
                                <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] ${
                                  isDelivered
                                    ? 'bg-emerald-600 text-white shadow-xs'
                                    : isCurrent 
                                      ? 'bg-red-600 text-white ring-4 ring-red-100 dark:ring-red-950/50' 
                                      : isDone 
                                        ? 'bg-red-100 dark:bg-red-950/40 text-red-600' 
                                        : 'bg-gray-100 dark:bg-slate-800 text-gray-400'
                                }`}>
                                  {isDelivered || isDone ? '✓' : idx + 1}
                                </div>
                                <span className="text-[10px] text-center">{stepName}</span>
                              </div>
                            );
                          })}
                        </div>

                        {/* Bar Line */}
                        <div className="w-full bg-gray-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                          <div 
                            className={`${isDelivered ? 'bg-emerald-500' : 'bg-gradient-to-r from-red-500 to-rose-600'} h-full transition-all duration-500`}
                            style={{ width: `${((currentStepIdx + 1) / statusSteps.length) * 100}%` }}
                          />
                        </div>
                      </div>
                    )}

                    {/* Expanded Items & Address Detail */}
                    {isExpanded && (
                      <div className="pt-3 border-t border-gray-50 dark:border-slate-800/40 space-y-4 animate-fadeIn">
                        
                        {/* Purchased Portion Items */}
                        <div className="space-y-2">
                          <span className="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">
                            Portion Details
                          </span>
                          <div className="space-y-2">
                            {(order.items || []).map((item, i) => (
                              <div key={i} className="flex items-center justify-between text-xs bg-gray-50/70 dark:bg-slate-800/40 p-2.5 rounded-xl">
                                <div className="flex items-center gap-2.5">
                                  <img 
                                    src={item.productImage || 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=400&q=80'} 
                                    alt={item.productName} 
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
                            <span className="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">
                              Delivered To:
                            </span>
                            <span className="font-bold text-gray-800 dark:text-gray-200 block">
                              {order.address?.name || 'Customer'} ({order.address?.type || 'Home'})
                            </span>
                            <p className="text-[10px] text-gray-500 dark:text-gray-400 leading-snug">
                              {order.address?.addressLine || 'Address N/A'}, {order.address?.city || ''} - {order.address?.zipCode || ''}
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
              })
            )}
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

      {/* Customer Live Order Chat Modal */}
      {activeChatOrder && (
        <div className="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-3">
          <div className="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl shadow-2xl flex flex-col h-[520px] overflow-hidden border border-gray-100 dark:border-slate-800 animate-fadeIn">
            {/* Header */}
            <div className="p-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
              <div className="flex items-center gap-2.5">
                <div className="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-xs">
                  💬
                </div>
                <div>
                  <h3 className="text-xs font-extrabold uppercase tracking-tight">Delivery Rider Live Chat</h3>
                  <p className="text-[10px] text-gray-400 font-mono">Order #{activeChatOrder.id}</p>
                </div>
              </div>
              <button
                onClick={() => setActiveChatOrder(null)}
                className="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-gray-300 flex items-center justify-center text-xs"
              >
                <X className="w-4 h-4" />
              </button>
            </div>

            {/* Quick Chips */}
            <div className="p-2 bg-gray-50 dark:bg-slate-800/40 border-b border-gray-100 dark:border-slate-800 flex gap-1.5 overflow-x-auto text-[10px] shrink-0">
              <button
                type="button"
                onClick={() => setChatInput("Where is my delivery? 🚴")}
                className="px-2.5 py-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-full font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap hover:bg-gray-100"
              >
                Where is my order? 🚴
              </button>
              <button
                type="button"
                onClick={() => setChatInput("Please leave at door 🚪")}
                className="px-2.5 py-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-full font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap hover:bg-gray-100"
              >
                Leave at door 🚪
              </button>
              <button
                type="button"
                onClick={() => setChatInput("Call me when outside 📞")}
                className="px-2.5 py-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-full font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap hover:bg-gray-100"
              >
                Call when outside 📞
              </button>
            </div>

            {/* Chat Messages List */}
            <div className="flex-1 p-3 overflow-y-auto space-y-2 bg-gray-50/60 dark:bg-slate-950/40">
              {chatMessages.length === 0 ? (
                <div className="text-center text-xs text-gray-400 py-8">
                  No messages yet. Send a quick message to your delivery rider!
                </div>
              ) : (
                chatMessages.map((m: any, idx: number) => {
                  const isCustomer = m.sender_type === 'customer';
                  const bubbleBg = isCustomer
                    ? 'bg-red-600 text-white rounded-br-none ml-auto'
                    : 'bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 rounded-bl-none border border-gray-100 dark:border-slate-700';
                  const align = isCustomer ? 'justify-end' : 'justify-start';

                  return (
                    <div key={idx} className={`flex ${align}`}>
                      <div className={`max-w-[80%] p-2.5 rounded-2xl ${bubbleBg} shadow-xs space-y-0.5`}>
                        <div className="text-[9px] font-bold opacity-80 flex items-center justify-between gap-3">
                          <span>{m.sender_name || (isCustomer ? 'You' : 'Delivery Rider')}</span>
                          <span>{new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <p className="text-xs font-medium leading-normal">{m.message}</p>
                      </div>
                    </div>
                  );
                })
              )}
            </div>

            {/* Input Bar or Locked Banner */}
            {activeChatOrder.status === 'Delivered' ? (
              <div className="p-3.5 bg-gray-100 dark:bg-slate-800/80 text-center text-xs font-extrabold text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-slate-800 flex items-center justify-center gap-1.5">
                <span>🔒</span> Order Delivered & Closed — Live chat session ended.
              </div>
            ) : (
              <form onSubmit={handleSendChat} className="p-3 bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 flex gap-2 items-center shrink-0">
                <input
                  type="text"
                  value={chatInput}
                  onChange={(e) => setChatInput(e.target.value)}
                  placeholder="Type a message to delivery rider..."
                  className="flex-1 px-3 py-2 bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs focus:outline-none focus:border-red-500 font-medium text-gray-900 dark:text-white"
                />
                <button
                  type="submit"
                  className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold rounded-xl transition-colors shrink-0 flex items-center gap-1.5 shadow-xs active:scale-95"
                >
                  <span>Send</span>
                  <Send className="w-3.5 h-3.5" />
                </button>
              </form>
            )}
          </div>
        </div>
      )}

    </div>
  );
};
