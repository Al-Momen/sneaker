@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <div class="row justify-content-between mb-2">
                <div class="col-xl-3 col-lg-3">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form--control form-control bg--white"
                                value="{{ request()->search }}" placeholder="@lang('Search by Trx')">
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
                            <option value="initial" {{ request()->status == 'initial' ? 'selected' : '' }}>@lang('Initiated')
                            </option>
                            <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>@lang('Pending')
                            </option>
                            <option value="approved" {{ request()->status == 'approved' ? 'selected' : '' }}>
                                @lang('Approved')
                            </option>
                            <option value="reject" {{ request()->status == 'reject' ? 'selected' : '' }}>@lang('Rejected')
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-md-12 mb-30">
                    <div class="card b-radius--10">
                        <div class="card-body p-0">
                            <div class="base--card bg--white border--none radius--8 tbl-wrap">
                                <table class="table table--responsive--lg">
                                    <thead>
                                        <tr>
                                            <th>@lang('TRX No')</th>
                                            <th class="text-center">@lang('Gateway')</th>
                                            <th class="text-center">@lang('Date')</th>
                                            <th class="text-center">@lang('Amount')</th>
                                            <th class="text-center">@lang('Conversion')</th>
                                            <th class="text-center">@lang('Status')</th>
                                            <th>@lang('Details')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items_table__body">
                                        @include('Template::components.user.tables.deposit_history_data')
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="pagination-wrapper"
                            class="pagination__wrapper py-4 {{ $deposits->hasPages() ? '' : 'd-none' }}">
                            @if ($deposits->hasPages())
                                {{ paginateLinks($deposits) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--base text--white"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script>
        (function($) {
            "use strict";
            let baseUrl = `{{ route('user.deposit.history', ':status') }}`;

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

    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.detailBtn', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush


