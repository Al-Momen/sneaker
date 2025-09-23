@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="explore-section py-50">
        <div class="container">
            <div class="row gy-4">
                @include($activeTemplate . 'components.side_filter')
                <div class="col-xxl-9 col-xl-9 col-lg-9">
                    <div class="row mb-3">
                        <div class="col-lg-12 mb-4 d-flex justify-content-between align-items-start gap--16 flex-wrap">
                            <div class="filter-btn--wrap">
                                <h6 class="mb-0 fw--500">@lang('RESULTS') <span class="text--black7 product-count">{{$products->count()}}</span></h6>
                            </div>
                            <div class="item-btn--wrap d-flex gap--12 flex-wrap">
                                <select class="form--control form-select ordering">
                                    <option selected value="">@lang('Select Ordering')</option>
                                    <option value="latest">@lang('Latest')</option>
                                    <option value="low_price">@lang('Low Price')</option>
                                    <option value="high_price">@lang('Hight Price')</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-2 gy-4" id="product-list" style="min-height: 300px;">
                        @forelse($products as $product)
                            <div class="col-xl-4 col-md-6">
                                @include($activeTemplate . 'components.product')
                            </div>
                        @empty
                            <h4 class="text-center">@lang('No product found')</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection