import React, { useEffect, useState } from 'react';
import { useApp } from '../context/AppContext';
import { DeliveryHub } from '../types';
import { MapPin, AlertCircle, X, Check, Sparkles, Loader2, Clock, CheckCircle } from 'lucide-react';

interface PincodeModalProps {
  isOpen: boolean;
  onClose: () => void;
}

type VerificationState = 'idle' | 'checking' | 'coming_soon' | 'invalid';

export const PincodeModal: React.FC<PincodeModalProps> = ({ isOpen, onClose }) => {
  const {
    activePincode,
    setPincode,
    serviceablePincodes,
    serviceableHubs,
    isLoadingPincodes,
    pincodesError,
    refreshServiceablePincodes,
    verifyPincode
  } = useApp();
  const [inputPin, setInputPin] = useState(activePincode || '');
  const [verificationState, setVerificationState] = useState<VerificationState>('idle');
  const [message, setMessage] = useState('');

  // Always pull a fresh Admin-controlled list whenever the modal is opened
  useEffect(() => {
    if (isOpen) {
      setInputPin(activePincode || '');
      refreshServiceablePincodes();
    }
  }, [isOpen]);

  if (!isOpen) return null;

  // Admin-managed delivery hubs (pincode-only fallback when no area name is configured)
  const hubs: DeliveryHub[] = serviceableHubs.length > 0
    ? serviceableHubs
    : serviceablePincodes.map(pin => ({ pincode: pin, areaName: null, area_name: null, isActive: true }));

  const resetVerification = () => {
    setVerificationState('idle');
    setMessage('');
  };

  const handleSubmit = async (pinToSet?: string) => {
    const targetPin = pinToSet || inputPin;
    const cleanPin = targetPin.trim().replace(/\D/g, '');

    if (!cleanPin || cleanPin.length !== 6) {
      setVerificationState('invalid');
      setMessage('Please enter a valid 6-digit Indian pincode (e.g. 700135).');
      return;
    }

    setVerificationState('checking');
    setMessage('');

    // Authoritative backend validation against the Admin-controlled pincode list
    const result = await verifyPincode(cleanPin);

    if (result.status === 'invalid') {
      setVerificationState('invalid');
      setMessage(result.message);
      return;
    }

    if (!result.isServiceable) {
      setVerificationState('coming_soon');
      setMessage(result.message);
      // Refresh in case the admin changed the list while the modal was open
      refreshServiceablePincodes();
      return;
    }

    resetVerification();
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
                  if (verificationState !== 'idle') resetVerification();
                }}
                onKeyDown={(e) => {
                  if (e.key === 'Enter') handleSubmit();
                }}
                placeholder="e.g. 700135, 700156, 700091"
                className="w-full pl-4 pr-24 py-3.5 bg-gray-50 dark:bg-slate-800/80 border border-gray-200 dark:border-slate-700 rounded-2xl text-base font-bold text-gray-900 dark:text-white tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500"
              />
              <button
                onClick={() => handleSubmit()}
                disabled={verificationState === 'checking'}
                className="absolute right-2 top-1/2 -translate-y-1/2 bg-red-600 hover:bg-red-700 disabled:opacity-70 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer flex items-center gap-1.5"
              >
                {verificationState === 'checking' ? (
                  <>
                    <Loader2 className="w-3.5 h-3.5 animate-spin" />
                    <span>Checking</span>
                  </>
                ) : (
                  <span>Apply</span>
                )}
              </button>
            </div>

            {verificationState === 'invalid' && message && (
              <div className="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 rounded-xl flex items-start gap-2 text-red-600 dark:text-red-400 text-xs font-semibold">
                <AlertCircle className="w-4 h-4 shrink-0 mt-0.5" />
                <span>{message}</span>
              </div>
            )}

            {/* Non-serviceable pincode → Coming Soon state */}
            {verificationState === 'coming_soon' && (
              <div className="p-3.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/60 rounded-2xl space-y-2">
                <div className="flex items-start gap-2">
                  <Clock className="w-4 h-4 shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" />
                  <div className="text-xs">
                    <p className="font-black text-amber-700 dark:text-amber-400">
                      Coming soon to {inputPin.trim().replace(/\D/g, '') || 'this area'}!
                    </p>
                    <p className="text-[11px] text-amber-700/90 dark:text-amber-300/90 font-medium mt-0.5 leading-relaxed">
                      {message || 'Our cold-chain delivery is not available here yet. Please choose one of our serviceable delivery areas below.'}
                    </p>
                  </div>
                </div>
                <button
                  type="button"
                  onClick={() => {
                    setInputPin('');
                    resetVerification();
                  }}
                  className="w-full text-[11px] font-extrabold text-amber-700 dark:text-amber-400 bg-amber-100/70 dark:bg-amber-950/50 hover:bg-amber-100 dark:hover:bg-amber-900/40 rounded-xl py-2 transition-colors cursor-pointer"
                >
                  Try Another Pincode
                </button>
              </div>
            )}

            {pincodesError && verificationState === 'idle' && (
              <div className="p-3 bg-gray-50 dark:bg-slate-800/60 border border-gray-200 dark:border-slate-700 rounded-xl flex items-start gap-2 text-gray-500 dark:text-gray-400 text-[11px] font-semibold">
                <AlertCircle className="w-4 h-4 shrink-0 mt-0.5" />
                <span>{pincodesError}</span>
              </div>
            )}
          </div>

          {/* Quick Select Serviceable Areas (admin controlled) */}
          <div className="space-y-2.5">
            <div className="flex items-center justify-between">
              <span className="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                <Sparkles className="w-3.5 h-3.5 text-amber-500" />
                <span>Serviceable Delivery Hubs</span>
              </span>
              <span className="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">
                {isLoadingPincodes
                  ? 'Loading…'
                  : `${hubs.length} Active Zone${hubs.length === 1 ? '' : 's'}`}
              </span>
            </div>

            {isLoadingPincodes ? (
              <div className="grid grid-cols-2 gap-2">
                {[0, 1, 2, 3].map(i => (
                  <div key={i} className="h-14 rounded-xl bg-gray-100 dark:bg-slate-800/60 animate-pulse" />
                ))}
              </div>
            ) : hubs.length === 0 ? (
              <div className="p-4 bg-gray-50 dark:bg-slate-800/40 border border-dashed border-gray-200 dark:border-slate-700 rounded-2xl text-center">
                <Clock className="w-5 h-5 mx-auto text-amber-500" />
                <p className="text-xs font-bold text-gray-700 dark:text-gray-200 mt-1.5">
                  Delivery zones are being updated
                </p>
                <p className="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">
                  Our serviceable delivery areas are being reconfigured right now. Please check back shortly.
                </p>
              </div>
            ) : (
              <div className="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-1">
                {hubs.map(hub => {
                  const isSelected = activePincode === hub.pincode;
                  const areaLabel = hub.areaName || hub.area_name || 'Serviceable Delivery Area';
                  return (
                    <button
                      key={hub.pincode}
                      type="button"
                      onClick={() => {
                        setInputPin(hub.pincode);
                        handleSubmit(hub.pincode);
                      }}
                      className={`p-2.5 rounded-xl border text-left transition-all cursor-pointer flex flex-col justify-between ${
                        isSelected
                          ? 'border-red-500 bg-red-50/60 dark:bg-red-950/30'
                          : 'border-gray-200/80 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/40 hover:border-gray-300 dark:hover:border-slate-700'
                      }`}
                    >
                      <div className="flex items-center justify-between mb-1">
                        <span className="font-mono font-bold text-xs text-gray-900 dark:text-white">
                          {hub.pincode}
                        </span>
                        {isSelected && (
                          <Check className="w-3.5 h-3.5 text-red-600 shrink-0" />
                        )}
                      </div>
                      <span className="text-[10.5px] text-gray-500 dark:text-gray-400 line-clamp-1">
                        {areaLabel}
                      </span>
                    </button>
                  );
                })}
              </div>
            )}
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
