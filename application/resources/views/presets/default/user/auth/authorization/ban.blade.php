{{-- @extends($activeTemplate .'layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-end">
                <a href="{{ route('home') }}" class="fw-bold home-link"> <i class="fa-solid fa-arrow-left-long"></i> @lang('Go to Home')</a>
            </div>
            <div class="card custom--card">
                <div class="card-body">
                    <h3 class="text-center text-danger">@lang('You are banned')</h3>
                    <p class="fw-bold mb-1">@lang('Reason'):</p>
                    <p>{{ $user->ban_reason }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}



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
                    <h4 class="title mb-2 text--white">@lang('Banned')</h4>
                    <h4 class="title mb-2 text--white d-none">@lang('WELCOME BACK TO THE HUB')</h4>
                    <p class="text-center text--white7 d-none">@lang('Welcome back! Select method to login')</p>
                </div>

                <div class="form--wrap">
                     <h3 class="text-center text-danger">@lang('You are banned')</h3>
                    <p class="fw-bold mb-1 text--white">@lang('Reason'):</p>
                    <p class="text--white">{{ $user->ban_reason }}</p>
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

