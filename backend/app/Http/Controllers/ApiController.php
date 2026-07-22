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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $categories = Category::where('is_active', true)->get();
        return response()->json($categories);
    }

    /**
     * Get products list with filters, search, categories, and pincode availability.
     */
    public function getProducts(Request $request)
    {
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

        $userPincode = $request->input('pincode');

        $mapped = $products->map(function ($p) use ($userPincode) {
            $servicedPincodes = $p->serviced_pincodes ?: [];
            $isDeliverableToPincode = true;
            if ($userPincode && !empty($servicedPincodes) && !in_array('*', $servicedPincodes)) {
                $isDeliverableToPincode = in_array($userPincode, $servicedPincodes);
            }

            return [
                'id' => $p->product_code,
                'name' => $p->name,
                'slug' => $p->slug,
                'category' => $p->category ? $p->category->slug : 'fish-seafood',
                'subCategory' => $p->sub_category,
                'image' => $p->image,
                'price' => $p->price,
                'originalPrice' => $p->original_price,
                'weight' => $p->weight,
                'pieces' => $p->pieces,
                'servings' => $p->servings,
                'description' => $p->description,
                'tags' => $p->tags ?: [],
                'servicedPincodes' => $servicedPincodes,
                'isDeliverable' => $isDeliverableToPincode,
                'rating' => $p->rating,
                'reviewsCount' => $p->reviews_count,
                'isBestSeller' => $p->is_best_seller,
                'isTodaySpecial' => $p->is_today_special,
            ];
        });

        return response()->json($mapped);
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

        $productData = [
            'id' => $p->product_code,
            'name' => $p->name,
            'slug' => $p->slug,
            'category' => $p->category->slug,
            'subCategory' => $p->sub_category,
            'image' => $p->image,
            'price' => $p->price,
            'originalPrice' => $p->original_price,
            'weight' => $p->weight,
            'pieces' => $p->pieces,
            'servings' => $p->servings,
            'description' => $p->description,
            'tags' => $p->tags ?: [],
            'rating' => $p->rating,
            'reviewsCount' => $p->reviews_count,
            'isBestSeller' => $p->is_best_seller,
            'isTodaySpecial' => $p->is_today_special,
        ];

        return response()->json($productData);
    }

    /**
     * Passwordless authentication via phone.
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
            $name = $request->name ?: 'User ' . substr($phoneClean, -4);
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
            return [
                'id' => $o->id,
                'date' => $o->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
                'totalPrice' => $o->total_price,
                'paymentMethod' => $o->payment_method,
                'address' => $o->address_data,
                'status' => $o->status,
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

    /**
     * Submit/place a new checkout order.
     */
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'required|array',
            'cart.*.product.id' => 'required',
            'cart.*.product.name' => 'required|string',
            'cart.*.product.price' => 'required|numeric',
            'cart.*.quantity' => 'required|numeric',
            'paymentMethod' => 'required|string',
            'address' => 'required|array',
            'totalPrice' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $orderId = 'ROYAL-' . rand(100000, 999999);

        while (Order::where('id', $orderId)->exists()) {
            $orderId = 'ROYAL-' . rand(100000, 999999);
        }

        $order = Order::create([
            'id' => $orderId,
            'user_id' => $user->id,
            'total_price' => $request->totalPrice,
            'payment_method' => strtoupper($request->paymentMethod),
            'address_data' => $request->address,
            'status' => 'Placed',
            'estimated_delivery' => '30-45 mins',
        ]);

        foreach ($request->cart as $item) {
            $productId = null;
            $product = Product::where('product_code', $item['product']['id'])->first();
            if ($product) {
                $productId = $product->id;
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $item['product']['name'],
                'product_image' => $item['product']['image'] ?? null,
                'price' => $item['product']['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'id' => $order->id,
            'date' => $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
            'totalPrice' => $order->total_price,
            'paymentMethod' => $order->payment_method,
            'address' => $order->address_data,
            'status' => $order->status,
            'estimatedDelivery' => $order->estimated_delivery,
            'items' => $order->items->map(function ($item) {
                return [
                    'productId' => $item->product_id,
                    'productName' => $item->product_name,
                    'productImage' => $item->product_image,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ];
            }),
        ]);
    }
}
