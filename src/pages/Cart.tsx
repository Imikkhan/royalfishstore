import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { ShoppingCart, Trash2, Tag, Truck, CreditCard, ChevronRight, CheckCircle, Smartphone, Globe, Landmark, MapPin, Plus, X, Loader2 } from 'lucide-react';

export const Cart: React.FC = () => {
  const {
    cart,
    addToCart,
    removeFromCart,
    clearCart,
    cartSubtotal,
    deliveryFee,
    discountAmount,
    couponCode,
    applyCoupon,
    cartTotal,
    minOrderAmount,
    freeDeliveryThreshold,
    addresses,
    addAddress,
    selectedAddressId,
    setSelectedAddressId,
    selectedPaymentMethod,
    setSelectedPaymentMethod,
    placeOrder,
    isPlacingOrder,
    navigateTo,
    activePincode,
    user,
    showToast
  } = useApp();

  const [couponInput, setCouponInput] = useState('');
  const [couponError, setCouponError] = useState('');
  const [couponSuccess, setCouponSuccess] = useState(false);

  // New Address Modal/Toggle State
  const [showAddressForm, setShowAddressForm] = useState(false);
  const [newAddrName, setNewAddrName] = useState('');
  const [newAddrType, setNewAddrType] = useState<'Home' | 'Work' | 'Other'>('Home');
  const [newAddrLine, setNewAddrLine] = useState('');
  const [newAddrCity, setNewAddrCity] = useState('');
  const [newAddrZip, setNewAddrZip] = useState(activePincode || '');
  const [newAddrPhone, setNewAddrPhone] = useState('');

  // Coupon apply handler
  const handleApply = (e: React.FormEvent) => {
    e.preventDefault();
    if (!couponInput) return;

    const success = applyCoupon(couponInput);
    if (success) {
      setCouponSuccess(true);
      setCouponError('');
    } else {
      setCouponError('Invalid coupon code. Try ROYAL20 or FREE60!');
      setCouponSuccess(false);
    }
  };

  // Address add handler
  const handleAddAddress = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newAddrName || !newAddrLine || !newAddrCity || !newAddrZip || !newAddrPhone) {
      showToast('Please fill out all address fields!', 'warning');
      return;
    }

    addAddress({
      name: newAddrName,
      type: newAddrType,
      addressLine: newAddrLine,
      city: newAddrCity,
      zipCode: newAddrZip,
      phone: newAddrPhone
    });

    // Reset Form
    setNewAddrName('');
    setNewAddrLine('');
    setNewAddrCity('');
    setNewAddrZip('');
    setNewAddrPhone('');
    setShowAddressForm(false);
  };

  if (cart.length === 0) {
    return (
      <div className="text-center py-20 space-y-6 max-w-md mx-auto select-none animate-fadeIn" id="cart-empty-state">
        <span className="text-6xl block">🛒</span>
        <div className="space-y-2">
          <h3 className="font-sans font-bold text-gray-900 dark:text-white text-xl">
            Your shopping basket is empty
          </h3>
          <p className="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Fill your cart with the freshest, juicy cuts of King fish, tender country chicken, and delicious marinades today.
          </p>
        </div>
        <button
          onClick={() => navigateTo('home')}
          className="bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm px-6 py-3 rounded-xl transition-all shadow-lg active:scale-95"
          id="btn-empty-shop-now"
        >
          Shop Fresh Meat & Seafood
        </button>
      </div>
    );
  }

  return (
    <div className="space-y-8 pb-12 animate-fadeIn" id="cart-checkout-page">
      
      {/* Page Title */}
      <div>
        <h2 className="font-sans font-extrabold text-2xl text-gray-900 dark:text-white tracking-tight">
          Review Your Basket & Checkout
        </h2>
        <p className="text-xs text-gray-500 dark:text-gray-400">
          Freshness is guaranteed. Double check weights and portions before finalizing.
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {/* Left Hand: Items list, Address Picker, Easy Payments */}
        <div className="lg:col-span-7 space-y-6">
          
          {/* Section 1: Cart Items Summary */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <div className="flex items-center justify-between border-b border-gray-50 dark:border-slate-800 pb-3">
              <h3 className="font-sans font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                <ShoppingCart className="w-5 h-5 text-red-500" />
                <span>Selected Portions ({cart.length})</span>
              </h3>
              <button
                onClick={clearCart}
                className="text-xs text-gray-400 hover:text-red-600 dark:hover:text-red-400 font-semibold"
                id="btn-clear-cart"
              >
                Clear All Items
              </button>
            </div>

            {/* List */}
            <div className="divide-y divide-gray-50 dark:divide-slate-800/40">
              {cart.map(item => {
                const stockQty = item.product.stockQuantity !== undefined ? Number(item.product.stockQuantity) : 999;
                const maxQty = Math.max(1, Number(item.product.maxOrderQty || (item.product as any).max_order_qty || 10));
                const minQty = Math.max(1, Number(item.product.minOrderQty || (item.product as any).min_order_qty || 1));
                const isOutOfStock = Boolean(item.product.isOutOfStock || stockQty <= 0);

                return (
                  <div key={item.product.id} className="py-4 flex items-start justify-between gap-3 group">
                    <div className="flex items-start gap-3">
                      {/* Tiny Image */}
                      <div className="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-gray-100 dark:border-slate-800">
                        <img
                          src={item.product.image}
                          alt={item.product.name}
                          referrerPolicy="no-referrer"
                          className="w-full h-full object-cover"
                          loading="lazy"
                        />
                      </div>
                      
                      {/* Content */}
                      <div className="space-y-0.5">
                        <h4 className="font-sans font-bold text-gray-800 dark:text-gray-200 text-sm leading-tight group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                          {item.product.name}
                        </h4>
                        {(item.product.shortDescription || item.product.short_description) && (
                          <p className="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-2 leading-tight">
                            {item.product.shortDescription || item.product.short_description}
                          </p>
                        )}
                        <div className="text-xs font-bold text-gray-900 dark:text-white mt-1 flex items-center gap-2">
                          <span>₹{item.product.price}</span>
                          {(minQty > 1 || maxQty < 10) && (
                            <span className="text-[10px] text-slate-400 font-normal">
                              ({minQty > 1 ? `Min: ${minQty}` : ''}{minQty > 1 && maxQty < 10 ? ' | ' : ''}{maxQty < 10 ? `Max: ${maxQty}` : ''})
                            </span>
                          )}
                        </div>

                        {item.quantity >= maxQty && (
                          <span className="text-[10px] font-bold text-amber-600 dark:text-amber-400 block mt-0.5">
                            Max purchase limit ({maxQty}) reached
                          </span>
                        )}
                        {stockQty < item.quantity && (
                          <span className="text-[10px] font-bold text-red-600 dark:text-red-400 block mt-0.5">
                            Only {stockQty} units available in inventory!
                          </span>
                        )}
                      </div>
                    </div>

                    {/* Quantity and Line Total */}
                    <div className="text-right space-y-2 shrink-0">
                      <span className="text-sm font-extrabold text-gray-900 dark:text-white block">
                        ₹{item.product.price * item.quantity}
                      </span>
                      
                      {/* Counter control */}
                      <div className="bg-red-50 dark:bg-slate-800 border border-red-200 dark:border-slate-700 text-red-600 dark:text-red-400 font-bold text-xs rounded-lg flex items-center select-none overflow-hidden">
                        <button
                          onClick={() => removeFromCart(item.product.id)}
                          className="px-2 py-1 hover:bg-red-100 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                          aria-label="Decrease"
                        >
                          -
                        </button>
                        <span className="px-2 font-mono text-center min-w-[16px]">
                          {item.quantity}
                        </span>
                        <button
                          onClick={() => addToCart(item.product)}
                          disabled={item.quantity >= stockQty || item.quantity >= maxQty}
                          className={`px-2 py-1 transition-colors ${item.quantity >= stockQty || item.quantity >= maxQty ? 'opacity-40 cursor-not-allowed' : 'hover:bg-red-100 dark:hover:bg-slate-700 cursor-pointer'}`}
                          aria-label="Increase"
                        >
                          +
                        </button>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Section 2: Home Delivery Address Selection */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <div className="flex items-center justify-between border-b border-gray-50 dark:border-slate-800 pb-3">
              <h3 className="font-sans font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                <MapPin className="w-5 h-5 text-red-500" />
                <span>Home Delivery Destination</span>
              </h3>
              <button
                onClick={() => setShowAddressForm(!showAddressForm)}
                className="text-xs font-bold text-red-600 dark:text-red-400 hover:underline flex items-center gap-0.5"
                id="btn-toggle-add-address"
              >
                <Plus className="w-3.5 h-3.5" />
                <span>Add Address</span>
              </button>
            </div>

            {/* If opening address form */}
            {showAddressForm && (
              <form onSubmit={handleAddAddress} className="bg-gray-50 dark:bg-slate-800/50 rounded-xl p-4 border border-gray-100 dark:border-slate-800 space-y-4 animate-fadeIn">
                <div className="flex items-center justify-between">
                  <span className="text-xs font-bold text-gray-800 dark:text-gray-200">New Delivery Location</span>
                  <button 
                    type="button"
                    onClick={() => setShowAddressForm(false)}
                    className="p-1 text-gray-400 hover:text-gray-600"
                  >
                    <X className="w-4 h-4" />
                  </button>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                  <div className="space-y-1">
                    <label className="text-[10px] font-bold text-gray-400 uppercase">Contact Name</label>
                    <input
                      type="text"
                      required
                      placeholder="e.g., Anjali Sharma"
                      value={newAddrName}
                      onChange={e => setNewAddrName(e.target.value)}
                      className="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-lg text-gray-800 dark:text-white focus:outline-none focus:border-red-500"
                    />
                  </div>

                  <div className="space-y-1">
                    <label className="text-[10px] font-bold text-gray-400 uppercase">Location Type</label>
                    <div className="grid grid-cols-3 gap-2">
                      {(['Home', 'Work', 'Other'] as const).map(t => (
                        <button
                          key={t}
                          type="button"
                          onClick={() => setNewAddrType(t)}
                          className={`py-1.5 text-xs font-semibold rounded-lg border text-center ${
                            newAddrType === t
                              ? 'bg-red-50 dark:bg-red-950/20 border-red-500 text-red-600'
                              : 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-700 text-gray-600 dark:text-gray-400'
                          }`}
                        >
                          {t}
                        </button>
                      ))}
                    </div>
                  </div>

                  <div className="sm:col-span-2 space-y-1">
                    <label className="text-[10px] font-bold text-gray-400 uppercase">Full Address Line</label>
                    <input
                      type="text"
                      required
                      placeholder="Flat/House No., Building Name, Street"
                      value={newAddrLine}
                      onChange={e => setNewAddrLine(e.target.value)}
                      className="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-lg text-gray-800 dark:text-white focus:outline-none focus:border-red-500"
                    />
                  </div>

                  <div className="space-y-1">
                    <label className="text-[10px] font-bold text-gray-400 uppercase">City</label>
                    <input
                      type="text"
                      required
                      placeholder="e.g., Mumbai"
                      value={newAddrCity}
                      onChange={e => setNewAddrCity(e.target.value)}
                      className="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-lg text-gray-800 dark:text-white focus:outline-none focus:border-red-500"
                    />
                  </div>

                  <div className="space-y-1">
                    <label className="text-[10px] font-bold text-gray-400 uppercase">Zip/Pincode</label>
                    <input
                      type="text"
                      required
                      placeholder="e.g., 400001"
                      value={newAddrZip}
                      onChange={e => setNewAddrZip(e.target.value)}
                      className="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-lg text-gray-800 dark:text-white focus:outline-none focus:border-red-500"
                    />
                  </div>

                  <div className="sm:col-span-2 space-y-1">
                    <label className="text-[10px] font-bold text-gray-400 uppercase">Mobile Number</label>
                    <input
                      type="tel"
                      required
                      placeholder="+91 99999 88888"
                      value={newAddrPhone}
                      onChange={e => setNewAddrPhone(e.target.value)}
                      className="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-lg text-gray-800 dark:text-white focus:outline-none focus:border-red-500"
                    />
                  </div>
                </div>

                <button
                  type="submit"
                  className="w-full py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg shadow-md transition-colors"
                  id="btn-submit-new-address"
                >
                  Save and Use Address
                </button>
              </form>
            )}

            {/* List of saved addresses */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
              {addresses.map(addr => {
                const isSelected = selectedAddressId === addr.id;
                return (
                  <div
                    key={addr.id}
                    onClick={() => setSelectedAddressId(addr.id)}
                    className={`p-3.5 rounded-xl border-2 text-left cursor-pointer transition-all ${
                      isSelected
                        ? 'bg-red-50/40 dark:bg-red-950/25 border-red-500 scale-[1.01]'
                        : 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-800/80 hover:border-gray-200'
                    }`}
                  >
                    <div className="flex items-center justify-between mb-1">
                      <div className="flex items-center gap-1.5">
                        <span className="text-sm">
                          {addr.type === 'Home' ? '🏠' : addr.type === 'Work' ? '💼' : '📍'}
                        </span>
                        <span className="font-extrabold text-xs text-gray-800 dark:text-gray-100">
                          {addr.name}
                        </span>
                      </div>
                      <div className={`w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center ${
                        isSelected ? 'border-red-500' : 'border-gray-300'
                      }`}>
                        {isSelected && <div className="w-1.5 h-1.5 bg-red-500 rounded-full" />}
                      </div>
                    </div>
                    <p className="text-[10px] text-gray-500 dark:text-gray-400 line-clamp-1 leading-normal mb-1">
                      {addr.addressLine}
                    </p>
                    <span className="text-[9px] font-mono font-medium text-gray-400 dark:text-gray-500">
                      Mob: {addr.phone}
                    </span>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Section 3: Easy Payment Options */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <div className="border-b border-gray-50 dark:border-slate-800 pb-3">
              <h3 className="font-sans font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                <CreditCard className="w-5 h-5 text-red-500" />
                <span>Select Easy Payment Method</span>
              </h3>
              <p className="text-[10px] text-gray-400 mt-0.5">
                Safe and secure transactions. Instant confirmation.
              </p>
            </div>

            {/* List of beautiful, responsive payment options */}
            <div className="space-y-3">
              
              {/* Option A: UPI / Instant App */}
              <div
                onClick={() => setSelectedPaymentMethod('upi')}
                className={`p-4 rounded-xl border cursor-pointer transition-all flex items-start gap-3.5 ${
                  selectedPaymentMethod === 'upi'
                    ? 'bg-red-50/30 dark:bg-red-950/20 border-red-400'
                    : 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-800'
                }`}
              >
                <div className="p-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-lg">
                  <Smartphone className="w-5 h-5" />
                </div>
                <div className="flex-1 space-y-1">
                  <div className="flex items-center justify-between">
                    <span className="font-bold text-xs text-gray-800 dark:text-gray-100">
                      UPI - GooglePay / PhonePe / Paytm
                    </span>
                    <span className="text-[9px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-1.5 py-0.5 rounded">
                      Super Fast
                    </span>
                  </div>
                  <p className="text-[10px] text-gray-400 dark:text-gray-500">
                    Pay instantly using any UPI app on your mobile device.
                  </p>
                  
                  {/* Collapsible UPI Mock selection inside active payment */}
                  {selectedPaymentMethod === 'upi' && (
                    <div className="pt-2.5 grid grid-cols-3 gap-2 animate-fadeIn">
                      <span className="text-[10px] text-center border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-2 py-1 rounded font-bold text-gray-700 dark:text-gray-300">
                        Google Pay
                      </span>
                      <span className="text-[10px] text-center border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-2 py-1 rounded font-bold text-gray-700 dark:text-gray-300">
                        PhonePe
                      </span>
                      <span className="text-[10px] text-center border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-2 py-1 rounded font-bold text-gray-700 dark:text-gray-300">
                        Paytm
                      </span>
                    </div>
                  )}
                </div>
              </div>

              {/* Option B: Credit / Debit Card */}
              <div
                onClick={() => setSelectedPaymentMethod('card')}
                className={`p-4 rounded-xl border cursor-pointer transition-all flex items-start gap-3.5 ${
                  selectedPaymentMethod === 'card'
                    ? 'bg-red-50/30 dark:bg-red-950/20 border-red-400'
                    : 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-800'
                }`}
              >
                <div className="p-2 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-lg">
                  <CreditCard className="w-5 h-5" />
                </div>
                <div className="flex-1 space-y-1">
                  <div className="flex items-center justify-between">
                    <span className="font-bold text-xs text-gray-800 dark:text-gray-100">
                      Credit / Debit Card (Visa, MasterCard, RuPay)
                    </span>
                  </div>
                  <p className="text-[10px] text-gray-400 dark:text-gray-500">
                    Accepts all domestic and international cards with secure authentication.
                  </p>

                  {/* Collapsible Card Details Form inside active payment */}
                  {selectedPaymentMethod === 'card' && (
                    <div className="pt-3 space-y-2 animate-fadeIn max-w-sm">
                      <div className="space-y-0.5">
                        <input
                          type="text"
                          placeholder="Card Number: 4321 0000 1111 2222"
                          className="w-full px-2.5 py-1 text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded text-gray-800 dark:text-white"
                          disabled
                        />
                      </div>
                      <div className="grid grid-cols-2 gap-2">
                        <input
                          type="text"
                          placeholder="Expiry: 12/28"
                          className="px-2.5 py-1 text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded text-gray-800 dark:text-white"
                          disabled
                        />
                        <input
                          type="password"
                          placeholder="CVV: ***"
                          className="px-2.5 py-1 text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded text-gray-800 dark:text-white"
                          disabled
                        />
                      </div>
                    </div>
                  )}
                </div>
              </div>

              {/* Option C: Net Banking */}
              <div
                onClick={() => setSelectedPaymentMethod('netbanking')}
                className={`p-4 rounded-xl border cursor-pointer transition-all flex items-start gap-3.5 ${
                  selectedPaymentMethod === 'netbanking'
                    ? 'bg-red-50/30 dark:bg-red-950/20 border-red-400'
                    : 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-800'
                }`}
              >
                <div className="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-lg">
                  <Landmark className="w-5 h-5" />
                </div>
                <div className="flex-1 space-y-1">
                  <span className="font-bold text-xs text-gray-800 dark:text-gray-100 block">
                    Internet Net Banking
                  </span>
                  <p className="text-[10px] text-gray-400 dark:text-gray-500">
                    Instantly transfer securely from your registered bank.
                  </p>
                </div>
              </div>

              {/* Option D: Cash on Delivery (COD) */}
              <div
                onClick={() => setSelectedPaymentMethod('cod')}
                className={`p-4 rounded-xl border cursor-pointer transition-all flex items-start gap-3.5 ${
                  selectedPaymentMethod === 'cod'
                    ? 'bg-red-50/30 dark:bg-red-950/20 border-red-400'
                    : 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-800'
                }`}
              >
                <div className="p-2 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-lg">
                  <CheckCircle className="w-5 h-5" />
                </div>
                <div className="flex-1 space-y-1">
                  <span className="font-bold text-xs text-gray-800 dark:text-gray-100 block">
                    Cash / UPI on Home Delivery
                  </span>
                  <p className="text-[10px] text-gray-400 dark:text-gray-500">
                    Pay with Cash, GooglePay, PhonePe, or cards at your doorstep.
                  </p>
                </div>
              </div>

            </div>
          </div>

        </div>

        {/* Right Hand: Billing Breakdowns, Coupon Apply Code, Final Checkout Action */}
        <div className="lg:col-span-5 space-y-6">
          
          {/* Section 4: Discount Coupon Field */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <h3 className="font-sans font-bold text-gray-900 dark:text-white text-sm flex items-center gap-1.5">
              <Tag className="w-4 h-4 text-red-500" />
              <span>Apply Promo Coupons</span>
            </h3>

            <form onSubmit={handleApply} className="flex gap-2">
              <input
                type="text"
                placeholder="Enter coupon code"
                value={couponInput}
                onChange={e => {
                  setCouponInput(e.target.value);
                  setCouponError('');
                }}
                className="flex-1 px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white border border-transparent focus:border-red-500 focus:bg-white rounded-xl focus:outline-none transition-all uppercase"
              />
              <button
                type="submit"
                className="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all active:scale-95 shrink-0"
              >
                Apply
              </button>
            </form>

            {/* Error or Success feedback */}
            {couponError && (
              <p className="text-[10px] font-bold text-red-600 dark:text-red-400">
                {couponError}
              </p>
            )}
            
            {couponSuccess && couponCode && (
              <div className="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-1.5 rounded-lg flex items-center justify-between">
                <span>Code &ldquo;{couponCode}&rdquo; Applied Successfully! Saved ₹{discountAmount}.</span>
                <button 
                  type="button" 
                  onClick={() => {
                    setCouponInput('');
                    setCouponSuccess(false);
                    applyCoupon(''); // resets context code
                  }}
                  className="font-extrabold underline"
                >
                  Remove
                </button>
              </div>
            )}

            {/* Micro suggestions helper */}
            <div className="pt-2 border-t border-gray-50 dark:border-slate-800/40 space-y-2">
              <span className="text-[10px] text-gray-400 font-bold block uppercase tracking-wider">
                Available Offers for You:
              </span>
              
              <div className="flex items-center justify-between text-xs py-1 bg-red-50/30 dark:bg-slate-800/20 px-2.5 rounded-lg border border-red-50/50">
                <div>
                  <span className="font-mono font-bold text-red-600 dark:text-red-400 block">ROYAL20</span>
                  <span className="text-[9px] text-gray-500 dark:text-gray-400">Save 20% on your full purchase order.</span>
                </div>
                <button
                  type="button"
                  onClick={() => {
                    setCouponInput('ROYAL20');
                    applyCoupon('ROYAL20');
                    setCouponSuccess(true);
                  }}
                  className="text-[10px] font-bold text-red-600 hover:underline"
                >
                  Apply
                </button>
              </div>

              <div className="flex items-center justify-between text-xs py-1 bg-red-50/30 dark:bg-slate-800/20 px-2.5 rounded-lg border border-red-50/50">
                <div>
                  <span className="font-mono font-bold text-red-600 dark:text-red-400 block">FREE60</span>
                  <span className="text-[9px] text-gray-500 dark:text-gray-400">Save up to ₹100 on standard products.</span>
                </div>
                <button
                  type="button"
                  onClick={() => {
                    setCouponInput('FREE60');
                    applyCoupon('FREE60');
                    setCouponSuccess(true);
                  }}
                  className="text-[10px] font-bold text-red-600 hover:underline"
                >
                  Apply
                </button>
              </div>
            </div>
          </div>

          {/* Section 5: Billing Details */}
          <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <h3 className="font-sans font-bold text-gray-900 dark:text-white text-sm border-b border-gray-50 dark:border-slate-800 pb-2">
              Order Cost Summary
            </h3>

            {/* Minimum Order Amount Alert Banner */}
            {cartSubtotal < minOrderAmount && minOrderAmount > 0 && (
              <div className="p-3.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl space-y-2">
                <div className="flex items-center justify-between text-xs font-bold text-amber-800 dark:text-amber-300">
                  <span className="flex items-center gap-1.5">
                    ⚠️ Minimum Order: ₹{minOrderAmount}
                  </span>
                  <span>₹{minOrderAmount - cartSubtotal} more needed</span>
                </div>
                <div className="w-full bg-amber-200/60 dark:bg-amber-900/40 h-2 rounded-full overflow-hidden">
                  <div 
                    className="bg-amber-500 h-full rounded-full transition-all duration-300"
                    style={{ width: `${Math.min(100, Math.round((cartSubtotal / minOrderAmount) * 100))}%` }}
                  ></div>
                </div>
                <p className="text-[10.5px] text-amber-700 dark:text-amber-400 leading-tight">
                  Add items worth ₹{minOrderAmount - cartSubtotal} more to your basket to proceed with checkout.
                </p>
              </div>
            )}

            <div className="space-y-2 text-xs">
              <div className="flex justify-between text-gray-600 dark:text-gray-400">
                <span>Cart Subtotal</span>
                <span className="font-bold text-gray-900 dark:text-white">₹{cartSubtotal}</span>
              </div>

              {discountAmount > 0 && (
                <div className="flex justify-between text-emerald-600 dark:text-emerald-400">
                  <span>Promo Discount Applied</span>
                  <span className="font-bold">-₹{discountAmount}</span>
                </div>
              )}

              <div className="flex justify-between text-gray-600 dark:text-gray-400">
                <span>Insulated Delivery Fee</span>
                {deliveryFee === 0 ? (
                  <span className="text-emerald-600 dark:text-emerald-400 font-bold uppercase text-[10px] bg-emerald-50 dark:bg-emerald-950/20 px-1.5 py-0.5 rounded">
                    FREE DELIVERY
                  </span>
                ) : (
                  <span className="font-bold text-gray-900 dark:text-white">₹{deliveryFee}</span>
                )}
              </div>

              {deliveryFee > 0 && freeDeliveryThreshold > cartSubtotal && (
                <p className="text-[10px] text-amber-600 font-medium">
                  Add ₹{freeDeliveryThreshold - cartSubtotal} more to get <strong>Free Insulated Shipping</strong>!
                </p>
              )}

              <hr className="border-gray-50 dark:border-slate-800/40 my-2" />

              <div className="flex justify-between text-sm">
                <span className="font-bold text-gray-900 dark:text-white">Total Payable Amount</span>
                <span className="font-extrabold text-red-600 dark:text-red-500 text-lg">
                  ₹{cartTotal}
                </span>
              </div>
            </div>

            {/* Delivery location short indicator */}
            <div className="bg-gray-50 dark:bg-slate-800/30 p-3 rounded-xl border border-gray-100 dark:border-slate-800 flex gap-2.5 items-start text-xs">
              <Truck className="w-4.5 h-4.5 text-gray-400 mt-0.5 shrink-0" />
              <div>
                <span className="font-bold text-gray-800 dark:text-gray-100 block">Deliver in 45-60 minutes</span>
                <p className="text-[10px] text-gray-500 dark:text-gray-400 leading-tight">
                  To the selected destination address. Sanitized temperature controlled dispatch.
                </p>
              </div>
            </div>

            {/* Main Action Trigger */}
            <button
              onClick={placeOrder}
              disabled={isPlacingOrder || (cartSubtotal < minOrderAmount && minOrderAmount > 0)}
              className={`w-full text-white font-extrabold text-sm py-3.5 rounded-xl transition-all shadow-xl flex items-center justify-center gap-2 ${
                isPlacingOrder
                  ? 'bg-orange-400 cursor-not-allowed opacity-90'
                  : cartSubtotal < minOrderAmount && minOrderAmount > 0
                    ? 'bg-slate-300 dark:bg-slate-800 text-slate-500 dark:text-slate-400 cursor-not-allowed shadow-none border border-slate-200 dark:border-slate-700'
                    : !user
                      ? 'bg-amber-600 hover:bg-amber-700 active:scale-[0.99] cursor-pointer'
                      : 'bg-[#fc490f] hover:bg-orange-600 active:scale-[0.99] cursor-pointer'
              }`}
              id="btn-place-order"
            >
              {isPlacingOrder ? (
                <>
                  <Loader2 className="w-5 h-5 animate-spin" />
                  <span>PLACING ORDER...</span>
                </>
              ) : cartSubtotal < minOrderAmount && minOrderAmount > 0 ? (
                <>
                  <span>MINIMUM ORDER ₹{minOrderAmount} REQUIRED</span>
                </>
              ) : !user ? (
                <>
                  <span>LOGIN TO PLACE ORDER</span>
                  <ChevronRight className="w-4 h-4" />
                </>
              ) : (
                <>
                  <span>CONFIRM & PLACE ORDER</span>
                  <ChevronRight className="w-4 h-4" />
                </>
              )}
            </button>

            <span className="block text-center text-[10px] text-gray-400">
              By confirming, you agree to our freshness quality guarantee.
            </span>
          </div>

        </div>

      </div>

    </div>
  );
};
