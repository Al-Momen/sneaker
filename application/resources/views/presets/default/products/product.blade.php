@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @if (!request()->search && !Route::is('search') && !Route::is('category.product'))
        <section class="categorey-section pb-100">
            <div class="container-fluid">
                <div class="categorey-section__content">
                    <div class="categorey-section__left">
                        <h3 class="catagorey-section__title m-0 text--black fs--48 fw--700">@lang('Shop by Categories')</h3>
                    </div>
                </div>
                <div class="row gy-4">
                    @foreach ($categories as $item)
                        <div class=" col-xl-2 col-lg-3 col-md-4 col-sm-4">
                            <div class="categorey-section__item position-relative ">
                                <a href="{{ route('category.product', [slug($item->name), $item->id]) }}"
                                    class="categorey-section__thumb ">
                                    <img class="radius--36 custom-image fit--img"
                                        src="{{ getImage(getFilePath('category') . '/' . $item->image) }}"
                                        alt="category-image">
                                </a>
                                <div class="categorey-section__sale-btn">
                                    <a class="w--100 btn white-btn pill"
                                        href="{{ route('category.product', [slug($item->name), $item->id]) }}">{{ __($item->name) }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <section class="filter-product whats-product--section">
        <div class="container-fluid">
            <div class="stop-items d-flex">
                <h2 class="stop-items__title text--black fs--36 fw--600">@lang('Shop')</h2>
                <p class="stop-items__desc text--grey fs--20 fw--500">{{ $products->count() }} @lang('Items')</p>
            </div>

            @include($activeTemplate . 'components.searching')

            <div class="row gy-4 justify-content-center pb-4">

                @include($activeTemplate . 'components.product')


                @if ($products->hasPages())
                    <div class="col-lg-12 explore-item--wrap mb-4">
                        <div class="row justify-content-center gy-4">
                            {{ $products->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
