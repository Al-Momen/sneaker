@php
    $languages = App\Models\Language::all();
    $pages = App\Models\Page::where('tempname', $activeTemplate)->get();
    $currentLang = $languages->firstWhere('code', session('lang', 'en'));
    $randomProduct = App\Models\Product::with(['category', 'firstImage', 'wishlists'])
        ->where('status', 1)
        ->inRandomOrder()
        ->first();
    $cartItem = session('cart');
    $total = 0;

@endphp

<!-- Header Start -->
<div class="header-main-area">
    <div class="header" id="header">
        <div class="container position-relative">
            <div class="header-wrapper">
                <!-- ham menu -->
                <i class="fa-sharp fa-solid fa-bars-staggered ham__menu" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"></i>

                <!-- logo -->
                <div class="header-menu-wrapper align-items-center d-flex gap--32">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}" class="normal-logo" id="normal-logo"> <img
                                src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                                alt="...">
                        </a>
                    </div>

                </div>
                <!-- / logo -->

                <div class="menu--wrap d-flex align-items-center gap--72">
                    <div class="menu-list-wrapper">
                        <ul class="main-menu">
                            @foreach ($pages as $page)
                                <a class="{{ Request::url() == url($page->slug) ? 'active' : '' }}"
                                    href="{{ route('pages', [$page->slug]) }}">{{ __($page->name) }}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <ul class="login-lng d-flex align-items-center gap-3 gap-md-4">
                    <li class="language">
                        <div class="language-box">
                            <button class="dropdown-toggle text--white p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('assets/images/frontend/icon/globe.png') }}" alt="@lang('Icon')">
                            </button>
                            <ul class="dropdown-menu lng--dropdown">
                                @foreach ($languages as $language)
                                    <li>
                                        <a class="dropdown-item lang-change @if (Session::get('lang') === $language->code) selected @endif"
                                            href="javascript:void(0)" data-lang="{{ $language->code }}">
                                            <img class="flag--img"
                                                src="{{ getImage(getFilePath('language') . '/' . $language->icon ?? '', getFileSize('language')) }}"
                                                alt="@lang('Icon')">
                                            {{ __($language->name ?? '') }}

                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>

                    <!-- search -->
                    <li class="search-btn--wrap position-relative">
                        <button class="p-0 search-toggle--btn">
                            <img src="{{ asset('assets/images/frontend/icon/search.png') }}"
                                alt="@lang('image')"></button>
                        <div class="search--bar__wrap">
                            <form action="{{ route('product') }}" method="get">
                                <div class="position-relative">
                                    <input class="form--control pill" name="search" value="{{ request()->search }}"
                                        placeholder="@lang('Search products')...">
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- search -->


                    <li class="fav-btn--wrap">
                        <a href="{{ route('user.get.wishlist') }}"> <img
                                src="{{ asset('assets/images/frontend/icon/fav.png') }}" alt="@lang('Wishlist')"
                                title="@lang('Wishlist')"></a>
                    </li>

                    <!-- cart btn -->
                    <li class="cart-btn--wrap">
                        <button
                            class="p-0 position-relative {{ count((array) session('cart')) > 0 ? 'text--base' : 'text--black' }} addToCartHeader"
                            data-bs-toggle="offcanvas" data-bs-target="#cartoffcanvasExample"
                            aria-controls="cartoffcanvasExample">
                            <img src="{{ asset('assets/images/frontend/icon/cart.png') }}" alt="@lang('Cart')">
                            @if (count((array) session('cart')) > 0)
                                <span class="cart--stump" id="cartItem">{{ count((array) session('cart')) }}</span>
                            @endif
                        </button>
                    </li>
                    <!-- cart btn -->
                    @auth
                        <!-- user action -->
                        <li class="usser-info--wrap">
                            <button class="text--black dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">
                                <img src="{{ asset('assets/images/frontend/icon/user.png') }}"
                                    alt="@lang('User')"></button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.home') }}"><i
                                            class="fa-regular fa-user"></i> @lang('Dashboard')</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('user.change.password') }}"><i
                                            class="fa-solid fa-key"></i> @lang('Password')</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('user.twofactor') }}"><i
                                            class="fa-solid fa-user-ninja"></i> @lang('2FA Security')</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('user.logout') }}"><i
                                            class="fa-solid fa-arrow-right-from-bracket"></i> @lang('Logout')</a>
                                </li>
                            </ul>
                        </li>
                    @endauth
                    @guest
                        <a class="text--black" href="{{ route('user.login') }}">
                            <img src="{{ asset('assets/images/frontend/icon/user.png') }}" alt="@lang('User')"></a>
                        <!-- user action -->
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Header section End -->

