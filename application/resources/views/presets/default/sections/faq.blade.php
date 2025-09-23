@php
    $faqSectionContent = getContent('faq.content', true);
    $faqSectionElements = getContent('faq.element', false, 12);
@endphp
<!-- < faq-section -->
<section class="faq-section pb-120 position-relative">
    <div class="container">
        <div class="row justify-content-between gy-4 mb-40">
            <div class="col-lg-4">
                <div
                    class="section-content-4 position-relative d-flex flex-wrap gap--8 justify-content-between align-items-center">
                    <h6 class="title heading--title wow animate__animated animate__fadeInUp text-start fs--28 fw--700 splite-text mb-0"
                        data-splitting data-wow-delay="0.2s">
                        {{ __($faqSectionContent->data_values->heading) }}
                    </h6>
                    <p>
                        {{ __(strLimit($faqSectionContent->data_values->short_description,200))?? '' }}
                    </p>
                </div>
            </div>

            <div class="col-md-4 d-flex justify-content-end align-items-center">
                <div class="btn--wrap text-start text-md-end">
                    <a href="{{ route('product') }}" class="view-all--btn text--base">@lang('View All')
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row gy-5 justify-content-center position-relative">
            <div class="col-lg-6">
                <div class="accordion custom--accordion1 accordion-flush" id="accordionFlushExample">
                    @foreach ($faqSectionElements ?? [] as $index => $item)
                  
                        @if ($index % 2 == 1)
                            <div class="accordion-item wow animate__fadeInUp animate__animated"
                                data-wow-delay="{{ 0.1 * $loop->iteration }}s">
                                <div class="accordion-header">
                                    <button class="accordion-button {{ $loop->iteration == 1 ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $item->id }}" aria-expanded="false"
                                        aria-controls="flush-collapse{{ $item->id }}">
                                        {{ __($item->data_values->question) }}
                                    </button>
                                </div>
                                <div id="flush-collapse{{ $item->id }}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        @php echo $item->data_values->answer; @endphp
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion custom--accordion1 accordion-flush" id="accordionFlushExample1">
                    @foreach ($faqSectionElements ?? [] as $index => $item)
                        @if ($index % 2 == 0)
                            <div class="accordion-item wow animate__fadeInUp animate__animated"
                                data-wow-delay="{{ 0.1 * $loop->iteration }}s">
                                <div class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $item->id }}" aria-expanded="false"
                                        aria-controls="flush-collapse{{ $item->id }}">
                                        {{ __($item->data_values->question) }}
                                    </button>
                                </div>
                                <div id="flush-collapse{{ $item->id }}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample1">
                                    <div class="accordion-body">
                                        @php echo $item->data_values->answer; @endphp
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!--  faq-section /> -->
