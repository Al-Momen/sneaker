@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 justify-content-between mb-3 pb-3">
        <div class="col-xl-4 col-lg-6">
            <div class="d-flex flex-wrap justify-content-start">
                <form class="form-inline">
                    <div class="search-input--wrap position-relative">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search color')..."
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
                                    <th>@lang('SI')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @include('admin.components.tables.color_data')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pagination-wrapper" class="pagination__wrapper py-4 {{ $colors->hasPages() ? '' : 'd-none' }}">
                    @if ($colors->hasPages())
                        {{ paginateLinks($colors) }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('breadcrumb-plugins')
        <a href="javascript:void(0)" class="btn btn-sm btn--primary addColor">@lang('Add New')</a>
    @endpush

    {{-- Add METHOD MODAL --}}
    <div id="addColorModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add Color')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.color.store') }}" class="edit-route" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>@lang('Color')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Color Code')</label>
                                    <div class="image-group">
                                        <input type="color" name="code" id="inputColorCode" class="form-control mb-2"
                                            required placeholder="@lang('Color Code')">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group mt-3">
                                    <label class="fw-bold">@lang('Status')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="status">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary btn-global w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editColorModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">@lang('Update Size')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>@lang('Size')</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" value="{{ old('name') }}"
                                            name="name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Color Code')</label>
                                    <div class="image-group">
                                        <input type="color" name="code" id="inputColorCode"
                                            class="form-control mb-2" required placeholder="@lang('Color Code')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group mt-3">
                                    <label class="fw-bold">@lang('Status')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="color_status">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addColor').on('click', function() {
                $('#addColorModel').modal('show');
            });

            $(document).on('click', '.editColorBtn', function() {
                var modal = $('#editColorModal');
                let url = $(this).data('action');
                let base = "{{ url('/') }}";

                $('#editForm').attr('action', url);
                modal.find('input[name="name"]').val($(this).data('name'));
                modal.find('input[name="code"]').val($(this).data('code'));

                if ($(this).data('status') == 1) {
                    modal.find('input[name="color_status"]').prop('checked', true);
                }

                if ($(this).data('status') == 0) {
                    modal.find('input[name="color_status"]').prop('checked', false);
                }
                modal.modal('show');
            });

            let baseUrl = `{{ route('admin.color.index', ':status') }}`;

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
                        $('#items_table__body').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
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
