<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    @include('includes.seo')
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/common/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/common/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/splitting.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    @stack('style')
    @stack('style-lib')
    <link rel="stylesheet"
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $general->base_color }}
        &secondColor={{ $general->secondary_color }}">
</head>

<body>
   
    @include($activeTemplate . 'components.loader')

    @if (!isBreadcrumbRoute())
        @include($activeTemplate . 'components.breadcrumb')
    @endif

    @if (!isPageRoute())
        @include($activeTemplate . 'components.header')
    @endif

    @if (request()->route()->uri == '/')
        @include($activeTemplate . 'components.banner')
    @endif

    @yield('content')

    @if (!isPageRoute())
        @include($activeTemplate . 'components.footer')
    @endif

    @include($activeTemplate . 'components.cookie')

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('assets/common/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/splitting.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/gsap.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/gsap-scroll-trigger.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')
    @stack('script')
    @include('includes.notify')
    @include('includes.plugins')
    @include($activeTemplate . 'components.add_to_cart_js')

    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.lang-change', function() {
                const lang = $(this).data('lang');
                window.location.href = "{{ route('home') }}/change/" + lang;
            });

            $(document).on('click', '.lang-change', function() {
                const lang = $(this).data('lang');
                window.location.href = "{{ route('home') }}/change/" + lang;
            });
            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

        })(jQuery);
    </script>

</body>

</html>