<!-- Sidebar mobile menu wrap Start-->
<div class="offcanvas offcanvas-start text-bg-light" tabindex="-1" id="offcanvasExample">
    <div class="offcanvas-header">
        <div class="logo">
            <div class="align-items-center d-flex">
                <div class="logo-wrapper">
                    <a href="{{ route('home') }}" class="normal-logo" id="offcanvas-logo-normal">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png', '?' . time()) }}"
                            alt="@lang('Logo')">
                    </a>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @auth
            <div class="user-info">
                <div class="user-thumb">
                    <a href="{{ route('user.home') }}">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image ?? '', getFileSize('userProfile')) }}"
                            alt="@lang('Image')">
                    </a>
                </div>
                <a href="{{ route('user.home') }}">
                    <h4>{{ auth()->user()->username }}</h4>
                    <p>{{ auth()->user()->fullname }}</p>
                </a>
            </div>
        @endauth
        <ul class="side-Nav">
            @foreach ($pages as $page)
                <li>
                    <a class='{{ Request::url() == url($page->slug) ? 'active' : '' }}'
                        href="{{ route('pages', [$page->slug]) }}"
                        aria-current="page">{{ __($page->name ?? '') }}</a>
                </li>
            @endforeach
            <li>
                @auth
                    <a href="{{ route('user.home') }}" class="login-btn">@lang('Dashboard')</a>
                @else
                    <a href="{{ route('user.login') }}" class="login-btn">@lang('Sign In')</a>
                @endauth
            </li>

            @foreach ($languages as $language)
                <li>
                    <a class="dropdown-item lang-change @if (Session::get('lang') === $language->code) selected @endif"
                        href="javascript:void(0)" data-lang="{{ $language->code }}">
                        <img class="flag--img"
                            src="{{ getImage(getFilePath('language') . '/' . $language->icon ?? '', getFileSize('language')) }}"
                            alt="@lang('Icon')">
                        {{ __($language->name ?? '') }}

                    </a>
                </li>
            @endforeach
            <li>
                <a href="{{ route('user.register') }}" class="login-btn">@lang('Signup')</a>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar mobile menu wrap End -->


<!-- cart sidebar -->
<div class="offcanvas offcanvas-end text-bg-light cart-side--bar" tabindex="-1" id="cartoffcanvasExample">
    <div class="offcanvas-header">
        <div class="title--wrap">
            <h6 class="mb-0">@lang('Your Cart Items')</h6>
        </div>
        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row gy-4">
            <div id="cartItemsContainer">
                @if (isset($cartItem))
                    @forelse (array_reverse($cartItem) as $product)
                        @php $total += @$product['price'] * @$product['quantity'] @endphp
                        <!-- cart item -->
                        <div class="col-lg-12 itemMainDiv mb-4" data-product_id={{ $product['id'] }}>
                            <div class="cart--item d-flex gap--32">
                                <div class="thumb--wrap radius--8 flex-shrink-0 position-relative">
                                    <img class="fit--img radius--8"
                                        src="{{ getImage(getFilePath('product') . '/thumb_' . $product['image']) }}"
                                        alt="@lang('product image')">

                                    <button
                                        class="remove--item position-absolute d-flex justify-content-center align-items-center text--danger remove-btn">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div class="content--wrap d-flex flex-column justify-content-between">
                                    <div class="title--wrap">
                                        <p class="fs--14 fw--500 mb-2">{{ __($product['category']) }}</p>
                                        <h6 class="fs--18 fw--500">{{ __($product['name']) }}</h6>
                                    </div>

                                    <div class="price--wrap d-flex justify-content-between align-items-center">
                                        <div
                                            class="quantity_box border--base d-flex justify-content-between align-items-center">
                                            <button type="button" class="counter-btn decrement"
                                                data-product_id="{{ $product['id'] }}"
                                                {{ $product['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input class="count-input count" data-product_id="{{ $product['id'] }}"
                                                type="number" id="quantityInput"
                                                value="{{ $product['quantity'] }}">
                                            <button type="button" class="counter-btn increment"
                                                data-product_id="{{ $product['id'] }}">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <h6 class="mb-0 total-amount total-amount-{{ $product['id'] }}">
                                            @php
                                                $amount = $product['quantity'] * $product['price'];
                                            @endphp

                                            {{ $general->cur_sym . showAmount($amount) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center noProduct" colspan="100%" data-label="@lang('cart-item')">
                            @lang('No product added to cart')
                        </div>
                    @endforelse
                @else
                @endif
            </div>
            <!-- cart item -->


            <!-- price info -->
            <div class="col-lg-12">
                <div class="price-calculate--box">
                    <ul class="d-flex flex-column gap--16">
                        <li class="d-flex justify-content-between">
                            <p class="price-details">@lang('Price Details') ({{ count((array) session('cart')) }}
                                @lang('Items'))</p>

                        </li>
                        <li class="d-flex justify-content-between">
                            <p>@lang('Total Amount')</p>
                            <p class="text--black fw--500 productFinalAmount">{{ $general->cur_sym . $total }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- price info -->

            <div class="col-lg-12">
                <div class="btn--wrap">
                    <a href="{{ route('get.checkout') }}"
                        class="btn btn--base btn--lg pill w--100 checkoutUrlBtn {{ count((array) session('cart')) <= 0 ? 'disabled' : '' }}">
                        @lang('Proceed to checkout')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- cart sidebar -->
