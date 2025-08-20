@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="explore-section py-50">
        <div class="container">
            <div class="row gy-4">

                <div class="col-xxl-3 col-xl-3 col-lg-3">
                    <div class="filter-box--wrap position-sticky">

                        <div class="filter-btn--wrapp">
                            <h6 class="text--black fw--600 fs--24 mb-0 filter--btn"><i class="fa-solid fa-sliders"></i> Filter
                            </h6>
                        </div>

                        <div class="filter--box h--100 base--radius">
                            <form>

                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none px-0">
                                    <div class="input--group search--input d-flex flex-nowrap position-relative">
                                        <input type="text" name="search" class="form--control" value=""
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
                                        <h6 class="title mb-0">CONDITION</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>


                                    <div class="filter-item--wrap">
                                        <div class="filter-item--content">
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--radio mb-2">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                        id="flexRadioDefault11">
                                                    <label class="form-radio-label" for="flexRadioDefault11">
                                                        Any Date
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--radio mb-2">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                        id="flexRadioDefault12">
                                                    <label class="form-radio-label" for="flexRadioDefault12">
                                                        In the last year
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--radio mb-2">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                        id="flexRadioDefault13">
                                                    <label class="form-radio-label" for="flexRadioDefault13">
                                                        In the last month
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--radio mb-2">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                        id="flexRadioDefault14">
                                                    <label class="form-radio-label" for="flexRadioDefault14">
                                                        In the last week
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--radio mb-2">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                        id="flexRadioDefault15">
                                                    <label class="form-radio-label" for="flexRadioDefault15">
                                                        In the last day
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">
                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">DEPARTMENT</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>

                                    <div class="filter-item--wrap">
                                        <div class="filter-item--content">
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="flexRadioDefault"
                                                        id="flexRadioDefault16">
                                                    <label class="form-check-label" for="flexRadioDefault16">
                                                        No Sales
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="flexRadioDefault"
                                                        id="flexRadioDefault17">
                                                    <label class="form-check-label" for="flexRadioDefault17">
                                                        Low
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="flexRadioDefault"
                                                        id="flexRadioDefault18">
                                                    <label class="form-check-label" for="flexRadioDefault18">
                                                        Medium
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="flexRadioDefault"
                                                        id="flexRadioDefault19">
                                                    <label class="form-check-label" for="flexRadioDefault19">
                                                        High
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault20">
                                                    <label class="form-check-label" for="flexRadioDefault20">
                                                        Top Sellers
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">


                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">BRANDS</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>


                                    <div class="filter-item--wrap">
                                        <div class="filter-item--content">
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault16">
                                                    <label class="form-check-label" for="flexRadioDefault16">
                                                        No Sales
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault17">
                                                    <label class="form-check-label" for="flexRadioDefault17">
                                                        Low
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault18">
                                                    <label class="form-check-label" for="flexRadioDefault18">
                                                        Medium
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault19">
                                                    <label class="form-check-label" for="flexRadioDefault19">
                                                        High
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault20">
                                                    <label class="form-check-label" for="flexRadioDefault20">
                                                        Top Sellers
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">


                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">SIZE</h6>
                                        <div class="icon-chevron is--down css-1gemut4"></div>
                                    </div>

                                    <div class="filter-item--wrap">
                                        <div class="filter-item--content">
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault16">
                                                    <label class="form-check-label" for="flexRadioDefault16">
                                                        No Sales
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault17">
                                                    <label class="form-check-label" for="flexRadioDefault17">
                                                        Low
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault18">
                                                    <label class="form-check-label" for="flexRadioDefault18">
                                                        Medium
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault19">
                                                    <label class="form-check-label" for="flexRadioDefault19">
                                                        High
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="form--check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="flexRadioDefault" id="flexRadioDefault20">
                                                    <label class="form-check-label" for="flexRadioDefault20">
                                                        Top Sellers
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div
                                    class="base--card filter--group radius--0 border-top--none border-right--none border-left--none p-0">

                                    <div
                                        class="title--wrap d-flex justify-content-between align-items-center position-relative">
                                        <h6 class="title mb-0">Price</h6>
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
                                                        <input type="range" class="range-min" min="10"
                                                            max="10000" value="10" step="100">
                                                        <input type="range" class="range-max" min="0"
                                                            max="10000" value="10000" step="100">
                                                    </div>
                                                </div>
                                                <div
                                                    class="price-input d-flex justify-content-between align-items-center gap--24">
                                                    <h6 class=" mb-0 text--black7">$<span
                                                            class="input-min min_price"></span>
                                                    </h6>
                                                    <h6 class=" mb-0 text--black7">$<span
                                                            class="input-max min_price"></span>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-9 col-xl-9 col-lg-9">
                    <div class="row mb-3">
                        <div class="col-lg-12 mb-4 d-flex justify-content-between align-items-start gap--16 flex-wrap">
                            <div class="filter-btn--wrap">
                                <h6 class="mb-0 fw--500">RESULTS <span class="text--black7">576</span></h6>
                            </div>
                            <div class="item-btn--wrap d-flex gap--12 flex-wrap">
                                <select class="form--control form-select">
                                    <option value="">NEW ARRIVALSsss</option>
                                    <option value="">NEW ARRIVALSSS</option>
                                    <option value="">NEW ARRIVALSS</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-2 gy-4">
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
