import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { MapPin, Search, CheckCircle, AlertCircle, X, Navigation } from 'lucide-react';

interface PincodeModalProps {
  isOpen: boolean;
  onClose: () => void;
}

const MAJOR_CITIES = [
  { city: 'Mumbai', pincode: '400001' },
  { city: 'Delhi NCR', pincode: '110001' },
  { city: 'Bengaluru', pincode: '560001' },
  { city: 'Hyderabad', pincode: '500001' },
  { city: 'Kolkata', pincode: '700001' },
  { city: 'Chennai', pincode: '600001' },
  { city: 'Pune', pincode: '411001' },
  { city: 'Kochi', pincode: '682001' },
];

export const PincodeModal: React.FC<PincodeModalProps> = ({ isOpen, onClose }) => {
  const { activePincode, setPincode } = useApp();
  const [inputPin, setInputPin] = useState(activePincode || '');
  const [errorMsg, setErrorMsg] = useState('');

  if (!isOpen) return null;

  const handleSubmit = (pinToSet?: string) => {
    const targetPin = pinToSet || inputPin;
    const cleanPin = targetPin.trim();

    if (!cleanPin || cleanPin.length !== 6 || !/^\d+$/.test(cleanPin)) {
      setErrorMsg('Please enter a valid 6-digit Indian pincode (e.g. 400001)');
      return;
    }

    setErrorMsg('');
    setPincode(cleanPin);
    onClose();
  };

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto select-none">
      {/* Backdrop */}
      <div 
        className="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity duration-300"
        onClick={onClose} 
      />

      <div className="flex min-h-full items-center justify-center p-4">
        <div className="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-slate-800 space-y-6 animate-fadeIn">
          
          {/* Header */}
          <div className="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4">
            <div className="flex items-center gap-2.5">
              <div className="p-2.5 bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 rounded-2xl">
                <MapPin className="w-6 h-6 animate-bounce" />
              </div>
              <div>
                <h3 className="font-sans font-black text-lg text-gray-900 dark:text-white tracking-tight">
                  Select Delivery Location
                </h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  Enter pincode for 45-min fresh express delivery
                </p>
              </div>
            </div>
            <button
              onClick={onClose}
              className="p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Form input */}
          <div className="space-y-3">
            <label className="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider block">
              Enter 6-Digit Pincode
            </label>

            <div className="relative">
              <input
                type="text"
                maxLength={6}
                value={inputPin}
                onChange={(e) => {
                  setInputPin(e.target.value);
                  if (errorMsg) setErrorMsg('');
                }}
                placeholder="e.g. 400001, 110001, 560001"
                className="w-full pl-4 pr-24 py-3.5 bg-gray-50 dark:bg-slate-800/80 border border-gray-200 dark:border-slate-700 rounded-2xl text-base font-bold text-gray-900 dark:text-white tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500"
              />
              <button
                onClick={() => handleSubmit()}
                className="absolute right-2 top-1/2 -translate-y-1/2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md active:scale-95"
              >
                Apply
              </button>
            </div>

            {errorMsg && (
              <div className="flex items-center gap-1.5 text-red-600 dark:text-red-400 text-xs font-semibold">
                <AlertCircle className="w-4 h-4 shrink-0" />
                <span>{errorMsg}</span>
              </div>
            )}
          </div>

          {/* Major Cities Quick Select */}
          <div className="space-y-3 pt-2">
            <span className="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">
              Or Choose Major Serviced City:
            </span>
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
              {MAJOR_CITIES.map((item) => (
                <button
                  key={item.city}
                  onClick={() => {
                    setInputPin(item.pincode);
                    handleSubmit(item.pincode);
                  }}
                  className={`p-2.5 rounded-xl border text-center text-xs font-bold transition-all ${
                    activePincode === item.pincode
                      ? 'bg-red-50 dark:bg-red-950/40 border-red-500 text-red-600 dark:text-red-400 ring-2 ring-red-100 dark:ring-red-900/30'
                      : 'bg-gray-50 dark:bg-slate-800 border-gray-150 dark:border-slate-700 text-gray-700 dark:text-gray-300 hover:border-red-300'
                  }`}
                >
                  <span className="block truncate">{item.city}</span>
                  <span className="text-[10px] text-gray-400 dark:text-gray-500 font-mono block mt-0.5">
                    {item.pincode}
                  </span>
                </button>
              ))}
            </div>
          </div>

          {/* Guarantee Footer */}
          <div className="pt-3 border-t border-gray-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">
            <div className="flex items-center gap-1.5">
              <CheckCircle className="w-4 h-4" />
              <span>Cold-chain delivery under 4°C assured</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
};
