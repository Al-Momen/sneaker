@extends($activeTemplate . 'layouts.frontend')
@php
    $checkoutTotal = 0;
@endphp
@section('content')
    <section class="checkout--section py-50">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xxl-8 col-xl-6 col-lg-6">
                    <div class="base--card card--bg border-0 radius--12">
                        <div class="input-info">
                            @guest
                                <p class="fs--24 mb-4 fw--600">@lang('Already have an account?') <a class="text--base  fs--20 fw--500"
                                        href="{{ route('user.login') }}">@lang('Login')</a>
                                </p>
                            @endguest

                            <h3 class="fs--24 mb-4 fw--600">@lang('Billing Information')</h3>
                            <form action="{{ route('user.product.payment') }}" method="POST" class="needs-validation"
                                novalidate>
                                @csrf
                                <input type="hidden" name="method_code">
                                <input type="hidden" name="currency">
                                <div class="row gy-4 mb-4">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label required">@lang('First Name')</label>
                                            <input type="text" class="form--control" id="firstname" name="firstname"
                                                placeholder="@lang('First Name')"
                                                value="{{ old('firstname', optional(auth()->user())->firstname) }}"
                                                {{ auth()->check() ? '' : 'required' }} readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label required">@lang('Last Name')</label>
                                            <input type="text" class="form--control" id="lastname" name="lastname"
                                                placeholder="@lang('Last Name')"
                                                value="{{ old('lastname', optional(auth()->user())->lastname) }}"
                                                {{ auth()->check() ? '' : 'required' }} readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-form mb-3">
                                            <label class="form--label required">@lang('Country')</label>
                                            <select id="country" name="country"
                                                class="form-select form--select form--control" disabled>
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}"
                                                        value="{{ $country->country }}"
                                                        {{ $key == auth()->user()->country_code ? 'selected' : '' }}
                                                        data-code="{{ $key }}">
                                                        {{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="input-form mb-3">
                                                <label class="form--label required">@lang('Country Code')</label>
                                                <div class="input-group country-code">
                                                    <span class="input-group-text mobile-code bg--base text--white">
                                                    </span>
                                                    <input type="hidden" name="mobile_code">
                                                    <input type="hidden" name="country_code">

                                                    <input type="number" class="checkUser form--control " name="mobile"
                                                        value="{{ old('mobile', optional(auth()->user())->mobile) }}"
                                                        required readonly>
                                                </div>
                                                <small class="text-danger mobileExist"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label required">@lang('Email')</label>
                                            <input type="email" class="form--control" id="your-email" name="email"
                                                placeholder="@lang('Email Address')"
                                                value="{{ old('email', optional(auth()->user())->email) }}"
                                                {{ auth()->check() ? '' : 'required' }} readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-form mb-3">
                                            <label class="form--label required">@lang('Shipping Charge')</label>
                                            <select name="shipping" class="form-select form--control shippingCharge">
                                                <option value="">@lang('Select Shipping')</option>
                                                @foreach ($shippings as $key => $shipping)
                                                    <option data-location="{{ $shipping->location }}"
                                                        data-charge="{{ $shipping->charge }}" value="{{ $shipping->id }}">
                                                        {{ __($shipping->location) }}
                                                        ({{ $general->cur_sym . showAmount($shipping->charge) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            @php
                                                $user = auth()->user();
                                                $address = $user && $user->address ? $user->address : null;
                                                $fullAddress = collect([
                                                    $address?->address,
                                                    $address?->state,
                                                    $address?->zip,
                                                    $address?->city,
                                                ])
                                                    ->filter()
                                                    ->implode(', ');
                                            @endphp
                                            <label class="form--label required">@lang('Address')</label>
                                            <textarea class="form--control w-100" id="your-address" placeholder="@lang('Address')" name="address">{{ $fullAddress }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="checkout__title">
                                            <h3>@lang('Payments')</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout__single">
                                            <select class="form-select form--control required" name="gateway" required>
                                                <option value="">@lang('Select One')</option>
                                                <option value="balance">@lang('Account Balance')
                                                    ({{ showAmount(auth()->user()->balance) }})</option>
                                                @foreach ($gatewayCurrency as $data)
                                                    <option value="{{ $data->method_code }}"
                                                        data-gateway="{{ $data }}">{{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="checkout__single">
                                            <div class="input-group">
                                                <input type="number" step="any" name="amount"
                                                    class="form-control form--control paymentPrice" value=""
                                                    disabled required>
                                                <span
                                                    class="input-group-text bg--base text--white border-0">{{ $general->cur_text }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn--wrap d-flex justify-content-end gap--20">
                                        <button type="submit" class="btn btn--base btn--lg w-100">
                                            @lang('Submit')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-lg-6">
                    <div class="base--card card--bg border-0 radius--12">
                        <h3 class="fs--24 fw--500 text--black mb-4">@lang('YOUR CART')</h3>
                        <div class="checkout-items checkoutItemsContainer">
                            @isset($cartItems)
                                @forelse (array_reverse($cartItems) as $product)
                                    @php $checkoutTotal += $product['price'] * $product['quantity'] @endphp
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
                                                        <input class="count-input count"
                                                            data-product_id="{{ $product['id'] }}" type="number"
                                                            id="quantityInput" value="{{ $product['quantity'] }}">
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
                                    <div class="text-muted text-center noProduct" colspan="100%"
                                        data-label="@lang('cart-item')">
                                        @lang('No product added to cart')
                                    </div>
                                @endforelse
                            @endisset

                            <div class="col-lg-12">
                                <div class="price-calculate--box">
                                    <p class="price-details mb-3">@lang('Product Count') ({{ count((array) session('cart')) }}
                                        @lang('Items'))</p>

                                    <ul class="d-flex flex-column gap--16">
                                        <li class="coreProductPrice d-none"></li>
                                        <li class="d-flex justify-content-between">
                                            <p>@lang('Shipping Charge')</p>
                                            <p class="text--black fw--500 shipping-charge">{{ $general->cur_sym }}0.00</p>
                                        </li>

                                        <li class="d-flex justify-content-between">
                                            <p>@lang('Product Amount')</p>
                                            <p class="text--black fw--500 productFinalAmount">
                                                {{ $general->cur_sym . $checkoutTotal }}</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="price-calculate--box2">
                                    <ul class="d-flex flex-column gap--16">
                                        <li class="d-flex justify-content-between">
                                            <p>@lang('Total Amount')</p>
                                            <p class="text--black fw--500 totalFinalAmount">
                                                {{ $general->cur_sym . $checkoutTotal }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('select[name=gateway]').on('change', function() {
                if (!$('select[name=gateway]').val()) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=gateway] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                if (resource.method.crypto == 1) {
                    var toFixedDigit = 8;
                    $('.crypto_currency').removeClass('d-none');
                } else {
                    var toFixedDigit = 2;
                    $('.crypto_currency').addClass('d-none');
                }
                $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if (amount <= 0) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                $('.preview-details').removeClass('d-none');
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                var payable = parseFloat((parseFloat(amount) + parseFloat(charge))).toFixed(2);
                $('.payable').text(payable);
                var final_amo = (parseFloat((parseFloat(amount) + parseFloat(charge))) * rate).toFixed(
                    toFixedDigit);
                $('.final_amo').text(final_amo);
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement =
                        `<span class="fw-bold">@lang('Conversion Rate')</span> <span><span  class="fw-bold">1 {{ __($general->cur_text) }} = <span class="rate">${rate}</span>  <span class="base-currency">${resource.currency}</span></span></span>`;
                    $('.rate-element').html(rateElement)
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                } else {
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }
                $('.base-currency').text(resource.currency);
                $('.method_currency').text(resource.currency);
                $('input[name=currency]').val(resource.currency);
                $('input[name=method_code]').val(resource.method_code);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input', function() {
                $('select[name=gateway]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });


        })(jQuery);
    </script>
    <script>
        (function($) {
            "use strict";
            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

        })(jQuery);
    </script>

    <script>
        $(document).ready(function() {
            'use strict';
            let oldShipping = "{{ old('shipping') }}";

            if (oldShipping) {
                let $shippingSelect = $('.shippingCharge');
                $shippingSelect.val(oldShipping);
                $shippingSelect.trigger('change');
            }


            let oldGateway = "{{ old('gateway') }}";
            if (oldGateway) {
                let $gatewaySelect = $('select[name=gateway]');
                $gatewaySelect.val(oldGateway);
                $gatewaySelect.trigger('change');
            }

        });
    </script>
@endpush
