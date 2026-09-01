import React, { useEffect } from 'react';
import { CheckCircle2, AlertCircle, Info, X } from 'lucide-react';

export interface ToastMessage {
  id: number;
  message: string;
  type?: 'success' | 'error' | 'info' | 'warning';
}

interface ToastProps {
  toast: ToastMessage | null;
  onClose: () => void;
}

export const Toast: React.FC<ToastProps> = ({ toast, onClose }) => {
  useEffect(() => {
    if (!toast) return;
    const timer = setTimeout(() => {
      onClose();
    }, 3500);
    return () => clearTimeout(timer);
  }, [toast, onClose]);

  if (!toast) return null;

  const getIcon = () => {
    switch (toast.type) {
      case 'success':
        return <CheckCircle2 className="w-5 h-5 text-emerald-500 shrink-0" />;
      case 'error':
        return <AlertCircle className="w-5 h-5 text-red-500 shrink-0" />;
      case 'warning':
        return <AlertCircle className="w-5 h-5 text-amber-500 shrink-0" />;
      default:
        return <Info className="w-5 h-5 text-blue-500 shrink-0" />;
    }
  };

  const getBorderColor = () => {
    switch (toast.type) {
      case 'success':
        return 'border-emerald-200 bg-emerald-50/95 dark:bg-emerald-950/90 text-emerald-900 dark:text-emerald-100';
      case 'error':
        return 'border-red-200 bg-red-50/95 dark:bg-red-950/90 text-red-900 dark:text-red-100';
      case 'warning':
        return 'border-amber-200 bg-amber-50/95 dark:bg-amber-950/90 text-amber-900 dark:text-amber-100';
      default:
        return 'border-slate-200 bg-white/95 dark:bg-slate-900/90 text-slate-900 dark:text-slate-100';
    }
  };

  return (
    <div className="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-[92vw] max-w-md animate-fadeIn shadow-2xl">
      <div className={`flex items-center justify-between gap-3 px-4 py-3 rounded-2xl border backdrop-blur-md transition-all ${getBorderColor()}`}>
        <div className="flex items-center gap-3 min-w-0">
          {getIcon()}
          <span className="text-xs sm:text-sm font-extrabold leading-snug">
            {toast.message}
          </span>
        </div>
        <button
          onClick={onClose}
          className="p-1 rounded-lg hover:bg-black/10 dark:hover:bg-white/10 text-current transition-colors shrink-0"
          aria-label="Close notification"
        >
          <X className="w-4 h-4" />
        </button>
      </div>
    </div>
  );
};
