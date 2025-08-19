@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-md-8 justify-content-center">
            <div class="base--card bg--white border--none radius--8">
                <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <x-custom-form identifier="act" identifierValue="kyc"></x-custom-form>
                    <button type="submit" class="btn btn--base btn--lg w-100 mt-3">@lang('Save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
