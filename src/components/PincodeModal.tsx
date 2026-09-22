import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { MapPin, CheckCircle, AlertCircle, X, Check, Sparkles } from 'lucide-react';

interface PincodeModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export const PincodeModal: React.FC<PincodeModalProps> = ({ isOpen, onClose }) => {
  const { activePincode, setPincode, serviceablePincodes, isPincodeServiceable } = useApp();
  const [inputPin, setInputPin] = useState(activePincode || '');
  const [errorMsg, setErrorMsg] = useState('');
  const [isVerifying, setIsVerifying] = useState(false);

  if (!isOpen) return null;

  const popularAreas = [
    { name: 'Action Area I (New Town)', pin: '700156' },
    { name: 'Action Area II / Chinar Park', pin: '700136' },
    { name: 'Action Area III (New Town)', pin: '700160' },
    { name: 'Rajarhat / DLF 1 & 2', pin: '700135' },
    { name: 'Sector V / Salt Lake IT Hub', pin: '700091' },
    { name: 'Salt Lake (Sector I, II, III)', pin: '700064' },
    { name: 'Ultadanga / Kankurgachi', pin: '700010' },
    { name: 'EM Bypass / Ruby', pin: '700107' },
  ];

  const handleSubmit = (pinToSet?: string) => {
    const targetPin = pinToSet || inputPin;
    const cleanPin = targetPin.trim().replace(/\D/g, '');

    if (!cleanPin || cleanPin.length !== 6) {
      setErrorMsg('Please enter a valid 6-digit Indian pincode (e.g. 700135)');
      return;
    }

    // Check serviceability
    if (!isPincodeServiceable(cleanPin)) {
      setErrorMsg(`Sorry, delivery is not available for ${cleanPin}. Please select from our serviceable areas.`);
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
        <div className="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-slate-800 space-y-5 animate-fadeIn">
          
          {/* Header */}
          <div className="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4">
            <div className="flex items-center gap-2.5">
              <div className="p-2.5 bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 rounded-2xl">
                <MapPin className="w-6 h-6 animate-bounce" />
              </div>
              <div>
                <h3 className="font-sans font-black text-lg text-gray-900 dark:text-white tracking-tight">
                  Delivery Location
                </h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  Select your pincode for fresh express delivery
                </p>
              </div>
            </div>
            <button
              onClick={onClose}
              className="p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Form input */}
          <div className="space-y-2.5">
            <label className="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider block">
              Enter 6-Digit Delivery Pincode
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
                onKeyDown={(e) => {
                  if (e.key === 'Enter') handleSubmit();
                }}
                placeholder="e.g. 700135, 700156, 700091"
                className="w-full pl-4 pr-24 py-3.5 bg-gray-50 dark:bg-slate-800/80 border border-gray-200 dark:border-slate-700 rounded-2xl text-base font-bold text-gray-900 dark:text-white tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500"
              />
              <button
                onClick={() => handleSubmit()}
                className="absolute right-2 top-1/2 -translate-y-1/2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer"
              >
                Apply
              </button>
            </div>

            {errorMsg && (
              <div className="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 rounded-xl flex items-start gap-2 text-red-600 dark:text-red-400 text-xs font-semibold">
                <AlertCircle className="w-4 h-4 shrink-0 mt-0.5" />
                <span>{errorMsg}</span>
              </div>
            )}
          </div>

          {/* Quick Select Serviceable Areas */}
          <div className="space-y-2.5">
            <div className="flex items-center justify-between">
              <span className="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                <Sparkles className="w-3.5 h-3.5 text-amber-500" />
                <span>Serviceable Delivery Hubs</span>
              </span>
              <span className="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">
                Active Zone
              </span>
            </div>

            <div className="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-1">
              {popularAreas.map(area => {
                const isSelected = activePincode === area.pin;
                return (
                  <button
                    key={area.pin}
                    type="button"
                    onClick={() => {
                      setInputPin(area.pin);
                      handleSubmit(area.pin);
                    }}
                    className={`p-2.5 rounded-xl border text-left transition-all cursor-pointer flex flex-col justify-between ${
                      isSelected
                        ? 'border-red-500 bg-red-50/60 dark:bg-red-950/30'
                        : 'border-gray-200/80 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/40 hover:border-gray-300 dark:hover:border-slate-700'
                    }`}
                  >
                    <div className="flex items-center justify-between mb-1">
                      <span className="font-mono font-bold text-xs text-gray-900 dark:text-white">
                        {area.pin}
                      </span>
                      {isSelected && (
                        <Check className="w-3.5 h-3.5 text-red-600 shrink-0" />
                      )}
                    </div>
                    <span className="text-[10.5px] text-gray-500 dark:text-gray-400 line-clamp-1">
                      {area.name}
                    </span>
                  </button>
                );
              })}
            </div>
          </div>

          {/* Guarantee Footer */}
          <div className="pt-3 border-t border-gray-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">
            <div className="flex items-center gap-1.5">
              <CheckCircle className="w-4 h-4" />
              <span>Cold-chain delivery under 4°C assured</span>
            </div>
            <span className="text-[10px] text-gray-400">Kolkata Delivery Zone</span>
          </div>

        </div>
      </div>
    </div>
  );
};
