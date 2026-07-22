import React from 'react';

export const SkeletonHero: React.FC = () => {
  return (
    <div className="w-full h-44 sm:h-64 rounded-2xl bg-slate-200 dark:bg-slate-800 animate-pulse overflow-hidden relative shadow-sm">
      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 dark:via-slate-700/30 to-transparent animate-shimmer" />
      <div className="p-6 space-y-3 max-w-sm">
        <div className="h-4 bg-slate-300 dark:bg-slate-700 rounded-full w-24" />
        <div className="h-7 bg-slate-300 dark:bg-slate-700 rounded-xl w-3/4" />
        <div className="h-4 bg-slate-300 dark:bg-slate-700 rounded-lg w-1/2" />
      </div>
    </div>
  );
};

export const SkeletonCategories: React.FC = () => {
  return (
    <div className="flex gap-4 overflow-x-auto pb-2 scrollbar-none">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="flex flex-col items-center gap-2 shrink-0 w-16 sm:w-20 animate-pulse">
          <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-slate-200 dark:bg-slate-800" />
          <div className="h-3 bg-slate-200 dark:bg-slate-800 rounded-full w-12" />
        </div>
      ))}
    </div>
  );
};

export const SkeletonProductCard: React.FC = () => {
  return (
    <div className="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 space-y-3 animate-pulse shadow-xs">
      <div className="w-full h-48 bg-slate-200 dark:bg-slate-800 rounded-xl" />
      <div className="h-4 bg-slate-200 dark:bg-slate-800 rounded-md w-3/4" />
      <div className="h-3 bg-slate-200 dark:bg-slate-800 rounded-md w-1/2" />
      <div className="flex items-center justify-between pt-2">
        <div className="h-6 bg-slate-200 dark:bg-slate-800 rounded-lg w-20" />
        <div className="h-9 bg-slate-200 dark:bg-slate-800 rounded-xl w-24" />
      </div>
    </div>
  );
};

export const SkeletonProductGrid: React.FC<{ count?: number }> = ({ count = 6 }) => {
  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      {Array.from({ length: count }).map((_, i) => (
        <SkeletonProductCard key={i} />
      ))}
    </div>
  );
};
