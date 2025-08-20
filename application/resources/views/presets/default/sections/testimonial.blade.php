 @php
     $testimonialSectionContent = getContent('testimonial.content', true);
     $testimonialSectionElements = getContent('testimonial.element', false, 6);
 @endphp
 <!-- < testimonial-section -->
 <section class="testimonial--section py-120 position-relative">
     <div class="container">
         <div class="row justify-content-between gy-4 mb-40">
             <div class="col-xl-4 col-lg-6 col-md-8">
                 <div
                     class="section-content-4 position-relative d-flex flex-wrap gap--8 justify-content-between align-items-center">
                     <h6 class="title heading--title wow animate__animated animate__fadeInUp text-start fs--28 fw--700 splite-text mb-0"
                         data-splitting data-wow-delay="0.2s">
                         {{ __($testimonialSectionContent->data_values->heading) ?? '' }}
                     </h6>
                     <p>
                         {{ __(strLimit($testimonialSectionContent->data_values->short_description, 100)) ?? '' }}
                     </p>
                 </div>
             </div>

             <div class="col-md-4 d-flex justify-content-end align-items-center">
                 <div class="testimonial-slider--icon position-relative">
                     <div class="swiper-button-next"></div>
                     <div class="swiper-button-prev"></div>
                 </div>
             </div>
         </div>

         <div class="row justify-content-center">
             <div class="col-lg-12">
                 <div class="swiper testimonial-slider">
                     <div class="swiper-wrapper">
                         @foreach ($testimonialSectionElements as $item)
                             <div class="swiper-slide">
                                 <div
                                     class="testimonial-card position-relative radius--8 d-flex justify-content-center">
                                     <div class="quote-img--wrap position-absolute">
                                         <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                             viewBox="0 0 48 48" fill="none">
                                             <path fill-rule="evenodd" clip-rule="evenodd"
                                                 d="M15.0912 28.6178C13.8752 30.3218 11.5776 32.1538 8.44964 33.6818C7.63684 34.0786 8.00164 35.3074 8.90084 35.1938C15.4736 34.3618 19.7328 31.4834 22.0608 27.2034C22.9248 25.6098 23.4688 23.9154 23.7584 22.1858C23.9504 21.045 24 20.189 24 19.2002C24 17.0785 23.1572 15.0436 21.6569 13.5433C20.1566 12.043 18.1218 11.2002 16 11.2002C13.8783 11.2002 11.8435 12.043 10.3432 13.5433C8.8429 15.0436 8.00004 17.0785 8.00004 19.2002C8.00004 23.6178 11.6384 27.2034 15.8208 27.2034C15.6 27.8082 15.5472 27.9794 15.0912 28.6178ZM32.6912 28.6178C31.4752 30.3218 29.1776 32.1538 26.0496 33.6818C25.2368 34.0786 25.6016 35.3074 26.5008 35.1938C33.0736 34.3618 37.3328 31.4834 39.6608 27.2034C40.5248 25.6098 41.0688 23.9154 41.3584 22.1858C41.5504 21.045 41.6 20.189 41.6 19.2002C41.6 17.0785 40.7572 15.0436 39.2569 13.5433C37.7566 12.043 35.7218 11.2002 33.6 11.2002C31.4783 11.2002 29.4435 12.043 27.9432 13.5433C26.4429 15.0436 25.6 17.0785 25.6 19.2002C25.6 23.6178 29.2384 27.2034 33.4208 27.2034C33.2 27.8082 33.1472 27.9794 32.6912 28.6178Z" />
                                         </svg>
                                     </div>

                                     <div
                                         class="item--wrap d-flex justify-content-start align-items-center position-relative">
                                         <div class="content-wrap">
                                             <p class="description text-start fs--16 fw--500">
                                                 {{ __(strLimit($item->data_values->description,100)) ?? '' }}
                                             </p>
                                             <div
                                                 class="user--info d-flex justify-content-start align-items-center gap--4 flex-wrap">
                                                 <p class="fs--16 text-start fw--600 mb-0">
                                                        {{ ($item->data_values->name) ?? '' }}
                                                 </p>
                                             </div>
                                         </div>

                                         <div class="user--thumb flex-shrink-0 position-relative d-flex">
                                             <img class="fit--img" src="{{getImage(getFilePath('testimonial').$item->data_values->image)}}" alt="@lang('User image')" >
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @endforeach
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <!--  testimonial-section /> -->
