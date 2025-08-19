@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 mb-4 pb-4">
        <div class="col-xxl-3 col-xl-4 col-md-6">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.users.active') }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-users"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Active Users')</span>
                    <h5 class="number">{{ $widget['active_user'] }}</h5>
                </div>
                <span class="badge badge--primary position-absolute">
                    <i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['active_user_percent'] }}%</span>
            </a>
        </div>

        <div class="col-xxl-3 col-xl-4 col-md-6">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.users.kyc.verified') }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-regular fa-circle-user"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('KYC verified')</span>
                    <h5 class="number">{{  $widget['kyc_verified_user'] }}</h5>
                </div>
                <span class="badge badge--primary position-absolute">
                    <i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['kyc_verified_user_percent'] }}%</span>
            </a>
        </div>

        <div class="col-xxl-3 col-xl-4 col-md-6">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.users.mobile.verified') }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-arrow-right-to-bracket"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Mobile Verified')</span>
                    <h5 class="number">{{ $widget['mobile_verified_user'] }}</h5>
                </div>
                <span class="badge badge--primary position-absolute"><i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['mobile_verified_user_percent'] }}%</span>
            </a>
        </div>

        <div class="col-xxl-3 col-xl-4 col-md-6">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.users.email.verified') }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-user-slash"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Email Verified')</span>
                    <h5 class="number">{{ $widget['email_verified_user'] }}</h5>
                </div>
                <span class="badge badge--primary position-absolute">
                    <i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['email_verified_user_percent'] }}%
                </span>
            </a>
        </div>
    </div>

    <div class="row gy-4 justify-content-between mb-3 pb-4">
        <div class="col-xl-4 col-lg-6">
            <div class="d-flex flex-wrap justify-content-start">
                <form class="form-inline">
                    <div class="search-input--wrap position-relative">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search User')" value="{{ request()->search }}">
                        <button class="search--btn position-absolute" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-xl-4 col-lg-6">
            <div class="btn--wrap d-flex flex-wrap justify-content-lg-end gap-3">
                <div class="filter--dropdown dropdown userActionDropdown">
                    <a class="btn btn--primary outline dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                        href="#"><i class="fa-solid fa-user-gear"></i> @lang('Choose Action')</a>
                    <ul class="dropdown-menu box--shadow1">
                        <li>
                            <a class="dropdown-item bulk-action-btn"
                                data-action="banned"
                                data-title="@lang('Ban Users')"
                                data-message="@lang('Are you sure you want to ban the selected users?')"
                                href="javascript:void(0)"
                            >
                                <i class="fa-solid fa-ban"></i> @lang('Banned')
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item bulk-action-btn"
                                data-action="unbanned"
                                data-title="@lang('Unban Users')"
                                data-message="@lang('Are you sure you want to unban the selected users?')"
                                href="javascript:void(0)"
                            >
                                <i class="fa-solid fa-rotate-left"></i> @lang('Unbanned')
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item bulk-action-btn"
                                data-action="mobile_verified"
                                data-title="@lang('Mobile Verified Users')"
                                data-message="@lang('Are you sure you want to email verify the selected users?')"
                                href="javascript:void(0)"
                            >
                                <i class="fa-solid fa-mobile-screen-button"></i> @lang('Mobile Verified')
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item bulk-action-btn"
                                data-action="email_verified"
                                data-title="@lang('Email Verified Users')"
                                data-message="@lang('Are you sure you want to email verify the selected users?')"
                                href="javascript:void(0)"
                            >
                                <i class="fa-regular fa-envelope"></i> @lang('Email Verified')
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item bulk-action-btn"
                                data-action="kyc_verified"
                                data-title="@lang('KYC Verified Users')"
                                data-message="@lang('Are you sure you want to verify KYC the selected users?')"
                                href="javascript:void(0)"
                            >
                                <i class="fa-solid fa-user-shield"></i> @lang('KYC Verified')
                            </a>
                        </li>
                    </ul>
                </div>


                <button type="button" class="btn btn--danger d-none" id="clearSelectionBtn">@lang('Clear Selection')</button>



            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @include('admin.components.tables.user_data')
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="pagination-wrapper"  class="pagination__wrapper py-4 {{ $items->hasPages() ? '' : 'd-none' }}">
                    @if ($items->hasPages())
                    {{ paginateLinks($items) }}
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="bulkActionModalLabel" class="modal-title">@lang('Confirm Action')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <p id="bulkActionMessage" class="mb-0">@lang('Are you sure you want to perform this action on the selected users?')</p>

                    <div class="row action_form">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="button" class="btn btn--primary" id="confirmBulkAction">@lang('Yes')</button>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('breadcrumb-plugins')
    <a class="btn btn--primary" href="{{ route('admin.users.create') }}"><i class="fa-solid fa-user-plus"></i> @lang('Add User')</a>
