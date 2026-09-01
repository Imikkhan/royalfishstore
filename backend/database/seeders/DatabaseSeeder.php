<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed Hero Carousel Slides
        $slides = [
            [
                'title' => 'Fresh Monsoon Sea Harvest',
                'subtitle' => 'Flat 20% OFF on premium tiger prawns and pomfret!',
                'code' => 'ROYAL20',
                'bg_gradient' => 'from-blue-600 to-cyan-500',
                'image' => 'https://images.unsplash.com/photo-1553618551-fba689030290?auto=format&fit=crop&w=800&q=80',
                'text_color' => 'text-white',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sukkha Goat Meat Special',
                'subtitle' => 'Pure Himalayan Goat Curry Cut. Extra tender, delivered fresh in 45 mins.',
                'code' => 'MUTTONLOVE',
                'bg_gradient' => 'from-amber-700 to-red-800',
                'image' => 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=800&q=80',
                'text_color' => 'text-white',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Easy Marinade Platters',
                'subtitle' => 'Buy 1 Get 1 Free on all Tikka and Malai marinades.',
                'code' => 'READY2COOK',
                'bg_gradient' => 'from-emerald-600 to-teal-500',
                'image' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=800&q=80',
                'text_color' => 'text-white',
                'sort_order' => 3,
                'is_active' => true,
            ]
        ];

        foreach ($slides as $s) {
            Slide::updateOrCreate(['title' => $s['title']], $s);
        }
        // 1. Seed Roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'permissions' => json_encode(['*'])
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'permissions' => json_encode(['dashboard', 'roles', 'users', 'categories', 'products', 'orders', 'media', 'settings'])
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'permissions' => json_encode(['dashboard', 'categories', 'products', 'orders', 'media'])
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'permissions' => json_encode(['dashboard', 'orders'])
            ]
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['slug' => $r['slug']], $r);
        }

        $superAdminRole = Role::where('slug', 'super-admin')->first();

        // 2. Seed Super Admin User
        User::updateOrCreate(
            ['email' => 'admin@royalfish.com'],
            [
                'name' => 'Super Admin',
                'phone' => '9999999999',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
            ]
        );

        // 3. Seed Settings
        $settings = [
            'website_name' => 'Royal Fish Store',
            'website_tagline' => 'Online Fish Delivery',
            'website_email' => 'care@royalfish.com',
            'website_phone' => '+91 98765 43210',
            'website_address' => 'Flat 402, Royal Residency, Marine Drive, Mumbai',
            'seo_meta_title' => 'Royal Fish Store - Premium Fresh Fish Online Delivery',
            'seo_meta_description' => 'Royal Fish Store offers the finest, premium fresh fish and seafood sourced directly and delivered fresh to your doorstep.',
            'seo_meta_keywords' => 'Royal Fish Store, Royal Fish, fresh fish, seafood delivery',
            'theme_color' => '#a80e0e',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => '2525',
            'smtp_user' => 'mock_user',
            'smtp_password' => 'mock_password',
            'social_facebook' => 'https://facebook.com/royalfishstore',
            'social_instagram' => 'https://instagram.com/royalfishstore',
            'social_twitter' => 'https://twitter.com/royalfishstore',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 4. Seed Categories & Subcategories
        $categories = [
            // 1. Wholesale Fish
            [
                'name' => 'Wholesale Fish',
                'slug' => 'wholesale-fish',
                'icon' => '📦',
                'image' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=500&q=80',
                'description' => 'Bulk & Wholesale Fresh Fish',
                'parent_slug' => null
            ],

            // 2. Fresh Fish & its Subcategories
            [
                'name' => 'Fresh Fish',
                'slug' => 'fresh-fish',
                'icon' => '🐟',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
                'description' => 'Daily Fresh Catch',
                'parent_slug' => null
            ],
            [
                'name' => 'Rohu & Catla',
                'slug' => 'rohu-catla',
                'icon' => '🏞️',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=200&q=80',
                'description' => 'Freshwater Rohu & Catla cuts',
                'parent_slug' => 'fresh-fish'
            ],
            [
                'name' => 'Live Fish',
                'slug' => 'live-fish',
                'icon' => '🌊',
                'image' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=200&q=80',
                'description' => 'Live swimming fresh fish',
                'parent_slug' => 'fresh-fish'
            ],
            [
                'name' => 'Prawn & Shell Fish',
                'slug' => 'prawn-shell-fish',
                'icon' => '🦐',
                'image' => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=200&q=80',
                'description' => 'Cleaned prawns & shellfish',
                'parent_slug' => 'fresh-fish'
            ],
            [
                'name' => 'Fillet',
                'slug' => 'fillet',
                'icon' => '🔪',
                'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=200&q=80',
                'description' => 'Boneless fish fillets',
                'parent_slug' => 'fresh-fish'
            ],
            [
                'name' => 'Premium Fish',
                'slug' => 'premium-fish',
                'icon' => '✨',
                'image' => 'https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?auto=format&fit=crop&w=200&q=80',
                'description' => 'Surmai, Pomfret & Salmon',
                'parent_slug' => 'fresh-fish'
            ],
            [
                'name' => 'Other Fish',
                'slug' => 'other-fish',
                'icon' => '🐠',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=200&q=80',
                'description' => 'Assorted regional catch',
                'parent_slug' => 'fresh-fish'
            ],

            // 3. Fresh Hilsa
            [
                'name' => 'Fresh Hilsa',
                'slug' => 'fresh-hilsa',
                'icon' => '👑',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
                'description' => 'Diamond Harbour Fresh Hilsa (Ilish)',
                'parent_slug' => null
            ],

            // 4. Seafood
            [
                'name' => 'Seafood',
                'slug' => 'seafood',
                'icon' => '🦀',
                'image' => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=500&q=80',
                'description' => 'Crabs, Lobsters & Marine Catch',
                'parent_slug' => null
            ],

            // 5. Chicken & its Subcategories
            [
                'name' => 'Chicken',
                'slug' => 'chicken',
                'icon' => '🍗',
                'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80',
                'description' => 'Farm Fresh Tender Chicken',
                'parent_slug' => null
            ],
            [
                'name' => 'Chicken Curry Cuts',
                'slug' => 'chicken-curry-cuts',
                'icon' => '🍗',
                'image' => 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?auto=format&fit=crop&w=200&q=80',
                'description' => 'Bone-in curry cuts',
                'parent_slug' => 'chicken'
            ],
            [
                'name' => 'Chicken Boneless',
                'slug' => 'chicken-boneless',
                'icon' => '🥩',
                'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=200&q=80',
                'description' => 'Boneless breast & thigh fillets',
                'parent_slug' => 'chicken'
            ],
            [
                'name' => 'Chicken Special Cuts',
                'slug' => 'chicken-special-cuts',
                'icon' => '🍢',
                'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=200&q=80',
                'description' => 'Drumsticks, wings & lollipop',
                'parent_slug' => 'chicken'
            ],
            [
                'name' => 'Chicken Liver',
                'slug' => 'chicken-liver',
                'icon' => '🫀',
                'image' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=200&q=80',
                'description' => 'Fresh chicken liver & gizzard',
                'parent_slug' => 'chicken'
            ],

            // 6. Mutton & its Subcategories
            [
                'name' => 'Mutton',
                'slug' => 'mutton',
                'icon' => '🥩',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80',
                'description' => 'Rich Pasture-Raised Goat Meat',
                'parent_slug' => null
            ],
            [
                'name' => 'Mutton Curry Cuts',
                'slug' => 'mutton-curry-cuts',
                'icon' => '🍖',
                'image' => 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=200&q=80',
                'description' => 'Standard goat curry cuts',
                'parent_slug' => 'mutton'
            ],
            [
                'name' => 'Mutton Boneless',
                'slug' => 'mutton-boneless',
                'icon' => '🥩',
                'image' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=200&q=80',
                'description' => 'Boneless mutton & keema',
                'parent_slug' => 'mutton'
            ],
            [
                'name' => 'Mutton Special Cuts',
                'slug' => 'mutton-special-cuts',
                'icon' => '🍢',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=200&q=80',
                'description' => 'Goat nalli, chops & ribs',
                'parent_slug' => 'mutton'
            ],
            [
                'name' => 'Mutton Liver',
                'slug' => 'mutton-liver',
                'icon' => '🫀',
                'image' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=200&q=80',
                'description' => 'Fresh goat liver (Kaleji)',
                'parent_slug' => 'mutton'
            ]
        ];

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categoryMap = [];
        // First loop: Seed Main categories
        foreach ($categories as $cat) {
            if ($cat['parent_slug'] === null) {
                $created = Category::updateOrCreate(['slug' => $cat['slug']], [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'icon' => $cat['icon'],
                    'image' => $cat['image'] ?? null,
                    'description' => $cat['description'],
                    'parent_id' => null,
                    'is_active' => true
                ]);
                $categoryMap[$cat['slug']] = $created->id;
            }
        }
        // Second loop: Seed Subcategories
        foreach ($categories as $cat) {
            if ($cat['parent_slug'] !== null) {
                $parentId = $categoryMap[$cat['parent_slug']] ?? null;
                $created = Category::updateOrCreate(['slug' => $cat['slug']], [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'icon' => $cat['icon'],
                    'image' => $cat['image'] ?? null,
                    'description' => $cat['description'],
                    'parent_id' => $parentId,
                    'is_active' => true
                ]);
                // Keep mapping key matching slug for products seeding
                $categoryMap[$cat['name']] = $created->id;
            }
        }

        // 5. Seed Products
        $products = [
            [
                'product_code' => 'fs-hilsa',
                'name' => 'Hilsa Fresh Diamond Harbour (1 Pc)* (.980kg-1kg)',
                'category_slug' => 'fresh-fish',
                'sub_category' => 'Seawater Fish',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
                'price' => 249,
                'original_price' => 265,
                'weight' => '1 Pc (.980kg-1kg)',
                'pieces' => '10-11 Pieces',
                'servings' => 'Serves 3-4',
                'description' => 'Sought-after delicious freshwater/seawater Hilsa sourced directly from Diamond Harbour. Perfectly processed and sliced for curry or frying.',
                'tags' => json_encode(['Royal Catch', 'Special Price']),
                'is_best_seller' => true,
                'is_today_special' => false,
                'rating' => 4.9,
                'reviews_count' => 312
            ],
            [
                'product_code' => 'fs-1',
                'name' => 'Surmai / Seer King Fish Steaks',
                'category_slug' => 'fresh-fish',
                'sub_category' => 'Seawater Fish',
                'image' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=500&q=80',
                'price' => 649,
                'original_price' => 799,
                'weight' => '500g',
                'pieces' => '5-7 Steaks',
                'servings' => 'Serves 2-3',
                'description' => 'Also known as King Fish or Surmai, these meaty steaks are freshly sliced, scales removed, and perfectly ready to be shallow fried or cooked in a tangy coastal gravy. Highly rich in Omega-3 fatty acids and protein.',
                'tags' => json_encode(['Best Seller', 'Fresh Catch', 'High Omega 3']),
                'is_best_seller' => true,
                'is_today_special' => false,
                'rating' => 4.9,
                'reviews_count' => 142
            ],
            [
                'product_code' => 'fs-2',
                'name' => 'White Tiger Prawns - Cleaned & De-veined',
                'category_slug' => 'fresh-fish',
                'sub_category' => 'Prawns',
                'image' => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=500&q=80',
                'price' => 399,
                'original_price' => 499,
                'weight' => '250g',
                'pieces' => '15-20 Pieces',
                'servings' => 'Serves 2',
                'description' => 'Juicy, sweet White Tiger Prawns, thoroughly cleaned, peeled, and de-veined with tail-on. Perfect for Butter Garlic prawns, tandoori skewers, or coastal curries.',
                'tags' => json_encode(['Cleaned & Peeled', 'No Mess', 'Sweet Taste']),
                'is_best_seller' => true,
                'is_today_special' => true,
                'rating' => 4.8,
                'reviews_count' => 208
            ],
            [
                'product_code' => 'fs-3',
                'name' => 'Premium Salmon Fillet (Skin On)',
                'category_slug' => 'fresh-fish',
                'sub_category' => 'Exotic Catch',
                'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=500&q=80',
                'price' => 1199,
                'original_price' => 1499,
                'weight' => '250g',
                'pieces' => '1 Fillet',
                'servings' => 'Serves 1',
                'description' => 'Sourced from clean Norwegian waters, this premium pink salmon fillet comes with the skin intact for a crispy cook. Extremely rich in heart-healthy Omega-3 fats.',
                'tags' => json_encode(['Imported', 'Sashimi Grade', 'Super Food']),
                'is_best_seller' => false,
                'is_today_special' => false,
                'rating' => 4.7,
                'reviews_count' => 89
            ],
            [
                'product_code' => 'fs-4',
                'name' => 'Freshwater Rohu - Bengali Cut (No Head)',
                'category_slug' => 'fresh-fish',
                'sub_category' => 'Freshwater Fish',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
                'price' => 249,
                'original_price' => 299,
                'weight' => '500g',
                'pieces' => '6-8 Pieces',
                'servings' => 'Serves 2-3',
                'description' => 'Sweet freshwater Rohu cut in traditional Bengali style. Perfect for Rohu Kalia or Jhol. Sourced daily from bio-secure farms and cleaned perfectly.',
                'tags' => json_encode(['Freshwater', 'Bengali Special']),
                'is_best_seller' => false,
                'is_today_special' => false,
                'rating' => 4.6,
                'reviews_count' => 312
            ],
            [
                'product_code' => 'ch-1',
                'name' => 'Tender Chicken Curry Cut (Small)',
                'category_slug' => 'chicken',
                'sub_category' => 'Curry Cuts',
                'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80',
                'price' => 169,
                'original_price' => 199,
                'weight' => '500g',
                'pieces' => '12-16 Pieces',
                'servings' => 'Serves 2-3',
                'description' => 'Freshly dressed, juicy, pasture-raised spring chicken cuts including breast, wing, and drumsticks. Ideal for aromatic Indian curries or home-style gravies.',
                'tags' => json_encode(['Antibiotic-free', 'Juicy Cuts', 'Daily Fresh']),
                'is_best_seller' => true,
                'is_today_special' => false,
                'rating' => 4.8,
                'reviews_count' => 521
            ],
            [
                'product_code' => 'ch-2',
                'name' => 'Premium Chicken Breast Fillet',
                'category_slug' => 'chicken',
                'sub_category' => 'Boneless & Mince',
                'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=500&q=80',
                'price' => 259,
                'original_price' => 320,
                'weight' => '500g',
                'pieces' => '3-4 Fillets',
                'servings' => 'Serves 2-3',
                'description' => 'Boneless, skinless breasts trimmed of fat. High in lean protein, low in calorie. Excellent choice for gym-goers, meal prep, pan-searing, or grilling.',
                'tags' => json_encode(['Lean Protein', 'Zero Fat', 'Fitness Choice']),
                'is_best_seller' => false,
                'is_today_special' => true,
                'rating' => 4.7,
                'reviews_count' => 410
            ],
            [
                'product_code' => 'mu-1',
                'name' => 'Rich Goat Curry Cut (Mix)',
                'category_slug' => 'mutton',
                'sub_category' => 'Curry Cuts',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80',
                'price' => 679,
                'original_price' => 799,
                'weight' => '500g',
                'pieces' => '15-18 Pieces',
                'servings' => 'Serves 3',
                'description' => 'Juicy, tender, fat-marbled pieces of goat meat cut from the leg, shoulder, and ribs. High quality pasture-raised goats from registered farms. Perfect for mutton biryani, korma, or slow-cooked stews.',
                'tags' => json_encode(['Tender Goat', 'Marbled Meat', 'No Added Hormones']),
                'is_best_seller' => true,
                'is_today_special' => false,
                'rating' => 4.9,
                'reviews_count' => 295
            ],
            [
                'product_code' => 'mu-2',
                'name' => 'Premium Goat Keema (Minced)',
                'category_slug' => 'mutton',
                'sub_category' => 'Keema & Minced',
                'image' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=500&q=80',
                'price' => 389,
                'original_price' => 449,
                'weight' => '250g',
                'pieces' => 'Finely Minced',
                'servings' => 'Serves 2',
                'description' => 'Finely minced mutton from succulent, boneless goat cuts. Delivers deep, authentic mutton flavor. Crafted for delicious keema matar, keema samosas, or juicy mutton patties.',
                'tags' => json_encode(['Boneless', 'Finely Ground', 'Quick Cook']),
                'is_best_seller' => false,
                'is_today_special' => false,
                'rating' => 4.8,
                'reviews_count' => 167
            ],
            [
                'product_code' => 'ma-1',
                'name' => 'Tandoori Chicken Tikka Marinade',
                'category_slug' => 'marinades',
                'sub_category' => 'Chicken Marinades',
                'image' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=500&q=80',
                'price' => 219,
                'original_price' => 269,
                'weight' => '350g',
                'pieces' => '10-12 Pieces',
                'servings' => 'Serves 2',
                'description' => 'Tender boneless chicken thigh cubes marinated in authentic spiced yogurt, ginger-garlic paste, mustard oil, and real Kashmiri red chilies. Ready to bake, grill, or pan fry in 10 minutes!',
                'tags' => json_encode(['Ready to Cook', 'Spicy', 'Chef Special']),
                'is_best_seller' => false,
                'is_today_special' => true,
                'rating' => 4.8,
                'reviews_count' => 334
            ],
            [
                'product_code' => 'ma-2',
                'name' => 'Hariyali Fish Tikka Marinade',
                'category_slug' => 'marinades',
                'sub_category' => 'Fish Marinades',
                'image' => 'https://images.unsplash.com/photo-1511216113906-8f57bb83e776?auto=format&fit=crop&w=500&q=80',
                'price' => 349,
                'original_price' => 429,
                'weight' => '300g',
                'pieces' => '8-10 Pieces',
                'servings' => 'Serves 2',
                'description' => 'Fresh Basa cubes generously coated with an herbaceous, cooling paste of mint, coriander, spinach, green chilies, and aromatic spices. Freshly packed on order.',
                'tags' => json_encode(['Herbal Spices', 'Mildly Hot', 'Exotic Taste']),
                'is_best_seller' => false,
                'is_today_special' => false,
                'rating' => 4.5,
                'reviews_count' => 94
            ],
            [
                'product_code' => 'cc-1',
                'name' => 'Chicken Salami (Smoked)',
                'category_slug' => 'cold-cuts',
                'sub_category' => 'Salami & Sausages',
                'image' => 'https://images.unsplash.com/photo-1629450646452-278271dbde1d?auto=format&fit=crop&w=500&q=80',
                'price' => 159,
                'original_price' => 199,
                'weight' => '200g',
                'pieces' => '12-15 Slices',
                'servings' => 'Serves 2-3',
                'description' => 'Fully cooked, hickory-smoked premium chicken breast salami slices. Gently flavored with black pepper and mild garlic. Perfect for breakfast sandwiches, wraps, or charcuterie boards.',
                'tags' => json_encode(['Ready to Eat', 'Smoked Flavor', 'Breakfast Essential']),
                'is_best_seller' => false,
                'is_today_special' => false,
                'rating' => 4.6,
                'reviews_count' => 178
            ],
            [
                'product_code' => 'co-1',
                'name' => 'Super Fish Fry & Curry Combo',
                'category_slug' => 'combos',
                'sub_category' => 'Combo Packs',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80',
                'price' => 799,
                'original_price' => 999,
                'weight' => '1kg Combo',
                'pieces' => '2 Packs',
                'servings' => 'Serves 4-5',
                'description' => 'Get the best of seawater and freshwater in one go! Includes 500g freshwater Rohu (Bengali Cut) and 500g seawater Basa Fillet at a discounted value pack price.',
                'tags' => json_encode(['Combo Deal', 'Seafood Lover', 'Big Saving']),
                'is_best_seller' => true,
                'is_today_special' => false,
                'rating' => 4.9,
                'reviews_count' => 220
            ]
        ];

        foreach ($products as $p) {
            $slug = Str::slug($p['name']);
            Product::updateOrCreate(
                ['product_code' => $p['product_code']],
                [
                    'name' => $p['name'],
                    'slug' => $slug,
                    'category_id' => $categoryMap[$p['category_slug']] ?? (reset($categoryMap) ?: 1),
                    'sub_category' => $p['sub_category'],
                    'image' => $p['image'],
                    'price' => $p['price'],
                    'original_price' => $p['original_price'],
                    'weight' => $p['weight'],
                    'pieces' => $p['pieces'],
                    'servings' => $p['servings'],
                    'description' => $p['description'],
                    'tags' => $p['tags'],
                    'is_best_seller' => $p['is_best_seller'],
                    'is_today_special' => $p['is_today_special'],
                    'rating' => $p['rating'],
                    'reviews_count' => $p['reviews_count'],
                ]
            );
        }

        // 6. Seed Hero Carousel Slides
        $slides = [
            [
                'title' => 'Flat 20% OFF on First Order',
                'subtitle' => 'Use code ROYAL20 for extra discount on all fresh cuts',
                'code' => 'ROYAL20',
                'bg_gradient' => 'from-red-600 to-rose-500',
                'image' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Express 45-Min Fish Delivery',
                'subtitle' => 'Daily Diamond Harbour & Bay catch direct to kitchen',
                'code' => 'EXPRESS',
                'bg_gradient' => 'from-blue-600 to-indigo-600',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Weekend Special Mutton Feast',
                'subtitle' => 'Rich pasture-raised goat curry cuts & keema',
                'code' => 'MUTTON60',
                'bg_gradient' => 'from-amber-600 to-orange-600',
                'image' => 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 3,
                'is_active' => true,
            ]
        ];

        foreach ($slides as $slideData) {
            \App\Models\Slide::updateOrCreate(
                ['code' => $slideData['code']],
                $slideData
            );
        }

        // 7. Seed Riders
        \App\Models\Rider::updateOrCreate(
            ['phone' => '9820198201'],
            [
                'name' => 'Ramesh Shinde',
                'phone' => '9820198201',
                'email' => 'ramesh@royalfish.com',
                'password' => Hash::make('password'),
                'vehicle_type' => 'Motorbike',
                'vehicle_number' => 'MH-01-AX-9911',
                'operating_pincodes' => json_encode(['400001', '400002']),
                'status' => 'Available',
                'earnings_per_delivery' => 50,
                'is_active' => true
            ]
        );

        \App\Models\Rider::updateOrCreate(
            ['phone' => '9820298202'],
            [
                'name' => 'Suresh Patil',
                'phone' => '9820298202',
                'email' => 'suresh@royalfish.com',
                'password' => Hash::make('password'),
                'vehicle_type' => 'EV Delivery Van',
                'vehicle_number' => 'MH-02-EV-4422',
                'operating_pincodes' => json_encode(['400003', '400004']),
                'status' => 'Available',
                'earnings_per_delivery' => 50,
                'is_active' => true
            ]
        );
    }
}
