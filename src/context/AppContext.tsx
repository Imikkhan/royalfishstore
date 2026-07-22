import React, { createContext, useContext, useState, useEffect } from 'react';
import { Product, CartItem, Address, Order, PageId, User, Slide } from '../types';
import { PRODUCTS as MOCK_PRODUCTS, CATEGORIES as MOCK_CATEGORIES, PROMO_SLIDES } from '../data/products';

const API_BASE_URL = 'http://127.0.0.1:8000/api';

interface AppContextType {
  theme: 'light' | 'dark';
  toggleTheme: () => void;
  currentPage: PageId;
  navigateTo: (page: PageId, productId?: string) => void;
  navigationHistory: PageId[];
  goBack: () => void;
  selectedProductId: string | null;
  selectedProduct: Product | null;
  
  // Dynamic Lists
  products: Product[];
  categories: any[];
  slides: Slide[];

  // Pincode Location State
  activePincode: string;
  setPincode: (pin: string) => void;
  isPincodeModalOpen: boolean;
  setIsPincodeModalOpen: (open: boolean) => void;

  // Loading States for Skeleton Loaders
  isLoadingProducts: boolean;
  isLoadingCategories: boolean;
  isLoadingSlides: boolean;

  // Cart
  cart: CartItem[];
  addToCart: (product: Product) => void;
  removeFromCart: (productId: string) => void;
  clearCart: () => void;
  getCartQuantity: (productId: string) => number;
  cartCount: number;
  cartSubtotal: number;
  deliveryFee: number;
  cartTotal: number;
  discountAmount: number;
  couponCode: string;
  applyCoupon: (code: string) => boolean;

  // Addresses
  addresses: Address[];
  addAddress: (address: Omit<Address, 'id'>) => void;
  selectedAddressId: string | null;
  setSelectedAddressId: (id: string | null) => void;

  // Payments
  selectedPaymentMethod: string;
  setSelectedPaymentMethod: (method: string) => void;

  // Orders
  orders: Order[];
  placeOrder: () => void;
  lastPlacedOrder: Order | null;

  // User auth state
  user: User | null;
  login: (name: string, phone: string, email: string) => void;
  logout: () => void;

  // Search/Filters
  searchQuery: string;
  setSearchQuery: (query: string) => void;
  selectedCategory: string | null;
  setSelectedCategory: (category: string | null) => void;
  selectedSubCategory: string | null;
  setSelectedSubCategory: (subCategory: string | null) => void;
  activeHeroIndex: number;
  setActiveHeroIndex: (index: number) => void;
}

const AppContext = createContext<AppContextType | undefined>(undefined);

const DEFAULT_ADDRESSES: Address[] = [
  {
    id: 'addr-1',
    name: 'Home (Default)',
    type: 'Home',
    addressLine: 'Flat 402, Royal Residency, Marine Drive',
    city: 'Mumbai',
    zipCode: '400002',
    phone: '+91 98765 43210'
  },
  {
    id: 'addr-2',
    name: 'Office',
    type: 'Work',
    addressLine: 'Wing B, Level 12, Tech Park Central',
    city: 'Mumbai',
    zipCode: '400051',
    phone: '+91 98765 43211'
  }
];

