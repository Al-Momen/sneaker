    @php
        $banner = getContent('banner.content', true);
    @endphp


    <!-- < Hero Section -->
    <section class="hero position-relative">
        <div class="hero-thumb--wrap position-absolute">
            <div class="shoe-thumb--one">
                <img src="{{ getImage(getFilePath('banner') . '/' . $banner->data_values->banner_image_one) }}" alt="@lang('Banner Image')">
            </div>
            <div class="shoe-thumb--two position-absolute">
                <img src="{{ getImage(getFilePath('banner') . '/' . $banner->data_values->banner_image_two) }}" alt="@lang('Banner Image')">
            </div>
        </div>


        <div class="hero-circle-btn--wrap" bis_skin_checked="1">
            <a href="javascript:void(0)" class="text--btn-wrap position-absolute d-block">
                <div class="circle-button" bis_skin_checked="1">
                    <div class="rotate-circle" bis_skin_checked="1">
                        <svg class="textcircle" viewBox="0 0 500 500">
                            <defs>
                                <path id="textcircle" d="M250,250 m-150,0 a150,150 0 1,1 300,0a150,150 0 1,1 -300,0">
                                </path>

                            </defs>
                            <text>
                                <textPath href="#textcircle" textLength="900">
                                    {{ __($banner->data_values->spinner_text) }}
                                </textPath>
                            </text>
                        </svg>
                    </div>
                    <div class="icon d-flex justify-content-center align-items-center" bis_skin_checked="1">
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="hero-thumb--shadow position-absolute">
            <img src="{{asset('assets/images/frontend/banner/banner-bg-shadow.png')}}" alt="....">
        </div>

        <div class="hero-content--wrap">
            <div class="container position-relative">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="title--wrap w--70">
                            <h2 class="title text-start fw--700 wow animate__animated animate__fadeInUp splite-text"
                                data-splitting data-wow-delay="0.2s">
                                     {{ __($banner->data_values->heading) }}
                            </h2>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="bg-title--wrap position-relative">
                            <h1 class="bg--title">{{$general->site_name}}</h1>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 justify-content-end">
                    <div class="col-xl-5 col-lg-7 col-md-12 d-flex align-items-center justify-content-center">
                        <div
                            class="hero--content position-relative d-flex flex-column justify-content-end align-items-end">
                            <p class="subtitle text-end fs--16  wow animate__animated animate__fadeInUp"
                                data-wow-delay="0.3s">{{ Str::limit(__($banner->data_values->short_details ?? ''), 200) }}</p>
                            <div class="btn--wrap d-flex justify-content-end align-items-end wow animate__animated animate__fadeInUp"
                                data-wow-delay="0.4s">
                                <a href="{{url($banner->data_values->button_link)}}" class="btn btn--base btn--lg pill">{{ __($banner->data_values->button_title) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="hero-ending--point"></div>
    </section>
    <!--  Hero Section />-->
