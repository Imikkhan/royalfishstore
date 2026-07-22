import { Product } from '../types';

export const CATEGORIES = [
  { id: 'fish-seafood', name: 'Fish & Seafood', icon: '🐟', description: 'Fresh Catch' },
  { id: 'chicken', name: 'Fresh Chicken', icon: '🍗', description: 'Tender Cuts' },
  { id: 'mutton', name: 'Rich Mutton', icon: '🥩', description: 'Premium Goat' },
  { id: 'marinades', name: 'Ready to Cook', icon: '🍢', description: 'Easy Marinades' },
  { id: 'cold-cuts', name: 'Cold Cuts', icon: '🥓', description: 'Salamis & Sausages' },
  { id: 'combos', name: 'Super Combos', icon: '🍱', description: 'Value Packs' },
] as const;

export const PROMO_SLIDES = [
  {
    id: 'slide-1',
    title: 'Fresh Monsoon Sea Harvest',
    subtitle: 'Flat 20% OFF on premium tiger prawns and pomfret!',
    code: 'ROYAL20',
    bgGradient: 'from-blue-600 to-cyan-500',
    image: 'https://images.unsplash.com/photo-1553618551-fba689030290?auto=format&fit=crop&w=800&q=80',
    textColor: 'text-white'
  },
  {
    id: 'slide-2',
    title: 'Sukkha Goat Meat Special',
    subtitle: 'Pure Himalayan Goat Curry Cut. Extra tender, delivered fresh in 45 mins.',
    code: 'MUTTONLOVE',
    bgGradient: 'from-amber-700 to-red-800',
    image: 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=800&q=80',
    textColor: 'text-white'
  },
  {
    id: 'slide-3',
    title: 'Easy Marinade Platters',
    subtitle: 'Buy 1 Get 1 Free on all Tikka and Malai marinades.',
    code: 'READY2COOK',
    bgGradient: 'from-emerald-600 to-teal-500',
    image: 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=800&q=80',
    textColor: 'text-white'
  }
];

