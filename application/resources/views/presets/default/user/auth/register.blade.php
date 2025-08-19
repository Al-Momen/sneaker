 @php
     $credentials = $general->socialite_credentials;
     $policyPages = getContent('policy_pages.element', false, null, true);
 @endphp

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

        <div class="row justify-content-end px-0 gx-0 w--100">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
                <div class="login-box w--100 d-flex flex-column justify-content-center">

                    <div class="logo--wrap d-flex justify-content-center align-items-center d-lg-none">
                        <a href="{{ route('home') }}">
                            <img src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png', '?' . time()) }}"
                                alt="@lang('Logo')">
                        </a>
                    </div>

                    <div class="content mb-5">
                        <h4 class="title mb-2 text--white">@lang('Create Your Account')</h4>
                        <p class="text-center text--white7">@lang('Welcome back! Select method to signup')</p>
                    </div>

                    <div class="form--wrap">
                        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                            @csrf
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <label class="form--label text--white">@lang('Username')</label>
                                        <input class="form--control text--white placeholder--white border--white7 checkUser"
                                            name="username" id="inputUserName" value="{{ old('username') }}"
                                            placeholder="@lang('User name')">
                                            <p class="text--danger mt-1 usernameExist"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <label class="form--label text--white">@lang('E-Mail Address')</label>
                                        <input class="form--control text--white placeholder--white border--white7 checkUser"
                                            value="{{ old('email') }}" type="email" id="inputEmail" name="email"
                                            placeholder="@lang('Email Address')">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <label class="form--label text--white">@lang('Country')</label>
                                        <select
                                            class="form-select form--control form-select--light form--select text--white placeholder--white border--white7"
                                            name="country" required="">
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                    value="{{ $country->country }}" data-code="{{ $key }}">
                                                    {{ __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <label class="form--label text--white">@lang('Phone Number')</label>
                                        <div class="input-group with--text mb-4">
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <span class="input-group-text mobile-code"></span>
                                            <input type="number" name="mobile" value=""
                                                class="form-control form--control checkUser text--white placeholder--white border--white7"
                                                required="" id="inputPhoneNumber" placeholder="@lang('Enter number')">
                                            <p class="text--danger mt-1 mobileExist"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <label class="form--label text--white">@lang('Password')</label>
                                        <div class="input--group position-relative">
                                            <input class="form--control text--white placeholder--white border--white7"
                                                id="password" placeholder="@lang('Password')" name="password"
                                                type="password" required>
                                            <div class="password-show-hide fas fa-eye-slash toggle-password-change text--white"
                                                data-target="password"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <label class="form--label text--white"
                                            for="confirm-password">@lang('Confirm Password')</label>
                                        <div class="input--group position-relative">
                                            <input class="form--control text--white placeholder--white border--white7"
                                                id="confirm-password" name="password_confirmation" type="password"
                                                placeholder="@lang('Confirm Password')">
                                            <div class="password-show-hide fas fa-eye-slash toggle-password-change text--white"
                                                data-target="confirm-password"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-captcha></x-captcha>

                            @if ($general->agree)
                                <div class="login-meta d-flex flex-wrap justify-content-between align-items-center mb-4"
                                    data-wow-delay="0.5s">
                                    <div class="d-flex flex-nowrap align-items-center">
                                        <div class="form--check">
                                            <input class="form-check-input border--white7" type="checkbox" name="agree"
                                                @checked(old('agree')) id="checkDefault" required>
                                            <label class="form-check-label text--white" for="checkDefault">
                                                @lang('I agree with')
                                                @foreach ($policyPages as $policy)
                                                    <a
                                                        class="text--white" href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                                        {{ __($policy->data_values->title) ?? '' }}
                                                    </a>
                                                    
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                   
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <button type="submit" class="btn btn--base btn--lg w-100 pill"
                                id="recaptcha">@lang('REGISTER')</button>
                        </form>
                    </div>

                    @if ($credentials->google->status == 1 || $credentials->facebook->status == 1)
                        <div class="social-option mb-4">
                            <div class="text position-relative d-flex justify-content-center mb-5">
                                <h6 class="text--white z--1 mb-0">@lang('OR')</h6>
                            </div>
                            <ul class="d-flex justify-content-center align-items-center gap--12">
                                @if ($credentials->google->status == 1)
                                    <li class="">
                                        <a href="{{ route('user.social.login', 'google') }}"
                                            class="w--100 h--100 d-flex justify-content-center align-items-center"><img
                                                src="{{ getImage(getFilePath('icon') . 'icon8.png') }}"
                                                alt="@lang('Google image')"></a>
                                    </li>
                                @endif
                                @if ($credentials->facebook->status == 1)
                                    <li class="">
                                        <a href="{{ route('user.social.login', 'facebook') }}"
                                            class="w--100 h--100 d-flex justify-content-center align-items-center"><img
                                                src="{{ getImage(getFilePath('icon') . 'icon9.png') }}"
                                                alt="@lang('Facebook image')"></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div class="text-center">
                        <p class="text--white7">@lang('You have any account?')
                            <a href="{{ route('user.login') }}" class="text--underline text--white">@lang('Login')
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{--=======-** Sign Up End **-=======--}}
    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center my-4">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .country-code .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }
    </style>
@endpush
@push('script-lib')
    <script src="{{ asset('assets/common/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').on('focus'function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').on('focusout', function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
