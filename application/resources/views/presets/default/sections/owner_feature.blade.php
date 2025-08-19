    @php
        $ownerFeatureContent = getContent('owner_feature.content', true);
        $products = App\Models\Product::with(['category', 'firstImage', 'wishlists', 'sizes'])
            ->where('status', 1)
            ->where('type', 2)
            ->where('start_date','<', now())
            ->where('end_date','>', now())
            ->orderByDesc('review_count')
            ->take(8)
            ->get();
         $cartItem = session('cart');
    @endphp

    <section class="featured--section product--section pt-120">
        <div class="container">
            <div class="row justify-content-between gy-4 mb-40">
                <div class="col-xl-4 col-lg-6 col-md-8">
                    <div
                        class="section-content-4 position-relative d-flex flex-wrap gap--8 justify-content-between align-items-center">
                        <h6 class="title heading--title wow animate__animated animate__fadeInUp text-start fs--28 fw--700 splite-text mb-0"
                            data-splitting data-wow-delay="0.2s">
                            {{ __($ownerFeatureContent->data_values->heading) }}
                        </h6>
                        <p>
                            {{ __($ownerFeatureContent->data_values->short_details) }}
                        </p>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-end align-items-center">
                    <div class="btn--wrap text-start text-md-end">
                        <a href="{{ route('product') }}" class="view-all--btn text--base">@lang('View All')
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-lg-12 position-relative">
                    <div class="swiper featured--slider">
                        <div class="swiper-wrapper">
                            @forelse($products as $product)
                                <div class="swiper-slide">
                                    @include($activeTemplate . 'components.product')
                                </div>
                            @empty
                                <h4 class="text-center">@lang('No product found')</h4>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div class="featured-slider--icon">
                    <div class="swiper-button-next featured-next"></div>
                    <div class="swiper-button-prev featured-prev"></div>
                </div>
            </div>
        </div>

    </section>
