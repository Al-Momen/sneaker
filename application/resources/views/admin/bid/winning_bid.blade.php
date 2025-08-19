@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-md-12 mb-30">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>#@lang('SI')</th>
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Bidding Price')</th>
                                    <th>@lang('Bidder')</th>
                                    <th>@lang('Created at')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">

                                @include('admin.components.tables.winning_bid_list_data')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pagination-wrapper" class="pagination__wrapper py-4 {{ $winningBids->hasPages() ? '' : 'd-none' }}">
                    @if ($winningBids->hasPages())
                        {{ paginateLinks($winningBids) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
