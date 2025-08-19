@php
    $keys = !empty($cartItem) ? array_keys($cartItem) : [];
    $isAdded = in_array($product->id, $keys);
@endphp
<div class="product--card position-relative radius--12">
    <div class="card--widgets w-100 position-absolute d-flex justify-content-between">
        <div class="add-to fav flex-shrink-0 radius--50 d-flex justify-content-center align-items-center addToWishlist"
            data-product_id="{{ $product->id }}" data-url="{{ route('user.wishlist.added') }}">
            @php echo isWishlist($product) @endphp
        </div>

        @if ($product->type == 1)
            <div class="add-to cart flex-shrink-0 radius--50 d-flex justify-content-center align-items-center addToCart"
                data-product_id="{{ $product->id }}">
                <img class="{{ $isAdded ? 'd-none' : 'd-block' }}"
                    src="{{ asset('assets/images/frontend/icon/cart-w.png') }}" alt="@lang('image')">
                <img class="{{ $isAdded ? 'd-block' : 'd-none' }}"
                    src="{{ asset('assets/images/frontend/icon/cart-w-fill.png') }}" alt="@lang('image')">
            </div>
        @endif

    </div>

    <div class="thumb--wrap radius--8 overflow-hidden">
        <a href="{{ route('product.details', ['slug' => slug($product->name), 'id' => $product->id]) }}"
            class="w-100 h-100">
            <img class="fit--img"
                src="{{ getImage(getFilePath('product') . '/' . 'thumb_' . $product->firstImage?->image ?? '') }}"
                alt="@lang('image')">
        </a>
    </div>

    <div class="content--wrap d-flex flex-column gap--12 justify-content-center align-items-center">
        <a href="{{ route('product.details', ['slug' => slug($product->name), 'id' => $product->id]) }}">
            <h6 class="title fs--24 fw--600">{{ strLimit(__($product->name), 20) }}</h6>
        </a>

        <div class="d-flex flex-column justify-content-center align-items-center">
            <p class="text--black7 fs--14 fw--500">
                @lang('Brand'): <span class="text--black">{{ __($product->brand) ?? '' }}</span>
            </p>
            <p class="text--black7 fs--14 fw--500">
                @lang('Condition'): <span class="text--black">{{ $product->type == 1 ? __('New') : __('Used') }}</span>
            </p>
        </div>
        <div class="d-flex flex-column justify-content-center align-items-center gap--8">
            <p class="fs--14 fw--500">@lang('Sizes')</p>
            
            <p class="fs--14 fw--500 text--black">
                @forelse ($product->sizes as $item)
                    {{ $item->size }}
                    @if (!$loop->last)
                        ,
                    @endif
                @empty
                    <h6 class="text-center">@lang('Size not available')</h6>
                @endforelse
            </p>
        </div>
    </div>

    <div class="cta--wrap d-flex justify-content-between align-items-center">
        <div class="price--wrap d-flex align-items-center gap--8">
            <h6 class="price fs--24 fw--600 mb-0">
                {{ $general->cur_sym . discountPrice($product->price, $product->discount) }}</h6>
            <span
                class="text-decoration-line-through text--black7">{{ $general->cur_sym . showAmount($product->price) }}</span>
        </div>

        <div class="btn--wrap">
            <a href="{{ route('product.details', ['slug' => slug($product->name), 'id' => $product->id]) }}"
                class="btn btn--base btn--md pill">
                @lang($product->type == 1 ? 'SHOP NOW' : 'BID NOW')
            </a>
        </div>
    </div>
</div>
