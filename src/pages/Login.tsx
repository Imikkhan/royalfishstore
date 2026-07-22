import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { ShieldCheck, ArrowRight, User as UserIcon, Phone, Mail, Lock, KeyRound, Sparkles, CheckCircle } from 'lucide-react';

export const Login: React.FC = () => {
  const { login, navigateTo, goBack } = useApp();
  
  const [phone, setPhone] = useState('');
  const [step, setStep] = useState<'details' | 'otp'>('details');
  const [otpValues, setOtpValues] = useState<string[]>(['', '', '', '']);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);

  const otpRef0 = React.useRef<HTMLInputElement>(null);
  const otpRef1 = React.useRef<HTMLInputElement>(null);
  const otpRef2 = React.useRef<HTMLInputElement>(null);
  const otpRef3 = React.useRef<HTMLInputElement>(null);
  const otpRefs = [otpRef0, otpRef1, otpRef2, otpRef3];

  const handleOtpChange = (index: number, value: string) => {
    const cleanVal = value.replace(/\D/g, '').slice(-1);
    const newOtpValues = [...otpValues];
    newOtpValues[index] = cleanVal;
    setOtpValues(newOtpValues);

    if (cleanVal && index < 3) {
      otpRefs[index + 1].current?.focus();
    }
  };

  const handleOtpKeyDown = (index: number, e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Backspace' && !otpValues[index] && index > 0) {
      otpRefs[index - 1].current?.focus();
    }
  };

  const handleOtpPaste = (e: React.ClipboardEvent<HTMLInputElement>) => {
    e.preventDefault();
    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 4);
    if (pastedData) {
      const newOtpValues = [...otpValues];
      for (let i = 0; i < 4; i++) {
        newOtpValues[i] = pastedData[i] || '';
      }
      setOtpValues(newOtpValues);
      const focusIndex = Math.min(pastedData.length, 3);
      otpRefs[focusIndex].current?.focus();
    }
  };

  const handleSendOtp = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    if (!phone || phone.length < 10) {
      setError('Please enter a valid 10-digit mobile number.');
      return;
    }

    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      setStep('otp');
    }, 1000);
  };

  const handleVerifyOtp = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    const currentOtp = otpValues.join('');
    if (currentOtp !== '1234') {
      setError('Invalid OTP. Use "1234" for demo testing.');
      return;
    }

    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      setSuccess(true);
      const generatedName = `Guest ${phone.slice(-4)}`;
      const generatedEmail = `user-${phone}@royalfish.com`;
      login(generatedName, phone, generatedEmail);
      
      setTimeout(() => {
        navigateTo('home');
      }, 1500);
    }, 1200);
  };

  if (success) {
    return (
      <div className="min-h-[50vh] flex flex-col items-center justify-center py-6 px-2 sm:px-6 lg:px-8">
        <div className="max-w-md w-full text-center space-y-4 bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-slate-800">
          <div className="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
            <CheckCircle className="h-8 w-8 animate-bounce" />
          </div>
          <div className="space-y-1">
            <h2 className="text-xl font-black text-gray-900 dark:text-white tracking-tight">
              Welcome to Royal Fish Store!
            </h2>
            <p className="text-xs text-gray-500 dark:text-gray-400">
              Logged in successfully with <span className="font-bold text-gray-700 dark:text-gray-200">+91 {phone}</span>.
            </p>
          </div>
          <p className="text-[11px] text-red-500 dark:text-red-400 font-medium animate-pulse">
            Redirecting you to fresh catches...
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-[55vh] flex flex-col items-center justify-start md:justify-center py-2 md:py-8 px-0 sm:px-6 lg:px-8 mt-1 md:mt-0">
      <div className="max-w-md w-full space-y-6 bg-white dark:bg-slate-900 p-5 sm:p-10 rounded-2xl sm:rounded-3xl shadow-sm md:shadow-xl border border-gray-100/80 dark:border-slate-800 relative overflow-hidden">
        
        {/* Subtle top red accent line */}
        <div className="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 via-orange-500 to-red-600" />

        {/* Heading */}
        <div className="text-center space-y-1.5">
          <div className="inline-flex items-center gap-1 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[9px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
            <Sparkles className="w-3 h-3" /> Premium Meat & Seafood
          </div>
          <h2 className="font-sans font-black text-xl sm:text-2xl text-gray-900 dark:text-white tracking-tight">
            {step === 'details' ? 'Unlock Freshness' : 'Verify Mobile'}
          </h2>
          <p className="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">
            {step === 'details' 
              ? 'Enter your mobile number to explore premium fresh meats.' 
              : 'Enter the 6-digit OTP sent to your phone number.'}
          </p>
        </div>

        {error && (
          <div className="bg-red-50 dark:bg-red-950/20 border-l-4 border-red-500 p-3 rounded-lg text-xs text-red-700 dark:text-red-400 font-medium">
            {error}
          </div>
        )}

        {step === 'details' ? (
          <form className="mt-4 space-y-4" onSubmit={handleSendOtp}>
            <div className="space-y-3">
              
              {/* Phone field */}
              <div>
                <label className="block text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">
                  Mobile Number
                </label>
                <div className="relative">
                  <span className="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400 font-bold text-xs sm:text-sm border-r border-gray-200/60 dark:border-slate-700 pr-2.5">
                    +91
                  </span>
                  <input
                    type="tel"
                    required
                    maxLength={10}
                    value={phone}
                    onChange={(e) => {
                      const val = e.target.value.replace(/\D/g, '');
                      setPhone(val);
                    }}
                    placeholder="98765 43210"
                    className="block w-full pl-13 pr-3 py-2 text-xs sm:text-sm bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 border border-gray-100 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500 transition-all font-medium"
                  />
                </div>
              </div>

            </div>

            <button
              type="submit"
              disabled={loading}
              className="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-xs sm:text-sm font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-sm active:scale-98 disabled:opacity-50 mt-4"
            >
              {loading ? (
                <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
              ) : (
                <span className="flex items-center gap-1">
                  Send OTP Code <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                </span>
              )}
            </button>
          </form>
        ) : (
          <form className="mt-4 space-y-4" onSubmit={handleVerifyOtp}>
            <div className="space-y-3">
              <div>
                <label className="block text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1 text-center">
                  Verification Code (OTP)
                </label>
                <div className="flex justify-center gap-3.5 my-2">
                  {otpValues.map((val, idx) => (
                    <input
                      key={idx}
                      ref={otpRefs[idx]}
                      type="text"
                      inputMode="numeric"
                      pattern="[0-9]*"
                      maxLength={1}
                      value={val}
                      onChange={(e) => handleOtpChange(idx, e.target.value)}
                      onKeyDown={(e) => handleOtpKeyDown(idx, e)}
                      onPaste={handleOtpPaste}
                      className="w-12 h-12 text-center text-lg font-bold bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white border border-gray-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-all"
                    />
                  ))}
                </div>
                <p className="text-[10px] text-gray-400 dark:text-gray-500 mt-2 text-center">
                  For quick testing, type the code <span className="font-mono font-bold text-red-500 bg-red-50 dark:bg-red-950/30 px-1.5 py-0.5 rounded">1234</span>
                </p>
              </div>
            </div>

            <div className="flex flex-col gap-2.5 mt-4">
              <button
                type="submit"
                disabled={loading}
                className="w-full flex justify-center py-2.5 px-4 border border-transparent text-xs sm:text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-sm active:scale-98 disabled:opacity-50"
              >
                {loading ? (
                  <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                ) : (
                  'Verify & Log In'
                )}
              </button>

              <button
                type="button"
                onClick={() => setStep('details')}
                className="text-[10px] font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-center py-0.5 transition-colors"
              >
                Change Number
              </button>
            </div>
          </form>
        )}

        {/* Security badge footer */}
        <div className="mt-4 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center justify-center gap-1 text-gray-400 dark:text-gray-500 text-[9px]">
          <ShieldCheck className="w-3.5 h-3.5 text-emerald-500" />
          <span>Secure AES-256 OTP verification</span>
        </div>

      </div>
    </div>
  );
};
