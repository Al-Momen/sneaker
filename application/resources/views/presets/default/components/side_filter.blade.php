<div class="col-xxl-3 col-xl-3 col-lg-3">
    <div class="filter-box--wrap position-sticky">
        <div class="filter-btn--wrapp">
            <h6 class="text--black fw--600 fs--24 mb-0 filter--btn"><i class="fa-solid fa-sliders"></i>
                @lang('Filter')
            </h6>
        </div>

        <div class="filter--box h--100 base--radius">
            <div
                class="base--card filter--group radius--0 border-top--none border-right--none border-left--none px-0">
                <div class="input--group search--input d-flex flex-nowrap position-relative">
                    <input type="text" name="search" class="form--control"
                        placeholder="Search Sneakers" id="search">
                    <button class="search-btn">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </div>
            <div
                class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0 show">
                <div
                    class="title--wrap d-flex justify-content-between align-items-center position-relative">
                    <h6 class="title mb-0">@lang('BRANDS')</h6>
                    <div class="icon-chevron is--down css-1gemut4"></div>
                </div>
                <div class="filter-item--wrap">
                    <div class="filter-item--content">
                        @foreach($brands as $key => $brand)
                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                <div class="form--check mb-2">
                                    <input class="form-check-input" type="checkbox" name="brands[]"
                                        id="brand-{{ $key }}" value="{{ $brand }}">
                                    <label class="form-check-label" for="brand-{{ $key }}">
                                        {{ ucfirst($brand) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div
                class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">
                <div
                    class="title--wrap d-flex justify-content-between align-items-center position-relative">
                    <h6 class="title mb-0">@lang('CATEGORIES')</h6>
                    <div class="icon-chevron is--down css-1gemut4"></div>
                </div>
                <div class="filter-item--wrap">
                    <div class="filter-item--content">
                        @foreach($categories as $cat)
                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                <div class="form--check mb-2">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                        id="cat-{{ $cat->id }}" value="{{ $cat->id }}">
                                    <label class="form-check-label" for="cat-{{ $cat->id }}">
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div
                class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">
                <div
                    class="title--wrap d-flex justify-content-between align-items-center position-relative">
                    <h6 class="title mb-0">@lang('SIZE')</h6>
                    <div class="icon-chevron is--down css-1gemut4"></div>
                </div>
                <div class="filter-item--wrap">
                    <div class="filter-item--content">
                        @foreach($sizes as $size)
                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                <div class="form--check mb-2">
                                    <input class="form-check-input" type="checkbox" name="sizes[]"
                                        id="size-{{ $size->id }}" value="{{ $size->id }}">
                                    <label class="form-check-label" for="size-{{ $size->id }}">
                                        @lang('Size'): {{ $size->size }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">
                <div
                    class="title--wrap d-flex justify-content-between align-items-center position-relative">
                    <h6 class="title mb-0">@lang('Price')</h6>
                    <div class="icon-chevron is--down css-1gemut4"></div>
                </div>

                <div class="filter-item--wrap">
                    <div class="filter-item--content">
                        <div class="range-slider-box pt-4">
                            <div class="slider-box mb-4 pb-2">
                                <div class="sliderr">
                                    <div class="progresss"></div>
                                </div>
                                <div class="range-input">
                                    <input type="range" class="range-min" min="10" max="10000"
                                        value="10" step="50">
                                    <input type="range" class="range-max" min="0" max="10000"
                                        value="10000" step="50">
                                </div>
                            </div>
                            <div
                                class="price-input d-flex justify-content-between align-items-center gap--24">
                                <h6 class=" mb-0 text--black7">{{$general->cur_sym}}<span
                                        class="input-min min_price"></span>
                                </h6>
                                <h6 class=" mb-0 text--black7">{{$general->cur_sym}}<span class="input-max min_price"></span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    'use strict';

    $(document).on('change keyup', "input[name='brands[]'], input[name='categories[]'], input[name='sizes[]'], #search, .range-min, .range-max, select.ordering", function () {
        filterProducts();
    });

    function filterProducts() {
        let brands = $("input[name='brands[]']:checked").map(function(){ return $(this).val(); }).get();
        let categories = $("input[name='categories[]']:checked").map(function(){ return $(this).val(); }).get();
        let sizes = $("input[name='sizes[]']:checked").map(function(){ return $(this).val(); }).get();
        let ordering = $("select.ordering").val();

        $.ajax({
            url: "{{ route('products.filter') }}",
            method: "get",
            data: {
                search: $("#search").val(),
                brands: brands,
                categories: categories,
                sizes: sizes,
                min_price: $(".range-min").val(),
                max_price: $(".range-max").val(),
                ordering: ordering
            },
            success: function(response){
                $("#product-list").html(response.html);
                $('html, body').animate({ scrollTop: 0 }, 500);
                $('.product-count').text(response.pCount)
            },
            error: function(){
                console.log('error')
            }
        });
    }
});
</script>
@endpush
