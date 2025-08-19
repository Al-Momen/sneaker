@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- login section -->
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
                        <h4 class="title mb-2 text--white">@lang('Reset Password')</h4>
                        <h4 class="title mb-2 text--white d-none">@lang('WELCOME BACK TO THE HUB')</h4>
                        <p class="text-center text--white7 d-none">@lang('Welcome back! Select method to login')</p>
                    </div>

                    <div class="form--wrap">
                        <div class="verification-code-wrapper">
                            <div class="verification-area">
                                <form method="POST" action="{{ route('user.password.update') }}">
                                    @csrf
                                    <p class="verification-text text--white mb-3">
                                        @lang("Your account is verified successfully. Now you can change your password. Please enter a strong password and don't share it with anyone.")
                                    </p>

                                    <input type="hidden" name="email" value="{{ $email }}">
                                    <input type="hidden" name="token" value="{{ $token }}">

                
                                    <label class="mb-2 form--label text--white">@lang('Password')</label>
                                    <input type="password" class="form--control text--white border--white7 placeholder--white" name="password" required>
                                    @if ($general->secure_password)
                                        <div class="input-popup">
                                            <p class="error lower">@lang('1 small letter minimum')</p>
                                            <p class="error capital">@lang('1 capital letter minimum')</p>
                                            <p class="error number">@lang('1 number minimum')</p>
                                            <p class="error special">@lang('1 special character minimum')</p>
                                            <p class="error minimum">@lang('6 character password')</p>
                                        </div>
                                    @endif

                                    <div class="mb-4 mt-3 form-group">
                                        <label class="mb-2 form--label text--white">@lang('Confirm Password')</label>
                                        <input type="password"
                                            class="form--control text--white border--white7 placeholder--white"
                                            name="password_confirmation" required>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit"
                                            class="btn btn--base btn--lg pill w-100">@lang('SUBMIT')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text--white7">@lang('Don\'t have any account?')
                            <a href="{{ route('user.register') }}"
                                class="text--underline text--white">@lang('Create Account')</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/common/js/secure_password.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').on('focus', function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').on('focusout', function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif
        })(jQuery);
    </script>
@endpush
