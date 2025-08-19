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
                        <h4 class="title mb-2 text--white">@lang('Verify Email Address')</h4>
                        <h4 class="title mb-2 text--white d-none">@lang('WELCOME BACK TO THE HUB')</h4>
                        <p class="text-center text--white7 d-none">@lang('Welcome back! Select method to login')</p>
                    </div>

                    <div class="form--wrap">
                        <div class="verification-code-wrapper">
                            <div class="verification-area">
                                
                                <form action="{{ route('user.verify.email') }}" method="POST" class="submit-form">
                                    @csrf
                                    <p class="verification-text text--white mb-3">@lang('A 6 digit verification code sent to your email address'):
                                        {{ showEmailAddress(auth()->user()->email) }}</p>

                                    @include($activeTemplate . 'components.verification_code')

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn--base btn--lg pill w-100">@lang('Save')</button>
                                    </div>

                                    <div class="mb-3">
                                        <p class="text--white">
                                            @lang('If you don\'t get any code'), <a class="text--white text--underline" href="{{ route('user.send.verify.code', 'email') }}">
                                                @lang('Try again')</a>
                                        </p>

                                        @if ($errors->has('resend'))
                                            <small class="text-danger d-block">{{ $errors->first('resend') }}</small>
                                        @endif
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
