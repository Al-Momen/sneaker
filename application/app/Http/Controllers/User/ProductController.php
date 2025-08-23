<?php

namespace App\Http\Controllers\User;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Constants\Status;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index($status = 'all')
    {
        $user = auth()->user();
        $query = Product::with(['category', 'firstImage', 'wishlists'])
            ->where('author_id', $user->id)
            ->where('author_type', 2)
            ->where('type', 1)
            ->searchable(['name'])
            ->latest();

        switch ($status) {
            case 'disable':
                $query->where('status', Status::DISABLE);
                break;
            case 'enable':
                $query->where('status', Status::ENABLE);
                break;
            case 'all':
                $query->whereIn('status', [Status::ENABLE, Status::DISABLE]);
                break;
            default:
                break;
        }

        $products = $query->paginate(getPaginate());
        if (request()->ajax()) {
            return response()->json([
                'html' => view('Template::components.user.tables.product_data', compact('products'))->render(),
                'pagination' => $products->hasPages() ? view('Template::components.user.tables.pagination', ['items' => $products])->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Products';
        return view('UserTemplate::product.index', compact('products', 'pageTitle'));
    }

    public function auctionProduct($status = 'all')
    {
        $user = auth()->user();
        $query = Product::with(['category', 'firstImage', 'wishlists'])
            ->where('author_id', $user->id)
            ->where('author_type', 2)
            ->where('type', 2)
            ->searchable(['name'])
            ->latest();

        switch ($status) {
            case 'disable':
                $query->where('status', Status::PRODUCT_DISABLE);
                break;
            case 'enable':
                $query->where('status', Status::PRODUCT_ENABLE);
                break;
            case 'expired_auction_product':
                $query->where('status', Status::EXPIRED_AUCTION_PRODUCT);
                break;
            case 'all':
                $query->whereIn('status', [Status::ENABLE, Status::DISABLE, Status::EXPIRED_AUCTION_PRODUCT]);
                break;
            default:
                break;
        }

        $products = $query->paginate(getPaginate());
        if (request()->ajax()) {
            return response()->json([
                'html' => view('Template::components.user.tables.auction_data', compact('products'))->render(),
                'pagination' => $products->hasPages() ? view('Template::components.user.tables.pagination', ['items' => $products])->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Products';
        return view('UserTemplate::product.auction', compact('products', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Create Product';
        $categories = Category::where('status', 1)->latest()->get();
        $colors = Color::where('status', 1)->latest()->get();
        $sizes = Size::where('status', 1)->latest()->get();
        return view('UserTemplate::product.create', compact('pageTitle', 'categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {

        if ($request->is_color) {
            if (!$request->has('color_images') || !is_array($request->color_images) || count($request->color_images) === 0) {
                $notify[] = ['error', 'At least one color image must be uploaded when color is enabled.'];
                return back()->withInput($request->all())->withNotify($notify);
            }
            $request->merge(['images' => []]);
        } else {
            if (!$request->has('images') || !is_array($request->images) || count($request->images) === 0) {
                $notify[] = ['error', 'At least one image must be uploaded.'];
                return back()->withInput($request->all())->withNotify($notify);
            }
            $request->merge(['color_images' => []]);
        }

        if ($request->type == 2) {
            $request->merge(['sizes' => []]);
        }

        if ($request->type == 1 && !$request->has('sizes') && !is_array($request->sizes)) {
            $notify[] = ['error', 'At least one size and quantity must be provided.'];
            return back()->withInput($request->all())->withNotify($notify);
        }

        // when type is 2, start date is required
        if ($request->type == 2 && $request->has('start_date')) {
            $startedAt = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        } else {
            $startedAt = now()->format('Y-m-d H:i:s');
        }
        // when type is 2, end date is required
        if ($request->has('end_date')) {
            $endedAt = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        }

        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'category'         => 'required|exists:categories,id',
            'type'             => 'required|in:1,2',
            'brand_name'       => 'required|string',
            'price'            => 'required_if:type,1' . ($request->type == 1 ? '|min:1|numeric' : ''),
            'discount'         => 'nullable|numeric' . ($request->type == 1 ? '|between:0,99.99' : ''),
            'description'      => 'required|string',
            'shipping_returns' => 'required|string',
            'meta_title'       => 'nullable|string',
            'meta_description' => 'nullable|string',
            'code_id'          => 'nullable|array',
            'code_id.*'        => 'nullable|string|max:255',
            'color_images'     => ($request->is_color ? 'required' : 'nullable') . '|array',
            'images'           => 'nullable|array',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'min_price'        => $request->type == 2 ? 'numeric|min:1' : 'nullable',
            'start_date'       => $request->type == 2 ? 'date|after:yesterday|before:end_date' : 'nullable',
            'end_date'         => $request->type == 2 ? 'date|after:start_date' : 'nullable',
            'sizes'             => $request->type == 1 ? 'required|array' : 'nullable',
            'sizes.*'           => $request->type == 1 ? 'required|not_in:0' : 'nullable',
        ]);

        // Validate color_images
        $validator->after(function ($validator) use ($request) {
            if ($request->has('color_images') && is_array($request->color_images)) {
                $validColorIds = DB::table('colors')->where('status', 1)->pluck('id')->toArray();

                foreach ($request->color_images as $key => $file) {
                    if (!in_array((int)$key, $validColorIds)) {
                        $validator->errors()->add("color_images.$key", "Invalid color ID: $key");
                        continue;
                    }
                    if (!$file) {
                        $validator->errors()->add("color_images.$key", "Image is required for color ID: $key");
                    } elseif (!$file instanceof \Illuminate\Http\UploadedFile || !$file->isValid()) {
                        $validator->errors()->add("color_images.$key", "Uploaded file for color ID $key is invalid.");
                    } elseif (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])) {
                        $validator->errors()->add("color_images.$key", "File for color ID $key must be an image.");
                    }
                }
            }
        });

        if ($validator->fails()) {
            $errors = $validator->errors();
            $notify = [];
            foreach ($errors->all() as $message) {
                $notify[] = ['error', $message];
            }
            return redirect()->back()->withNotify($notify)->withInput();
        }

        DB::beginTransaction();
        try {
            $product                       = new Product();
            $purifier                      = new \HTMLPurifier();
            $product->product_code         = getTrx(8);
            $product->author_id            = auth()->id();
            $product->author_type          = 2;
            $product->type                 = $request->type;
            $product->name                 = $request->name;
            $product->brand_name           = $request->brand_name;
            $product->is_size              = (count($request->sizes) > 0 && $request->type == 1) ? 1 : 0;
            $product->is_color             = $request->is_color ? 1 : 0;
            $product->category_id          = $request->category;
            $product->price                = $request->type == 1 ? $request->price : 0;
            $product->min_price            = $request->type == 2 ? $request->min_price : 0;
            $product->start_date           = $request->type == 2 ? $startedAt : null;
            $product->end_date             = $request->type == 2 ? $endedAt : null;
            $product->discount             = $request->type == 1 ? $request->discount : 0;
            $product->meta_title           = $request->meta_title;
            $product->description          = $purifier->purify($request->description);
            $product->shipping_description = $purifier->purify($request->shipping_returns);
            $product->meta_description     = $purifier->purify($request->meta_description);
            $product->sizes                = $request->type == 1 ? $request->sizes : null;
            $product->status               = 1;
            $product->save();
            try {
                $images = [];
                if (!$request->is_color && $request->hasFile('images')) {
                    $images = $request->images;
                    foreach ($images as $img) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image'      => fileUploader($img, getFilePath('product'), getFileSize('product'), null, getFileThumbSize('product')),
                        ]);
                    }
                }

                if ($request->is_color && $request->hasFile('color_images')) {
                    foreach ($request->color_images as $colorId => $img) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'color_id'   => $colorId,
                            'image'      => fileUploader($img, getFilePath('product'), getFileSize('product'), null, getFileThumbSize('product')),
                        ]);
                    }
                }
            } catch (\Exception $exp) {
                $notify[] = ['error' => "Couldn't Create Product Images"];
                return back()->withNotify($notify)->withInput($request->all());
            }

            DB::commit();
            $notify[] = ['success', 'Product created successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', "Couldn't Create Products"];
            return back()->withNotify($notify)->withInput($request->all());
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Product';
        $product = Product::with('category', 'images.color', 'wishlists')
            ->where('author_id', auth()->id())
            ->where('author_type', 2)
            ->findOrFail($id);
        $colors = Color::where('status', 1)->latest()->get();
        $categories = Category::where('status', 1)->latest()->get();
        $sizes = Size::where('status', 1)->latest()->get();
        $usedColorIds = $product->images->pluck('color_id')->toArray();

        return view('UserTemplate::product.edit', compact('pageTitle', 'categories', 'product', 'colors', 'usedColorIds', 'sizes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);
        $purifier = new \HTMLPurifier();

        if ($product->is_color) {
            $existingImages = $product->images->where('color_id', '!=', null)->pluck('id')->toArray();
            if (!$request->has('color_images') && !is_array($product->color_images) && count($existingImages) <= 0) {
                $notify[] = ['error', 'At least one color image must be uploaded when color is enabled.'];
                return back()->withNotify($notify);
            }
            $request->merge(['images' => []]);
        } else {
            $existingImages = $product->images->where('color_id', '=', null)->pluck('id')->toArray();
            if (!$request->has('images') && !is_array($request->images) && count($existingImages) <= 0) {
                $notify[] = ['error', 'At least one image must be uploaded.'];
                return back()->withNotify($notify);
            }
            $request->merge(['color_images' => []]);
        }

        if ($request->type == 2) {
            $request->merge(['sizes' => []]);
        }

        // when type is 2, start date is required
        if ($product->type == 2 && $request->has('start_date') && $request->start_date < $product->start_date) {
            $startedAt = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        } else {
            $startedAt = $product->start_date;
        }
        // when type is 2, end date is required
        if ($product->type == 2 && $request->has('end_date')) {
            $endedAt = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        }


        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'brand_name'       => 'required|string',
            'category'         => 'required|exists:categories,id',
            'price'            => 'required_if:type,1' . ($product->type == 1 ? '|min:1|numeric' : ''),
            'discount'         => 'nullable|numeric|' . ($product->type == 1 ? '|between:0,99.99' : ''),
            'description'      => 'required|string',
            'shipping_returns' => 'required|string',
            'meta_title'       => 'nullable|string',
            'meta_description' => 'nullable|string',
            'code_id'          => 'nullable|array',
            'code_id.*'        => 'nullable|numeric',
            'color_images'     => ($request->is_color ? 'required' : 'nullable') . '|array',
            'images'           => 'nullable|array',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'min_price'        => $product->type == 2 ? 'numeric|min:1' : 'nullable',
            'start_date'       => $product->type == 2 ? 'date|after:yesterday|before:end_date' : 'nullable',
            'end_date'         => $product->type == 2 ? 'date|after:start_date' : 'nullable',
            'sizes'            => $product->type == 1 ? 'required|array' : 'nullable|array',
            'sizes.*'          => $product->type == 1 ? 'required|not_in:0' : 'nullable',

        ]);

        // Validate color_images
        $validator->after(function ($validator) use ($request) {
            if ($request->has('color_images') && is_array($request->color_images)) {
                $validColorIds = DB::table('colors')->where('status', 1)->pluck('id')->toArray();
                foreach ($request->color_images as $key => $file) {
                    if (!in_array((int)$key, $validColorIds)) {
                        $validator->errors()->add("color_images.$key", "Invalid color ID: $key");
                        continue;
                    }
                    if (!$file) {
                        $validator->errors()->add("color_images.$key", "Image is required for color ID: $key");
                    } elseif (!$file instanceof \Illuminate\Http\UploadedFile || !$file->isValid()) {
                        $validator->errors()->add("color_images.$key", "Uploaded file for color ID $key is invalid.");
                    } elseif (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])) {
                        $validator->errors()->add("color_images.$key", "File for color ID $key must be an image.");
                    }
                }
            }
        });

        if ($validator->fails()) {
            $errors = $validator->errors();
            $notify = [];
            foreach ($errors->all() as $message) {
                $notify[] = ['error', $message];
            }
            return redirect()->back()->withNotify($notify)->withInput();
        }

        DB::beginTransaction();
        try {
            $purifier                      = new \HTMLPurifier();
            $product->product_code         = getTrx(8);
            $product->name                 = $request->name;
            $product->brand_name           = $request->brand_name;
            $product->is_size              = (count($request->sizes) > 0 &&  $request->type == 1) ? 1 : 0;
            $product->category_id          = $request->category;
            $product->price                = $product->type == 1 ? $request->price : 0;
            $product->min_price            = ($product->type == 2 && $product->start_date > now()) ? $request->min_price : $product->min_price;
            $product->start_date           = ($product->type == 2 && $product->start_date > now()) ? $startedAt : $product->start_date;
            $product->end_date             = $product->type == 2 ? $endedAt : null;
            $product->discount             = $product->type == 1 ? $request->discount : 0;
            $product->meta_title           = $request->meta_title;
            $product->description          = $purifier->purify($request->description);
            $product->shipping_description = $purifier->purify($request->shipping_returns);
            $product->meta_description     = $purifier->purify($request->meta_description);
            $product->sizes                = $product->type == 1 ? $request->sizes : null;
            $product->save();

            try {
                $images = [];
                if (!$request->is_color && $request->hasFile('images')) {
                    $images = $request->images;
                    foreach ($images as $img) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image'      => fileUploader($img, getFilePath('product'), getFileSize('product'), null, getFileThumbSize('product')),
                        ]);
                    }
                }

                if ($request->hasFile('color_images')) {
                    foreach ($request->color_images as $colorId => $img) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'color_id'   => $colorId,
                            'image'      => fileUploader($img, getFilePath('product'), getFileSize('product'), null, getFileThumbSize('product')),
                        ]);
                    }
                }
            } catch (\Exception $exp) {
                $notify[] = ['error' => "Couldn't Create Product Images"];
                return back()->withNotify($notify)->withInput($request->all());
            }

            DB::commit();
            $notify[] = ['success', 'Product created successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', "Couldn't Create Products"];
            return back()->withNotify($notify);
        }
    }

    public function imageDelete($id)
    {
        try {
            $productImage = ProductImage::findOrFail($id);
            fileManager()->removeFile(getFilePath('product') . '/' . $productImage->image);

            if (file_exists(getFilePath('product') . '/thumb_' . $productImage->image)) {
                fileManager()->removeFile(getFilePath('product') . '/thumb_' . $productImage->image);
            }
            $productImage->delete();
            $notify[] = ['success', 'Image deleted successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t delete your image'];
            return back()->withNotify($notify);
        }
    }

    public function statusUpdate($id)
    {
        $product = Product::findOrFail($id);
        $product->status = ($product->status == 1) ? 0 : 1;
        $product->save();
        $notify[] = ['success', 'Product status Update successfully'];
        return back()->withNotify($notify);
    }
}
