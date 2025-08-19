@php
    $cartItem = session('cart');
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- < product details  -->
    <section class="product-details pt-50">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-6">
                    <div class="product--img__preview">
                        <div class="product--thumb bg--white radius--8 overflow-hidden mb-2">
                            <div class="main--thumb__preview radius--8">
                                <img class="fit--img" id="productImgSrc"
                                    src="{{ getImage(getFilePath('product') . '/' . $product->firstImage?->image ?? '') }}"
                                    alt="@lang('Product image')">
                            </div>
                        </div>

                        <div class="product-all--img d-flex gap--32 flex-wrap justify-content-start align-items-center">
                            @foreach ($product->images ?? [] as $img)
                                <div
                                    class="item-gallery__image-wrapper d-flex flex-column justify-content-center align-items-center">
                                    <div class="thumb">
                                        <img class="fit--img"
                                            src="{{ getImage(getFilePath('product') . '/' . $img?->image ?? '') }}"
                                            data-category="{{ $img->color?->name }}" alt="@lang('Product image')">
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product--info__wrap">
                        <div class="d-flex flex-column gap--40">
                            <div class="product--info__item">
                                <p class="mb-2">{{ strLimit(__($product->category->name), 50) }}</p>
                                <h6 class="fs--24 fw--600 mb-0">{{ strLimit(__($product->name), 50) }}</h6>
                            </div>
                            <div class="product-variable--wrap d-flex flex-column gap--24">
                                <div class="product--variable">
                                    <p class="mb-2 fs--16 text--black">@lang('Condition')</p>
                                    <p class="fs--16">
                                        @if ($product->type == 1)
                                            @lang('Product')
                                        @else
                                            @lang('Auction')
                                        @endif
                                    </p>
                                </div>

                                @php
                                    $hasColor = $product->images->contains(function ($img) {
                                        return !empty($img->color?->name);
                                    });

                                @endphp
                                @if ($hasColor)
                                    <div class="product--variable">
                                        <p class="mb-2 fs--16 text--black">@lang('Color')</p>
                                        <div class="color-select--option d-flex gap--12 align-items-center">
                                            @foreach ($product->images as $image)
                                                @if (!empty($image->color?->name))
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="form--radio color--radio">
                                                            <input class="form-radio-input" name="categories" type="radio"
                                                                value="{{ $image->color?->name }}"
                                                                id="color{{ $image->color?->name }}" hidden>
                                                            <label style="background: {{ $image->color?->code }};"
                                                                class="form-check-label cursor-pointer"
                                                                for="color{{ $image->color?->name }}">
                                                                <span class="outline"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($product->sizes->isNotEmpty())
                                    <div class="product--variable">
                                        <p class="mb-2 fs--16 text--black">@lang('Size')</p>
                                        <div class="size-select--option d-flex gap--12 align-items-center">
                                            @foreach ($product->sizes ?? [] as $size)
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="form--radio size--radio">
                                                        <input class="form-radio-input" name="size" type="radio"
                                                            value="{{ $size->id }}" id="size_{{ $size->id }}"
                                                            hidden>
                                                        <label class="form-check-label cursor-pointer"
                                                            for="size_{{ $size->id }}">
                                                            {{ $size->size }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="product--info__item d-flex gap--16 align-items-center">
                                <h6 class="fs--32 fw--600 mb-0">
                                    {{ $general->cur_sym . discountPrice($product->price, $product->discount) }}</h6>
                                <hp class="mb-0 text--black7 fs--16 text-decoration-line-through">
                                    {{ $general->cur_sym . showAmount($product->price) }}</hp>
                                <h6 class="mb-0 text--black fs--16">{{ showAmount($product->discount) }}% @lang('OFF')
                                </h6>
                            </div>
                            <div class="btn--wrap d-flex flex-column gap--24">
                                <div class="product--info__item d-flex gap--12 mb-0">
                                    @if ($product->type == 1)
                                        <button class="btn btn-outline--base btn--lg w--100 pills addToCart"
                                            data-product_id="{{ $product->id }}">
                                            @lang('ADD TO CART')
                                        </button>
                                    @else
                                        @auth
                                            <button type="button" class="btn btn--base btn--lg w--100 pills bidNow"
                                                data-product-id="{{ $product->id }}"
                                                data-product-title="{{ $product->name }}"
                                                data-product-price="{{ showAmount($highestBidPrice, 2) }}">@lang('BID NOW')</button>
                                        @endauth
                                        @guest
                                            <button type="button"
                                                class="btn btn--base btn--lg w--100 pills loginBid">@lang('LOGIN TO BID')</button>
                                        @endguest
                                    @endif
                                    <button class="btn btn-outline--base btn--lg w--100 pills addToWishlist"
                                        data-product_id="{{ $product->id }}"
                                        data-url="{{ route('user.wishlist.added') }}">
                                        @lang('ADD TO WISHLIST')
                                    </button>
                                </div>
                                @if ($product->type == 1)
                                    <div class="product--info__item">
                                        <form action="{{ route('cart.add.direct') }}" method="GET"
                                            class="d-inline w-100">
                                            @csrf
                                            <input type="hidden" name="productId" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn--base btn--lg w--100 pills">
                                                @lang('PURCHASE NOW')
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            <div class="product-meta--wrap">
                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0 show">
                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">@lang('DESCRIPTION')</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>

                                    <div class="filter-item--wrap">
                                        <div class="filter-item--content">
                                            <div class="mb-3 wyg">
                                                @php
                                                    echo $product->description;
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">
                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">@lang('SHIPPING & RETURNS')</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>

                                    <div class="filter-item--wrap">
                                        <div class="filter-item--content">
                                            <div class="mb-3">
                                                @php
                                                    echo $product->shipping_description;
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">
                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">@lang('REVIEWS')</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>

                                    <div class="filter-item--wrap">
                                        <form action="{{ route('user.reviews.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="star" id="rating" value="0">

                                            <div class="review-box mb-4" bis_skin_checked="1">
                                                <input type="hidden" name="tour_package_id" value="18">
                                                <input type="hidden" name="star" id="rating" value="0">
                                                <div class="d-flex align-items-center star rating-wrap rating-stars mb-3 gap-1"
                                                    bis_skin_checked="1">
                                                    <i class="far fa-star star--color" data-rating="1"></i>
                                                    <i class="far fa-star star--color" data-rating="2"></i>
                                                    <i class="far fa-star star--color" data-rating="3"></i>
                                                    <i class="far fa-star star--color" data-rating="4"></i>
                                                    <i class="far fa-star star--color" data-rating="5"></i>
                                                </div>
                                                <textarea class="form--control mb-3" name="review" placeholder="@lang('Write Your Review')"></textarea>

                                                <div class="text-end" bis_skin_checked="1">
                                                    <button type="submit" class="btn btn--base btn--lg pills">
                                                        @lang('Submit')</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="filter-item--content">
                                            <div class="product-review--wrap swiper review--slider">
                                                <div class="swiper-wrapper">
                                                    @forelse ($reviews ?? [] as $item)
                                                        <div class="swiper-slide">
                                                            <div class="review-card">
                                                                <ul class="review-star--wrap d-flex gap--4">
                                                                    <li class="text--base">
                                                                        @php echo showRatings($item->rating) @endphp
                                                                    </li>
                                                                </ul>
                                                                <div class="content">
                                                                    <p class="discription">{{ $item->message }}</p>
                                                                </div>
                                                                <div class="user-info">
                                                                    <div class="user-name">
                                                                        <p class="name mb-0 text--black">
                                                                            {{ $item->user?->fullname }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <h5 class="text-center no-review text--black">
                                                            @lang('No Reviews')
                                                        </h5>
                                                    @endforelse

                                                </div>
                                                <div class="swiper-pagination"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product details  /> -->

    <section class="recent--section product position-relative py-100">
        <div class="container">
            <div class="row justify-content-start gy-4 mb-40">
                <div class="col-lg-4">
                    <div
                        class="section-content-4 position-relative d-flex flex-wrap gap--8 justify-content-between align-items-center">
                        <h6 class="title heading--title wow animate__animated animate__fadeInUp text-start fs--28 fw--700 splite-text mb-0"
                            data-splitting data-wow-delay="0.2s">@lang('RECOMMENDED FOR YOU')
                        </h6>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center gy-4">
                @forelse($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        @include($activeTemplate . 'components.product')
                    </div>
                @empty
                    <h4 class="text-center">@lang('No product found')</h4>
                @endforelse
            </div>
        </div>
    </section>


    <div class="modal fade" id="login-upcomming-bid" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog custom--modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Login To Your Account')!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                    @csrf
                    <input type="hidden" name="another" value="1">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="username" class="mb-2 form--label">@lang('Email or Username')</label>
                                    <div class="input--group position-relative">
                                        <input class="form--control" id="username" name="username"
                                            placeholder="@lang('Email or Username')" type="text">
                                        <div class="password-show-hide fas fa-user" data-target="password"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password" class="mb-2 form--label">@lang('Password')</label>
                                <div class="input--group position-relative">
                                    <input type="password" class="form--control" id="password" name="password"
                                        required="" placeholder="@lang('Password')">
                                    <div class="password-show-hide fas fa-eye-slash toggle-password-change"
                                        data-target="password"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-captcha></x-captcha>
                        <button type="reset" class="btn btn--dark text--white pills"
                            data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" id="recaptcha"
                            class="btn btn--md btn--base pills">@lang('Login')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bidAuctionProduct" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg custom--modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabelLiveAuctionBid">@lang('Would you like to participate in the')"<span
                            class="live_auction_product_title"></span>" @lang('bidding competition')?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.bid') }}" method="post">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" id="live_auction_product" name="product_id">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="mb-2 form--label">@lang('Bid Amount')</label>
                                    <input type="number" class="form--control" name="price"
                                        placeholder="@lang('Enter Your Price')" step="any" min="1"
                                        value="" required>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn--dark text--white pills"
                            data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" id="recaptcha"
                            class="btn btn--md btn--base pills">@lang('BID NOW')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- BidAuction Modal  -->
@endsection


@push('script')
    <script>
        // rating set
        $(document).ready(function() {
            'use strict'

            var initialRating = parseInt($('#rating').val());
            if (initialRating > 0) {
                updateStars(initialRating);
            }

            $('.rating-stars i').on('click', function() {
                var rating = parseInt($(this).data('rating'));
                $('#rating').val(rating);
                updateStars(rating);
            });

            $('#rating').on('input', function() {
                var rating = $(this).val();
                updateStars(rating);
            });

            function updateStars(rating) {
                var stars = $('.rating-stars i');
                stars.removeClass('fas').addClass('far');
                stars.each(function(index) {
                    if (index < rating) {
                        $(this).removeClass('far').addClass('fas');
                    }
                });
            }

        });
        // end rating set
    </script>

    <script>
        $(document).ready(function() {
            'use strict'
            $(".loginBid").on('click', function() {
                $('#login-upcomming-bid').modal('show');
            })
        });
    </script>

    <script>
        $(document).ready(function() {
            'use strict'
            $(".bidNow").on('click', function() {

                var modal = $('#bidAuctionProduct');
                var productTitle = $(this).data('product-title');
                var productId = $(this).data('product-id');
                var productPrice = $(this).data('product-price');
                modal.find('.live_auction_product_title').text(productTitle);
                modal.find('input[name="product_id"]').val(productId);
                modal.find('input[name="price"]').attr('placeholder',
                    "Highest Bid {{ $general->cur_sym }}" + productPrice);
                modal.modal('show');
            })
        });
    </script>
@endpush

@push('style')
    <style>
        .wyg h1,
        h2,
        h3,
        h4 {
            color: #383838;
        }

        .wyg strong {
            color: #383838;
            margin-bottom: 16px;
        }

        .wyg p {
            margin-bottom: 16px;
            font-size: 16px;
            color: #373737;
        }

        .wyg ul {
            margin-left: 18px
        }

        .wyg ul li {
            list-style-type: disc;
            color: #373737;
            text-align: start;
            margin-bottom: 12px;
            font-size: 16px;
        }

        .section-title {
            font-size: 30px;
            margin-bottom: 0;
        }
    </style>
@endpush
