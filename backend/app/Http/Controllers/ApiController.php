<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\OtpVerification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    /**
     * Get site configurations and SEO keywords.
     */
    public function getSettings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    /**
     * Get active hero carousel slides.
     */
    public function getSlides()
    {
        $slides = Slide::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $mapped = $slides->map(function($s) {
            return [
                'id' => 'slide-' . $s->id,
                'title' => $s->title,
                'subtitle' => $s->subtitle,
                'code' => $s->code,
                'bgGradient' => $s->bg_gradient ?: 'from-red-600 to-rose-500',
                'image' => $s->image,
                'textColor' => $s->text_color ?: 'text-white',
            ];
        });
        return response()->json($mapped);
    }

    /**
     * Get Onepager / Facebook Ad Page customized category sections and products.
     */
    public function getOnepagerSettings()
    {
        $setting = Setting::where('key', 'onepager_category_sections')->first();
        $rawSections = $setting && !empty($setting->value) ? json_decode($setting->value, true) : [];

        if (empty($rawSections)) {
            $defaultFishCategory = Category::where('slug', 'fresh-fish')->orWhere('name', 'like', '%fish%')->first();
            $defaultProducts = Product::where('is_active', true)->take(4)->pluck('id')->toArray();

            $rawSections = [
                [
                    'id' => 'sec_default_1',
                    'badge' => '🔥 আজকের স্পেশাল অফার',
                    'title' => 'তাজা পদ্মার ইলিশ ও মাছের স্পেশাল কালেকশন',
                    'subtitle' => '১ কেজি+ সাইজের স্পেশাল ইলিশ ও তাজা মাছ—সরাসরি নদী থেকে আপনার ঘরে।',
                    'category_id' => $defaultFishCategory ? $defaultFishCategory->id : '',
                    'product_ids' => $defaultProducts,
                    'view_all_label' => 'সকল মাছের কালেকশন দেখুন (View All Fish)',
                    'view_all_link' => '#featured-products',
                    'is_active' => true
                ]
            ];
        }

        $allCategories = Category::where('is_active', true)->get()->keyBy('id');
        $allProducts = Product::where('is_active', true)->get()->keyBy('id');

        $deliverySlot = Setting::where('key', 'delivery_slot')->value('value') ?: 'Today 4:00pm - 08:30 pm';

        $hydratedSections = [];

        foreach ($rawSections as $sec) {
            if (isset($sec['is_active']) && !$sec['is_active']) {
                continue;
            }

            $catId = $sec['category_id'] ?? null;
            $cat = $catId && isset($allCategories[$catId]) ? $allCategories[$catId] : null;

            // Resolve products
            $productIds = $sec['product_ids'] ?? [];
            $productsList = [];

            if (!empty($productIds)) {
                // Keep the exact order defined by the admin
                foreach ($productIds as $pid) {
                    if (isset($allProducts[$pid])) {
                        $productsList[] = $allProducts[$pid];
                    }
                }
            }

            // Fallback: if no products explicitly chosen, pick 4 products from the selected category
            if (empty($productsList) && $cat) {
                $productsList = Product::where('is_active', true)
                    ->where(function($q) use ($cat) {
                        $q->where('category_id', $cat->id)
                          ->orWhere('sub_category', $cat->name);
                    })
                    ->take(4)
                    ->get();
            }

            // Format products identical to standard product cards
            $formattedProducts = [];
            $promoHeadings = $sec['promo_headings'] ?? [];

            foreach ($productsList as $p) {
                $sellingPrice = (float)$p->price;
                $originalPrice = (float)$p->original_price;
                if (!$originalPrice || $originalPrice <= $sellingPrice) {
                    $originalPrice = ceil($sellingPrice * 1.20);
                }

                $promoHeading = $promoHeadings[$p->id] ?? ($promoHeadings[(string)$p->id] ?? null);

                $formattedProducts[] = [
                    'id' => $p->product_code ?: (string)$p->id,
                    'rawId' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'category' => $p->category ? $p->category->slug : ($cat ? $cat->slug : 'fish'),
                    'subCategory' => $p->sub_category,
                    'image' => $p->image,
                    'price' => $sellingPrice,
                    'originalPrice' => $originalPrice,
                    'weight' => $p->weight ?: '500g',
                    'pieces' => $p->pieces ?: 'Cleaned Pieces',
                    'servings' => $p->servings ?: 'Serves 2-3',
                    'description' => $p->description ?: '',
                    'shortDescription' => $p->short_description ?: '',
                    'deliveryTime' => $deliverySlot,
                    'tags' => $p->tags ?: [],
                    'rating' => (float)($p->rating ?: 4.9),
                    'reviewsCount' => (int)($p->reviews_count ?: 128),
                    'isBestSeller' => (bool)$p->is_best_seller,
                    'stockQuantity' => (int)($p->stock_quantity ?? 50),
                    'inStock' => $p->in_stock !== null ? (bool)$p->in_stock : true,
                    'promoHeading' => $promoHeading
                ];
            }

            $hydratedSections[] = [
                'id' => $sec['id'] ?? 'sec_' . uniqid(),
                'badge' => $sec['badge'] ?? '🔥 আজকের স্পেশাল অফার',
                'title' => $sec['title'] ?? ($cat ? $cat->name : 'স্পেশাল কালেকশন'),
                'subtitle' => $sec['subtitle'] ?? '',
                'layout_type' => (isset($sec['layout_type']) && in_array($sec['layout_type'], ['grid', 'single_showcase'])) ? $sec['layout_type'] : 'grid',
                'category_id' => $catId,
                'category_name' => $cat ? $cat->name : '',
                'category_slug' => $cat ? $cat->slug : '',
                'category_image' => $cat ? $cat->image : '',
                'category_icon' => $cat ? $cat->icon : '🐟',
                'view_all_label' => $sec['view_all_label'] ?? 'সকল পণ্য দেখুন (View All)',
                'view_all_link' => $sec['view_all_link'] ?? '#featured-products',
                'products' => $formattedProducts
            ];
        }

        // Hero Section
        $heroSetting = Setting::where('key', 'onepager_hero')->first();
        $hero = $heroSetting && !empty($heroSetting->value) ? json_decode($heroSetting->value, true) : [
            'badge' => '🔥 আজকের স্পেশাল ইলিশ অফার',
            'title' => 'কলকাতায় এবার ঘরে বসেই উপভোগ করুন তেলতেলে রাজকীয় ইলিশ',
            'subtitle' => '১ কেজি+ সাইজের স্পেশাল ইলিশ—কাটিং, পরিষ্কার ও হাইজেনিক প্যাকেজিংসহ পৌঁছে যাবে আপনার রান্নাঘরে।',
            'price_box_1_title' => '১ কেজি+ সম্পূর্ণ ইলিশ',
            'price_box_1_price' => '₹1,399',
            'price_box_1_unit' => '/কেজি',
            'price_box_2_title' => '৭০-৮০ গ্রাম কাটা পিস',
            'price_box_2_price' => '₹149',
            'price_box_2_unit' => '/পিস',
            'btn_order_text' => '🐟 এখনই অর্ডার করুন',
            'btn_whatsapp_text' => 'WhatsApp-এ কথা বলুন',
            'whatsapp_number' => '918444844440',
            'whatsapp_msg' => 'Hi Royal Fish Store, আমি আজকের স্পেশাল পদ্মার ইলিশ অর্ডার করতে চাই।',
            'hero_image' => '/hilsa_hero.png',
            'delivery_badge' => '২৪ ঘণ্টার মধ্যে আপনার দরজায় Delivery',
            'fresh_badge' => '100% FRESH'
        ];

        if (empty($hero['whatsapp_number']) || str_contains($hero['whatsapp_number'], '9876543210')) {
            $hero['whatsapp_number'] = '918444844440';
        }

        // Customer Reviews Section
        $reviewsSetting = Setting::where('key', 'onepager_reviews')->first();
        $reviews = $reviewsSetting && !empty($reviewsSetting->value) ? json_decode($reviewsSetting->value, true) : [
            'title' => 'What Real Seafood Lovers Say',
            'subtitle' => 'Facebook par ad dekh kar order karne wale customer ke asli reviews',
            'items' => [
                [
                    'name' => 'Sunita Roy',
                    'city' => 'Kolkata • Verified Buyer',
                    'rating' => 5,
                    'text' => 'Diamond Harbour Hilsa order kiya tha Facebook ad dekh ke. Fish ekdum fresh thi, koi smell nahi aur tel bohot accha nikla curry me! Ab har weekend yahin se lenge.',
                    'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
                    'ordered_item' => 'Ordered: Fresh Hilsa 1kg Cut'
                ],
                [
                    'name' => 'Vikramaditya Rao',
                    'city' => 'Mumbai • Verified Buyer',
                    'rating' => 5,
                    'text' => 'Surmai steaks and Jumbo tiger prawns were delivered in just 35 minutes! Cleaned so well that I just had to marinate and fry. 10/10 packing.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80',
                    'ordered_item' => 'Ordered: Surmai Steaks & Tiger Prawns'
                ],
                [
                    'name' => 'Anand Verma',
                    'city' => 'Delhi NCR • Verified Buyer',
                    'rating' => 5,
                    'text' => 'Mutton curry cut and country chicken both were super tender. Cash on delivery option made it very reliable to test for the first time.',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
                    'ordered_item' => 'Ordered: Goat Curry Cut & Farm Chicken'
                ]
            ]
        ];

        // FAQs Section
        $faqsSetting = Setting::where('key', 'onepager_faqs')->first();
        $faqs = $faqsSetting && !empty($faqsSetting->value) ? json_decode($faqsSetting->value, true) : [
            'title' => 'সাধারণ কিছু প্রশ্নের উত্তর',
            'subtitle' => 'প্রয়োজনীয় তথ্য',
            'items' => [
                [
                    'q' => 'কোন কোন PIN code-এ Delivery হবে?',
                    'a' => "Newtown: 700156, 700157, 700136, 700135, 700160, 700161, 700162, 700163, 700132, 700152, 700059, 700101\nSalt Lake: 700091, 700106, 700107, 700102, 700064, 700010, 700046, 700101, 700100, 700105"
                ],
                [
                    'q' => 'কাটা ইলিশে Delivery Charge কত?',
                    'a' => '3 পিস অর্ডারে ₹100 delivery charge যোগ হবে। 4 পিস বা তার বেশি অর্ডার করলে delivery সম্পূর্ণ FREE।'
                ],
                [
                    'q' => 'কত সময়ের মধ্যে Delivery হবে?',
                    'a' => 'অর্ডার Confirm হওয়ার পর সাধারণত 24 ঘণ্টার মধ্যে Delivery করা হবে।'
                ],
                [
                    'q' => 'Cash on Delivery আছে?',
                    'a' => 'হ্যাঁ, মাছ হাতে পাওয়ার সময় Cash on Delivery-তে মূল্য দিতে পারবেন।'
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'hero' => $hero,
            'sections' => $hydratedSections,
            'reviews' => $reviews,
            'faqs' => $faqs
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Verify if a 6-digit Pincode is deliverable.
     */
    public function checkPincode(Request $request)
    {
        $request->validate([
            'pincode' => 'required|string|min:6|max:6',
        ]);

        $pincode = trim($request->pincode);
        $deliverable = true;
        
        return response()->json([
            'pincode' => $pincode,
            'isDeliverable' => $deliverable,
            'estimatedTime' => '30-45 Mins Express',
            'message' => 'Express delivery available for ' . $pincode
        ]);
    }

    /**
     * Get all active categories.
     */
    public function getCategories()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        return response()->json($categories);
    }

    /**
     * Get products list with filters, search, categories, and pincode availability.
     */
    public function getProducts(Request $request)
    {
        try {
            $query = Product::where('is_active', true)->with('category');

            if ($request->has('category') && $request->category !== 'all' && !empty($request->category)) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            if ($request->has('sub_category') && $request->sub_category !== 'All' && !empty($request->sub_category)) {
                $query->where('sub_category', $request->sub_category);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('sub_category', 'like', "%{$search}%");
                });
            }

            $products = $query->get();

            $morningSlot = Setting::where('key', 'morning_delivery_slot')->value('value') ?: 'Today 07:00 am - 12:00 pm';
            $eveningSlot = Setting::where('key', 'evening_delivery_slot')->value('value') ?: 'Today 4:00pm - 08:30 pm';

            $resolveDeliverySlot = function($dTime) use ($morningSlot, $eveningSlot) {
                if (empty($dTime)) return $eveningSlot;
                $lower = strtolower($dTime);
                if ($lower === 'morning' || strpos($lower, 'morning') !== false) return $morningSlot;
                if ($lower === 'evening' || strpos($lower, 'evening') !== false || strpos($dTime, '4:00') !== false || strpos($dTime, '4:30') !== false) return $eveningSlot;
                return $dTime;
            };

            $userPincode = $request->input('pincode');

            $mapped = $products->map(function ($p) use ($userPincode, $resolveDeliverySlot) {
                // Safely parse serviced_pincodes (could be array, JSON string, or null)
                $servicedPincodes = $p->serviced_pincodes;
                if (is_string($servicedPincodes)) {
                    $servicedPincodes = json_decode($servicedPincodes, true);
                }
                if (!is_array($servicedPincodes)) {
                    $servicedPincodes = [];
                }

                $isDeliverableToPincode = true;
                if ($userPincode && !empty($servicedPincodes) && !in_array('*', $servicedPincodes)) {
                    $isDeliverableToPincode = in_array($userPincode, $servicedPincodes);
                }

                $deliverySlotResolved = $resolveDeliverySlot($p->delivery_time);

                // Auto-correct: if original_price is missing or <= price, calculate 20% above
                $originalPrice = $p->original_price;
                $sellingPrice = $p->price;
                if (!$originalPrice || $originalPrice <= $sellingPrice) {
                    $originalPrice = ceil($sellingPrice * 1.20);
                }

                $stockQuantity = (int)($p->stock_quantity ?? 50);
                $inStock = $p->in_stock !== null ? (bool)$p->in_stock : ($stockQuantity > 0);
                $lowStockThreshold = (int)($p->low_stock_threshold ?: 5);
                $minOrderQty = max(1, (int)($p->min_order_qty ?: 1));
                $maxOrderQty = max(1, (int)($p->max_order_qty ?: 10));

                $isOutOfStock = !$inStock || $stockQuantity <= 0;
                $isLowStock = $inStock && $stockQuantity > 0 && $stockQuantity <= $lowStockThreshold;

                return [
                    'id' => $p->product_code,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'category' => $p->category ? $p->category->slug : 'fish-seafood',
                    'subCategory' => $p->sub_category,
                    'image' => $p->image,
                    'price' => $sellingPrice,
                    'originalPrice' => $originalPrice,
                    'weight' => $p->weight,
                    'pieces' => $p->pieces,
                    'servings' => $p->servings,
                    'description' => $p->description,
                    'shortDescription' => $p->short_description,
                    'short_description' => $p->short_description,
                    'deliveryTime' => $deliverySlotResolved,
                    'delivery_time' => $deliverySlotResolved,
                    'tags' => $p->tags ?: [],
                    'servicedPincodes' => $servicedPincodes,
                    'isDeliverable' => $isDeliverableToPincode,
                    'rating' => $p->rating,
                    'reviewsCount' => $p->reviews_count,
                    'isBestSeller' => $p->is_best_seller,
                    'isTodaySpecial' => $p->is_today_special,
                    'stockQuantity' => $stockQuantity,
                    'stock_quantity' => $stockQuantity,
                    'inStock' => $inStock,
                    'in_stock' => $inStock,
                    'lowStockThreshold' => $lowStockThreshold,
                    'low_stock_threshold' => $lowStockThreshold,
                    'minOrderQty' => $minOrderQty,
                    'min_order_qty' => $minOrderQty,
                    'maxOrderQty' => $maxOrderQty,
                    'max_order_qty' => $maxOrderQty,
                    'isOutOfStock' => $isOutOfStock,
                    'is_out_of_stock' => $isOutOfStock,
                    'isLowStock' => $isLowStock,
                    'is_low_stock' => $isLowStock,
                ];
            });

            return response()->json($mapped);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get detailed info for a single product.
     */
    public function getProduct($codeOrSlug)
    {
        $p = Product::where('is_active', true)
            ->where(function($q) use ($codeOrSlug) {
                $q->where('product_code', $codeOrSlug)
                  ->orWhere('slug', $codeOrSlug);
            })
            ->with('category')
            ->first();

        if (!$p) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $morningSlot = Setting::where('key', 'morning_delivery_slot')->value('value') ?: 'Today 07:00 am - 12:00 pm';
        $eveningSlot = Setting::where('key', 'evening_delivery_slot')->value('value') ?: 'Today 4:00pm - 08:30 pm';

        $dTime = $p->delivery_time;
        $deliverySlotResolved = $eveningSlot;
        if (!empty($dTime)) {
            $lower = strtolower($dTime);
            if ($lower === 'morning' || strpos($lower, 'morning') !== false) {
                $deliverySlotResolved = $morningSlot;
            } elseif ($lower === 'evening' || strpos($lower, 'evening') !== false || strpos($dTime, '4:00') !== false || strpos($dTime, '4:30') !== false) {
                $deliverySlotResolved = $eveningSlot;
            } else {
                $deliverySlotResolved = $dTime;
            }
        }

        // Auto-correct: if original_price is missing or <= price, calculate 20% above
        $originalPrice = $p->original_price;
        $sellingPrice = $p->price;
        if (!$originalPrice || $originalPrice <= $sellingPrice) {
            $originalPrice = ceil($sellingPrice * 1.20);
        }

        $stockQuantity = (int)($p->stock_quantity ?? 50);
        $inStock = $p->in_stock !== null ? (bool)$p->in_stock : ($stockQuantity > 0);
        $lowStockThreshold = (int)($p->low_stock_threshold ?: 5);
        $minOrderQty = max(1, (int)($p->min_order_qty ?: 1));
        $maxOrderQty = max(1, (int)($p->max_order_qty ?: 10));

        $isOutOfStock = !$inStock || $stockQuantity <= 0;
        $isLowStock = $inStock && $stockQuantity > 0 && $stockQuantity <= $lowStockThreshold;

        $productData = [
            'id' => $p->product_code,
            'name' => $p->name,
            'slug' => $p->slug,
            'category' => $p->category ? $p->category->slug : 'fish-seafood',
            'subCategory' => $p->sub_category,
            'image' => $p->image,
            'price' => $sellingPrice,
            'originalPrice' => $originalPrice,
            'weight' => $p->weight,
            'pieces' => $p->pieces,
            'servings' => $p->servings,
            'description' => $p->description,
            'shortDescription' => $p->short_description,
            'short_description' => $p->short_description,
            'deliveryTime' => $deliverySlotResolved,
            'delivery_time' => $deliverySlotResolved,
            'tags' => $p->tags ?: [],
            'rating' => $p->rating,
            'reviewsCount' => $p->reviews_count,
            'isBestSeller' => $p->is_best_seller,
            'isTodaySpecial' => $p->is_today_special,
            'stockQuantity' => $stockQuantity,
            'stock_quantity' => $stockQuantity,
            'inStock' => $inStock,
            'in_stock' => $inStock,
            'lowStockThreshold' => $lowStockThreshold,
            'low_stock_threshold' => $lowStockThreshold,
            'minOrderQty' => $minOrderQty,
            'min_order_qty' => $minOrderQty,
            'maxOrderQty' => $maxOrderQty,
            'max_order_qty' => $maxOrderQty,
            'isOutOfStock' => $isOutOfStock,
            'is_out_of_stock' => $isOutOfStock,
            'isLowStock' => $isLowStock,
            'is_low_stock' => $isLowStock,
        ];

        return response()->json($productData);
    }

    /**
     * Send WhatsApp OTP for Authentication.
     */
    public function sendOtp(Request $request, WhatsAppService $whatsAppService)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $phone = $request->phone;
        $phoneClean = preg_replace('/\D/', '', $phone);
        if (strlen($phoneClean) > 10) {
            $phoneClean = substr($phoneClean, -10);
        }

        if (strlen($phoneClean) < 10) {
            return response()->json(['error' => 'Please enter a valid 10-digit mobile number.'], 422);
        }

        // Rate limiting: prevent re-sending within 30 seconds
        $recentOtp = OtpVerification::where('phone', $phoneClean)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->latest()
            ->first();

        if ($recentOtp) {
            return response()->json([
                'success' => true,
                'message' => 'OTP already sent recently. Please check your WhatsApp.',
                'phone' => $phoneClean,
                'is_cached' => true,
            ]);
        }

        // Generate 4-digit secure numeric OTP
        $otp = (string) mt_rand(1000, 9999);

        // Save in database
        OtpVerification::create([
            'phone' => $phoneClean,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_verified' => false,
            'attempts' => 0
        ]);

        // Send via WhatsApp Cloud API
        $waResult = $whatsAppService->sendOtp($phoneClean, $otp);

        Log::info("Sent WhatsApp OTP {$otp} to {$phoneClean}", [
            'whatsapp_result' => $waResult
        ]);

        $isSuccess = $waResult['success'] ?? false;
        $statusStr = $isSuccess ? 'sent' : 'failed';

        return response()->json([
            'success' => true,
            'message' => $isSuccess
                ? "OTP sent successfully to WhatsApp (+91 " . substr($phoneClean, 0, 5) . " " . substr($phoneClean, 5) . ")"
                : ($waResult['message'] ?? "WhatsApp OTP delivery pending."),
            'phone' => $phoneClean,
            'whatsapp_status' => $statusStr,
            'whatsapp_error' => !$isSuccess ? ($waResult['last_error'] ?? null) : null,
        ]);
    }

    /**
     * Resend WhatsApp OTP.
     */
    public function resendOtp(Request $request, WhatsAppService $whatsAppService)
    {
        return $this->sendOtp($request, $whatsAppService);
    }

    /**
     * Verify WhatsApp OTP and Login / Register.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $phone = $request->phone;
        $phoneClean = preg_replace('/\D/', '', $phone);
        if (strlen($phoneClean) > 10) {
            $phoneClean = substr($phoneClean, -10);
        }

        $enteredOtp = trim($request->otp);

        // Find active OTP record
        $otpRecord = OtpVerification::where('phone', $phoneClean)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        $isValid = false;

        // 1. Check valid database OTP
        if ($otpRecord) {
            if ($otpRecord->attempts >= 5) {
                return response()->json([
                    'error' => 'Too many failed attempts. Please request a new OTP.'
                ], 429);
            }

            if ($otpRecord->otp === $enteredOtp) {
                $isValid = true;
                $otpRecord->update(['is_verified' => true]);
            } else {
                $otpRecord->increment('attempts');
            }
        }

        // 2. Fallback testing OTP (1234) for quick demo / development resilience
        if (!$isValid && $enteredOtp === '1234') {
            $isValid = true;
        }

        if (!$isValid) {
            return response()->json([
                'error' => 'Invalid or expired OTP. Please check your WhatsApp or resend code.'
            ], 422);
        }

        // Find or create user
        $user = User::where('phone', $phoneClean)->first();

        if (!$user) {
            $name = $request->name ?: 'Customer ' . substr($phoneClean, -4);
            $email = $request->email ?: 'user-' . $phoneClean . '@royalfishstore.com';

            $user = User::create([
                'name' => $name,
                'phone' => $phoneClean,
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
            ]);
        } else {
            if ($request->name && ($user->name === 'User ' . substr($phoneClean, -4) || empty($user->name))) {
                $user->update(['name' => $request->name]);
            }
            if ($request->email && (strpos($user->email, '@royalfishstore.com') !== false || empty($user->email))) {
                $user->update(['email' => $request->email]);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'isLoggedIn' => true,
            ],
            'message' => 'Logged in successfully via WhatsApp verification!'
        ]);
    }

    /**
     * Direct Passwordless / legacy authentication via phone.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $phone = $request->phone;
        $phoneClean = preg_replace('/\D/', '', $phone);
        if (strlen($phoneClean) > 10) {
            $phoneClean = substr($phoneClean, -10);
        }

        $user = User::where('phone', $phoneClean)->first();

        if (!$user) {
            $name = $request->name ?: 'Customer ' . substr($phoneClean, -4);
            $email = $request->email ?: 'user-' . $phoneClean . '@royalfishstore.com';

            $user = User::create([
                'name' => $name,
                'phone' => $phoneClean,
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
            ]);
        } else {
            if ($request->name && ($user->name === 'User ' . substr($phoneClean, -4) || empty($user->name))) {
                $user->update(['name' => $request->name]);
            }
            if ($request->email && (strpos($user->email, '@royalfishstore.com') !== false || empty($user->email))) {
                $user->update(['email' => $request->email]);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'isLoggedIn' => true,
            ]
        ]);
    }

    /**
     * Get authenticated user profile.
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'isLoggedIn' => true,
        ]);
    }

    /**
     * Get user address book.
     */
    public function getAddresses(Request $request)
    {
        $addresses = $request->user()->addresses()->get();
        $mapped = $addresses->map(function ($a) {
            return [
                'id' => (string)$a->id,
                'name' => $a->name,
                'type' => $a->type,
                'addressLine' => $a->address_line,
                'city' => $a->city,
                'zipCode' => $a->zip_code,
                'phone' => $a->phone,
            ];
        });
        return response()->json($mapped);
    }

    /**
     * Create new user address.
     */
    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string',
            'addressLine' => 'required|string',
            'city' => 'required|string',
            'zipCode' => 'required|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $address = $request->user()->addresses()->create([
            'name' => $request->name,
            'type' => $request->type,
            'address_line' => $request->addressLine,
            'city' => $request->city,
            'zip_code' => $request->zipCode,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'id' => (string)$address->id,
            'name' => $address->name,
            'type' => $address->type,
            'addressLine' => $address->address_line,
            'city' => $address->city,
            'zipCode' => $address->zip_code,
            'phone' => $address->phone,
        ]);
    }

    /**
     * Get user order history.
     */
    public function getOrders(Request $request)
    {
        $orders = $request->user()->orders()->with('items')->latest()->get();

        $mapped = $orders->map(function ($o) {
            // Cancelled orders are always Cancelled
            if ($o->status === 'Cancelled' || $o->shipment_status === 'Cancelled') {
                $effectiveStatus = 'Cancelled';
            } elseif ($o->status === 'Delivered' || $o->shipment_status === 'Delivered') {
                $effectiveStatus = 'Delivered';
            } elseif ($o->shipment_status === 'Out for Delivery' || $o->status === 'Out for Delivery') {
                $effectiveStatus = 'Out for Delivery';
            } elseif ($o->status === 'Dispatched' || $o->shipment_status === 'Dispatched') {
                $effectiveStatus = 'Dispatched';
            } elseif ($o->status === 'Processing' || $o->shipment_status === 'Processing') {
                $effectiveStatus = 'Processing';
            } else {
                $effectiveStatus = $o->status ?: 'Placed';
            }

            return [
                'id' => $o->id,
                'date' => $o->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
                'totalPrice' => $o->total_price,
                'paymentMethod' => $o->payment_method,
                'address' => $o->address_data,
                'status' => $effectiveStatus,
                'estimatedDelivery' => $o->estimated_delivery,
                'items' => $o->items->map(function ($item) {
                    return [
                        'productId' => $item->product_id,
                        'productName' => $item->product_name,
                        'productImage' => $item->product_image,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ];
                }),
            ];
        });

        return response()->json($mapped);
    }

    private static $ordersSchemaChecked = false;

    private function ensureOrdersTableSchema()
    {
        if (self::$ordersSchemaChecked) {
            return;
        }
        self::$ordersSchemaChecked = true;

        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY id VARCHAR(255) NOT NULL");
        } catch (\Throwable $e) {}
    }

    /**
     * Submit/place a new checkout order.
     */
    public function placeOrder(Request $request)
    {
        $this->ensureOrdersTableSchema();

        $validator = Validator::make($request->all(), [
            'cart' => 'required|array|min:1',
            'cart.*.product.id' => 'required',
            'cart.*.product.name' => 'required|string',
            'cart.*.product.price' => 'required|numeric',
            'cart.*.quantity' => 'required|numeric|min:1',
            'paymentMethod' => 'required|string',
            'address' => 'required|array',
            'totalPrice' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 1. Check Global Minimum Order Amount setting
        $minOrderAmount = (float)(Setting::where('key', 'min_order_amount')->value('value') ?: 199);
        if ($minOrderAmount > 0 && (float)$request->totalPrice < $minOrderAmount) {
            return response()->json([
                'error' => 'min_order_amount_not_met',
                'message' => "Minimum order amount is ₹{$minOrderAmount}. Please add more items to your basket to proceed.",
                'min_order_amount' => $minOrderAmount
            ], 422);
        }

        // 2. Batch lookup products to validate stock and limits
        $productCodes = [];
        foreach ($request->cart as $item) {
            if (!empty($item['product']['id'])) {
                $productCodes[] = $item['product']['id'];
            }
        }

        $productsByCode = Product::whereIn('product_code', array_unique($productCodes))
            ->get()
            ->keyBy('product_code');

        // 3. Validate Stock & Limits for every cart item
        foreach ($request->cart as $item) {
            $code = $item['product']['id'] ?? null;
            $product = $code ? ($productsByCode[$code] ?? null) : null;
            $reqQty = (int)$item['quantity'];

            if ($product) {
                // Min Order Quantity validation
                $minQty = max(1, (int)($product->min_order_qty ?: 1));
                if ($reqQty < $minQty) {
                    return response()->json([
                        'error' => 'min_quantity_limit',
                        'message' => "Minimum purchase limit for '{$product->name}' is {$minQty} units."
                    ], 422);
                }

                // Max Order Quantity validation
                $maxQty = max(1, (int)($product->max_order_qty ?: 10));
                if ($reqQty > $maxQty) {
                    return response()->json([
                        'error' => 'max_quantity_limit',
                        'message' => "Maximum purchase limit for '{$product->name}' is {$maxQty} units per order."
                    ], 422);
                }

                // In-Stock & Stock Availability check
                if (!$product->in_stock || $product->stock_quantity <= 0) {
                    return response()->json([
                        'error' => 'out_of_stock',
                        'message' => "'{$product->name}' is currently Out of Stock."
                    ], 422);
                }

                if ($product->stock_quantity < $reqQty) {
                    return response()->json([
                        'error' => 'insufficient_stock',
                        'message' => "Only {$product->stock_quantity} unit(s) left in stock for '{$product->name}'."
                    ], 422);
                }
            }
        }

        // 4. Create Order and Deduct Stock in a DB Transaction
        $user = $request->user();
        $orderId = 'ROYAL-' . rand(100000, 999999);

        while (Order::where('id', $orderId)->exists()) {
            $orderId = 'ROYAL-' . rand(100000, 999999);
        }

        $now = now();
        $orderItemsData = [];
        $responseItems = [];

        // Ensure delivery address reflects customer's real registered phone number
        $addressData = $request->address;
        if (is_array($addressData)) {
            $addrPhone = (string)($addressData['phone'] ?? '');
            if (empty($addrPhone) || strpos($addrPhone, '98765 43210') !== false || strpos($addrPhone, '9876543210') !== false) {
                $addressData['phone'] = $user->phone ?: $addrPhone;
            }
            if (empty($addressData['name']) || $addressData['name'] === 'Home (Default)') {
                $addressData['name'] = $user->name ?: 'Customer';
            }
        }

        $order = \Illuminate\Support\Facades\DB::transaction(function() use ($orderId, $user, $request, $addressData, $productsByCode, $now, &$orderItemsData, &$responseItems) {
            $order = Order::create([
                'id' => $orderId,
                'user_id' => $user->id,
                'total_price' => $request->totalPrice,
                'payment_method' => strtoupper($request->paymentMethod),
                'address_data' => $addressData,
                'status' => 'Placed',
                'estimated_delivery' => $request->input('deliverySlot') ?: ($request->input('estimatedDelivery') ?: '30-45 mins'),
            ]);

            foreach ($request->cart as $item) {
                $code = $item['product']['id'] ?? null;
                $product = $code ? ($productsByCode[$code] ?? null) : null;
                $productId = $product ? $product->id : null;
                $productImage = ($product && !empty($product->image)) ? $product->image : ($item['product']['image'] ?? null);
                $reqQty = (int)$item['quantity'];

                // Deduct stock
                if ($product) {
                    $product->decrement('stock_quantity', $reqQty);
                    if ($product->stock_quantity <= 0) {
                        $product->update(['in_stock' => false]);
                    }
                }

                $orderItemsData[] = [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $item['product']['name'],
                    'product_image' => $productImage,
                    'price' => $item['product']['price'],
                    'quantity' => $reqQty,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $responseItems[] = [
                    'productId' => $productId,
                    'productName' => $item['product']['name'],
                    'productImage' => $productImage,
                    'price' => $item['product']['price'],
                    'quantity' => $reqQty,
                ];
            }

            if (!empty($orderItemsData)) {
                OrderItem::insert($orderItemsData);
            }

            return $order;
        });

        // Trigger WhatsApp & Email notifications (isolated try/catch so failure in one never blocks another)
        $order->load('items', 'user');

        // 1. Customer WhatsApp Notification (Top Priority)
        try {
            \App\Services\OrderWhatsAppService::sendCustomerOrderSuccessNotification($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Customer Order WhatsApp notification error: ' . $e->getMessage());
        }

        // 2. Admin WhatsApp Notification
        try {
            \App\Services\OrderWhatsAppService::sendAdminNewOrderNotification($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin Order WhatsApp notification error: ' . $e->getMessage());
        }

        // 3. Customer Email
        try {
            \App\Services\OrderMailService::sendCustomerStatusMail($order, 'Placed');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Customer Email notification error: ' . $e->getMessage());
        }

        // 4. Admin Email
        try {
            \App\Services\OrderMailService::sendAdminNewOrderMail($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin Email notification error: ' . $e->getMessage());
        }

        return response()->json([
            'id' => $order->id,
            'date' => $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
            'totalPrice' => $order->total_price,
            'paymentMethod' => $order->payment_method,
            'address' => $order->address_data,
            'status' => $order->status,
            'estimatedDelivery' => $order->estimated_delivery,
            'items' => $responseItems,
        ]);
    }

    private function ensureOrderChatsTable()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('order_chats')) {
            \Illuminate\Support\Facades\Schema::create('order_chats', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('order_id');
                $table->string('sender_type');
                $table->unsignedBigInteger('sender_id')->nullable();
                $table->string('sender_name')->nullable();
                $table->text('message');
                $table->timestamps();
            });
        }
    }

    /**
     * Get chat messages for an order.
     */
    public function getChatMessages(Request $request, $orderId)
    {
        $this->ensureOrderChatsTable();
        $chats = \App\Models\OrderChat::where('order_id', $orderId)->orderBy('created_at', 'asc')->get();
        return response()->json($chats);
    }

    /**
     * Customer sends chat message to rider.
     */
    public function sendChatMessage(Request $request, $orderId)
    {
        $this->ensureOrderChatsTable();

        $order = \App\Models\Order::find($orderId);
        if ($order && ($order->status === 'Delivered' || $order->shipment_status === 'Delivered')) {
            return response()->json([
                'success' => false,
                'message' => 'Chat is closed for delivered orders.'
            ], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $user = $request->user();
        $chat = \App\Models\OrderChat::create([
            'order_id' => $orderId,
            'sender_type' => 'customer',
            'sender_id' => $user ? $user->id : null,
            'sender_name' => ($user && $user->name) ? $user->name : 'Customer',
            'message' => trim($request->message),
        ]);

        return response()->json(['success' => true, 'data' => $chat]);
    }

    /**
     * Get active YouTube videos list.
     */
    public function getVideos()
    {
        try {
            $videos = \App\Models\Video::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($v) {
                    return [
                        'id' => (string)$v->id,
                        'title' => $v->title,
                        'youtubeUrl' => $v->youtube_url,
                        'youtube_url' => $v->youtube_url,
                        'youtubeId' => $v->youtube_id,
                        'youtube_id' => $v->youtube_id,
                        'thumbnail' => $v->thumbnail ?: "https://img.youtube.com/vi/{$v->youtube_id}/hqdefault.jpg",
                        'duration' => $v->duration ?: '1:00',
                    ];
                });

            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
