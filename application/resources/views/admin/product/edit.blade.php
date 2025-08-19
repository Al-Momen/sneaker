@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.product.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">@lang('Name')</label>
                                    <input type="text" name="name" id="name" value="{{ $product->name }}"
                                        class="form-control" placeholder="@lang('Product Name')" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="category" class="form-label">@lang('Category')</label>
                                    <select name="category" class="form-control" required>
                                        <option disabled>@lang('Select Category')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ __($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="brand_name">@lang('Brand')</label>
                                    <input type="text" name="brand_name" id="brand_name"
                                        value="{{ $product->brand_name }}" class="form-control"
                                        placeholder="@lang('Brand Name')" required>
                                </div>
                            </div>


                            <div class="col-lg-4 mb-4 {{ $product->type == 2 ? 'd-none' : '' }} ">
                                <label>@lang('Price')</label>
                                <div class="input-group">
                                    <input type="text" name="price" value="{{ showAmount($product->price, 2, false) }}"
                                        class="form-control">
                                    <span class="input-group-text">{{ $general->cur_sym }}</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-4 {{ $product->type == 2 ? 'd-none' : '' }}">
                                <label>@lang('Discount')</label>
                                <div class="input-group">
                                    <input type="number" name="discount" min="0" max="100" step="any"
                                        value="{{ showAmount($product->discount, 2, false) }}" class="form-control">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>


                            <div class="row auctionInput {{ $product->type == 1 ? 'd-none' : '' }}">
                                @if ($product->start_date > now())
                                    <div class="col-lg-4 mb-4">
                                        <label for="min-price" class="form-label">@lang('Min Price')</label>
                                        <div class="input-group">
                                            <input type="number" name="min_price" id="min-price" min="0"
                                                max="100" step="any" value="{{ showAmount($product->min_price) }}"
                                                class="form-control" placeholder="@lang('Product Min Price')">

                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-4">
                                        <label for="start_date" class="form-label">@lang('Start at')</label>
                                        <div class="form-group">
                                            <input type="datetime-local" name="start_date" id="start_date"
                                                value="{{ $product->start_date }}" class="form-control"
                                                placeholder="@lang('Auction Start Date')">
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-4 mb-4">
                                    <label for="end_date" class="form-label">@lang('End at')</label>
                                    <div class="form-group">
                                        <input type="datetime-local" name="end_date" id="end_date"
                                            value="{{ $product->end_date }}" class="form-control"
                                            placeholder="@lang('Auction End Date')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="p-4 rounded border mb-4">
                                    <div class="normalImage {{ old('is_color', $product->is_color) ? 'd-none' : '' }}">
                                        <div class="form-group">
                                            <label>@lang('Images')</label>
                                            <input type="file" name="images[]" class="form-control mb-2">
                                            <div id="fileUploadsContainer"></div>
                                            <button type="button" class="btn btn--primary btn--sm addFile ">
                                                <i class="fa fa-plus"></i> @lang('Add New')
                                            </button>
                                        </div>

                                        <div class="mt-5 row">
                                            @foreach ($product->images as $item)
                                                <div class="image-card col-auto text-center mb-3 me-3">
                                                    <button type="button" class="remove-btn confirmationBtn"
                                                        data-id="{{ $item->id }}"
                                                        data-action="{{ route('admin.product.delete', $item->id) }}"
                                                        data-question="@lang('Are you sure to delete this image?')">&times;</button>

                                                    <img src="{{ getImage(getFilePath('product') . '/' . $item->image ?? '') }}"
                                                        alt="@lang('Image')" class="mb-2"
                                                        style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group {{ old('is_color', $product->is_color) ? '' : 'd-none' }}">
                                        <label>@lang('Color Name')</label>
                                        <select name="code_id[]" class="form-control select2-auto-tokenize"
                                            multiple="multiple">
                                            @foreach ($colors as $color)
                                                @if (!in_array($color->id, $usedColorIds))
                                                    <option value="{{ $color->id }}" data-name="{{ $color->name }}"
                                                        data-code="{{ $color->code }}">
                                                        {{ $color->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <div id="colorRowsContainer"></div>

                                        <div class="d-flex flex-wrap mt-4">
                                            @foreach ($product->images as $item)
                                                <div class="image-card col-auto text-center mb-3 me-3">
                                                    <button type="button" class="remove-btn confirmationBtn"
                                                        data-id="{{ $item->id }}"
                                                        data-action="{{ route('admin.product.delete', $item->id) }}"
                                                        data-question="@lang('Are you sure to delete this image?')">&times;</button>

                                                    <img src="{{ getImage(getFilePath('product') . '/' . $item->image ?? '') }}"
                                                        alt="@lang('Image')" class="mb-2"
                                                        style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">

                                                    @if ($item->color)
                                                        <div>
                                                            <small class="d-block">{{ $item->color->name }}</small>
                                                            <div
                                                                style="width: 20px; height: 20px; background-color: {{ $item->color->code }}; border: 1px solid #ccc; display: inline-block; border-radius: 4px;">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 size_and_quantity {{ $product->type == 2 ? 'd-none' : '' }}">
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
                                                            <option value="0" disabled
                                                                {{ old('size_quantity.0.size') ? '' : 'selected' }}>
                                                                @lang('Select Size')</option>

                                                            @foreach ($sizes as $size)
                                                                <option value="{{ $size->id }}"
                                                                    data-name="{{ $size->name }}"
                                                                    {{ isset($product->sizes[0]) && $product->sizes[0]->id == $size->id ? 'selected' : '' }}>
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
                                                            id="quantity"
                                                            value="{{ old('size_quantity.0.quantity', $product->sizes[0]->pivot->quantity ?? 1) }}"
                                                            class="form-control" placeholder="@lang('Product Size Quantity')"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="sizeAndQuantityTemplateContainer">
                                                @foreach ($product->sizes->skip(1) as $item)
                                                    <div class="row size_and_quantity_row size_and_quantity_template"
                                                        data-index="{{ $loop->iteration }}">
                                                        <div class="col-lg-12 text-end mb-2">
                                                            <button type="button"
                                                                class="btn btn--danger btn--sm deleteRow">
                                                                <i class="fa fa-trash"></i> @lang('Delete')
                                                            </button>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('Size')</label>
                                                                <select class="form-control size-select form-select"
                                                                    name="size_quantity[{{ $loop->iteration }}][size]"
                                                                    required>
                                                                    <option value="" disabled>@lang('Select Size')
                                                                    </option>
                                                                    @foreach ($sizes as $size)
                                                                        <option value="{{ $size->id }}"
                                                                            {{ $item->id == $size->id ? 'selected' : '' }}>
                                                                            {{ __($size->size) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('Quantity')</label>
                                                                <input type="number"
                                                                    name="size_quantity[{{ $loop->iteration }}][quantity]"
                                                                    class="form-control"
                                                                    value="{{ $item->pivot->quantity }}"
                                                                    placeholder="@lang('Product Size Quantity')" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control trumEdit">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>@lang('Shipping & Returns')</label>
                                    <textarea name="shipping_returns" class="form-control trumEdit">{{ old('shipping_returns', $product->shipping_description) }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="meta_title" class="form-label">@lang('Meta Title')</label>
                                    <input class="form-control" name="meta_title" id="meta_title"
                                        value="{{ $product->meta_title }}" placeholder="@lang('Meta Title')">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="meta_description" class="form-label">@lang('Meta Description')</label>
                                    <textarea class="form-control" name="meta_description" id="meta_description" rows="10"
                                        placeholder="@lang('Meta Description')">{{ $product->meta_description }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn--primary">@lang('Update')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>

    <!-- Hidden template row -->
    <div id="sizeAndQuantityTemplate" class="d-none">
        <div class="row size_and_quantity_row size_and_quantity_template" data-index="__index__">
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
@endsection

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

        .image-card {
            position: relative;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s ease-in-out;
            padding: 10px;
            background-color: #fff;
        }

        .image-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .image-card .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgb(220, 53, 70);
            color: #fff;
            border: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            z-index: 10;
            line-height: 17px;
        }
    </style>
@endpush



@push('script')
    <script>
        (function($) {
            "use strict";

            const select2El = $('.select2-auto-tokenize');
            const container = $('#colorRowsContainer');

            // Init select2
            select2El.select2({
                dropdownParent: $('.card-body'),
                tags: false,
                placeholder: "Select colors",
                tokenSeparators: [','],
            }).val(null).trigger('change');

            // Checkbox toggle for color section
            $('input[name=is_color]').on('change', function() {
                if ($(this).is(':checked')) {
                    select2El.closest('.form-group').removeClass('d-none');
                    $('.normalImage').addClass('d-none');
                } else {
                    select2El.closest('.form-group').addClass('d-none');
                    $('.normalImage').removeClass('d-none');
                }
            });

            // Select2 onchange handler
            select2El.on('change', function() {
                const selectedOptions = $(this).val() || [];
                const allOptions = select2El.find('option');

                // Hide already selected options from dropdown


                select2El.trigger('change.select2'); // re-render dropdown with updated disabled status

                const existingRows = {};
                container.find('.color-row').each(function() {
                    const colorId = $(this).data('color-id');
                    existingRows[colorId] = $(this);
                });

                const newColorIds = [];

                selectedOptions.forEach(function(colorId) {
                    const option = select2El.find(`option[value="${colorId}"]`);
                    const code = option.data('code');
                    const name = option.data('name');

                    newColorIds.push(colorId);
                    if (existingRows[colorId]) return;

                    const existingImage = window.productColorImages?.[colorId]?.image_url || '';
                    const rowHtml = `
                    <div class="row mb-2 mt-3 color-row" data-color-id="${colorId}">
                        <div class="col-md-2 d-flex align-items-center">
                            <strong>${name}</strong>
                            <div style="width: 20px; height: 20px; background-color: ${code}; margin-left: 10px; border: 1px solid #ccc; border-radius: 4px;"></div>
                        </div>
                        <div class="col-md-10">
                            <input type="file" name="color_images[${colorId}]" class="form-control" accept="image/*">
                            ${existingImage ? `<img src="${existingImage}" alt="Image" class="mt-2" height="50">` : ''}
                        </div>
                    </div>
                `;
                    container.append(rowHtml);
                });

                // Remove rows that are no longer selected
                Object.keys(existingRows).forEach(function(colorId) {
                    if (!newColorIds.includes(colorId)) {
                        existingRows[colorId].remove();
                    }
                });
            });

            // Normal image uploader
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 20) {
                    alert('Maximum 20 files allowed');
                    return;
                }
                fileAdded++;
                $('#fileUploadsContainer').append(`
                <div class="row elements">
                    <div class="col-sm-12">
                        <div class="file-upload input-group mb-4">
                            <input type="file" name="images[]" class="form-control">
                            <button class="input-group-text btn--danger remove-btn"><i class="las la-times"></i></button>
                        </div>
                    </div>
                </div>
            `);
            });

            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.elements').remove();
            });

        })(jQuery);


        $(document).on('click', '.remove-btn', function() {
            const id = $(this).data('id');

        });
    </script>
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


            let index = {{ count($sizes) }}; // index 0 already used in default
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
