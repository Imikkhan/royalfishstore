export interface Product {
  id: string;
  name: string;
  category: string;
  subCategory?: string;
  image: string;
  price: number;
  originalPrice: number;
  weight: string; // e.g., "500g"
  pieces: string; // e.g., "12-15 Pieces"
  servings: string; // e.g., "Serves 3-4"
  grossWeight?: string;
  netWeight?: string;
  piecesAfterCutting?: string;
  deliveryTime?: string;
  delivery_time?: string;
  description: string;
  shortDescription?: string;
  short_description?: string;
  tags: string[];
  servicedPincodes?: string[];
  isDeliverable?: boolean;
  isBestSeller?: boolean;
  isTodaySpecial?: boolean;
  rating: number;
  reviewsCount: number;
  stockQuantity?: number;
  stock_quantity?: number;
  inStock?: boolean;
  in_stock?: boolean;
  lowStockThreshold?: number;
  low_stock_threshold?: number;
  minOrderQty?: number;
  min_order_qty?: number;
  maxOrderQty?: number;
  max_order_qty?: number;
  isOutOfStock?: boolean;
  is_out_of_stock?: boolean;
  isLowStock?: boolean;
  is_low_stock?: boolean;
}

export interface Slide {
  id: string;
  title: string;
  subtitle: string;
  code?: string;
  bgGradient?: string;
  image: string;
  textColor?: string;
}

export interface Video {
  id: string;
  title: string;
  youtubeUrl?: string;
  youtube_url?: string;
  youtubeId?: string;
  youtube_id?: string;
  thumbnail?: string;
  duration?: string;
}

export interface CartItem {
  product: Product;
  quantity: number;
}

export interface Address {
  id: string;
  name: string;
  type: 'Home' | 'Work' | 'Other';
  addressLine: string;
  city: string;
  zipCode: string;
  phone: string;
}

export interface Order {
  id: string;
  date: string;
  items: {
    productId: string;
    productName: string;
    productImage: string;
    price: number;
    quantity: number;
  }[];
  totalPrice: number;
  paymentMethod: string;
  address: Address;
  status: 'Placed' | 'Processing' | 'Out for Delivery' | 'Delivered';
  estimatedDelivery: string;
}

export interface User {
  name: string;
  phone: string;
  email: string;
  isLoggedIn: boolean;
}

export type PageId = 'home' | 'product-details' | 'cart' | 'profile' | 'login' | 'category-view' | 'categories' | 'search' | 'onepager';
