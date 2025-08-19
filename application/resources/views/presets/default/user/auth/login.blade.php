 @php
     $credentials = $general->socialite_credentials;
 @endphp
 @extends($activeTemplate . 'layouts.frontend')
 @section('content')
     <!-- login section -->
     <section class="login-section position-relative bg--img"
         style="background-image: url({{ asset(getImage(getFilePath('shape') . 'login-bg.png')) }});">
         <div class="login-content--wrap position-absolute d-flex flex-column justify-content-start align-items-start gap--48 d-none d-lg-block">
             <div class="logo--wrap">
                 <a href="{{ route('home') }}">
                     <img src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png', '?' . time()) }}"
                         alt="@lang('Logo')">
                 </a>
             </div>
             <div class="content">
                 <h4 class="title mb-3 text--white">@lang('WELCOME BACK TO THE HUB')</h4>
                 <p class="text--white7">
                     @lang("Log in to unlock your full sneaker experience — buy, sell, bid, track orders, and stay connected with the culture. Everything you need, all in one place.")
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
                         <h4 class="title mb-2 text--white">@lang('Login')</h4>
                         <h4 class="title mb-2 text--white d-none">@lang('WELCOME BACK TO THE HUB')</h4>

                         <p class="text-center text--white7 d-none">@lang('Welcome back! Select method to login')</p>
                     </div>

                     <div class="form--wrap">
                         <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                             @csrf
                             <div class="mb-4 form-group">
                                 <label class="mb-2 form--label text--white">@lang('Username or Email')</label>
                                 <input class="form--control text--white border--white7 placeholder--white" name="username"
                                     value="{{ old('username') }}" placeholder="@lang('Username or Email')">
                             </div>
                             <div class="mb-4">
                                 <div class="form-group">
                                     <label class="mb-2 form--label text--white">@lang('Password')</label>
                                     <div class="input--group position-relative">
                                         <input class="form--control text--white border--white7 placeholder--white"
                                             id="password" placeholder="@lang('Password')" name="password"
                                             type="password">
                                         <div class="password-show-hide fas fa-eye-slash toggle-password-change text--white"
                                             data-target="password"></div>
                                     </div>
                                 </div>
                             </div>

                             <div class="mb-4 form-group">
                                 <x-captcha></x-captcha>
                             </div>

                             <div class="login-meta d-flex flex-wrap justify-content-between align-items-center mb-4"
                                 data-wow-delay="0.5s">
                                 <div class="d-flex flex-nowrap align-items-center">
                                     <div class="form--check">
                                         <input class="form-check-input border--white7" id="1" type="checkbox"
                                             name="remember" id="remember" value=""
                                             {{ old('remember') ? 'checked' : '' }}>
                                     </div>
                                     <div class="condition-text">
                                         <label for="remember" class="text--white remambar-text">@lang('Remember me')
                                         </label>
                                     </div>
                                 </div>
                                 <a href="{{ route('user.password.request') }}" class="text--underline text--white">
                                     @lang('Forgot Password?')</a>
                             </div>

                             <button class="btn btn--base btn--lg w-100 pill custom-btn" id="recaptcha">@lang('Sign In')</button>
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
                         <p class="text--white7">@lang('Don\'t have any account?')
                             <a href="{{ route('user.register') }}" class="text--underline text--white">@lang('Create Account')</a>
                         </p>
                     </div>
                 </div>
             </div>
         </div>
     </section>
 @endsection
