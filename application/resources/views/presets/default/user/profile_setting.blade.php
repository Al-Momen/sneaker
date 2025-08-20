@extends($activeTemplate . 'layouts.master')
@section('content')
    <form class="register" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-4">
                <div class="dashboard_profile-card">
                    <div class="user-profile text-center">
                        <div class="dashboard_profile_wrap">
                            <div class="profile_photo mb-2">
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                                    id="profileImage" alt="@lang('user-image')">
                                <div class="photo_upload">
                                    <label for="photo_upload"><i class="fa-regular fa-image"></i></label>
                                    <input id="photo_upload" type="file" name="image" class="upload_file">
                                </div>
                            </div>
                            <div class="profile-details">
                                <ul>
                                    <li>
                                        <p class="user-name text--white">{{ '@' . $user->username }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="contact-info">
                        <div class="info-wrap">
                            <div class="info">
                                <i class="fa-regular fa-envelope"></i>
                                <p>@lang('Email Address')</p>
                            </div>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                    <div class="contact-info">
                        <div class="info-wrap">
                            <div class="info">
                                <i class="fa-solid fa-phone"></i>
                                <p>@lang('Mobile Number')</p>
                            </div>
                            <span>{{ $user->mobile }}</span>
                        </div>
                    </div>
                    <div class="contact-info">
                        <div class="info-wrap border-bottom--none">
                            <div class="info">
                                <i class="fa-solid fa-location-dot"></i>
                                <p>@lang('Address')</p>
                            </div>
                            <span>
                                @foreach ($user->address ?? [] as $item)
                                    {{ $item }},
                                @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 justify-content-center">
                <div class="base--card bg--white border--none radius--8">
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <h4 class="mb-1">@lang('Personal Information')</h4>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form--label mb-2">
                                    @lang('First Name')</label>
                                <input type="text" class="form--control" id="name"  name="firstname" value="{{ $user->firstname }}" placeholder="@lang('First Name')" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="lastname" class="form--label mb-2">@lang('Last Name')</label>
                                <input type="text" class="form--control" id="lastname" name="lastname"
                                    value="{{ $user->lastname }}"placeholder="@lang('Last Name')" required >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form--label mb-2">@lang('Address')</label>
                                <input type="text" class="form--control" placeholder="@lang('Address')" name="address"
                                    value="{{ $user->address->address }}" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mb-2 form--label">@lang('State')</label>
                                <input type="text" class="form--control" name="state"
                                    value="{{ $user->address->state }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mb-2 form--label">@lang('Zip Code')</label>
                                <input type="text" class="form--control" name="zip"
                                    value="{{ $user->address->zip }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mb-2 form--label">@lang('City')</label>
                                <input type="text" class="form--control" name="city"
                                    value="{{ $user->address->city }}">
                            </div>
                        </div>
                       <div class="col-lg-12">
                            <div class="form-group">
                                <label class="mb-2 form--label">@lang('Country')</label>
                                <input class="form--control" value="{{ $user->address->country }}" disabled>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn--base btn--lg w-100">
                                @lang('Submit')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('script')
    <script>
        'use strict';
        const fileInput = document.getElementById('photo_upload');
        const profileImage = document.getElementById('profileImage');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
