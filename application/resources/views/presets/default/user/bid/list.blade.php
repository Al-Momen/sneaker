@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <div class="row gy-4">
                <div class="col-md-12 mb-30">
                    <div class="card b-radius--10 ">
                        <div class="card-body p-0">
                            <div class="base--card bg--white border--none radius--8 tbl-wrap">
                                <table class="table table--responsive--lg">
                                    <thead>
                                        <tr>
                                            <th>#@lang('SI')</th>
                                            <th>@lang('Bidder Name')</th>
                                            <th>@lang('Bidding Price')</th>
                                            <th>@lang('Bidder')</th>
                                            <th>@lang('Created at')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items_table__body">
                                        @include('Template::components.user.tables.bid_list_data')
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="pagination-wrapper"
                            class="pagination__wrapper py-4 {{ $bids->hasPages() ? '' : 'd-none' }}">
                            @if ($bids->hasPages())
                                {{ paginateLinks($bids) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('User Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group userData mb-2">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>@lang('Full Name'):</strong>
                        <span class="fullname"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>@lang('Email'):</strong>
                        <span class="email"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>@lang('Phone'):</strong>
                        <span class="phone"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>@lang('Address'):</strong>
                        <span class="address w-50"></span>
                    </li>
                </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--base text--white"
                    data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.detailBtn', function() {
                var modal = $('#detailModal');
                var fullname = $(this).data('fullname');
                var email = $(this).data('email');
                var phone = $(this).data('phone');
                var address = $(this).data('address');
                modal.find('.fullname').text(fullname);
                modal.find('.email').text(email);
                modal.find('.phone').text(phone);
                modal.find('.address').text(address);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
