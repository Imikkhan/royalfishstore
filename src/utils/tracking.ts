import { Product, CartItem, Order } from '../types';

declare global {
  interface Window {
    dataLayer?: any[];
  }
}

/**
 * Push an event to window.dataLayer following GA4 / Meta Pixel GTM specifications.
 * Always clears previous ecommerce object with { ecommerce: null } prior to pushing event.
 */
const pushDataLayer = (eventPayload: Record<string, any>) => {
  if (typeof window === 'undefined') return;
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({ ecommerce: null });
  window.dataLayer.push(eventPayload);

  if (process.env.NODE_ENV !== 'production') {
    console.log('[GTM dataLayer Event]', eventPayload);
  }
};

/**
 * 1. Product Page — view_item
 * Fire when product page loads
 */
export const trackViewItem = (product: Product) => {
  if (!product) return;
  const price = Number(product.price || 0);
  const sku = String((product as any).sku || (product as any).product_code || product.id || '');
  const category = typeof product.category === 'object'
    ? (product.category as any)?.name || 'Fish & Seafood'
    : String(product.category || 'Fish & Seafood');

  pushDataLayer({
    event: 'view_item',
    ecommerce: {
      currency: 'INR',
      value: price,
      items: [{
        item_name: product.name,
        item_id: sku,
        price: price,
        item_category: category,
        quantity: 1
      }]
    }
  });
};

/**
 * 2. Add to Cart Button — add_to_cart
 * Fire when user clicks Add to Cart
 */
export const trackAddToCart = (product: Product, quantity: number = 1) => {
  if (!product) return;
  const price = Number(product.price || 0);
  const qty = Math.max(1, Number(quantity) || 1);
  const sku = String((product as any).sku || (product as any).product_code || product.id || '');
  const category = typeof product.category === 'object'
    ? (product.category as any)?.name || 'Fish & Seafood'
    : String(product.category || 'Fish & Seafood');

  pushDataLayer({
    event: 'add_to_cart',
    ecommerce: {
      currency: 'INR',
      value: price * qty,
      items: [{
        item_name: product.name,
        item_id: sku,
        price: price,
        item_category: category,
        quantity: qty
      }]
    }
  });
};

/**
 * 3. Checkout Page — begin_checkout
 * Fire when checkout page loads
 */
export const trackBeginCheckout = (cartItems: CartItem[], total: number) => {
  if (!cartItems || cartItems.length === 0) return;
  const totalValue = Number(total || 0);

  const items = cartItems.map(item => {
    const p = item.product;
    const price = Number(p.price || 0);
    const sku = String((p as any).sku || (p as any).product_code || p.id || '');
    const category = typeof p.category === 'object'
      ? (p.category as any)?.name || 'Fish & Seafood'
      : String(p.category || 'Fish & Seafood');

    return {
      item_name: p.name,
      item_id: sku,
      price: price,
      item_category: category,
      quantity: Number(item.quantity || 1)
    };
  });

  pushDataLayer({
    event: 'begin_checkout',
    ecommerce: {
      currency: 'INR',
      value: totalValue,
      items: items
    }
  });
};

/**
 * 4. Order Success Page — purchase
 * Fire on Order Success page ONLY — once per order
 */
export const trackPurchase = (order: Order | any, fallbackCartItems?: CartItem[]) => {
  if (!order || !order.id) return;

  const orderId = String(order.id);
  const sessionKey = 'rfs_tracked_purchases';

  try {
    const trackedOrders: string[] = JSON.parse(sessionStorage.getItem(sessionKey) || '[]');
    if (trackedOrders.includes(orderId)) {
      // Prevent duplicate firing on page refresh / re-render
      return;
    }
    trackedOrders.push(orderId);
    sessionStorage.setItem(sessionKey, JSON.stringify(trackedOrders));
  } catch {
    // Graceful fallback if sessionStorage is restricted
  }

  const orderTotal = Number(
    order.totalPrice !== undefined
      ? order.totalPrice
      : (order.total !== undefined ? order.total : 0)
  );

  let items: any[] = [];
  if (Array.isArray(order.items) && order.items.length > 0) {
    items = order.items.map((item: any) => {
      const price = Number(item.price || 0);
      const sku = String(item.sku || item.productId || item.item_id || item.id || '');
      const category = typeof item.category === 'object'
        ? (item.category as any)?.name || 'Fish & Seafood'
        : String(item.category || item.item_category || 'Fish & Seafood');

      return {
        item_name: item.productName || item.name || item.item_name || 'Fish & Seafood Item',
        item_id: sku,
        price: price,
        item_category: category,
        quantity: Number(item.quantity || 1)
      };
    });
  } else if (fallbackCartItems && fallbackCartItems.length > 0) {
    items = fallbackCartItems.map(ci => {
      const p = ci.product;
      const price = Number(p.price || 0);
      const sku = String((p as any).sku || (p as any).product_code || p.id || '');
      const category = typeof p.category === 'object'
        ? (p.category as any)?.name || 'Fish & Seafood'
        : String(p.category || 'Fish & Seafood');

      return {
        item_name: p.name,
        item_id: sku,
        price: price,
        item_category: category,
        quantity: Number(ci.quantity || 1)
      };
    });
  }

  pushDataLayer({
    event: 'purchase',
    ecommerce: {
      transaction_id: orderId,
      currency: 'INR',
      value: orderTotal,
      items: items
    }
  });
};
