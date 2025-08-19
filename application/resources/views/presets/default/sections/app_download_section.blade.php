@php
    $appDownloadContent = getContent('app_download_section.content', true);
@endphp

<section class="callout--section py-120">
    <div class="container">
        <div class="content--wrap radius--12 position-relative bg--img parallax--bg"
            style="background-image: url({{getImage(getFilePath('app_download_section').$appDownloadContent->data_values->background_image)}})">
            <div class="bg--element position-absolute top_image_bounce">
                <img src="{{getImage(getFilePath('app_download_section').$appDownloadContent->data_values->top_image)}}" alt="@lang('image')" >
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="title--wrap d-flex flex-column gap--24">
                        <h6 class="title text--white fs--32 w--60 mb-0 wow animate__animated animate__fadeInUp splite-text"
                            data-splitting data-wow-delay="0.2s">
                            {{ __($appDownloadContent->data_values->heading) ?? '' }}
                        </h6>
                        <p class="description text--white7 fs--16 wow animate__animated animate__fadeInUp"
                            data-wow-delay="0.3s">
                            {{ __(strLimit($appDownloadContent->data_values->short_description,3050)) ?? '' }}
                        </p>
                    </div>

                    <div class="appstore-link--wrap d-flex gap--20 wow animate__animated animate__fadeInUp"
                        data-wow-delay="0.4s">
                        <a href="{{$appDownloadContent->data_values->app_image_one_link}}" class="thumb--wrap radius--8 overflow-hidden">
                            <img src="{{getImage(getFilePath('app_download_section').$appDownloadContent->data_values->app_image_one)}}" alt="@lang('image')" >
                        </a>

                        <a href="{{$appDownloadContent->data_values->app_image_two_link}}" class="thumb--wrap radius--8 overflow-hidden">
                            <img src="{{getImage(getFilePath('app_download_section').$appDownloadContent->data_values->app_image_two)}}" alt="@lang('image')" >
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
