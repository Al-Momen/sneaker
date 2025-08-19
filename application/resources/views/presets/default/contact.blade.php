@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contactSection = getContent('contact_us.content', true);
        $socialIcons = getContent('social_icon.element', false,'',true);
        $user = auth()->user();
    @endphp

    <section class="contact-section pt-200 pb-120">
        <div class="container">
            <div class="contact-box--wrap bg--img radius--12 overflow-hidden"
                style="background-image: url({{asset(getImage(getFilePath('contact').$contactSection->data_values->background_image))}})">
                <div class="row gy-5 justify-content-center">
                    <div class="col-xxl-6 col-xl-6 col-lg-12">
                        <div class="d-flex flex-column gap--36 justify-content-between h--100">
                            <div class="contact--content">
                                <h6 class="text--white fs--32">
                                    {{ __($contactSection->data_values->heading) }}
                                </h6>
                                <p class="text--white7">
                                    {{ __($contactSection->data_values->contact_header_description) }}
                                </p>
                            </div>

                            <div class="contact--info d-flex justify-content-between align-items-center w-100">
                                <div>
                                    <h6 class="text--white">@lang('CONTACT US')</h6>
                                    <ul class="social-list position-relative d-flex gap--12">
                                        <li class="social-list--item">
                                            <a href="mailto:{{ $contactSection->data_values->email }}"
                                                class="social-list__link icon-wrapper">
                                                <div class="icon">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="social-list--item">
                                            <a href="tel:{{ $contactSection->data_values->contact_number }}"
                                                class="social-list__link icon-wrapper active">
                                                <div class="icon"><i class="fa-solid fa-phone"></i></div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div>
                                    <h6 class="text--white">@lang('FOLLOW US')</h6>

                                    <ul class="social-list position-relative d-flex gap--12">
                                        @foreach ($socialIcons as $index => $item)
                                            <li class="social-list--item">
                                                <a href="{{ $item->data_values->url }}"
                                                    class="social-list__link icon-wrapper {{ $index == 1 ? 'active' : '' }}">
                                                    <div class="icon">
                                                        @php echo $item->data_values->social_icon; @endphp
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6 col-lg-12">
                        <div class="base--card bg--white radius--12">
                            <div class="content--wrap mb-4">
                                <h6 class="text--base">@lang('GET IN TOUCH')</h6>
                                <h6 class="fs--32 mb-1 fw--600 text--black">@lang('Have any queries')?</h6>
                            </div>
                            <form method="post" action="#" class="verify-gcaptcha">
                                @csrf
                                <div class="row gy-3 mb-4">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Username')</label>
                                            <input class="form--control" placeholder="@lang('Enter your username')" id="Username"
                                                name="name"
                                                value="@if(auth()->user()){{auth()->user()->fullname}}@else{{ old('name') }}@endif"
                                                @if(auth()->user())readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Email Address')</label>
                                            <input class="form--control" placeholder="@lang('Enter your email address')" id="email"
                                                name="email"
                                                value="@if(auth()->user()){{ auth()->user()->email}}@else{{ old('email')}}@endif"
                                                @if(auth()->user())readonly @endif required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gy-3 mb-4">

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Subject')</label>
                                            <input class="form--control" type="text" placeholder="@lang('Write your subject')"
                                                id="subject" name="subject" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 form-group">
                                    <label class="form--label">@lang('Message')</label>
                                    <textarea class="form--control" name="message" placeholder="@lang('Enter your message here')..." id="message" {{ old('message') }}
                                        required></textarea>
                                </div>
                                <button class="btn btn--base btn--lg w-100 pill" id="recaptcha">
                                    @lang('Send Message')
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
