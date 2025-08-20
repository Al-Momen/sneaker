@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-xl-4 col-lg-4">
            @if (!auth()->user()->ts)
                <div class="base--card radius--8">
                    <h6 class="mb-5">@lang('Two Factor Authenticator')</h6>
                    <p class="mb-3">
                        @lang('Use the QR code or setup key on your Google Authenticator app to add your account.')</p>
                    <div class="qr-img mb-4 mx-auto text-center">
                        <img class="mx-auto" src="{{ $qrCodeUrl }}"alt="@lang('QR Code')">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form--label" for="key">@lang('Setup Key')</label>
                        <div class="input-group">
                            <input type="text" value="{{ $secret }}" class="form-control form--control referralURL"
                                readonly="" id="key" >
                            <button type="button" class="input-group-text btn btn--base copytext" id="copyBoard">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-8 col-lg-8">
            @if (auth()->user()->ts)
                <div class="base--card radius--8">
                    <h5>@lang('Disable 2FA Security')</h5>
                    <form action="{{ route('user.twofactor.disable') }}" method="POST">
                        @csrf
                        <input type="hidden" name="key" value="{{ $secret }}">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form--label required" for="code">@lang('Google Authenticator OTP')</label>
                                <input type="text" class="form--control" name="code" required="" id="code">
                            </div>
                        </div>
                        <button type="submit" class="btn btn--base btn-lg w-100">@lang('Submit')</button>
                    </form>
                </div>
            @else
                <div class="base--card radius--8">
                    <h5>@lang('Enable 2FA Security')</h5>
                    <form action="{{ route('user.twofactor.enable') }}" method="POST">
                        @csrf
                        <input type="hidden" name="key" value="{{ $secret }}">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form--label required" for="code">@lang('Google Authenticator OTP')</label>
                                <input type="text" class="form-control form--control" name="code" id="code" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--base btn--lg w-100">@lang('Submit')</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
