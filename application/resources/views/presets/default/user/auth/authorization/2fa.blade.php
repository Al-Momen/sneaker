@extends($activeTemplate . 'layouts.frontend')
<section class="login-section position-relative bg--img"
    style="background-image: url({{ asset(getImage(getFilePath('shape') . 'login-bg.png')) }});">
    <div
        class="login-content--wrap position-absolute d-flex flex-column justify-content-start align-items-start gap--48 d-none d-lg-block">
        <div class="logo--wrap">
            <a href="{{ route('home') }}">
                <img src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png', '?' . time()) }}"
                    alt="@lang('Logo')">
            </a>
        </div>
        <div class="content">
            <h4 class="title mb-3 text--white">@lang('WELCOME BACK TO THE HUB')</h4>
            <p class="text--white7">
                @lang('Log in to unlock your full sneaker experience — buy, sell, bid, track orders, and stay connected with the culture. Everything you need, all in one place.')
            </p>
        </div>
    </div>

    <div class="row justify-content-end w--100 px-0 gx-0">
        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
            <div class="login-box w--100 d-flex flex-column justify-content-center">
                <div class="logo--wrap d-flex justify-content-center align-items-center d-lg-none">
                    <a href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png', '?' . time()) }}"
                            alt="@lang('Logo')">
                    </a>
                </div>

                <div class="content mb-5">
                    <h4 class="title mb-2 text--white">@lang('2FA Verification')</h4>
                    <h4 class="title mb-2 text--white d-none">@lang('WELCOME BACK TO THE HUB')</h4>
                    <p class="text-center text--white7 d-none">@lang('Welcome back! Select method to login')</p>
                </div>

                <div class="form--wrap">
                    <div class="verification-code-wrapper">
                        <div class="verification-area">
                            <form action="{{ route('user.go2fa.verify') }}" method="POST" class="submit-form">
                                @csrf
                       
                                @include($activeTemplate . 'components.verification_code')
                                <div class="mb-3">
                                    <button type="submit"
                                        class="btn btn--base btn--lg pill w-100">@lang('Save')</button>
                                </div>
                              
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('script')
 <script>
        (function($) {
            "use strict";
            $('#code').on('input change', function() {
                var xx = document.getElementById('code').value;
                $(this).val(function(index, value) {
                    value = value.substr(0, 7);
                    return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
                });
            });
        })(jQuery)
    </script>
@endpush