@endpush



@push('script')
    <script>
        (function ($) {
            'use strict';

            $('.userActionDropdown').hide();
            let selectedBulkAction = null;

            let action = null;
            let message = null;

            $(document).on('click', '.bulk-action-btn', function (e) {
                e.preventDefault();

                if (!selectedUserIds.length) {
                    return alert('No users selected');
                }

                selectedBulkAction = $(this).data('action');
                const title = $(this).data('title');
                const message = $(this).data('message');

                $('#bulkActionModalLabel').text(title);
                $('#bulkActionMessage').text(message);

                if(selectedBulkAction == 'banned') {
                    $('.action_form').html(`
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="fw-bold mt-2">@lang('Reason for Action')</label>
                                <textarea name="message" maxlength="255" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                    `);
                }else{
                    $('.action_form').html('');
                }

                action = selectedBulkAction;

                const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
                modal.show();
            });

            $('#confirmBulkAction').on('click', function () {
                if (!selectedBulkAction) return;
                let message = $('textarea[name=message]').val() ?? null;

                $.ajax({
                    url: '{{ route("admin.users.bulk.action") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        action: selectedBulkAction,
                        user_ids: selectedUserIds,
                        message: message,
                        action: action
                    },
                    success: function (response) {
                        selectedUserIds = [];
                        localStorage.removeItem('selectedUserIds');
                        window.location.href = "{{ route('admin.users.all') }}";
                        notify('success', response.message);
                    },
                    error: function () {
                        alert('Something went wrong.');
                    }
                });

                const modalEl = document.getElementById('bulkActionModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            });

            let selectedUserIds = JSON.parse(localStorage.getItem('selectedUserIds')) || [];

            $(document).on('change', '.form-check-input.action--check', function () {
                const userId = $(this).val();

                if ($(this).is(':checked')) {
                    if (!selectedUserIds.includes(userId)) {
                        selectedUserIds.push(userId);
                    }
                } else {
                    selectedUserIds = selectedUserIds.filter(id => id !== userId);
                }

                localStorage.setItem('selectedUserIds', JSON.stringify(selectedUserIds));

                toggleUserActionDropdown();
            });

            function toggleUserActionDropdown() {
                if (selectedUserIds.length > 0) {
                    $('.userActionDropdown').show(200);
                        $('#clearSelectionBtn').removeClass('d-none');
                } else {
                    $('.userActionDropdown').hide(200);
                    $('#clearSelectionBtn').addClass('d-none');
                }
            }

            function restoreCheckedStates() {
                $('.form-check-input.action--check').each(function () {
                    const userId = $(this).val();
                    if (selectedUserIds.includes(userId)) {
                        $(this).prop('checked', true);
                    }
                });

                toggleUserActionDropdown();
            }

            $(document).ready(function () {
                restoreCheckedStates();
            });

            $('#clearSelectionBtn').on('click', function () {
                selectedUserIds = [];
                localStorage.removeItem('selectedUserIds');
                $('.form-check-input.action--check').prop('checked', false);
                toggleUserActionDropdown();
            });

            let baseUrl = `{{ route('admin.users.all', ':status') }}`;

            $('#table__data__filter').on('change', function () {
                let statusMap = {
                    '1': 'approved',
                    '2': 'pending',
                    '3': 'rejected',
                    '0': 'initiated',
                    'all': 'all'
                };

                let status = $(this).val();
                let url = baseUrl.replace(':status', statusMap[status] ?? 'all');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: $('#search-box').val()
                    },
                    beforeSend: function () {
                        $('#items_table__body').html('<tr><td colspan="5">Loading...</td></tr>');
                    },
                    success: function (response) {
                        $('#items_table__body').html(response.html);
                        $('.card-footer').html(response.pagination);

                        if ($.trim(response.pagination) === '') {
                            $('#pagination-wrapper').addClass('d-none');
                        } else {
                            $('#pagination-wrapper').removeClass('d-none');
                        }
                    },
                    error: function () {
                        alert('Failed to load filtered tickets.');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