export const PRODUCTS: Product[] = [
  // Fish & Seafood
  {
    id: 'fs-hilsa',
    name: 'Hilsa Fresh Diamond Harbour 1 Pc)* (.980kg-1kg)',
    category: 'fish-seafood',
    subCategory: 'Seawater Fish',
    image: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
    price: 249,
    originalPrice: 265,
    weight: '1 Pc (.980kg-1kg)',
    pieces: '10-11 Pieces',
    servings: 'Serves 3-4',
    grossWeight: '980gms -1Kg',
    netWeight: '900-950gms',
    piecesAfterCutting: '10-11 pieces after cutting.',
    deliveryTime: 'Today 4:00pm - 08:30 pm',
    description: 'Sought-after delicious freshwater/seawater Hilsa sourced directly from Diamond Harbour. Perfectly processed and sliced for curry or frying.',
    tags: ['Royal Catch', 'Special Price'],
    isBestSeller: true,
    rating: 4.9,
    reviewsCount: 312
  },
  {
    id: 'fs-1',
    name: 'Surmai / Seer King Fish Steaks',
    category: 'fish-seafood',
    subCategory: 'Seawater Fish',
    image: 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=500&q=80',
    price: 649,
    originalPrice: 799,
    weight: '500g',
    pieces: '5-7 Steaks',
    servings: 'Serves 2-3',
    description: 'Also known as King Fish or Surmai, these meaty steaks are freshly sliced, scales removed, and perfectly ready to be shallow fried or cooked in a tangy coastal gravy. Highly rich in Omega-3 fatty acids and protein.',
    tags: ['Best Seller', 'Fresh Catch', 'High Omega 3'],
    isBestSeller: true,
    rating: 4.9,
    reviewsCount: 142
  },
  {
    id: 'fs-2',
    name: 'White Tiger Prawns - Cleaned & De-veined',
    category: 'fish-seafood',
    subCategory: 'Prawns',
    image: 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=500&q=80',
    price: 399,
    originalPrice: 499,
    weight: '250g',
    pieces: '15-20 Pieces',
    servings: 'Serves 2',
    description: 'Juicy, sweet White Tiger Prawns, thoroughly cleaned, peeled, and de-veined with tail-on. Perfect for Butter Garlic prawns, tandoori skewers, or coastal curries.',
    tags: ['Cleaned & Peeled', 'No Mess', 'Sweet Taste'],
    isBestSeller: true,
    isTodaySpecial: true,
    rating: 4.8,
    reviewsCount: 208
  },
  {
    id: 'fs-3',
    name: 'Premium Salmon Fillet (Skin On)',
    category: 'fish-seafood',
    subCategory: 'Exotic Catch',
    image: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=500&q=80',
    price: 1199,
    originalPrice: 1499,
    weight: '250g',
    pieces: '1 Fillet',
    servings: 'Serves 1',
    description: 'Sourced from clean Norwegian waters, this premium pink salmon fillet comes with the skin intact for a crispy cook. Extremely rich in heart-healthy Omega-3 fats.',
    tags: ['Imported', 'Sashimi Grade', 'Super Food'],
    rating: 4.7,
    reviewsCount: 89
  },
  {
    id: 'fs-4',
    name: 'Freshwater Rohu - Bengali Cut (No Head)',
    category: 'fish-seafood',
    subCategory: 'Freshwater Fish',
    image: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
    price: 249,
    originalPrice: 299,
    weight: '500g',
    pieces: '6-8 Pieces',
    servings: 'Serves 2-3',
    description: 'Sweet freshwater Rohu cut in traditional Bengali style. Perfect for Rohu Kalia or Jhol. Sourced daily from bio-secure farms and cleaned perfectly.',
    tags: ['Freshwater', 'Bengali Special'],
    rating: 4.6,
    reviewsCount: 312
  },

  // Chicken
  {
    id: 'ch-1',
    name: 'Tender Chicken Curry Cut (Small)',
    category: 'chicken',
    subCategory: 'Curry Cuts',
    image: 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80',
    price: 169,
    originalPrice: 199,
    weight: '500g',
    pieces: '12-16 Pieces',
    servings: 'Serves 2-3',
    description: 'Freshly dressed, juicy, pasture-raised spring chicken cuts including breast, wing, and drumsticks. Ideal for aromatic Indian curries or home-style gravies.',
    tags: ['Antibiotic-free', 'Juicy Cuts', 'Daily Fresh'],
    isBestSeller: true,
    rating: 4.8,
    reviewsCount: 521
  },
  {
    id: 'ch-2',
    name: 'Premium Chicken Breast Fillet',
    category: 'chicken',
    subCategory: 'Boneless & Mince',
    image: 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=500&q=80',
    price: 259,
    originalPrice: 320,
    weight: '500g',
    pieces: '3-4 Fillets',
    servings: 'Serves 2-3',
    description: 'Boneless, skinless breasts trimmed of fat. High in lean protein, low in calorie. Excellent choice for gym-goers, meal prep, pan-searing, or grilling.',
    tags: ['Lean Protein', 'Zero Fat', 'Fitness Choice'],
    isTodaySpecial: true,
    rating: 4.7,
    reviewsCount: 410
  },

  // Mutton
  {
    id: 'mu-1',
    name: 'Rich Goat Curry Cut (Mix)',
    category: 'mutton',
    subCategory: 'Curry Cuts',
    image: 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80',
    price: 679,
    originalPrice: 799,
    weight: '500g',
    pieces: '15-18 Pieces',
    servings: 'Serves 3',
    description: 'Juicy, tender, fat-marbled pieces of goat meat cut from the leg, shoulder, and ribs. High quality pasture-raised goats from registered farms. Perfect for mutton biryani, korma, or slow-cooked stews.',
    tags: ['Tender Goat', 'Marbled Meat', 'No Added Hormones'],
    isBestSeller: true,
    rating: 4.9,
    reviewsCount: 295
  },
  {
    id: 'mu-2',
    name: 'Premium Goat Keema (Minced)',
    category: 'mutton',
    subCategory: 'Keema & Minced',
    image: 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=500&q=80',
    price: 389,
    originalPrice: 449,
    weight: '250g',
    pieces: 'Finely Minced',
    servings: 'Serves 2',
    description: 'Finely minced mutton from succulent, boneless goat cuts. Delivers deep, authentic mutton flavor. Crafted for delicious keema matar, keema samosas, or juicy mutton patties.',
    tags: ['Boneless', 'Finely Ground', 'Quick Cook'],
    rating: 4.8,
    reviewsCount: 167
  },

  // Marinades
  {
    id: 'ma-1',
    name: 'Tandoori Chicken Tikka Marinade',
    category: 'marinades',
    subCategory: 'Chicken Marinades',
    image: 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=500&q=80',
    price: 219,
    originalPrice: 269,
    weight: '350g',
    pieces: '10-12 Pieces',
    servings: 'Serves 2',
    description: 'Tender boneless chicken thigh cubes marinated in authentic spiced yogurt, ginger-garlic paste, mustard oil, and real Kashmiri red chilies. Ready to bake, grill, or pan fry in 10 minutes!',
    tags: ['Ready to Cook', 'Spicy', 'Chef Special'],
    isTodaySpecial: true,
    rating: 4.8,
    reviewsCount: 334
  },
  {
    id: 'ma-2',
    name: 'Hariyali Fish Tikka Marinade',
    category: 'marinades',
    subCategory: 'Fish Marinades',
    image: 'https://images.unsplash.com/photo-1511216113906-8f57bb83e776?auto=format&fit=crop&w=500&q=80',
    price: 349,
    originalPrice: 429,
    weight: '300g',
    pieces: '8-10 Pieces',
    servings: 'Serves 2',
    description: 'Fresh Basa cubes generously coated with an herbaceous, cooling paste of mint, coriander, spinach, green chilies, and aromatic spices. Freshly packed on order.',
    tags: ['Herbal Spices', 'Mildly Hot', 'Exotic Taste'],
    rating: 4.5,
    reviewsCount: 94
  },

  // Cold Cuts
  {
    id: 'cc-1',
    name: 'Chicken Salami (Smoked)',
    category: 'cold-cuts',
    subCategory: 'Salami & Sausages',
    image: 'https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=500&q=80',
    price: 159,
    originalPrice: 199,
    weight: '200g',
    pieces: '12-15 Slices',
    servings: 'Serves 2-3',
    description: 'Fully cooked, hickory-smoked premium chicken breast salami slices. Gently flavored with black pepper and mild garlic. Perfect for breakfast sandwiches, wraps, or charcuterie boards.',
    tags: ['Ready to Eat', 'Smoked Flavor', 'Breakfast Essential'],
    rating: 4.6,
    reviewsCount: 178
  },

  // Combos
  {
    id: 'co-1',
    name: 'Super Fish Fry & Curry Combo',
    category: 'combos',
    subCategory: 'Combo Packs',
    image: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
    price: 799,
    originalPrice: 999,
    weight: '1kg Combo',
    pieces: '2 Packs',
    servings: 'Serves 4-5',
    description: 'Get the best of seawater and freshwater in one go! Includes 500g freshwater Rohu (Bengali Cut) and 500g seawater Basa Fillet at a discounted value pack price.',
    tags: ['Combo Deal', 'Seafood Lover', 'Big Saving'],
    isBestSeller: true,
    rating: 4.9,
    reviewsCount: 220
  }
];

export const SUBCATEGORIES: Record<string, string[]> = {
  'fish-seafood': ['All', 'Seawater Fish', 'Freshwater Fish', 'Prawns', 'Exotic Catch'],
  'chicken': ['All', 'Curry Cuts', 'Boneless & Mince'],
  'mutton': ['All', 'Curry Cuts', 'Keema & Minced'],
  'marinades': ['All', 'Chicken Marinades', 'Fish Marinades'],
  'cold-cuts': ['All', 'Salami & Sausages'],
  'combos': ['All', 'Combo Packs']
};

