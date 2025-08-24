@php
    $contactSection = getContent('contact_us.content', true);
    $socialIcons = getContent('social_icon.element', false);
    $pages = App\Models\Menu::with(['items', 'menuItems'])
        ->where('slug', 'footer-menu')
        ->first();
    $policyLinks = getContent('footer_policy_term_links.element', false, null, true);
    $subscriptionSectionContent = getContent('subscribe.content', true);
@endphp


<!-- Footer Start Here -->
<footer class="footer-area overflow--hidden z--1">
    <div class="footer-top py-100">
        <div class="container">
            <div class="row gy-5 justify-content-between">
                <div class="col-xl-6 col-sm-6">
                    <div class="footer-item">
                        <div class="footer-item--logo">
                            <a href="{{ route('home') }}" class="footer-logo-normal" id="footer-logo-normal">
                                <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                                    alt="@lang('logo')">
                            </a>
                        </div>

                        <ul class="footer-menu d-flex flex-wrap gap--28">
                           
                            @foreach ($pages->items as $k => $data)
                                @if ($data->link_type == 2)
                                    <li class="nav-item">
                                        <a href="{{ $data->url ?? '' }}" target="_blank">{{ __($data->title) }}</a>
                                    </li>
                                @else
                                    <li
                                        class="menu--item">
                                        <a href="{{ route('pages', [$data->url]) }}"
                                            class="menu--link fs--16 fw--500"><i class="fa-solid fa-arrow-right-long"></i>{{ __($data->title) }}</a>
                                    </li>
                                @endif
                            @endforeach

                         
                        </ul>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item--title">
                            {{ __($subscriptionSectionContent->data_values->heading) ?? '' }}
                        </h5>

                        <div class="subscribe-box mb-3">
                            <form action="{{ route('subscribe') }}" method="POST">
                                @csrf
                                <input class="form--control footer-input pill w--70" name="email" type="text"
                                    placeholder="@lang('Email Address')">
                                <button class="btn btn--base btn--lg pill" type="submit">
                                    @lang('SUBSCRIBE')
                                </button>
                            </form>

                        </div>
                        <p class="footer-item--desc fs--14 text--black7">
                            {{ __($subscriptionSectionContent->data_values->short_description) ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Top End-->

    <!-- bottom Footer -->
    <div class="bottom-footer">
        <div class="container">
            <div class="row text-center gy-2">
                <div class="col-md-6">
                    <div class="bottom-footer-text d-flex justify-content-start align-items-center pt-4 pb-3">
                        <div class="text--white copyright mb-0">
                            @php echo $contactSection->data_values->website_footer; @endphp
                        </div>
                    </div>
                </div>

                <div
                    class="col-md-6 d-flex justify-content-md-end justify-content-center align-items-center pb-3 pd-md-0">
                    <ul class="social-list position-relative d-flex gap--12">
                        @foreach ($socialIcons as $index => $item)
                            <a href="{{ $item->data_values->url }}"
                                class="social-list__link icon-wrapper {{ $index == 1 ? 'active' : '' }}">
                                <div class="icon">
                                    @php echo $item->data_values->social_icon; @endphp
                                </div>
                            </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ==================== Footer End Here ==================== -->

<div class="scroll-top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
            style="
            transition: stroke-dashoffset 10ms linear 0s;
            stroke-dasharray: 307.919, 307.919;
            stroke-dashoffset: 197.514;
          ">
        </path>
    </svg>
</div>
<!-- footer -->
