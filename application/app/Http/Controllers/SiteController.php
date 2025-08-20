<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Size;
use App\Models\Product;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Shipping;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Cookie;

class SiteController extends Controller
{
    public function index()
    {
        if (isset($_GET['reference'])) {
            session()->put('reference', $_GET['reference']);
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', '/')->first();
        return view('Template::home', compact('pageTitle', 'sections'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view('Template::pages', compact('pageTitle', 'sections'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        return view('Template::contact', compact('pageTitle'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug, $id)
    {
        $policy = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view('Template::policy', compact('policy', 'pageTitle'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogDetails($slug, $id)
    {
        $blog = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        return view('Template::blog_details', compact('blog', 'pageTitle'));
    }

    public function cookieAccept()
    {
        $general = gs();
        Cookie::queue('gdpr_cookie', $general->site_name, 43200);
        return back();
    }

    public function cookiePolicy()
    {
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        $general = gs();
        if ($general->maintenance_mode) {
            $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
            return view('Template::maintenance', compact('pageTitle', 'maintenance'));
        }
        return to_route('home');
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 255, 255, 255);
        $bgFill    = imagecolorallocate($image, 28, 35, 47);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function product(Request $request)
    {
        $pageTitle = 'Products';
        $products = Product::with(['category', 'firstImage', 'wishlists'])->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->where('status', 1)->inRandomOrder()->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $sizes = Size::where('status', 1)->latest()->get();
        $brands = Product::where('status', 1)
            ->whereNotNull('brand_name')
            ->pluck('brand_name')
            ->unique()
            ->values();
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'product')->first();
        return view('Template::products.product', compact('pageTitle', 'products', 'sections', 'sizes', 'categories', 'brands'));
    }

    public function productsFilter(Request $request)
    {
        $query = Product::query()->where('status', 1);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('brands')) {
            $brands = $request->brands;
            $query->where(function ($q) use ($brands) {
                foreach ($brands as $brand) {
                    $q->orWhere('brand_name', 'like', "%{$brand}%");
                }
            });
        }

        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->filled('sizes')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->whereIn('sizes.id', $request->sizes);
            });
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        if ($request->filled('ordering')) {
            switch ($request->ordering) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'low_price':
                    $query->orderBy('price', 'asc');
                    break;
                case 'high_price':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->get();
        $productCounts = $products->count();
        $html = view('Template::components.filter_product', compact('products'))->render();

        return response()->json([
            'html' => $html,
            'pCount' => $productCounts,
        ]);
    }


    public function productDetails($slug, $id)
    {
        $pageTitle = 'Product Details';
        $product = Product::with(['category', 'images.color', 'wishlists', 'sizes', 'bids'])->findOrFail($id);
        $highestBid = $product->bids->sortByDesc('price')->first();

        $highestBidPrice = $highestBid ? $highestBid->price : $product->min_price;

        $products = Product::with(['category', 'firstImage', 'wishlists',])
            ->where('status', 1)
            ->inRandomOrder()
            ->take(4)
            ->get();
        $reviews = $product->reviews()->with('user')->paginate(getPaginate(5));
        return view('Template::products.details', compact('pageTitle', 'product', 'products', 'reviews', 'highestBidPrice'));
    }


    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:subscribers',
        ]);
        $subscribe = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();
        $notify[] = ['success', 'You have successfully subscribed to the Newsletter'];
        return back()->withNotify($notify);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'quantity' => 'required|gt:0'
        ]);


        $id = $request->productId;
        $product = Product::with('firstImage', 'category', 'wishlists')->findOrFail($id);

        if (auth()->user() && $product->author_id == auth()->user()->id) {
            if ($product->author_id == auth()->user()->id && $product->author_type == 2) {
                return response()->json([
                    'data' => [
                        'message' => "You can not add your own product",
                        'replaceCart' => 0,
                        'status' => false,
                    ]
                ]);
            }
        }

        $discountedPrice = $product->price - ($product->price * $product->discount / 100);
        $quantity = $request->quantity;

        $cart = session()->get('cart', []);
        if (!empty($cart)) {
            $firstItem = reset($cart);
            if ($firstItem['author_id'] !== $product->author_id || $firstItem['author_type'] !== $product->author_type) {
                session()->forget('replaceCart');
                $totalPrice = ($product->discount != 0) ? $discountedPrice : $product->price;
                $replaceCart[$id] = [
                    "id"          => $product->id,
                    "author_id"   => $product->author_id,
                    "author_type" => $product->author_type,
                    "author_type" => $product->author_type,
                    "name"        => $product->name,
                    "category"    => $product->category->name,
                    "quantity"    => $quantity,
                    "price"       => showAmount($totalPrice, 2, false),
                    "image"       => $product?->firstImage ? $product->firstImage->image : "default.png",
                ];

                session()->put('replaceCart', $replaceCart);

                return response()->json([
                    'data' => [
                        'message' => "You can only add products from the same vendor",
                        'replaceCart' => 1,
                        'status' => false,
                    ]
                ]);
            }
        }


        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $totalPrice = ($product->discount != 0) ? $discountedPrice : $product->price;
            $cart[$id] = [
                "id"          => $product->id,
                "author_id"   => $product->author_id,
                "author_type" => $product->author_type,
                "name"        => $product->name,
                "category"    => $product->category->name,
                "quantity"    => $quantity,
                "price"       => showAmount($totalPrice, 2, false),
                "image"       => $product?->firstImage ? $product->firstImage->image : "default.png",
            ];
        }

        session()->put('cart', $cart);

        $cartItemCount = count((array) session('cart'));

        // all item total price
        $totalPrice = 0;
        foreach ($cart as $item) {
            $price = (float) str_replace(',', '', $item['price']);
            $quantity = (int) $item['quantity'];
            $totalPrice += $price * $quantity;
        }

        return response()->json([
            'message' => 'Product added to cart',
            'cartItemCount' => $cartItemCount,
            'totalPrice' => gs()->cur_sym . $totalPrice,
            'cartItem' => $cart[$id],
        ], 200);
    }

    public function replaceToCart()
    {
        $replaceCart = session()->get('replaceCart');
        session()->put('cart', $replaceCart);
        return response()->json([
            'message' => 'Product added to cart',
            'status' => true,

        ], 200);
    }

    public function getCart()
    {

        $pageTitle = "Your Cart";
        $cartItem = session('cart');

        if (!$cartItem) {
            $notify[] = ['error', 'At least one services add to cart'];
            return back()->withNotify($notify);
        }

        return view('Template::user.cart.cart', compact('pageTitle', 'cartItem'));
    }

    // update quantity
    public function updateQuantity(Request $request)
    {
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');

        if ($productId && $quantity) {
            $cart = session()->get('cart');
            $cart[$productId]["quantity"] = $quantity;
            session()->put('cart', $cart);
        }

        $product = Product::findOrFail($productId);

        if (isset($product->discount)) {
            $discount = $product->price - ($product->price * $product->discount / 100);
            $totalAmount = $quantity * $discount;
        } else {
            $totalAmount = $quantity * $product->price;
        }

        $formattedTotalAmount = showAmount($totalAmount, 2, false);

        return response()->json([
            'totalAmount' => $formattedTotalAmount,
            'quantity' => $quantity,
        ]);
    }

    public function removeCartItem(Request $request)
    {
        $productId = $request->input('productId');
        $cart = session()->get('cart', []);
        $checkCoupon = session()->get('coupon');


        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        if (count($cart) <= 0) {
            // session()->forget('coupon');
        }

        $cartItemCount = count($cart);

        return response()->json([
            'message' => 'Product removed from the cart',
            'cartItemCount' => $cartItemCount,
            'checkCoupon' => $checkCoupon
        ]);
    }

    public function getCheckOut()
    {
        if (empty(session('cart'))) {
            $notify[] = ['error', 'At least one product add to cart'];
            return back()->withNotify($notify);
        }

        $pageTitle = "Checkout";
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        $cartItems = session('cart');
        $shippings = Shipping::where('status', 1)->get();
        return view('Template::checkout', compact('gatewayCurrency', 'mobileCode', 'countries', 'pageTitle', 'cartItems', 'shippings'));
    }

    public function directAddToCart(Request $request)
    {
        $product = Product::with('firstImage', 'category', 'wishlists')->findOrFail($request->productId);

        $cart = session()->get('cart', []);

        $productId = $product->id;

        // If already exists in cart, increase quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity ?? 1;
        } else {
            // Otherwise add new product with quantity
            $cart[$productId] = [
                'id' => $productId,
                'name' => $product->name,
                'category' => $product->category->name,
                'price' => discountPrice($product->price, $product->discount),
                'quantity' => $request->quantity ?? 1,
                'image' => $product->firstImage->image ?? 'default.png',
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('get.checkout');
    }
}
