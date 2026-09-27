import React, { createContext, useContext, useState, useEffect } from 'react';
import { Product, CartItem, Address, Order, PageId, User, Slide, Video, DeliveryHub } from '../types';
import { PRODUCTS, CATEGORIES, PROMO_SLIDES } from '../data/products';
import { API_BASE_URL } from '../config';

import { Toast, ToastMessage } from '../components/Toast';
import { trackAddToCart, trackPurchase } from '../utils/tracking';

// Helper: resolve relative image URLs to absolute using the API domain
const API_DOMAIN = API_BASE_URL.replace(/\/api\/?$/, '');
const resolveImageUrl = (img: string | null | undefined): string => {
  if (!img) return 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80';
  if (img.startsWith('http://') || img.startsWith('https://')) return img;
  // Relative path like /uploads/xxx.webp
  return `${API_DOMAIN}${img.startsWith('/') ? '' : '/'}${img}`;
};

// Helper: safely parse a JSON string field into an array, or return as-is if already array
const safeParseArray = (val: any): any[] => {
  if (Array.isArray(val)) return val;
  if (typeof val === 'string') {
    try { const parsed = JSON.parse(val); return Array.isArray(parsed) ? parsed : []; } catch { return []; }
  }
  return [];
};

// Helper: Route parsing and URL generation for full SPA routing
export interface ParsedRoute {
  page: PageId;
  productId: string | null;
  category: string | null;
  query: string | null;
}

