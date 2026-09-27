import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { Star, CheckCircle2, Image as ImageIcon, Video as VideoIcon, Plus, X, ArrowLeft, Camera, ThumbsUp, ShieldCheck, Film, UploadCloud } from 'lucide-react';
import { Review } from '../types';

export const Reviews: React.FC = () => {
  const { reviews, reviewsSummary, isLoadingReviews, submitReview, navigateTo, user } = useApp();
  
  const [activeFilter, setActiveFilter] = useState<'all' | 'media' | '5' | '4' | '3'>('all');
  const [isWriteModalOpen, setIsWriteModalOpen] = useState(false);
  const [lightboxImage, setLightboxImage] = useState<string | null>(null);

  // Form State for Write a Review
  const [name, setName] = useState(user?.name || '');
  const [rating, setRating] = useState(5);
  const [hoverRating, setHoverRating] = useState(0);
  const [quote, setQuote] = useState('');
  const [productTag, setProductTag] = useState('');
  const [imageFiles, setImageFiles] = useState<File[]>([]);
  const [imagePreviews, setImagePreviews] = useState<string[]>([]);
  const [videoFile, setVideoFile] = useState<File | null>(null);
  const [videoFilePreview, setVideoFilePreview] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Filter reviews
  const filteredReviews = reviews.filter(r => {
    if (activeFilter === 'media') {
      const hasImg = r.images && r.images.length > 0;
      const hasVid = Boolean(r.video_url || r.videoUrl);
      return hasImg || hasVid;
    }
    if (activeFilter === '5') return r.rating === 5;
    if (activeFilter === '4') return r.rating === 4;
    if (activeFilter === '3') return r.rating === 3;
    return true;
  });

  const totalReviews = reviewsSummary?.total_reviews || reviews.length;
  const avgRating = reviewsSummary?.average_rating || (reviews.length > 0 ? (reviews.reduce((acc, r) => acc + r.rating, 0) / reviews.length).toFixed(1) : '4.9');
  const withMediaCount = reviewsSummary?.with_media_count || reviews.filter(r => (r.images && r.images.length > 0) || r.video_url).length;

  const ratingLabels: { [key: number]: string } = {
    5: 'Excellent! Pure fresh catch & on-time',
    4: 'Good quality, satisfied',
    3: 'Average experience',
    2: 'Below expectations',
    1: 'Needs improvement'
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files) return;
    const files = Array.from(e.target.files);
    setImageFiles(prev => [...prev, ...files]);
    
    // Create preview URLs
    const newPreviews = files.map(f => URL.createObjectURL(f));
    setImagePreviews(prev => [...prev, ...newPreviews]);
  };

  const removeImage = (index: number) => {
    setImageFiles(prev => prev.filter((_, i) => i !== index));
    setImagePreviews(prev => prev.filter((_, i) => i !== index));
  };

  const handleVideoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files || !e.target.files[0]) return;
    const file = e.target.files[0];
    
    // Validation: 50MB max
    if (file.size > 50 * 1024 * 1024) {
      alert('Video file size exceeds 50MB limit. Please select a shorter clip.');
      return;
    }

    setVideoFile(file);
    setVideoFilePreview(URL.createObjectURL(file));
  };

  const removeVideo = () => {
    setVideoFile(null);
    if (videoFilePreview) {
      URL.revokeObjectURL(videoFilePreview);
      setVideoFilePreview(null);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) return;
    if (!quote.trim()) return;

    setIsSubmitting(true);
    const formData = new FormData();
    formData.append('name', name.trim());
    formData.append('rating', String(rating));
    formData.append('quote', quote.trim());
    formData.append('product_tag', productTag.trim() || 'Royal Fish Fresh Catch');
    
    imageFiles.forEach(file => {
      formData.append('images[]', file);
    });

    if (videoFile) {
      formData.append('video_file', videoFile);
      formData.append('video', videoFile);
    }

    const result = await submitReview(formData);
    setIsSubmitting(false);

    if (result.success) {
      setIsWriteModalOpen(false);
      // Reset form
      setQuote('');
      setProductTag('');
      setImageFiles([]);
      setImagePreviews([]);
      removeVideo();
    }
  };

  return (
    <div className="max-w-5xl mx-auto space-y-6 pb-12 animate-fadeIn">
      {/* Top Back & Header */}
      <div className="flex items-center justify-between">
        <button
          onClick={() => navigateTo('home')}
          className="inline-flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-300 hover:text-[#fc490f] transition-colors cursor-pointer bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-slate-800 shadow-2xs"
        >
          <ArrowLeft className="w-4 h-4" />
          <span>Back to Home</span>
        </button>

        <button
          onClick={() => setIsWriteModalOpen(true)}
          className="inline-flex items-center gap-2 bg-[#fc490f] hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md transition-all active:scale-95 cursor-pointer"
        >
          <Plus className="w-4 h-4" />
          <span>Write a Review</span>
        </button>
      </div>

      {/* Hero Reviews Summary Card */}
      <div className="bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-transparent bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-amber-200/60 dark:border-slate-800 shadow-sm">
        <div className="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
          
          {/* Left: Overall Rating */}
          <div className="md:col-span-5 text-center md:text-left space-y-2 border-b md:border-b-0 md:border-r border-amber-200/60 dark:border-slate-800 pb-6 md:pb-0 md:pr-6">
            <span className="text-[11px] font-extrabold uppercase tracking-widest text-[#fc490f]">
              Customer Satisfaction
            </span>
            <div className="flex items-baseline justify-center md:justify-start gap-2">
              <span className="text-5xl font-black text-gray-900 dark:text-white tracking-tight">
                {avgRating}
              </span>
              <span className="text-gray-400 font-bold text-lg">/ 5.0</span>
            </div>

            <div className="flex items-center justify-center md:justify-start gap-1 text-amber-400">
              {[...Array(5)].map((_, i) => (
                <Star key={i} className="w-5 h-5 fill-amber-400" />
              ))}
            </div>

            <p className="text-xs text-gray-600 dark:text-gray-300 font-medium">
              Based on <span className="font-bold text-gray-900 dark:text-white">{totalReviews}</span> verified seafood lover reviews
            </p>

            <div className="pt-2 flex items-center justify-center md:justify-start gap-2 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
              <ShieldCheck className="w-4 h-4" />
              <span>100% Genuine & Verified Purchases</span>
            </div>
          </div>

          {/* Right: Feature Highlights & CTA */}
          <div className="md:col-span-7 space-y-4">
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xs p-3 rounded-2xl border border-gray-100 dark:border-slate-700/60 text-center">
                <span className="text-lg font-black text-[#fc490f]">99.4%</span>
                <p className="text-[10px] text-gray-500 font-medium mt-0.5">Freshness Rated 5★</p>
              </div>
              <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xs p-3 rounded-2xl border border-gray-100 dark:border-slate-700/60 text-center">
                <span className="text-lg font-black text-emerald-600">30-45m</span>
                <p className="text-[10px] text-gray-500 font-medium mt-0.5">Average Express Delivery</p>
              </div>
              <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xs p-3 rounded-2xl border border-gray-100 dark:border-slate-700/60 text-center col-span-2 sm:col-span-1">
                <span className="text-lg font-black text-purple-600">{withMediaCount}</span>
                <p className="text-[10px] text-gray-500 font-medium mt-0.5">Photos & Videos Shared</p>
              </div>
            </div>

            <div className="flex flex-wrap items-center justify-between gap-3 pt-2">
              <p className="text-xs text-gray-600 dark:text-gray-400">
                Ordered recently? Upload your unboxing or cooking video & photos to help other fish lovers!
              </p>
              <button
                onClick={() => setIsWriteModalOpen(true)}
                className="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs px-4 py-2 rounded-xl shadow-xs transition-all active:scale-95 cursor-pointer flex items-center gap-1.5"
              >
                <VideoIcon className="w-3.5 h-3.5" />
                <span>Upload Photos / Video</span>
              </button>
            </div>
          </div>

        </div>
      </div>

      {/* Filter Tabs */}
      <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        <button
          onClick={() => setActiveFilter('all')}
          className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer ${
            activeFilter === 'all'
              ? 'bg-[#fc490f] text-white shadow-sm'
              : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-800 hover:bg-gray-50'
          }`}
        >
          All Reviews ({reviews.length})
        </button>

        <button
          onClick={() => setActiveFilter('media')}
          className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 ${
            activeFilter === 'media'
              ? 'bg-[#fc490f] text-white shadow-sm'
              : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-800 hover:bg-gray-50'
          }`}
        >
          <Camera className="w-3.5 h-3.5" />
          <span>With Photos & Videos ({withMediaCount})</span>
        </button>

        <button
          onClick={() => setActiveFilter('5')}
          className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1 ${
            activeFilter === '5'
              ? 'bg-[#fc490f] text-white shadow-sm'
              : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-800 hover:bg-gray-50'
          }`}
        >
          <Star className="w-3 h-3 fill-amber-400 text-amber-400" />
          <span>5 Stars</span>
        </button>

        <button
          onClick={() => setActiveFilter('4')}
          className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1 ${
            activeFilter === '4'
              ? 'bg-[#fc490f] text-white shadow-sm'
              : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-800 hover:bg-gray-50'
          }`}
        >
          <Star className="w-3 h-3 fill-amber-400 text-amber-400" />
          <span>4 Stars</span>
        </button>

        <button
          onClick={() => setActiveFilter('3')}
          className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1 ${
            activeFilter === '3'
              ? 'bg-[#fc490f] text-white shadow-sm'
              : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-800 hover:bg-gray-50'
          }`}
        >
          <Star className="w-3 h-3 fill-amber-400 text-amber-400" />
          <span>3 Stars</span>
        </button>
      </div>

      {/* Reviews Grid */}
      {isLoadingReviews ? (
        <div className="space-y-4">
          {[1, 2, 3].map(n => (
            <div key={n} className="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-slate-800 animate-pulse space-y-3">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-full bg-gray-200 dark:bg-slate-800"></div>
                <div className="space-y-1.5 flex-1">
                  <div className="w-28 h-3 bg-gray-200 dark:bg-slate-800 rounded"></div>
                  <div className="w-20 h-2.5 bg-gray-100 dark:bg-slate-800 rounded"></div>
                </div>
              </div>
              <div className="w-full h-12 bg-gray-100 dark:bg-slate-800 rounded-xl"></div>
            </div>
          ))}
        </div>
      ) : filteredReviews.length === 0 ? (
        <div className="bg-white dark:bg-slate-900 p-12 text-center rounded-3xl border border-gray-100 dark:border-slate-800 space-y-3">
          <div className="w-12 h-12 rounded-full bg-orange-100 text-[#fc490f] flex items-center justify-center mx-auto">
            <Camera className="w-6 h-6" />
          </div>
          <h4 className="text-sm font-bold text-gray-900 dark:text-white">No reviews found under this filter</h4>
          <p className="text-xs text-gray-500">Be the first to share your seafood review, photos, or video!</p>
          <button
            onClick={() => setIsWriteModalOpen(true)}
            className="mt-2 bg-[#fc490f] text-white font-bold text-xs px-4 py-2 rounded-xl shadow-xs"
          >
            Write a Review
          </button>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {filteredReviews.map((rev) => {
            const imgs: string[] = rev.images && Array.isArray(rev.images) ? rev.images : [];
            const vUrl = rev.video_url || rev.videoUrl;

            return (
              <div 
                key={rev.id} 
                className="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between space-y-4"
              >
                <div className="space-y-3">
                  {/* Top user header */}
                  <div className="flex items-start justify-between gap-3">
                    <div className="flex items-center gap-3">
                      <img 
                        src={rev.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80'} 
                        alt={rev.name} 
                        className="w-11 h-11 rounded-full object-cover border border-gray-200 dark:border-slate-700 shrink-0"
                      />
                      <div>
                        <div className="flex items-center gap-1.5">
                          <h4 className="text-xs sm:text-sm font-bold text-gray-900 dark:text-white">
                            {rev.name}
                          </h4>
                          {(rev.isVerified ?? rev.is_verified ?? true) && (
                            <span className="inline-flex items-center gap-0.5 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold">
                              <CheckCircle2 className="w-3.5 h-3.5 fill-current" />
                              <span className="hidden sm:inline">Verified Buyer</span>
                            </span>
                          )}
                        </div>

                        {/* Stars */}
                        <div className="flex items-center gap-1 mt-0.5">
                          {[...Array(5)].map((_, i) => (
                            <Star 
                              key={i} 
                              className={`w-3.5 h-3.5 ${
                                i < rev.rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 dark:text-slate-700'
                              }`} 
                            />
                          ))}
                          <span className="text-[10px] text-gray-400 font-bold ml-1">
                            {rev.rating}.0
                          </span>
                        </div>
                      </div>
                    </div>

                    <span className="text-[10px] text-gray-400 shrink-0">
                      {rev.timeAgo || 'Recently'}
                    </span>
                  </div>

                  {/* Review Text */}
                  <p className="text-xs sm:text-[13px] text-gray-700 dark:text-gray-300 leading-relaxed italic">
                    &ldquo;{rev.quote || rev.comment}&rdquo;
                  </p>

                  {/* Attached Media: Photos Gallery */}
                  {imgs.length > 0 && (
                    <div className="space-y-1.5 pt-1">
                      <span className="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                        <ImageIcon className="w-3 h-3 text-purple-500" /> Customer Photos
                      </span>
                      <div className="flex items-center gap-2 flex-wrap">
                        {imgs.map((img, i) => (
                          <div 
                            key={i} 
                            onClick={() => setLightboxImage(img)}
                            className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border border-gray-200 dark:border-slate-800 cursor-pointer hover:opacity-90 hover:scale-105 transition-all shadow-2xs group relative bg-slate-950"
                          >
                            <img src={img} alt="Review attachment" className="w-full h-full object-cover" />
                            <div className="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                              <span className="text-white text-[10px] font-bold">Zoom</span>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Attached Media: Uploaded Customer Video */}
                  {vUrl && (
                    <div className="pt-1 space-y-1.5">
                      <span className="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                        <Film className="w-3 h-3 text-red-500" /> Customer Video
                      </span>
                      
                      <div className="relative rounded-2xl overflow-hidden border border-gray-200 dark:border-slate-800 bg-black shadow-xs max-w-sm">
                        <video 
                          src={vUrl} 
                          controls 
                          playsInline 
                          preload="metadata"
                          className="w-full max-h-64 object-cover rounded-2xl bg-black" 
                        />
                      </div>
                    </div>
                  )}
                </div>

                {/* Bottom product chip & feedback tag */}
                <div className="flex items-center justify-between text-[11px] text-gray-500 pt-2 border-t border-gray-100 dark:border-slate-800/80">
                  <span className="font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-slate-800/60 px-2.5 py-1 rounded-full border border-gray-100 dark:border-slate-700">
                    🏷️ {rev.productTag || rev.product_tag || 'Royal Fish Seafood'}
                  </span>

                  <span className="flex items-center gap-1 text-[10px] text-emerald-600 font-bold">
                    <ThumbsUp className="w-3 h-3" /> Verified Buyer
                  </span>
                </div>
              </div>
            );
          })}
        </div>
      )}

      {/* Write a Review Modal */}
      {isWriteModalOpen && (
        <div 
          onClick={() => setIsWriteModalOpen(false)}
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 animate-fadeIn"
        >
          <div 
            onClick={e => e.stopPropagation()}
            className="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl relative"
          >
            {/* Modal Header */}
            <div className="flex items-center justify-between p-5 sm:p-6 border-b border-gray-100 dark:border-slate-800">
              <div>
                <h3 className="font-extrabold text-gray-900 dark:text-white text-base sm:text-lg flex items-center gap-2">
                  <Star className="w-5 h-5 text-amber-400 fill-amber-400" />
                  <span>Share Your Experience</span>
                </h3>
                <p className="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                  Help other seafood lovers with your honest review & media
                </p>
              </div>
              <button 
                onClick={() => setIsWriteModalOpen(false)}
                className="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 flex items-center justify-center cursor-pointer"
              >
                <X className="w-4 h-4" />
              </button>
            </div>

            {/* Modal Form */}
            <form onSubmit={handleSubmit} className="p-5 sm:p-6 space-y-4">
              {/* Star Rating Picker */}
              <div className="space-y-1.5 text-center sm:text-left">
                <label className="block text-xs font-bold text-gray-700 dark:text-gray-300">
                  Overall Rating *
                </label>
                <div className="flex items-center justify-center sm:justify-start gap-1.5 py-1">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <button
                      type="button"
                      key={star}
                      onMouseEnter={() => setHoverRating(star)}
                      onMouseLeave={() => setHoverRating(0)}
                      onClick={() => setRating(star)}
                      className="p-1 hover:scale-125 transition-transform cursor-pointer"
                    >
                      <Star 
                        className={`w-7 h-7 ${
                          star <= (hoverRating || rating)
                            ? 'text-amber-400 fill-amber-400'
                            : 'text-gray-200 dark:text-slate-700'
                        }`} 
                      />
                    </button>
                  ))}
                  <span className="text-xs font-bold text-gray-700 dark:text-gray-300 ml-2">
                    {rating} / 5 Stars
                  </span>
                </div>
                <p className="text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                  {ratingLabels[hoverRating || rating]}
                </p>
              </div>

              {/* Name & Ordered Product */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                  <label className="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Your Name *
                  </label>
                  <input
                    type="text"
                    required
                    value={name}
                    onChange={e => setName(e.target.value)}
                    placeholder="e.g. Moumita Sen"
                    className="w-full px-3.5 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Product Ordered
                  </label>
                  <input
                    type="text"
                    value={productTag}
                    onChange={e => setProductTag(e.target.value)}
                    placeholder="e.g. Fresh Hilsa / Golda Prawns"
                    className="w-full px-3.5 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white"
                  />
                </div>
              </div>

              {/* Feedback text */}
              <div>
                <label className="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                  Your Review / Experience *
                </label>
                <textarea
                  rows={3}
                  required
                  value={quote}
                  onChange={e => setQuote(e.target.value)}
                  placeholder="How was the freshness, taste, cleaning, and delivery speed? Share details..."
                  className="w-full px-3.5 py-2 text-xs bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white"
                />
              </div>

              {/* Media Attachments Section (Photos & Video Upload) */}
              <div className="p-4 rounded-2xl bg-orange-50/50 dark:bg-slate-800/40 border border-orange-200/60 dark:border-slate-700/60 space-y-4">
                <span className="text-xs font-extrabold text-gray-900 dark:text-white flex items-center gap-1.5">
                  <Camera className="w-4 h-4 text-[#fc490f]" /> Add Photos & Video (Direct Upload)
                </span>

                {/* Photo Upload */}
                <div>
                  <label className="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1.5 flex items-center justify-between">
                    <span>1. Upload Photos (Unboxing, Fresh Catch, Cooked Dish)</span>
                    <span className="text-[10px] text-gray-400 font-normal">JPG, PNG, WebP</span>
                  </label>
                  <input 
                    type="file" 
                    multiple 
                    accept="image/*" 
                    onChange={handleImageChange}
                    className="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-[#fc490f] hover:file:bg-orange-200 cursor-pointer"
                  />

                  {/* Photo Previews */}
                  {imagePreviews.length > 0 && (
                    <div className="flex items-center gap-2 mt-2 flex-wrap">
                      {imagePreviews.map((src, idx) => (
                        <div key={idx} className="relative w-14 h-14 rounded-xl overflow-hidden border border-gray-200 dark:border-slate-700 shadow-2xs group">
                          <img src={src} alt="Preview" className="w-full h-full object-cover" />
                          <button
                            type="button"
                            onClick={() => removeImage(idx)}
                            className="absolute top-0.5 right-0.5 w-4 h-4 bg-red-600 text-white rounded-full flex items-center justify-center text-[10px] cursor-pointer"
                          >
                            ✕
                          </button>
                        </div>
                      ))}
                    </div>
                  )}
                </div>

                {/* Direct Video File Upload */}
                <div className="pt-2 border-t border-orange-200/50 dark:border-slate-700/60">
                  <label className="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1.5 flex items-center justify-between">
                    <span className="flex items-center gap-1">
                      <Film className="w-3.5 h-3.5 text-red-500" />
                      <span>2. Upload Customer Video Clip (MP4, MOV, WebM)</span>
                    </span>
                    <span className="text-[10px] text-gray-400 font-normal">Max 50MB</span>
                  </label>

                  {!videoFile ? (
                    <label className="border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 text-center cursor-pointer hover:border-[#fc490f] dark:hover:border-[#fc490f] transition-colors bg-white/50 dark:bg-slate-900/50">
                      <UploadCloud className="w-6 h-6 text-gray-400 hover:text-[#fc490f]" />
                      <span className="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Click to select video from your phone or PC
                      </span>
                      <span className="text-[10px] text-gray-400">
                        Record or choose your unboxing or cooking video
                      </span>
                      <input 
                        type="file" 
                        accept="video/mp4,video/webm,video/quicktime,video/*" 
                        onChange={handleVideoChange}
                        className="hidden"
                      />
                    </label>
                  ) : (
                    <div className="p-3 bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-700 space-y-2">
                      <div className="flex items-center justify-between">
                        <span className="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                          ✓ Video selected ({((videoFile.size) / (1024 * 1024)).toFixed(1)} MB)
                        </span>
                        <button
                          type="button"
                          onClick={removeVideo}
                          className="text-xs font-bold text-red-500 hover:text-red-700 cursor-pointer"
                        >
                          ✕ Remove Video
                        </button>
                      </div>

                      {videoFilePreview && (
                        <div className="relative aspect-video max-h-48 rounded-xl overflow-hidden bg-black border border-gray-200 dark:border-slate-800">
                          <video 
                            src={videoFilePreview} 
                            controls 
                            className="w-full h-full object-cover" 
                          />
                        </div>
                      )}
                    </div>
                  )}
                </div>
              </div>

              {/* Submit Buttons */}
              <div className="flex items-center justify-end gap-3 pt-2">
                <button
                  type="button"
                  onClick={() => setIsWriteModalOpen(false)}
                  className="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="bg-[#fc490f] hover:bg-orange-600 disabled:opacity-50 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md transition-all active:scale-95 cursor-pointer flex items-center gap-2"
                >
                  {isSubmitting ? (
                    <>
                      <span className="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                      <span>Uploading & Publishing...</span>
                    </>
                  ) : (
                    <span>Submit Review</span>
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Lightbox Image Modal */}
      {lightboxImage && (
        <div 
          onClick={() => setLightboxImage(null)}
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 animate-fadeIn"
        >
          <div 
            onClick={e => e.stopPropagation()}
            className="relative max-w-3xl max-h-[90vh] rounded-2xl overflow-hidden shadow-2xl"
          >
            <button
              onClick={() => setLightboxImage(null)}
              className="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/70 hover:bg-black text-white flex items-center justify-center text-sm font-bold cursor-pointer z-10"
            >
              ✕
            </button>
            <img 
              src={lightboxImage} 
              alt="Full size review photo" 
              className="max-h-[85vh] w-auto max-w-full object-contain mx-auto rounded-2xl" 
            />
          </div>
        </div>
      )}

    </div>
  );
};
