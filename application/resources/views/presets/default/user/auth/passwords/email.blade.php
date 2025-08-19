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
                        <h4 class="title mb-2 text--white">{{ __($pageTitle) }}</h4>
                        <h4 class="title mb-2 text--white d-none">@lang('WELCOME BACK TO THE HUB')</h4>
                        <p class="text-center text--white7 d-none">@lang('Welcome back! Select method to login')</p>
                    </div>

                    <div class="form--wrap">
                        <form method="POST" action="{{ route('user.password.email') }}">
                            @csrf
                              <div class="mb-4 form-group">
                                <label class="mb-2 form--label text--white">@lang('Email or Username')</label>
                                <input class="form--control text--white border--white7 placeholder--white" name="value"
                                    value="{{ old('value') }}" placeholder="@lang('Email or Username')" required autofocus="off">
                            </div>

                            <button type="submit" class="btn btn--base btn--lg w-100 pill custom-btn">@lang('Send')</button>
                        </form>
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
