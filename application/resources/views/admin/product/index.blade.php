@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 justify-content-between mb-3 pb-3">
        <div class="col-xl-4 col-lg-6">
            <div class="d-flex flex-wrap justify-content-start">
                <form class="form-inline">
                    <div class="search-input--wrap position-relative">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search product')..."
                            value="{{ request()->search ?? '' }}">
                        <button class="search--btn position-absolute"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-2 col-lg-6">
            <div class="d-flex justify-content-end">
                <select id="status-filter" name="status" class="form-control form-select bg--transparent outline">
                    <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>@lang('All')</option>
                    <option value="enable" {{ request()->status == 'enable' ? 'selected' : '' }}>@lang('Enable')</option>
                    <option value="disable" {{ request()->status == 'disable' ? 'selected' : '' }}>@lang('Disable')
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-md-12 mb-30">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Regular Price')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @include('admin.components.tables.product_data')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pagination-wrapper" class="pagination__wrapper py-4 {{ $products->hasPages() ? '' : 'd-none' }}">
                    @if ($products->hasPages())
                        {{ paginateLinks($products) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>
@endsection
@push('breadcrumb-plugins')
    <a href="{{ route('admin.product.create') }}" class="btn btn-sm btn--primary">
        <i class="fa-solid fa-plus"></i>
            @lang('Add New')
        </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let baseUrl = `{{ route('admin.product.index', ':status') }}`;

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
                        $('#items_table__body').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
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