export const parseLocationToRoute = (): ParsedRoute => {
  if (typeof window === 'undefined') {
    return { page: 'home', productId: null, category: null, query: null };
  }

  let rawPath = window.location.pathname;
  let rawSearch = window.location.search;
  if (rawPath.includes('?')) {
    const parts = rawPath.split('?');
    rawPath = parts[0];
    rawSearch = rawSearch ? rawSearch + '&' + parts[1] : '?' + parts[1];
  }
  const path = rawPath.replace(/\/+$/, '') || '/';
  const search = rawSearch;
  const hash = window.location.hash;
  const params = new URLSearchParams(search);
  const pageParam = params.get('page');

  // 1. Onepager check (supports /onepager, #onepager, or ad tracking parameters)
  if (
    path === '/onepager' ||
    pageParam === 'onepager' ||
    hash === '#onepager' ||
    params.get('ad') === 'fb' ||
    params.has('fbclid')
  ) {
    return { page: 'onepager', productId: null, category: null, query: null };
  }

  // 2. Product Details check
  // Clean paths: /product/:slugOrId or /products/:slugOrId
  const productMatch = path.match(/^\/(?:product|products)\/([^/?#]+)/i);
  if (productMatch) {
    return {
      page: 'product-details',
      productId: decodeURIComponent(productMatch[1]),
      category: null,
      query: null,
    };
  }
  // Query param fallback: ?page=product-details&id=... or ?product=...
  if (pageParam === 'product-details' || params.has('product')) {
    const prodId = params.get('id') || params.get('product') || params.get('code') || params.get('slug');
    if (prodId) {
      return {
        page: 'product-details',
        productId: prodId,
        category: null,
        query: null,
      };
    }
  }

  // 3. Category View check
  // Clean path: /category/:categorySlug
  const categoryMatch = path.match(/^\/category\/([^/?#]+)/i);
  if (categoryMatch) {
    return {
      page: 'category-view',
      productId: null,
      category: decodeURIComponent(categoryMatch[1]),
      query: null,
    };
  }
  if (pageParam === 'category-view' || (pageParam === 'categories' && params.has('category')) || params.has('category')) {
    const cat = params.get('category');
    if (cat) {
      return {
        page: 'category-view',
        productId: null,
        category: cat,
        query: null,
      };
    }
  }

  // 4. Other Standard Clean Paths
  if (path === '/cart' || pageParam === 'cart') {
    return { page: 'cart', productId: null, category: null, query: null };
  }
  if (path === '/login' || pageParam === 'login') {
    return { page: 'login', productId: null, category: null, query: null };
  }
  if (path === '/profile' || pageParam === 'profile') {
    return { page: 'profile', productId: null, category: null, query: null };
  }
  if (path === '/categories' || pageParam === 'categories') {
    return { page: 'categories', productId: null, category: null, query: null };
  }
  if (path === '/search' || pageParam === 'search') {
    return {
      page: 'search',
      productId: null,
      category: null,
      query: params.get('q') || params.get('search') || null,
    };
  }

  return { page: 'home', productId: null, category: null, query: null };
};

export const buildUrlForRoute = (
  page: PageId,
  productId?: string | null,
  productData?: Product | null,
  categorySlug?: string | null,
  query?: string | null
): string => {
  switch (page) {
    case 'home':
      return '/';
    case 'product-details': {
      const slugOrId = productData?.slug || productData?.product_code || (productData ? String(productData.id) : null) || productId;
      return slugOrId ? `/product/${encodeURIComponent(slugOrId)}` : '/';
    }
    case 'category-view': {
      return categorySlug ? `/category/${encodeURIComponent(categorySlug)}` : '/categories';
    }
    case 'categories':
      return '/categories';
    case 'cart':
      return '/cart';
    case 'login':
      return '/login';
    case 'profile':
      return '/profile';
    case 'search':
      return query ? `/search?q=${encodeURIComponent(query)}` : '/search';
    case 'onepager':
      return '/onepager';
    default:
      return '/';
  }
};

// Normalize a product from the API response to match the frontend Product type
const normalizeProduct = (p: any): Product => {
  const price = Number(p.price || 0);
  const originalPrice = Number(p.originalPrice || p.original_price || 0);

  const stockQuantity = p.stockQuantity !== undefined ? Number(p.stockQuantity) : (p.stock_quantity !== undefined ? Number(p.stock_quantity) : 50);
  const inStock = p.inStock !== undefined ? Boolean(p.inStock) : (p.in_stock !== undefined ? Boolean(p.in_stock) : (stockQuantity > 0));
  const lowStockThreshold = Number(p.lowStockThreshold || p.low_stock_threshold || 5);
  const minOrderQty = Math.max(1, Number(p.minOrderQty || p.min_order_qty || 1));
  const maxOrderQty = Math.max(1, Number(p.maxOrderQty || p.max_order_qty || 10));
  const isOutOfStock = !inStock || stockQuantity <= 0;
  const isLowStock = inStock && stockQuantity > 0 && stockQuantity <= lowStockThreshold;

  const productCode = p.product_code || p.productCode || (p.id ? String(p.id) : undefined);
  const slug = p.slug || (p.name ? p.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') : productCode);

  return {
    ...p,
    id: String(p.id || productCode || slug || Math.random()),
    product_code: productCode,
    slug: slug,
    name: p.name || 'Fresh Seafood',
    category: typeof p.category === 'object' && p.category !== null ? p.category.slug || p.category.name : (p.category || 'fish-seafood'),
    subCategory: p.subCategory || p.sub_category || 'Fresh Fish',
    sub_category: p.subCategory || p.sub_category || 'Fresh Fish',
    price: price,
    originalPrice: originalPrice,
    original_price: originalPrice,
    weight: p.weight || '500g',
    pieces: p.pieces || 'Cleaned & Cut Pieces',
    servings: p.servings || 'Serves 2-3',
    description: p.description || 'Sourced fresh daily and vacuum packed under 4°C.',
    shortDescription: p.shortDescription || p.short_description || '',
    short_description: p.shortDescription || p.short_description || '',
    deliveryTime: p.deliveryTime || p.delivery_time || 'Today 4:00pm - 08:30 pm',
    delivery_time: p.deliveryTime || p.delivery_time || 'Today 4:00pm - 08:30 pm',
    image: resolveImageUrl(p.image),
    tags: safeParseArray(p.tags),
    servicedPincodes: safeParseArray(p.servicedPincodes || p.serviced_pincodes),
    rating: Number(p.rating || 4.9),
    reviewsCount: Number(p.reviewsCount || p.reviews_count || 128),
    isBestSeller: Boolean(p.isBestSeller || p.is_best_seller),
    isTodaySpecial: Boolean(p.isTodaySpecial || p.is_today_special),
    stockQuantity: stockQuantity,
    stock_quantity: stockQuantity,
    inStock: inStock,
    in_stock: inStock,
    lowStockThreshold: lowStockThreshold,
    low_stock_threshold: lowStockThreshold,
    minOrderQty: minOrderQty,
    min_order_qty: minOrderQty,
    maxOrderQty: maxOrderQty,
    max_order_qty: maxOrderQty,
    isOutOfStock: isOutOfStock,
    is_out_of_stock: isOutOfStock,
    isLowStock: isLowStock,
    is_low_stock: isLowStock,
  };
};

interface AppContextType {
  theme: 'light' | 'dark';
  toggleTheme: () => void;
  currentPage: PageId;
  navigateTo: (page: PageId, productId?: string, productData?: Product, categorySlug?: string) => void;
  navigationHistory: PageId[];
  goBack: () => void;
  selectedProductId: string | null;
  selectedProduct: Product | null;
  isLoadingSingleProduct: boolean;
  
  // Dynamic Lists
  products: Product[];
  categories: any[];
  slides: Slide[];
  videos: Video[];

  // Global Store Settings
  minOrderAmount: number;
  freeDeliveryThreshold: number;

  // Pincode Location State (Admin-controlled serviceable pincodes)
  activePincode: string;
  setPincode: (pin: string) => void;
  serviceablePincodes: string[];
  serviceableHubs: DeliveryHub[];
  isLoadingPincodes: boolean;
  pincodesError: string | null;
  refreshServiceablePincodes: () => Promise<void>;
  verifyPincode: (pin: string) => Promise<{
    isServiceable: boolean;
    status: 'serviceable' | 'coming_soon' | 'invalid';
    areaName: string | null;
    message: string;
  }>;
  isPincodeServiceable: (pin: string, product?: Product) => boolean;
  isPincodeModalOpen: boolean;
  setIsPincodeModalOpen: (open: boolean) => void;

  // Loading States for Skeleton Loaders
  isLoadingProducts: boolean;
  isLoadingCategories: boolean;
  isLoadingSlides: boolean;
  isLoadingVideos: boolean;

  // Cart State
  cart: CartItem[];
  addToCart: (product: Product) => void;
  removeFromCart: (productId: string) => void;
  clearCart: () => void;
  getCartQuantity: (productId: string) => number;
  totalCartItems: number;
  cartCount: number;
  subtotalAmount: number;
  cartSubtotal: number;
  deliveryFee: number;
  totalAmount: number;
  cartTotal: number;
  couponCode: string;
  applyCoupon: (code: string) => boolean;
  discountAmount: number;

  // User State & Auth
  user: User | null;
  setUser: (user: User | null) => void;
  login: (name: string, phone: string, email: string, token?: string) => Promise<void>;
  logout: () => void;

  // Address State
  addresses: Address[];
  selectedAddressId: string | null;
  setSelectedAddressId: (id: string) => void;
  addAddress: (address: Omit<Address, 'id'>) => void;
  
  // Payment State
  selectedPaymentMethod: string;
  setSelectedPaymentMethod: (method: string) => void;

  // Search & Categories State
  searchQuery: string;
  setSearchQuery: (query: string) => void;
  selectedCategory: string | null;
  setSelectedCategory: (category: string | null) => void;
  selectedSubCategory: string | null;
  setSelectedSubCategory: (subCat: string | null) => void;
  activeHeroIndex: number;
  setActiveHeroIndex: React.Dispatch<React.SetStateAction<number>>;

  // Order State
  orders: Order[];
  lastPlacedOrder: Order | null;
  placeOrder: (deliverySlot?: string) => Promise<void>;
  refreshOrders: () => Promise<void>;
  isPlacingOrder: boolean;
  showOrderSuccessModal: boolean;
  setShowOrderSuccessModal: React.Dispatch<React.SetStateAction<boolean>>;
  // Toast Notification System
  toast: ToastMessage | null;
  showToast: (message: string, type?: 'success' | 'error' | 'info' | 'warning') => void;
  hideToast: () => void;
}

const DEFAULT_ADDRESSES: Address[] = [];

const AppContext = createContext<AppContextType | undefined>(undefined);

export const AppProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  // Theme state
  const [theme, setTheme] = useState<'light' | 'dark'>(() => {
    const saved = localStorage.getItem('royal-fish-theme');
    return (saved as 'light' | 'dark') || 'light';
  });

  // Routing state parsed from initial URL (pathname, search or hash)
  const initialRoute = parseLocationToRoute();
  const [currentPage, setCurrentPage] = useState<PageId>(initialRoute.page);
  const [selectedProductId, setSelectedProductId] = useState<string | null>(initialRoute.productId);
  const [selectedProductData, setSelectedProductData] = useState<Product | null>(null);
  const [navigationHistory, setNavigationHistory] = useState<PageId[]>([initialRoute.page]);
  const [isLoadingSingleProduct, setIsLoadingSingleProduct] = useState<boolean>(false);

  // Pincode & Location State
  const [activePincode, setActivePincode] = useState<string>(() => {
    const saved = localStorage.getItem('royal-fish-pincode');
    if (saved && saved !== '400001' && saved !== '110001') {
      return saved;
    }
    return '700135';
  });
  const [serviceablePincodes, setServiceablePincodes] = useState<string[]>([]);
  const [serviceableHubs, setServiceableHubs] = useState<DeliveryHub[]>([]);
  const [isLoadingPincodes, setIsLoadingPincodes] = useState<boolean>(true);
  const [pincodesError, setPincodesError] = useState<string | null>(null);
  // True once the admin has configured at least one pincode (backend is_configured flag)
  const [arePincodesConfigured, setArePincodesConfigured] = useState<boolean>(false);
  const [isPincodeModalOpen, setIsPincodeModalOpen] = useState(false);

  // Dynamic Lists State — start empty so only API data is shown (skeleton shows while loading)
  const [products, setProducts] = useState<Product[]>([]);
  const [categories, setCategories] = useState<any[]>([]);
  const [slides, setSlides] = useState<Slide[]>([]);
  const [videos, setVideos] = useState<Video[]>([]);

  // Skeleton Loading States — starts as true while fetching live backend API data
  const [isLoadingProducts, setIsLoadingProducts] = useState<boolean>(true);
  const [isLoadingCategories, setIsLoadingCategories] = useState<boolean>(true);
  const [isLoadingSlides, setIsLoadingSlides] = useState<boolean>(true);
  // Toast System
  const [toast, setToast] = useState<ToastMessage | null>(null);
  const showToast = (message: string, type: 'success' | 'error' | 'info' | 'warning' = 'info') => {
    setToast({ id: Date.now(), message, type });
  };
  const hideToast = () => setToast(null);

  const [isLoadingVideos, setIsLoadingVideos] = useState<boolean>(true);

  // Helper to verify if a pincode is serviceable.
  // The Admin-managed serviceable pincode list (from the backend) is the single source of truth.
  const isPincodeServiceable = (pin: string, product?: Product): boolean => {
    if (!pin) return false;
    const clean = pin.trim().replace(/\D/g, '');
    if (clean.length !== 6) return false;

    // Per-product restriction check (product level delivery zones)
    if (product) {
      const pins = product.servicedPincodes;
      if (!pins || pins.length === 0) return false;
      if (pins.includes('*')) return true;
      return pins.includes(clean);
    }

    // Admin has configured serviceable pincodes → only those are deliverable
    if (arePincodesConfigured) {
      return serviceablePincodes.includes(clean);
    }

    // Admin has not configured any pincode yet → fall back to product level delivery zones
    if (products.length > 0) {
      return products.some(p => {
        const pins = p.servicedPincodes;
        return pins && (pins.includes('*') || pins.includes(clean));
      });
    }

    return false;
  };

  const setPincode = (pin: string) => {
    const clean = pin.trim().replace(/\D/g, '');
    setActivePincode(clean);
    localStorage.setItem('royal-fish-pincode', clean);
  };

  // Fetch the Admin-controlled serviceable pincode list (called on load and every time the modal opens)
  const refreshServiceablePincodes = async () => {
    try {
      const res = await fetch(`${API_BASE_URL}/serviceable-pincodes`);
      if (!res.ok) throw new Error('Failed to load pincodes');

      const data = await res.json();
      const list: string[] = Array.isArray(data.serviceable_pincodes)
        ? data.serviceable_pincodes.map((pin: any) => String(pin).trim()).filter(Boolean)
        : [];
      const hubs: DeliveryHub[] = Array.isArray(data.hubs) && data.hubs.length > 0
        ? data.hubs.map((hub: any) => ({
            pincode: String(hub.pincode || '').trim(),
            areaName: hub.areaName || hub.area_name || null,
            area_name: hub.area_name || hub.areaName || null,
            isActive: hub.is_active !== undefined ? Boolean(hub.is_active) : true,
            is_active: hub.is_active !== undefined ? Boolean(hub.is_active) : true,
          })).filter((hub: DeliveryHub) => hub.pincode.length === 6)
        : list.map(pin => ({ pincode: pin, areaName: null, area_name: null, isActive: true }));

      setServiceablePincodes(list);
      setServiceableHubs(hubs);
      setArePincodesConfigured(data.is_configured !== undefined ? Boolean(data.is_configured) : list.length > 0);
      setPincodesError(null);
    } catch (err) {
      setPincodesError('Unable to load our delivery areas right now. Please try again in a moment.');
    } finally {
      setIsLoadingPincodes(false);
    }
  };

  // Authoritative server side validation (never trust the client)
  const verifyPincode = async (pin: string) => {
    const clean = pin.trim().replace(/\D/g, '');

    if (clean.length !== 6) {
      return {
        isServiceable: false,
        status: 'invalid' as const,
        areaName: null,
        message: 'Please enter a valid 6-digit Indian pincode (e.g. 700135).'
      };
    }

    try {
      const res = await fetch(`${API_BASE_URL}/check-pincode`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pincode: clean })
      });

      if (!res.ok) throw new Error('Pincode check failed');

      const data = await res.json();
      const isServiceable = Boolean(data.isDeliverable);
      const status: 'serviceable' | 'coming_soon' | 'invalid' =
        data.status === 'invalid' ? 'invalid' : (isServiceable ? 'serviceable' : 'coming_soon');

      return {
        isServiceable,
        status,
        areaName: data.areaName || null,
        message: data.message || (isServiceable
          ? `Express delivery available for ${clean}`
          : `We are not delivering to ${clean} yet. Please select one of our serviceable areas.`)
      };
    } catch (err) {
      // Offline / API failure → fall back to the locally cached admin list
      const localServiceable = isPincodeServiceable(clean);
      return {
        isServiceable: localServiceable,
        status: localServiceable ? ('serviceable' as const) : ('coming_soon' as const),
        areaName: null,
        message: localServiceable
          ? `Express delivery available for ${clean}`
          : `We are not delivering to ${clean} yet. Please select one of our serviceable areas.`
      };
    }
  };

  // Load the serviceable pincodes once on app start
  useEffect(() => {
    refreshServiceablePincodes();
  }, []);

  // Cart State
  const [cart, setCart] = useState<CartItem[]>(() => {
    const saved = localStorage.getItem('royal-fish-cart');
    return saved ? JSON.parse(saved) : [];
  });

  const [couponCode, setCouponCode] = useState<string>('');
  const [discountAmount, setDiscountAmount] = useState<number>(0);

  // Addresses State (filter out old dummy demo addresses)
  const [addresses, setAddresses] = useState<Address[]>(() => {
    const saved = localStorage.getItem('royal-fish-addresses');
    if (saved) {
      try {
        const parsed = JSON.parse(saved);
        if (Array.isArray(parsed)) {
          const valid = parsed.filter((a: Address) => 
            !a.addressLine?.includes('Royal Residency') &&
            !a.addressLine?.includes('Marine Drive') &&
            !a.addressLine?.includes('Tech Park Central') &&
            !a.phone?.includes('98765 43210') &&
            !a.phone?.includes('98765 43211')
          );
          return valid;
        }
      } catch (e) {}
    }
    return DEFAULT_ADDRESSES;
  });

  const [selectedAddressId, setSelectedAddressId] = useState<string | null>(() => {
    const saved = localStorage.getItem('royal-fish-addresses');
    if (saved) {
      try {
        const parsed = JSON.parse(saved);
        if (Array.isArray(parsed)) {
          const valid = parsed.filter((a: Address) => 
            !a.addressLine?.includes('Royal Residency') &&
            !a.phone?.includes('98765 43210')
          );
          if (valid.length > 0) return valid[0].id;
        }
      } catch (e) {}
    }
    return null;
  });

  // Sync addresses to localStorage & keep selectedAddressId valid
  useEffect(() => {
    localStorage.setItem('royal-fish-addresses', JSON.stringify(addresses));
    if (addresses.length > 0) {
      if (!selectedAddressId || !addresses.some(a => a.id === selectedAddressId)) {
        setSelectedAddressId(addresses[0].id);
      }
    } else {
      setSelectedAddressId(null);
    }
  }, [addresses]);

  // Payment Method
  const [selectedPaymentMethod, setSelectedPaymentMethod] = useState<string>('cod');

  // Orders state
  const [orders, setOrders] = useState<Order[]>(() => {
    const saved = localStorage.getItem('royal-fish-orders');
    return saved ? JSON.parse(saved) : [];
  });
  const [lastPlacedOrder, setLastPlacedOrder] = useState<Order | null>(null);
  const [isPlacingOrder, setIsPlacingOrder] = useState(false);
  const [showOrderSuccessModal, setShowOrderSuccessModal] = useState(false);

  // Search & Categories State
  const [searchQuery, setSearchQuery] = useState<string>(initialRoute.query || '');
  const [selectedCategory, setSelectedCategory] = useState<string | null>(initialRoute.category);
  const [selectedSubCategory, setSelectedSubCategory] = useState<string | null>(null);
  const [activeHeroIndex, setActiveHeroIndex] = useState<number>(0);

  // User Auth State
  const [user, setUser] = useState<User | null>(() => {
    const saved = localStorage.getItem('royal-fish-user');
    return saved ? JSON.parse(saved) : null;
  });

  // Global Settings State
  const [minOrderAmount, setMinOrderAmount] = useState<number>(199);
  const [freeDeliveryThreshold, setFreeDeliveryThreshold] = useState<number>(499);

  // Fetch categories, slides, videos and settings on boot & updates
  useEffect(() => {
    const fetchSettings = () => {
      fetch(`${API_BASE_URL}/settings`)
        .then(res => {
          if (!res.ok) throw new Error();
          return res.json();
        })
        .then(data => {
          if (data && typeof data === 'object') {
            if (data.min_order_amount !== undefined) {
              setMinOrderAmount(Number(data.min_order_amount) || 199);
            }
            if (data.free_delivery_threshold !== undefined) {
              setFreeDeliveryThreshold(Number(data.free_delivery_threshold) || 499);
            }
          }
        })
        .catch(() => {});
    };

    fetchSettings();

    const fetchCategories = () => {
      fetch(`${API_BASE_URL}/categories`)
        .then(res => {
          if (!res.ok) throw new Error();
          return res.json();
        })
        .then(data => {
          if (Array.isArray(data)) {
            const mapped = data.map((cat: any) => ({
              ...cat,
              sort_order: cat.sort_order !== undefined ? Number(cat.sort_order) : 0,
              image: resolveImageUrl(cat.image)
            })).sort((a: any, b: any) => ((a.sort_order || 0) - (b.sort_order || 0)) || ((a.id || 0) - (b.id || 0)));
            setCategories(mapped);
          }
          setIsLoadingCategories(false);
        })
        .catch(() => {
          setIsLoadingCategories(false);
        });
    };

    fetchCategories();

    const fetchSlides = () => {
      fetch(`${API_BASE_URL}/slides`)
        .then(res => {
          if (!res.ok) throw new Error();
          return res.json();
        })
        .then(data => {
          if (Array.isArray(data)) {
            setSlides(data);
          }
          setIsLoadingSlides(false);
        })
        .catch(() => {
          setIsLoadingSlides(false);
        });
    };

    fetchSlides();

    const fetchVideos = () => {
      fetch(`${API_BASE_URL}/videos`)
        .then(res => {
          if (!res.ok) throw new Error();
          return res.json();
        })
        .then(data => {
          if (Array.isArray(data)) {
            setVideos(data);
          }
          setIsLoadingVideos(false);
        })
        .catch(() => {
          setIsLoadingVideos(false);
        });
    };

    fetchVideos();

    // Re-fetch slides, categories, settings and videos on window focus and every 5s so Admin updates sync instantly
    const onFocus = () => {
      fetchSettings();
      fetchCategories();
      fetchSlides();
      fetchVideos();
    };
    window.addEventListener('focus', onFocus);
    const syncInterval = setInterval(() => {
      fetchSettings();
      fetchCategories();
      fetchSlides();
      fetchVideos();
    }, 5000);

    return () => {
      window.removeEventListener('focus', onFocus);
      clearInterval(syncInterval);
    };
  }, []);

  const fetchProductsList = () => {
    const params = new URLSearchParams();
    if (selectedCategory) params.append('category', selectedCategory);
    if (selectedSubCategory) params.append('sub_category', selectedSubCategory);
    if (searchQuery) params.append('search', searchQuery);
    if (activePincode) params.append('pincode', activePincode);

    fetch(`${API_BASE_URL}/products?${params.toString()}`)
      .then(res => {
        if (!res.ok) throw new Error();
        return res.json();
      })
      .then(data => {
        if (Array.isArray(data)) {
          setProducts(data.map(normalizeProduct));
        }
        setIsLoadingProducts(false);
      })
      .catch(() => {
        setIsLoadingProducts(false);
      });
  };

  useEffect(() => {
    setIsLoadingProducts(true);
    fetchProductsList();

    const onFocus = () => fetchProductsList();
    window.addEventListener('focus', onFocus);
    const interval = setInterval(fetchProductsList, 5000);

    return () => {
      window.removeEventListener('focus', onFocus);
      clearInterval(interval);
    };
  }, [selectedCategory, selectedSubCategory, searchQuery, activePincode]);

  // Load User Data (Profile, Addresses, orders) from API
  const loadUserData = async (token: string) => {
    try {
      const headers = { 'Authorization': `Bearer ${token}` };
      
      const profRes = await fetch(`${API_BASE_URL}/profile`, { headers });
      if (profRes.ok) {
        const profData = await profRes.json();
        if (profData && (profData.phone || profData.name)) {
          const syncedUser: User = {
            name: profData.name,
            phone: profData.phone,
            email: profData.email,
            isLoggedIn: true
          };
          setUser(syncedUser);
          localStorage.setItem('royal-fish-user', JSON.stringify(syncedUser));
        }
      }

      const addrRes = await fetch(`${API_BASE_URL}/addresses`, { headers });
      if (addrRes.ok) {
        const addrData = await addrRes.json();
        if (Array.isArray(addrData) && addrData.length > 0) {
          setAddresses(addrData);
          setSelectedAddressId(addrData[0].id);
        }
      }

      const ordRes = await fetch(`${API_BASE_URL}/orders`, { headers });
      if (ordRes.ok) {
        const ordData = await ordRes.json();
        if (Array.isArray(ordData)) {
          setOrders(ordData);
        }
      }
    } catch(err) {
      console.error('Failed to sync user data from API', err);
    }
  };

  // Check existing token on boot
  useEffect(() => {
    const token = localStorage.getItem('royal-fish-token');
    if (token) {
      loadUserData(token);
    }
  }, []);

  const login = async (name: string, phone: string, email: string, token?: string) => {
    const loggedInUser: User = { name, phone, email, isLoggedIn: true };
    setUser(loggedInUser);
    localStorage.setItem('royal-fish-user', JSON.stringify(loggedInUser));

    // Automatically sync user's registered phone into address book if it has dummy demo numbers
    const userPhoneFormatted = phone.startsWith('+91') ? phone : `+91 ${phone.trim()}`;
    setAddresses(prev => {
      const updated = prev.map(a => {
        if (!a.phone || a.phone.includes('98765 43210') || a.phone.includes('9876543210')) {
          return { ...a, phone: userPhoneFormatted };
        }
        return a;
      });
      localStorage.setItem('royal-fish-addresses', JSON.stringify(updated));
      return updated;
    });

    if (token) {
      localStorage.setItem('royal-fish-token', token);
      loadUserData(token);
    }
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('royal-fish-user');
    localStorage.removeItem('royal-fish-token');
    setAddresses(DEFAULT_ADDRESSES);
    setOrders([]);
  };

  // Reset subcategory when category changes
  useEffect(() => {
    setSelectedSubCategory(null);
  }, [selectedCategory]);

  // Effect to apply theme class
  useEffect(() => {
    const root = window.document.documentElement;
    if (theme === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
    localStorage.setItem('royal-fish-theme', theme);
  }, [theme]);

  // Sync cart to local storage
  useEffect(() => {
    localStorage.setItem('royal-fish-cart', JSON.stringify(cart));
  }, [cart]);

  // Sync addresses to local storage (only as local fallback)
  useEffect(() => {
    localStorage.setItem('royal-fish-addresses', JSON.stringify(addresses));
  }, [addresses]);

  // Sync orders to local storage (only as local fallback)
  useEffect(() => {
    localStorage.setItem('royal-fish-orders', JSON.stringify(orders));
  }, [orders]);

  const toggleTheme = () => {
    setTheme(prev => (prev === 'light' ? 'dark' : 'light'));
  };

  const navigateTo = (
    page: PageId,
    productId?: string,
    productData?: Product,
    categorySlug?: string
  ) => {
    if (productId !== undefined) {
      setSelectedProductId(productId || null);
    }
    if (productData !== undefined) {
      setSelectedProductData(productData || null);
    }
    if (categorySlug !== undefined) {
      setSelectedCategory(categorySlug || null);
    }
    if (page === 'home') {
      setSelectedCategory(null);
      setSelectedSubCategory(null);
    }

    const activeCat = categorySlug !== undefined ? categorySlug : (page === 'category-view' ? (categorySlug || selectedCategory) : null);
    const targetUrl = buildUrlForRoute(
      page,
      productId !== undefined ? productId : selectedProductId,
      productData !== undefined ? productData : selectedProductData,
      activeCat,
      page === 'search' ? searchQuery : null
    );

    if (typeof window !== 'undefined') {
      const currentUrl = window.location.pathname + window.location.search;
      if (currentUrl !== targetUrl) {
        window.history.pushState(
          { page, productId: productId || null, category: activeCat },
          '',
          targetUrl
        );
      }
    }

    setCurrentPage(page);
    setNavigationHistory(prev => [...prev, page]);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const goBack = () => {
    if (typeof window !== 'undefined' && window.history.length > 1 && navigationHistory.length > 1) {
      window.history.back();
    } else {
      navigateTo('home');
    }
  };

  // Sync browser Back/Forward navigation with state
  useEffect(() => {
    const handlePopState = () => {
      const route = parseLocationToRoute();
      setCurrentPage(route.page);
      if (route.productId) {
        setSelectedProductId(route.productId);
      }
      if (route.category) {
        setSelectedCategory(route.category);
      }
      if (route.query !== null) {
        setSearchQuery(route.query);
      }
      setNavigationHistory(prev => [...prev, route.page]);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.addEventListener('popstate', handlePopState);
    return () => window.removeEventListener('popstate', handlePopState);
  }, []);

  // Normalize initial URL if it has old-style query params or hashes
  useEffect(() => {
    if (typeof window === 'undefined') return;
    const route = parseLocationToRoute();
    const cleanUrl = buildUrlForRoute(
      route.page,
      route.productId,
      null,
      route.category,
      route.query
    );
    if (
      window.location.search.includes('page=') ||
      window.location.search.includes('product=') ||
      window.location.hash === '#onepager'
    ) {
      window.history.replaceState({ page: route.page, productId: route.productId }, '', cleanUrl);
    }
  }, []);

  // Fetch product directly by slug or ID when product-details page is requested directly
  useEffect(() => {
    if (currentPage === 'product-details' && selectedProductId) {
      // Check if we already have matching product data loaded
      if (
        selectedProductData &&
        (selectedProductData.slug === selectedProductId ||
          String(selectedProductData.id) === selectedProductId ||
          (selectedProductData as any).product_code === selectedProductId)
      ) {
        return;
      }

      // Check if present in the loaded products list
      const match = products.find(
        p =>
          p.slug === selectedProductId ||
          String(p.id) === selectedProductId ||
          (p as any).product_code === selectedProductId
      );
      if (match) {
        setSelectedProductData(match);
        return;
      }

      // If products list has already finished loading or if not found, fetch single product from API
      setIsLoadingSingleProduct(true);
      fetch(`${API_BASE_URL}/products/${encodeURIComponent(selectedProductId)}`)
        .then(res => {
          if (!res.ok) throw new Error('Product not found');
          return res.json();
        })
        .then(data => {
          if (data && (data.id || data.name)) {
            setSelectedProductData(normalizeProduct(data));
          }
        })
        .catch(err => {
          console.warn('Could not load product directly:', selectedProductId, err);
        })
        .finally(() => {
          setIsLoadingSingleProduct(false);
        });
    }
  }, [currentPage, selectedProductId, products]);

  // Use directly stored product data first, then fall back to products array lookup
  const selectedProduct = selectedProductData 
    ? selectedProductData
    : selectedProductId 
      ? products.find(p => 
          String(p.id) === String(selectedProductId) || 
          String((p as any).product_code) === String(selectedProductId) ||
          String((p as any).slug) === String(selectedProductId)
        ) || null 
      : null;

  // Cart operations
  const addToCart = (product: Product) => {
    if (product.isOutOfStock || (product.stockQuantity !== undefined && product.stockQuantity <= 0)) {
      showToast(`${product.name} is currently out of stock.`, 'warning');
      return;
    }

    // Check pincode deliverability if activePincode is set
    if (activePincode && product.servicedPincodes && product.servicedPincodes.length > 0) {
      if (!product.servicedPincodes.includes('*') && !product.servicedPincodes.includes(activePincode)) {
        showToast(`${product.name} is not deliverable to pincode ${activePincode}. Admin has restricted delivery for this product.`, 'warning');
        return;
      }
    }

    const minQty = Math.max(1, Number(product.minOrderQty || (product as any).min_order_qty || 1));
    const maxQty = Math.max(1, Number(product.maxOrderQty || (product as any).max_order_qty || 10));
    const availableStock = product.stockQuantity !== undefined ? Number(product.stockQuantity) : 999;

    // Track add_to_cart for GA4 / Meta Pixel GTM
    const existingInCart = cart.find(item => item.product.id === product.id);
    if (existingInCart) {
      if (existingInCart.quantity + 1 <= maxQty && existingInCart.quantity + 1 <= availableStock) {
        trackAddToCart(product, 1);
      }
    } else {
      const initialQty = Math.min(minQty, availableStock);
      if (initialQty <= maxQty) {
        trackAddToCart(product, initialQty);
      }
    }

    setCart(prev => {
      const existingIndex = prev.findIndex(item => item.product.id === product.id);
      if (existingIndex > -1) {
        const currentQty = prev[existingIndex].quantity;
        const newQty = currentQty + 1;

        if (newQty > maxQty) {
          showToast(`Maximum purchase limit for ${product.name} is ${maxQty} units per order.`, 'warning');
          return prev;
        }

        if (newQty > availableStock) {
          showToast(`Only ${availableStock} units left in stock for ${product.name}.`, 'warning');
          return prev;
        }

        const updated = [...prev];
        updated[existingIndex] = {
          ...updated[existingIndex],
          quantity: newQty
        };
        return updated;
      }

      // Initial add respecting minOrderQty
      const initialQty = Math.min(minQty, availableStock);
      if (initialQty > maxQty) {
        showToast(`Cannot add item: min order limit exceeds max limit.`, 'warning');
        return prev;
      }
      return [...prev, { product, quantity: initialQty }];
    });
  };

  const removeFromCart = (productId: string) => {
    setCart(prev => {
      const existingIndex = prev.findIndex(item => item.product.id === productId);
      if (existingIndex > -1) {
        const item = prev[existingIndex];
        const minQty = Math.max(1, Number(item.product.minOrderQty || (item.product as any).min_order_qty || 1));

        if (item.quantity > minQty) {
          const updated = [...prev];
          updated[existingIndex] = {
            ...item,
            quantity: item.quantity - 1
          };
          return updated;
        } else {
          return prev.filter(i => i.product.id !== productId);
        }
      }
      return prev;
    });
  };

  const clearCart = () => {
    setCart([]);
    setCouponCode('');
    setDiscountAmount(0);
  };

  const getCartQuantity = (productId: string): number => {
    const item = cart.find(i => i.product.id === productId);
    return item ? item.quantity : 0;
  };

  const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
  const cartSubtotal = cart.reduce((total, item) => total + (item.product.price * item.quantity), 0);
  const deliveryFee = cartSubtotal === 0 ? 0 : (cartSubtotal >= freeDeliveryThreshold ? 0 : 49);

  const applyCoupon = (code: string): boolean => {
    const cleanCode = code.toUpperCase().trim();
    if (cleanCode === 'ROYAL20' && cartSubtotal > 0) {
      setCouponCode('ROYAL20');
      setDiscountAmount(Math.round(cartSubtotal * 0.2));
      return true;
    } else if (cleanCode === 'FREE60' && cartSubtotal > 0) {
      setCouponCode('FREE60');
      setDiscountAmount(Math.round(Math.min(cartSubtotal, 100)));
      return true;
    }
    return false;
  };

  const cartTotal = Math.max(0, cartSubtotal + deliveryFee - discountAmount);

  // Address operations
  const addAddress = async (newAddr: Omit<Address, 'id'>) => {
    const token = localStorage.getItem('royal-fish-token');
    if (!token) {
      // Offline fallback
      const created: Address = { ...newAddr, id: `addr-${Date.now()}` };
      setAddresses(prev => [...prev, created]);
      setSelectedAddressId(created.id);
      return;
    }

    try {
      const res = await fetch(`${API_BASE_URL}/addresses`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(newAddr)
      });
      if (res.ok) {
        const created = await res.json();
        setAddresses(prev => [...prev, created]);
        setSelectedAddressId(created.id);
      }
    } catch(err) {
      const created: Address = { ...newAddr, id: `addr-${Date.now()}` };
      setAddresses(prev => [...prev, created]);
      setSelectedAddressId(created.id);
    }
  };

  // Place Order
  const placeOrder = async (deliverySlot?: string) => {
    if (cart.length === 0 || isPlacingOrder) return;
    
    // Check Minimum Order Amount
    if (cartSubtotal < minOrderAmount && minOrderAmount > 0) {
      showToast(`Minimum order value is ₹${minOrderAmount}. Please add ₹${minOrderAmount - cartSubtotal} more to checkout.`, 'warning');
      return;
    }

    const token = localStorage.getItem('royal-fish-token');
    if (!token || !user) {
      showToast("Please login first to place an order.", "warning");
      navigateTo('login');
      return;
    }

    if (!selectedAddressId || addresses.length === 0) {
      showToast("Please add your delivery address to proceed.", "warning");
      return;
    }

    const rawAddress = addresses.find(a => a.id === selectedAddressId) || addresses[0];
    const userPhoneClean = user?.phone ? (user.phone.startsWith('+91') ? user.phone : `+91 ${user.phone.trim()}`) : '';
    const selectedAddress = {
      ...rawAddress,
      phone: (rawAddress?.phone && !rawAddress.phone.includes('98765 43210') && !rawAddress.phone.includes('9876543210')) 
        ? rawAddress.phone 
        : (userPhoneClean || rawAddress?.phone || '')
    };

    // Strict Pincode validation: Check delivery address pincode
    const deliveryPincode = (selectedAddress?.zipCode || (selectedAddress as any)?.pincode || activePincode || '').toString().trim().replace(/\D/g, '');
    if (!deliveryPincode || deliveryPincode.length !== 6) {
      showToast("Please provide a valid 6-digit delivery pincode.", "warning");
      return;
    }

    // Admin-controlled serviceability gate (backend also enforces this when the order is placed)
    if (arePincodesConfigured && !serviceablePincodes.includes(deliveryPincode)) {
      showToast(`Sorry, we are not delivering to pincode ${deliveryPincode} yet. Please choose one of our serviceable delivery pincodes.`, "warning");
      return;
    }

    // Check all cart items for serviceability to deliveryPincode
    const undeliverableItems = cart.filter(item => {
      const pins = item.product.servicedPincodes;
      if (!pins || pins.length === 0) return true;
      if (pins.includes('*')) return false;
      return !pins.includes(deliveryPincode);
    });

    if (undeliverableItems.length > 0) {
      const names = undeliverableItems.map(i => i.product.name).join(', ');
      showToast(`Item(s) [${names}] are not deliverable to pincode ${deliveryPincode}. Admin has restricted delivery for this product.`, "error");
      return;
    }

    setIsPlacingOrder(true);

    try {
      const res = await fetch(`${API_BASE_URL}/orders`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          cart: cart,
          paymentMethod: selectedPaymentMethod,
          address: selectedAddress,
          totalPrice: cartTotal,
          deliverySlot: deliverySlot || '30-45 mins'
        })
      });

      if (res.ok) {
        const newOrder = await res.json();
        setOrders(prev => [newOrder, ...prev]);
        setLastPlacedOrder(newOrder);
        // Track purchase event for GA4 / Meta Pixel GTM (once per order)
        trackPurchase(newOrder, cart);
        clearCart();
        setShowOrderSuccessModal(true);
      } else {
        const errData = await res.json().catch(() => ({}));
        if (res.status === 401) {
          showToast("Your session has expired. Please login again.", "warning");
          setUser(null);
          localStorage.removeItem('royal-fish-token');
          localStorage.removeItem('royal-fish-user');
          navigateTo('login');
        } else {
          showToast(errData.message || "Failed to place order. Please try again.", "error");
        }
      }
    } catch(err) {
      console.error('Order placement error:', err);
      showToast("Network connection error. Please try again.", "error");
    } finally {
      setIsPlacingOrder(false);
    }
  };

  const refreshOrders = async () => {
    const token = localStorage.getItem('royal-fish-token');
    if (!token) return;
    try {
      const ordRes = await fetch(`${API_BASE_URL}/orders`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (ordRes.ok) {
        const ordData = await ordRes.json();
        if (Array.isArray(ordData)) {
          setOrders(ordData);
        }
      }
    } catch(err) {
      console.error('Failed to sync orders from API', err);
    }
  };

  return (
    <AppContext.Provider
      value={{
        theme,
        toggleTheme,
        currentPage,
        navigateTo,
        navigationHistory,
        goBack,
        selectedProductId,
        selectedProduct,
        isLoadingSingleProduct,
        products,
        categories,
        slides,
        videos,
        minOrderAmount,
        freeDeliveryThreshold,
        activePincode,
        setPincode,
        serviceablePincodes,
        serviceableHubs,
        isLoadingPincodes,
        pincodesError,
        refreshServiceablePincodes,
        verifyPincode,
        isPincodeServiceable,
        isPincodeModalOpen,
        setIsPincodeModalOpen,
        isLoadingProducts,
        isLoadingCategories,
        isLoadingSlides,
        isLoadingVideos,
        cart,
        addToCart,
        removeFromCart,
        clearCart,
        getCartQuantity,
        cartCount,
        cartSubtotal,
        deliveryFee,
        cartTotal,
        discountAmount,
        couponCode,
        applyCoupon,
        addresses,
        addAddress,
        selectedAddressId,
        setSelectedAddressId,
        selectedPaymentMethod,
        setSelectedPaymentMethod,
        orders,
        placeOrder,
        refreshOrders,
        isPlacingOrder,
        showOrderSuccessModal,
        setShowOrderSuccessModal,
        lastPlacedOrder,
        user,
        setUser,
        login,
        logout,
        searchQuery,
        setSearchQuery,
        selectedCategory,
        setSelectedCategory,
        selectedSubCategory,
        setSelectedSubCategory,
        activeHeroIndex,
        setActiveHeroIndex,
        toast,
        showToast,
        hideToast
      }}
    >
      <Toast toast={toast} onClose={hideToast} />
      {children}
    </AppContext.Provider>
  );
};

export const useApp = () => {
  const context = useContext(AppContext);
  if (context === undefined) {
    throw new Error('useApp must be used within an AppProvider');
  }
  return context;
};
