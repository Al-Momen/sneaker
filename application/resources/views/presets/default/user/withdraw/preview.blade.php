@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row gy-4 justify-content-center">
            <div class="col-md-8 justify-content-center">
                <div class="base--card bg--white border--none radius--8">
                    <form action="{{ route('user.withdraw.submit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            @php
                                echo $withdraw->method->description;
                            @endphp
                        </div>
                        <x-custom-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}"></x-custom-form>
                        @if (auth()->user()->ts)
                            <div class="form-group mb-3">
                                <label class="form--label mb-2">@lang('Google Authenticator Code')</label>
                                <input type="text" name="authenticator_code" class="form-control form--control" required>
                            </div>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="btn btn--base btn--lg w-100 mt-3">@lang('Save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
