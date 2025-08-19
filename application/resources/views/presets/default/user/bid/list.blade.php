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
                                            <th>@lang('Email')</th>
                                            <th>@lang('Phone')</th>
                                            <th>@lang('Address')</th>
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