export const AppProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  // Theme state
  const [theme, setTheme] = useState<'light' | 'dark'>(() => {
    const saved = localStorage.getItem('royal-fish-theme');
    return (saved as 'light' | 'dark') || 'light';
  });

  // Routing state
  const [currentPage, setCurrentPage] = useState<PageId>('home');
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
  const [navigationHistory, setNavigationHistory] = useState<PageId[]>(['home']);

  // Pincode & Location State
  const [activePincode, setActivePincode] = useState<string>(() => {
    return localStorage.getItem('royal-fish-pincode') || '400001';
  });
  const [isPincodeModalOpen, setIsPincodeModalOpen] = useState(false);

  // Dynamic Lists State (with mock fallbacks)
  const [products, setProducts] = useState<Product[]>(MOCK_PRODUCTS);
  const [categories, setCategories] = useState<any[]>(Array.from(MOCK_CATEGORIES));
  const [slides, setSlides] = useState<Slide[]>(PROMO_SLIDES);

  // Skeleton Loading States
  const [isLoadingProducts, setIsLoadingProducts] = useState<boolean>(true);
  const [isLoadingCategories, setIsLoadingCategories] = useState<boolean>(true);
  const [isLoadingSlides, setIsLoadingSlides] = useState<boolean>(true);

  const setPincode = (pin: string) => {
    setActivePincode(pin);
    localStorage.setItem('royal-fish-pincode', pin);
  };

  // Cart State
  const [cart, setCart] = useState<CartItem[]>(() => {
    const saved = localStorage.getItem('royal-fish-cart');
    return saved ? JSON.parse(saved) : [];
  });

  const [couponCode, setCouponCode] = useState<string>('');
  const [discountAmount, setDiscountAmount] = useState<number>(0);

  // Addresses State
  const [addresses, setAddresses] = useState<Address[]>(() => {
    const saved = localStorage.getItem('royal-fish-addresses');
    return saved ? JSON.parse(saved) : DEFAULT_ADDRESSES;
  });
  const [selectedAddressId, setSelectedAddressId] = useState<string | null>('addr-1');

  // Payment Method
  const [selectedPaymentMethod, setSelectedPaymentMethod] = useState<string>('upi');

  // Orders state
  const [orders, setOrders] = useState<Order[]>(() => {
    const saved = localStorage.getItem('royal-fish-orders');
    return saved ? JSON.parse(saved) : [];
  });
  const [lastPlacedOrder, setLastPlacedOrder] = useState<Order | null>(null);

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

  // Fetch categories, slides and products on boot & updates
  useEffect(() => {
    setIsLoadingCategories(true);
    fetch(`${API_BASE_URL}/categories`)
      .then(res => {
        if (!res.ok) throw new Error();
        return res.json();
      })
      .then(data => {
        if (Array.isArray(data) && data.length > 0) {
          setCategories(data);
        }
      })
      .catch(() => console.log('Backend categories offline, using mockup.'))
      .finally(() => setIsLoadingCategories(false));

    const fetchSlides = () => {
      fetch(`${API_BASE_URL}/slides`)
        .then(res => {
          if (!res.ok) throw new Error();
          return res.json();
        })
        .then(data => {
          if (Array.isArray(data) && data.length > 0) {
            setSlides(data);
          }
        })
        .catch(() => console.log('Backend slides offline, using mockup.'))
        .finally(() => setIsLoadingSlides(false));
    };

    fetchSlides();

    // Re-fetch slides on window focus and every 10s so Admin updates sync instantly
    const onFocus = () => fetchSlides();
    window.addEventListener('focus', onFocus);
    const slidesInterval = setInterval(fetchSlides, 10000);

    return () => {
      window.removeEventListener('focus', onFocus);
      clearInterval(slidesInterval);
    };
  }, []);

  useEffect(() => {
    setIsLoadingProducts(true);
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
          setProducts(data);
        }
      })
      .catch(() => console.log('Backend products offline, using mockup.'))
      .finally(() => setIsLoadingProducts(false));
  }, [selectedCategory, selectedSubCategory, searchQuery, activePincode]);

  // Load User Data (Addresses, orders) from API
  const loadUserData = async (token: string) => {
    try {
      const headers = { 'Authorization': `Bearer ${token}` };
      
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

  const login = async (name: string, phone: string, email: string) => {
    try {
      const res = await fetch(`${API_BASE_URL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, phone, email })
      });
      if (res.ok) {
        const data = await res.json();
        if (data.token) {
          localStorage.setItem('royal-fish-token', data.token);
          setUser(data.user);
          localStorage.setItem('royal-fish-user', JSON.stringify(data.user));
          loadUserData(data.token);
        }
      } else {
        // Fallback mockup login if server is offline
        const loggedInUser = { name, phone, email, isLoggedIn: true };
        setUser(loggedInUser);
        localStorage.setItem('royal-fish-user', JSON.stringify(loggedInUser));
      }
    } catch(err) {
      // Offline fallback
      const loggedInUser = { name, phone, email, isLoggedIn: true };
      setUser(loggedInUser);
      localStorage.setItem('royal-fish-user', JSON.stringify(loggedInUser));
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

  const navigateTo = (page: PageId, productId?: string) => {
    if (productId) {
      setSelectedProductId(productId);
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

  const selectedProduct = selectedProductId 
    ? products.find(p => p.id === selectedProductId) || null 
    : null;

  // Cart operations
  const addToCart = (product: Product) => {
    setCart(prev => {
      const existingIndex = prev.findIndex(item => item.product.id === product.id);
      if (existingIndex > -1) {
          const updated = [...prev];
          updated[existingIndex] = {
            ...updated[existingIndex],
            quantity: updated[existingIndex].quantity + 1
          };
          return updated;
      }
      return [...prev, { product, quantity: 1 }];
    });
  };

  const removeFromCart = (productId: string) => {
    setCart(prev => {
      const existingIndex = prev.findIndex(item => item.product.id === productId);
      if (existingIndex > -1) {
        const updated = [...prev];
        const item = updated[existingIndex];
        if (item.quantity > 1) {
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
  const deliveryFee = cartSubtotal === 0 ? 0 : (cartSubtotal >= 499 ? 0 : 49);

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
  const placeOrder = async () => {
    if (cart.length === 0) return;
    
    const token = localStorage.getItem('royal-fish-token');
    const selectedAddress = addresses.find(a => a.id === selectedAddressId) || addresses[0];

    if (!token) {
      // Offline mock fallback
      const newOrder: Order = {
        id: `ROYAL-${Math.floor(100000 + Math.random() * 900000)}`,
        date: new Date().toLocaleDateString('en-IN', {
          day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }),
        items: cart.map(item => ({
          productId: item.product.id,
          productName: item.product.name,
          productImage: item.product.image,
          price: item.product.price,
          quantity: item.quantity
        })),
        totalPrice: cartTotal,
        paymentMethod: selectedPaymentMethod.toUpperCase(),
        address: selectedAddress,
        status: 'Placed',
        estimatedDelivery: '30-45 mins'
      };
      setOrders(prev => [newOrder, ...prev]);
      setLastPlacedOrder(newOrder);
      clearCart();
      navigateTo('profile');
      return;
    }

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
          totalPrice: cartTotal
        })
      });
      if (res.ok) {
        const newOrder = await res.json();
        setOrders(prev => [newOrder, ...prev]);
        setLastPlacedOrder(newOrder);
        clearCart();
        navigateTo('profile');
      }
    } catch(err) {
      console.error(err);
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
        activePincode,
        setPincode,
        isPincodeModalOpen,
        setIsPincodeModalOpen,
        isLoadingProducts,
        isLoadingCategories,
        isLoadingSlides,
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
        lastPlacedOrder,
        user,
        login,
        logout,
        searchQuery,
        setSearchQuery,
        selectedCategory,
        setSelectedCategory,
        selectedSubCategory,
        setSelectedSubCategory,
        activeHeroIndex,
        setActiveHeroIndex
      }}
    >
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
