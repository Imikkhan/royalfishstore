import React, { createContext, useContext, useState, useEffect } from 'react';
import { Product, CartItem, Address, Order, PageId, User, Slide, Video } from '../types';
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

  return {
    ...p,
    id: String(p.id || p.product_code || p.slug || Math.random()),
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
  navigateTo: (page: PageId, productId?: string, productData?: Product) => void;
  navigationHistory: PageId[];
  goBack: () => void;
  selectedProductId: string | null;
  selectedProduct: Product | null;
  
  // Dynamic Lists
  products: Product[];
  categories: any[];
  slides: Slide[];
  videos: Video[];

  // Global Store Settings
  minOrderAmount: number;
  freeDeliveryThreshold: number;

  // Pincode Location State
  activePincode: string;
  setPincode: (pin: string) => void;
  serviceablePincodes: string[];
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
  subtotalAmount: number;
  deliveryFee: number;
  totalAmount: number;
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

  // Routing state
  const [currentPage, setCurrentPage] = useState<PageId>(() => {
    if (typeof window !== 'undefined') {
      const search = window.location.search;
      const hash = window.location.hash;
      const path = window.location.pathname;
      const params = new URLSearchParams(search);
      const pageParam = params.get('page');
      
      if (
        pageParam === 'onepager' || 
        hash === '#onepager' || 
        path === '/onepager' || 
        params.get('ad') === 'fb' || 
        params.has('fbclid')
      ) {
        return 'onepager';
      }
      
      if (pageParam && ['home', 'product-details', 'cart', 'profile', 'login', 'category-view', 'categories', 'search', 'onepager'].includes(pageParam)) {
        return pageParam as PageId;
      }
    }
    return 'home';
  });
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
  const [selectedProductData, setSelectedProductData] = useState<Product | null>(null);
  const [navigationHistory, setNavigationHistory] = useState<PageId[]>(['home']);

  // Pincode & Location State
  const [activePincode, setActivePincode] = useState<string>(() => {
    const saved = localStorage.getItem('royal-fish-pincode');
    if (saved && saved !== '400001' && saved !== '110001') {
      return saved;
    }
    return '700135';
  });
  const [serviceablePincodes, setServiceablePincodes] = useState<string[]>([]);
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

  // Helper to verify if a pincode is serviceable
  const isPincodeServiceable = (pin: string, product?: Product): boolean => {
    if (!pin) return false;
    const clean = pin.trim().replace(/\D/g, '');
    if (clean.length !== 6) return false;

    if (product) {
      const pins = product.servicedPincodes;
      if (!pins || pins.length === 0) return false;
      if (pins.includes('*')) return true;
      return pins.includes(clean);
    }

    if (serviceablePincodes.length > 0) {
      return serviceablePincodes.includes(clean);
    }

    if (products.length > 0) {
      return products.some(p => {
        const pins = p.servicedPincodes;
        return pins && (pins.includes('*') || pins.includes(clean));
      });
    }

    return true;
  };

  const setPincode = (pin: string) => {
    const clean = pin.trim().replace(/\D/g, '');
    setActivePincode(clean);
    localStorage.setItem('royal-fish-pincode', clean);
  };

  // Fetch unique serviceable pincodes from backend
  useEffect(() => {
    const fetchServiceable = () => {
      fetch(`${API_BASE_URL}/serviceable-pincodes`)
        .then(res => res.json())
        .then(data => {
          if (Array.isArray(data.serviceable_pincodes)) {
            setServiceablePincodes(data.serviceable_pincodes.map(String));
          }
        })
        .catch(() => {});
    };
    fetchServiceable();
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
  const [searchQuery, setSearchQuery] = useState<string>('');
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
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

  const navigateTo = (page: PageId, productId?: string, productData?: Product) => {
    if (productId) {
      setSelectedProductId(productId);
    }
    if (productData) {
      setSelectedProductData(productData);
    }
    if (page === 'home') {
      setSelectedCategory(null);
      setSelectedSubCategory(null);
    }
    setCurrentPage(page);
    setNavigationHistory(prev => [...prev, page]);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const goBack = () => {
    if (navigationHistory.length > 1) {
      const updatedHistory = [...navigationHistory];
      updatedHistory.pop();
      const prevPage = updatedHistory[updatedHistory.length - 1];
      setNavigationHistory(updatedHistory);
      setCurrentPage(prevPage);
    } else {
      setCurrentPage('home');
    }
  };

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
        products,
        categories,
        slides,
        videos,
        minOrderAmount,
        freeDeliveryThreshold,
        activePincode,
        setPincode,
        serviceablePincodes,
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
