@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form id="productCreateForm" action="{{ route('admin.product.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card radius--base br--solid p-16">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="name" class="form-label">@lang('Name')</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            class="form-control" placeholder="@lang('Product Name')" required>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="category" class="form-label">@lang('Category')</label>
                                        <select class="form-control form-select" name="category" required>
                                            <option selected disabled>@lang('Select Category')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category') == $category->id ? 'selected' : '' }}>
                                                    {{ __($category->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="type" class="form-label">@lang('Product Type')</label>
                                    <div class="input-group">
                                        <select class="form-control form-select" name="type" required>
                                            <option value="" selected>@lang('Select Type')</option>
                                            <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>
                                                @lang('New Product')
                                            </option>
                                            <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>
                                                @lang('Auction Product')
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-4">
                                    <label for="brand_name" class="form-label">@lang('Brand')</label>
                                    <div class="form-group">
                                        <input type="text" name="brand_name" id="brand_name"
                                            value="{{ old('brand_name') }}" class="form-control form--control"
                                            placeholder="@lang('Brand Name')" required>
                                    </div>
                                </div>


                                <div class="col-lg-4 mb-4 productPrice {{ old('type') != null && old('type') == 2 ? 'd-none' : '' }}">
                                    <label for="price" class="form-label">@lang('Price')</label>
                                    <div class="form-group">
                                        <input type="number" name="price" id="price" value="{{ old('price') }}"
                                            class="form-control form--control" placeholder="@lang('Product Price')">
                                    </div>
                                </div>

                                <div
                                    class="col-lg-4 mb-4 discountInput {{ old('type') != null && old('type') == 2 ? 'd-none' : '' }}">
                                    <label for="discount" class="form-label">@lang('Discount')(%)</label>
                                    <div class="input-group">
                                        <input type="number" name="discount" id="discount" min="0" max="100"
                                            step="any" value="{{ old('discount') }}" class="form-control"
                                            placeholder="@lang('Product Discount')">
                                        <span class="input-group-text mobile-code">%</span>
                                    </div>
                                </div>

                                <div class="col-lg-12">

                                    <div class="row auctionInput {{ old('type') != 2 ? 'd-none' : '' }}">
                                        <div class="col-lg-4 mb-4">
                                            <label for="min-price" class="form-label">@lang('Starting Price')</label>
                                            <div class="input-group">
                                                <input type="number" name="min_price" id="min-price" min="0"
                                                    max="100" step="any" value="{{ old('min_price') }}"
                                                    class="form-control" placeholder="@lang('Product Min Price')">

                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="start_date" class="form-label">@lang('Start Date')</label>
                                            <div class="form-group">
                                                <input type="datetime-local" name="start_date" id="start_date"
                                                    value="{{ old('start_date') }}" class="form-control"
                                                    placeholder="@lang('Auction Start Date')">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="end_date" class="form-label">@lang('End Date')</label>
                                            <div class="form-group">
                                                <input type="datetime-local" name="end_date" id="end_date"
                                                    value="{{ old('end_date') }}" class="form-control"
                                                    placeholder="@lang('Auction End Date')">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="mb-4 p-4 rounded border">
                                        <div
                                            class="d-flex flex-wrap justify-content-start align-items-center {{ old('type') != null && old('type') == 2 ? 'd-none' : '' }} isColor">
                                            <div class="form-group col-md-2 col-sm-6 mb-4">
                                                <label class="fw-bold">@lang('Is Color')</label>
                                                <label class="switch m-0">
                                                    <input type="checkbox" value="0" class="toggle-switch"
                                                        name="is_color">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 normalImage">
                                            <div class="form-group">
                                                <div class="text-end">
                                                    <button type="button" class="btn btn--primary btn--sm addFile">
                                                        <i class="fa fa-plus"></i> @lang('Add New')
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="file-upload">
                                                            <label class="form-label">@lang('Images')</label>
                                                            <input type="file" name="images[]" id="inputImages"
                                                                class="form-control form--control mb-2"
                                                                placeholder="Images" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="fileUploadsContainer">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group colorImage d-none">
                                            <label>@lang('Color Name')</label>
                                            <small class="ms-2 mt-2">@lang('Separate color name by')
                                                <code>,</code>(@lang('comma')) @lang('or')
                                                <code>@lang('enter')</code>
                                                @lang('key')</small>
                                            <select name="code_id[0]" class="form-control select2-auto-tokenize"
                                                multiple="multiple">
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}" data-name="{{ $color->name }}"
                                                        data-code="{{ $color->code }}">
                                                        {{ __($color->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="colorRowsContainer"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12 size_and_quantity {{ old('type') != null && old('type') == 2 ? 'd-none' : '' }}">
                                    <div class="mb-4 p-4 rounded border">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="text-end">
                                                    <button type="button"
                                                        class="btn btn--primary btn--sm mb-3 addSizeAndQuantityTemplate">
                                                        <i class="fa fa-plus"></i> @lang('Add New')
                                                    </button>
                                                </div>
                                                {{-- Default Row --}}
                                                <div class="row size_and_quantity_row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="size"
                                                                class="form-label">@lang('Size')</label>
                                                            <select class="form-control size-select form-select"
                                                                name="size_quantity[0][size]" required>
                                                                <option value="0" selected disabled>@lang('Select Size')
                                                                </option>
                                                                @foreach ($sizes as $size)
                                                                    <option value="{{ $size->id }}"
                                                                        data-name="{{ $size->name }}">
                                                                        {{ __($size->size) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="quantity"
                                                                class="form-label">@lang('Quantity')</label>
                                                            <input type="text" name="size_quantity[0][quantity]"
                                                                id="quantity" value="{{ old('quantity') ?? 1 }}"
                                                                class="form-control" placeholder="@lang('Product Size Quantity')"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="sizeAndQuantityTemplateContainer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">@lang('Description')</label>
                                    <textarea class="form-control trumEdit" name="description" id="description" rows="3"
                                        placeholder="@lang('Description')">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="shipping_returns" class="form-label">@lang('Shipping & Returns')</label>
                                    <textarea class="form-control trumEdit" name="shipping_returns" id="shipping_returns" rows="3"
                                        placeholder="@lang('Shipping & Returns')">{{ old('shipping_returns') }}</textarea>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="meta_title" class="form-label">@lang('Meta Title')</label>
                                    <input class="form-control" name="meta_title" id="meta_title"
                                        value="{{ old('meta_title') }}" placeholder="@lang('Meta Title')">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="meta_description" class="form-label">@lang('Meta Description')</label>
                                    <textarea class="form-control" name="meta_description" id="meta_description" rows="10"
                                        placeholder="@lang('Meta Description')">{{ old('shipping_returns') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn--primary btn-global">@lang('Create')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden template row -->
    <div id="sizeAndQuantityTemplate" class="d-none">
        <div class="size_and_quantity_row size_and_quantity_template" data-index="__index__">
            <div class="row">
            <div class="col-lg-12 text-end mb-2">
                <button type="button" class="btn btn--danger btn--sm deleteRow">
                    <i class="fa fa-trash"></i> @lang('Delete')
                </button>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label">@lang('Size')</label>
                    <select class="form-control size-select form-select" name="size_quantity[__index__][size]" required>
                        <option value="" selected disabled>@lang('Select Size')</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ __($size->size) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label">@lang('Quantity')</label>
                    <input type="number" value="1" name="size_quantity[__index__][quantity]" class="form-control"
                        placeholder="@lang('Product Size Quantity')" required>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn--primary">
        <i class="fa-solid fa-arrow-left"></i>
        @lang('Back')
    </a>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/common/css/select2.min.css') }}">
@endpush


@push('script-lib')
    <script src="{{ asset('assets/common/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/ckeditor.js') }}"></script>
@endpush

@push('style')
    <style>
        .ck.ck-editor__main>.ck-editor__editable {
            min-height: 131px;
        }

        .size_and_quantity_template {
            border: 1px dashed #aaa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
@endpush


@push('script')
    <script>
        $('input[name=is_color]').on('change', function() {
            if ($(this).is(':checked')) {
                $('.select2-auto-tokenize').closest('.form-group').removeClass('d-none');
                $('.color-row').removeClass('d-none');
                $('.normalImage').addClass('d-none');
            } else {
                $('.select2-auto-tokenize').closest('.form-group').addClass('d-none');
                $('.color-row').addClass('d-none');
                $('.normalImage').removeClass('d-none');

            }
        });
    </script>

    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 20) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                <div class="row elements">
                    <div class="col-sm-12">
                        <div class="file-upload input-group mb-4">
                            <input type="file" name="images[]" id="inputImages" class="form-control form--control"
                                 placeholder="@lang('Image')" />     
                                <button class="input-group-text btn--danger remove-btn"><i class="las la-times"></i></button>                                        
                        </div>
                    </div>
                </div>
            `)

            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.elements').remove();
            });
        })(jQuery);
    </script>

    <script>
        (function($) {
            "use strict";
            $('.select2-auto-tokenize').select2({
                dropdownParent: $('.card-body'),
                tags: false,
                placeholder: "Select colors",
                tokenSeparators: [','],
            });


            $('.select2-auto-tokenize').on('change', function() {
                const selectedOptions = $(this).find('option:selected');
                const container = $('#colorRowsContainer');

                const existingRows = {};
                container.find('.color-row').each(function() {
                    const colorId = $(this).data('color-id');
                    existingRows[colorId] = $(this);
                });


                const newColorIds = [];

                selectedOptions.each(function(index, option) {
                    const colorId = $(option).val();
                    const code = $(option).data('code');
                    const name = $(option).data('name');
                    newColorIds.push(colorId);
                    if (existingRows[colorId]) return;
                    const rowHtml = `
                        <div class="row mb-2 mt-3 color-row" data-color-id="${colorId}">
                            <div class="col-md-2 d-flex align-items-center">
                                <strong>${name}</strong>
                                <div style="width: 20px; height: 20px; background-color: ${code}; margin-left: 10px; border: 1px solid #ccc; border-radius: 4px;"></div>
                            </div>
                            <div class="col-md-10">
                                <input type="file" name="color_images[${colorId}]" class="form-control" accept="image/*">
                            </div>
                        </div>
                    `;
                    container.append(rowHtml);
                });


                Object.keys(existingRows).forEach(function(colorId) {
                    if (!newColorIds.includes(colorId)) {
                        existingRows[colorId].remove();
                    }
                });
            });

        })(jQuery);
    </script>

    {{-- LOAD EDITOR --}}
    <script>
        "use strict";
        document.querySelectorAll('.trumEdit').forEach(element => {
            ClassicEditor
                .create(element)
                .then(editor => {
                    window.editor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("select[name='type']").on('change', function() {
                if ($(this).val() == 1) {
                    $('.size_and_quantity').removeClass('d-none');
                    $('.auctionInput').addClass('d-none');
                    $('.productPrice').removeClass('d-none');
                    $('.isColor').removeClass('d-none');
                    $('.discountInput').removeClass('d-none');
                    $('input[name=is_color]').val(0).prop('checked', false);

                } else {
                    $('.size_and_quantity').addClass('d-none');
                    $('.auctionInput').removeClass('d-none');
                    $('.productPrice').addClass('d-none');
                    $('.isColor').addClass('d-none');
                    $('.normalImage').removeClass('d-none');
                    $('.colorImage').addClass('d-none');
                    $('.discountInput').addClass('d-none');

                }
            });



            let index = 1; // index 0 already used in default
            const sizesCount = {{ count($sizes) }} + 1;

            function getSelectedSizes() {
                let selected = [];
                $('select[name^="size_quantity"]').each(function() {
                    let val = $(this).val();
                    if (val) selected.push(val);
                });
                return selected;
            }

            function updateOptions() {
                const selectedSizes = getSelectedSizes();

                $('select.size-select').each(function() {
                    let currentVal = $(this).val();
                    $(this).find('option').each(function() {
                        let optionVal = $(this).val();
                        if (optionVal && optionVal !== currentVal) {
                            $(this).prop('disabled', selectedSizes.includes(optionVal));
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                });
            }

            $(document).on('change', 'select.size-select', function() {
                updateOptions();
            });

            $('.addSizeAndQuantityTemplate').on('click', function() {

                if ($('.size-select').length >= sizesCount) {
                    notify('error', 'All sizes already selected.');
                    return;
                }

                let templateHtml = $('#sizeAndQuantityTemplate').html();
                let newHtml = templateHtml.replaceAll('__index__', index);
                $('#sizeAndQuantityTemplateContainer').append(newHtml);
                ++index;
                updateOptions();
            });

            $(document).on('click', '.deleteRow', function() {
                $(this).closest('.size_and_quantity_row').remove();
                updateOptions();
            });

            updateOptions(); // Initial Function Call
        });
    </script>
@endpush
