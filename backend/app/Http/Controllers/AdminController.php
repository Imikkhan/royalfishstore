<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Media;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show Login page.
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle Login post request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => true, 'message' => 'Logged in successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid email or password.'], 401);
    }

    /**
     * Handle Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    /**
     * Dashboard home stats.
     */
    public function dashboard()
    {
        $totalSales = Order::where('status', '!=', 'Cancelled')->sum('total_price');
        $totalOrders = Order::count();
        $totalCustomers = User::whereNull('role_id')->count();
        $lowStockProducts = Product::where('is_active', true)->take(5)->get(); // Mock stock details
        $recentOrders = Order::with('user')->latest()->take(6)->get();

        // Chart Data (Mocking daily totals for the past 7 days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');
            $sales = Order::whereDate('created_at', $date)->where('status', '!=', 'Cancelled')->sum('total_price');
            $chartData[] = [
                'day' => $dateFormatted,
                'sales' => $sales
            ];
        }

        return view('admin.dashboard', compact('totalSales', 'totalOrders', 'totalCustomers', 'recentOrders', 'chartData', 'lowStockProducts'));
    }

    /*
    |--------------------------------------------------------------------------
    | ROLES & PERMISSIONS CRUD
    |--------------------------------------------------------------------------
    */
    public function rolesIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('roles')) abort(403);

        if ($request->ajax()) {
            $roles = Role::all();
            return response()->json(['data' => $roles]);
        }
        return view('admin.roles');
    }

    public function rolesStore(Request $request)
    {
        if (!Auth::user()->hasPermission('roles')) abort(403);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'permissions' => json_encode($request->permissions),
        ]);

        return response()->json(['success' => true, 'message' => 'Role created successfully!', 'data' => $role]);
    }

    public function rolesUpdate(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('roles')) abort(403);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $role = Role::findOrFail($id);
        
        // Prevent editing Super Admin slug
        $slug = $role->slug === 'super-admin' ? 'super-admin' : Str::slug($request->name);

        $role->update([
            'name' => $request->name,
            'slug' => $slug,
            'permissions' => json_encode($request->permissions),
        ]);

        return response()->json(['success' => true, 'message' => 'Role updated successfully!', 'data' => $role]);
    }

    public function rolesDelete(Request $request)
    {
        if (!Auth::user()->hasPermission('roles')) abort(403);

        $ids = $request->ids;
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No items selected.'], 400);

        // Don't delete Super Admin role
        Role::whereIn('id', $ids)->where('slug', '!=', 'super-admin')->delete();

        return response()->json(['success' => true, 'message' => 'Selected role(s) deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | USERS & STAFF CRUD
    |--------------------------------------------------------------------------
    */
    public function usersIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('users')) abort(403);

        if ($request->ajax()) {
            $users = User::with('role')->get();
            return response()->json(['data' => $users]);
        }
        $roles = Role::all();
        return view('admin.users', compact('roles'));
    }

    public function usersStore(Request $request)
    {
        if (!Auth::user()->hasPermission('users')) abort(403);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => preg_replace('/\D/', '', $request->phone),
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json(['success' => true, 'message' => 'User created successfully!', 'data' => $user]);
    }

    public function usersUpdate(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('users')) abort(403);

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => preg_replace('/\D/', '', $request->phone),
            'role_id' => $request->role_id,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User updated successfully!', 'data' => $user]);
    }

    public function usersDelete(Request $request)
    {
        if (!Auth::user()->hasPermission('users')) abort(403);

        $ids = $request->ids;
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No users selected.'], 400);

        // Prevent self deletion
        $currentUserId = Auth::id();
        User::whereIn('id', $ids)->where('id', '!=', $currentUserId)->delete();

        return response()->json(['success' => true, 'message' => 'Selected user(s) deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES CRUD
    |--------------------------------------------------------------------------
    */
    public function categoriesIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('categories')) abort(403);

        if ($request->ajax()) {
            $categories = Category::with('parent')->get();
            return response()->json(['data' => $categories]);
        }
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories', compact('parentCategories'));
    }

    public function categoriesStore(Request $request)
    {
        if (!Auth::user()->hasPermission('categories')) abort(403);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $imagePath = asset('uploads/' . $fileName);
        }

        $category = Category::create([
            'parent_id' => $request->parent_id ?: null,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Category created successfully!', 'data' => $category]);
    }

    public function categoriesUpdate(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('categories')) abort(403);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category = Category::findOrFail($id);
        
        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $imagePath = asset('uploads/' . $fileName);
        }

        $category->update([
            'parent_id' => $request->parent_id ?: null,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Category updated successfully!', 'data' => $category]);
    }

    public function categoriesDelete(Request $request)
    {
        if (!Auth::user()->hasPermission('categories')) abort(403);

        $ids = $request->ids;
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No items selected.'], 400);

        Category::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected category(s) deleted successfully.']);
    }

    public function categoriesToggleStatus(Request $request)
    {
        if (!Auth::user()->hasPermission('categories')) abort(403);

        $category = Category::findOrFail($request->id);
        $category->is_active = !$category->is_active;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'is_active' => $category->is_active]);
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS CRUD
    |--------------------------------------------------------------------------
    */
    public function productsIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('products')) abort(403);

        if ($request->ajax()) {
            $products = Product::with('category')->get();
            return response()->json(['data' => $products]);
        }
        $categories = Category::whereNull('parent_id')->get();
        $subCategories = Category::whereNotNull('parent_id')->get();
        return view('admin.products', compact('categories', 'subCategories'));
    }

    public function productsStore(Request $request)
    {
        if (!Auth::user()->hasPermission('products')) abort(403);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|string',
            'pieces' => 'nullable|string',
            'servings' => 'nullable|string',
            'description' => 'nullable|string',
            'tags' => 'nullable|string', // Comma separated tags
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_best_seller' => 'nullable|boolean',
            'is_today_special' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagePath = 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80'; // Default fallback
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Ensure uploads folder exists in public directory
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $imagePath = asset('uploads/' . $fileName);
        }

        $tagsArray = [];
        if ($request->tags) {
            $tagsArray = array_map('trim', explode(',', $request->tags));
        }

        $servicedPincodesArray = [];
        if ($request->serviced_pincodes) {
            $servicedPincodesArray = array_map('trim', explode(',', $request->serviced_pincodes));
        }

        $productCode = 'fs-' . Str::random(6);
        $product = Product::create([
            'product_code' => $productCode,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category ?: 'All',
            'image' => $imagePath,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'weight' => $request->weight ?: '500g',
            'pieces' => $request->pieces,
            'servings' => $request->servings,
            'description' => $request->description,
            'tags' => json_encode($tagsArray),
            'serviced_pincodes' => json_encode($servicedPincodesArray),
            'is_best_seller' => $request->has('is_best_seller'),
            'is_today_special' => $request->has('is_today_special'),
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Product created successfully!', 'data' => $product]);
    }

    public function productsUpdate(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('products')) abort(403);

        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|string',
            'pieces' => 'nullable|string',
            'servings' => 'nullable|string',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_best_seller' => 'nullable|boolean',
            'is_today_special' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $imagePath = asset('uploads/' . $fileName);
        }

        $tagsArray = [];
        if ($request->tags) {
            $tagsArray = array_map('trim', explode(',', $request->tags));
        }

        $servicedPincodesArray = [];
        if ($request->serviced_pincodes) {
            $servicedPincodesArray = array_map('trim', explode(',', $request->serviced_pincodes));
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category ?: 'All',
            'image' => $imagePath,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'weight' => $request->weight,
            'pieces' => $request->pieces,
            'servings' => $request->servings,
            'description' => $request->description,
            'tags' => json_encode($tagsArray),
            'serviced_pincodes' => json_encode($servicedPincodesArray),
            'is_best_seller' => $request->has('is_best_seller'),
            'is_today_special' => $request->has('is_today_special'),
        ]);

        return response()->json(['success' => true, 'message' => 'Product updated successfully!', 'data' => $product]);
    }

    public function productsDelete(Request $request)
    {
        if (!Auth::user()->hasPermission('products')) abort(403);

        $ids = $request->ids;
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No items selected.'], 400);

        Product::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected product(s) deleted successfully.']);
    }

    public function productsToggleStatus(Request $request)
    {
        if (!Auth::user()->hasPermission('products')) abort(403);

        $product = Product::findOrFail($request->id);
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'is_active' => $product->is_active]);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDERS TRACKING CRUD
    |--------------------------------------------------------------------------
    */
    public function ordersIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('orders')) abort(403);

        if ($request->ajax()) {
            $orders = Order::with('user')->get();
            return response()->json(['data' => $orders]);
        }
        return view('admin.orders');
    }

    public function orderDetails($id)
    {
        if (!Auth::user()->hasPermission('orders')) abort(403);

        $order = Order::with(['items', 'user'])->findOrFail($id);
        return response()->json($order);
    }

    public function ordersUpdateStatus(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('orders')) abort(403);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Order status updated successfully!', 'status' => $order->status]);
    }

    public function ordersDelete(Request $request)
    {
        if (!Auth::user()->hasPermission('orders')) abort(403);

        $ids = $request->ids;
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No items selected.'], 400);

        Order::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected order(s) deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA MANAGER
    |--------------------------------------------------------------------------
    */
    public function mediaIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('media')) abort(403);

        if ($request->ajax()) {
            $media = Media::latest()->get();
            return response()->json(['data' => $media]);
        }
        return view('admin.media');
    }

    public function mediaUpload(Request $request)
    {
        if (!Auth::user()->hasPermission('media')) abort(403);

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $origName = $file->getClientOriginalName();
            $fileName = time() . '_' . $origName;
            
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $filePath = asset('uploads/' . $fileName);
            
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

            $media = Media::create([
                'file_name' => $origName,
                'file_path' => $filePath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
            ]);

            return response()->json(['success' => true, 'message' => 'Uploaded successfully!', 'data' => $media]);
        }

        return response()->json(['success' => false, 'message' => 'File upload failed.'], 400);
    }

    public function mediaDelete($id)
    {
        if (!Auth::user()->hasPermission('media')) abort(403);

        $media = Media::findOrFail($id);
        
        // Delete the physical file if it exists inside public/uploads
        $localName = basename($media->file_path);
        $localPath = public_path('uploads/' . $localName);
        if (file_exists($localPath)) {
            unlink($localPath);
        }

        $media->delete();

        return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | SETTINGS MODULE
    |--------------------------------------------------------------------------
    */
    public function settingsIndex()
    {
        if (!Auth::user()->hasPermission('settings')) abort(403);

        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function settingsSave(Request $request)
    {
        if (!Auth::user()->hasPermission('settings')) abort(403);

        $data = $request->except(['_token', 'logo', 'favicon']);

        // Handle settings save
        foreach ($data as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // Handle Logo file upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            Setting::updateOrCreate(['key' => 'website_logo'], ['value' => asset('uploads/' . $fileName)]);
        }

        // Handle Favicon file upload
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            Setting::updateOrCreate(['key' => 'website_favicon'], ['value' => asset('uploads/' . $fileName)]);
        }

        return response()->json(['success' => true, 'message' => 'Settings saved successfully!']);
    }

    /*
    |--------------------------------------------------------------------------
    | HERO BANNERS / SLIDES CRUD
    |--------------------------------------------------------------------------
    */
    public function slidesIndex(Request $request)
    {
        if (!Auth::user()->hasPermission('slides') && !Auth::user()->hasPermission('*')) abort(403);

        if ($request->ajax()) {
            $slides = Slide::orderBy('sort_order', 'asc')->get();
            return response()->json(['data' => $slides]);
        }
        return view('admin.slides');
    }

    public function slidesStore(Request $request)
    {
        if (!Auth::user()->hasPermission('slides') && !Auth::user()->hasPermission('*')) abort(403);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'bg_gradient' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagePath = 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'slide_' . time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $imagePath = asset('uploads/' . $fileName);
        }

        $slide = Slide::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'code' => $request->code ?: 'FRESH' . rand(10, 99),
            'bg_gradient' => $request->bg_gradient ?: 'from-red-600 to-rose-500',
            'image' => $imagePath,
            'sort_order' => $request->sort_order ?: 1,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Hero Banner created successfully!', 'data' => $slide]);
    }

    public function slidesUpdate(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('slides') && !Auth::user()->hasPermission('*')) abort(403);

        $slide = Slide::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'bg_gradient' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagePath = $slide->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'slide_' . time() . '_' . $file->getClientOriginalName();
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $imagePath = asset('uploads/' . $fileName);
        }

        $slide->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'code' => $request->code,
            'bg_gradient' => $request->bg_gradient ?: 'from-red-600 to-rose-500',
            'image' => $imagePath,
            'sort_order' => $request->sort_order ?: 1,
        ]);

        return response()->json(['success' => true, 'message' => 'Hero Banner updated successfully!', 'data' => $slide]);
    }

    public function slidesDelete(Request $request)
    {
        if (!Auth::user()->hasPermission('slides') && !Auth::user()->hasPermission('*')) abort(403);

        $ids = $request->ids;
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No banners selected.'], 400);

        Slide::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected banner(s) deleted successfully.']);
    }

    public function slidesToggleStatus(Request $request)
    {
        if (!Auth::user()->hasPermission('slides') && !Auth::user()->hasPermission('*')) abort(403);

        $slide = Slide::findOrFail($request->id);
        $slide->is_active = !$slide->is_active;
        $slide->save();

        return response()->json(['success' => true, 'message' => 'Banner status updated.', 'is_active' => $slide->is_active]);
    }
}
