@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <div class="row justify-content-between mb-2">
                <div class="col-xl-3 col-lg-3">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form--control form-control bg--white"
                                value="{{ request()->search }}" placeholder="@lang('Search by order number')">
                            <button type="submit" class="input-group-text bg--base text-white border-0">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-xl-2 col-lg-6">
                    <div class="d-flex justify-content-end">
                        <select id="status-filter" name="status" class="form--control form-select bg--white">
                            <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>@lang('All')
                            </option>
                            <option value="approved" {{ request()->status == 'approved' ? 'selected' : '' }}>
                                @lang('Approved')
                            </option>
                            <option value="processing" {{ request()->status == 'processing' ? 'selected' : '' }}>
                                @lang('Processing')
                            </option>
                            <option value="delivered" {{ request()->status == 'delivered' ? 'selected' : '' }}>
                                @lang('Delivered')
                            </option>
                             <option value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>
                                @lang('Completed')
                            </option>
                            <option value="reject" {{ request()->status == 'reject' ? 'selected' : '' }}>@lang('Canceled')
                            </option>
                        </select>
                    </div>
                </div>
            </div>
         
            <div class="row gy-4">
                <div class="col-md-12 mb-30">
                    <div class="card b-radius--10 ">
                        <div class="card-body p-0">
                            <div class="base--card bg--white border--none radius--8 tbl-wrap">
                                <table class="table table--responsive--lg">
                                    <thead>
                                        <tr>
                                            <th>@lang('SI')</th>
                                            <th>@lang('Order Number')</th>
                                            <th>@lang('Full Name')</th>
                                            <th>@lang('Total Price')</th>
                                            <th>@lang('Email')</th>
                                            <th>@lang('Mobile')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items_table__body">
                                        @include('Template::components.user.tables.get_order_data')
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="pagination-wrapper"
                            class="pagination__wrapper py-4 {{ $orders->hasPages() ? '' : 'd-none' }}">
                            @if ($orders->hasPages())
                                {{ paginateLinks($orders) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            let baseUrl = `{{ route('user.orders.get', ':status') }}`;

            $('#status-filter').on('change', function() {
                let status = $(this).val();
                let url = baseUrl.replace(':status', status);

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: $('#search-box').val()
                    },
                    beforeSend: function() {
                        $('#items_table__body').html(
                            '<tr><td colspan="8" class="text-center">Loading...</td></tr>');
                    },
                    success: function(response) {
                        $('#items_table__body').html(response.html);
                        $('.card-footer').html(response.pagination);

                        if ($.trim(response.pagination) === '') {
                            $('#pagination-wrapper').addClass('d-none');
                        } else {
                            $('#pagination-wrapper').removeClass('d-none');
                        }
                    },
                    error: function(response) {
                        alert('Failed to load filtered tickets.');
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
