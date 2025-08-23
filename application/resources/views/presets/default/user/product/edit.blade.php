@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-md-12 justify-content-center">
            <div class="base--card bg--white border--none radius--8">
                <form action="{{ route('user.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="name" class="form--label mb-2 required">@lang('Name')</label>
                                <input type="text" name="name" id="name" value="{{ $product->name }}"
                                    class="form--control" placeholder="@lang('Product Name')" required>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="category" class="form--label mb-2 required">@lang('Category')</label>
                                <select name="category" class="form--control form-select" required>
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


                        <div class="col-lg-4 mb-4">
                            <div class="form-group">
                                <label for="brand_name" class="form--label mb-2 required">@lang('Brand')</label>
                                <input type="text" name="brand_name" id="brand_name" value="{{ $product->brand_name }}"
                                    class="form--control" placeholder="@lang('Brand Name')" required>
                            </div>
                        </div>


                        <div class="col-lg-4 mb-4 {{ $product->type == 2 ? 'd-none' : '' }} ">
                            <label class="form--label mb-2 required">@lang('Price')</label>
                            <div class="input-group">
                                <input type="text" name="price" value="{{ showAmount($product->price, 2, false) }}"
                                    class="form--control form--control form-control bg--white">
                                <span class="input-group-text bg--base text-white border-0">{{ $general->cur_sym }}</span>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4 {{ $product->type == 2 ? 'd-none' : '' }}">
                            <label class="form--label mb-2">@lang('Discount')</label>
                            <div class="input-group">
                                <input type="number" name="discount" min="0" max="100" step="any"
                                    value="{{ showAmount($product->discount, 2, false) }}"
                                    class="form--control form--control form-control bg--white">
                                <span class="input-group-text bg--base text-white border-0">%</span>
                            </div>
                        </div>


                        <div class="row auctionInput {{ $product->type == 1 ? 'd-none' : '' }}">
                            @if ($product->start_date > now())
                                <div class="col-lg-4 mb-4">
                                    <label for="min-price" class="form--label mb-2 required">@lang('Starting Price')</label>
                                    <div class="input-group">
                                        <input type="number" name="min_price" id="min-price" min="0" step="any"
                                            value="{{ showAmount($product->min_price) }}" class="form--control"
                                            placeholder="@lang('Product Starting Price')">

                                    </div>
                                </div>

                                <div class="col-lg-4 mb-4">
                                    <label for="start_date" class="form--label mb-2 required">@lang('Start at')</label>
                                    <div class="form-group">
                                        <input type="datetime-local" name="start_date" id="start_date"
                                            value="{{ $product->start_date }}" class="form--control"
                                            placeholder="@lang('Auction Start Date')">
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-4 mb-4">
                                <label for="end_date" class="form--label mb-2 required">@lang('End at')</label>
                                <div class="form-group">
                                    <input type="datetime-local" name="end_date" id="end_date"
                                        value="{{ $product->end_date }}" class="form--control"
                                        placeholder="@lang('Auction End Date')">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="p-4 rounded border mb-4">
                                <div class="normalImage {{ old('is_color', $product->is_color) ? 'd-none' : '' }}">
                                    <div class="form-group">
                                        <label class="form--label mb-2 required">@lang('Images')</label>
                                        <input type="file" name="images[]" class="form--control mb-3">
                                        <div id="fileUploadsContainer"></div>
                                        <button type="button" class="btn btn--base btn--md addFile ">
                                            <i class="fa fa-plus"></i> @lang('Add New')
                                        </button>
                                    </div>

                                    <div class="mt-5 row">
                                        @foreach ($product->images as $item)
                                            <div class="image-card col-auto text-center mb-3 me-3">
                                                <button type="button" class="remove-btn confirmationBtn"
                                                    data-id="{{ $item->id }}"
                                                    data-action="{{ route('user.product.delete', $item->id) }}"
                                                    data-question="@lang('Are you sure to delete this image?')">&times;</button>

                                                <img src="{{ getImage(getFilePath('product') . '/' . $item->image ?? '') }}"
                                                    alt="@lang('Image')" class="mb-2"
                                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group {{ old('is_color', $product->is_color) ? '' : 'd-none' }}">
                                    <label class="form--label mb-2 required">@lang('Color Name')</label>
                                    <select name="code_id[]" class="form--control from-select select2-auto-tokenize"
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
                                                    data-action="{{ route('user.product.delete', $item->id) }}"
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

                                        {{-- Default Row --}}
                                        <div class="row size_and_quantity_row mb-3">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="size"
                                                        class="form--label mb-2 required">@lang('Sizes')</label>
                                                    <small class="ms-2 mt-2">@lang('Separate size name by')
                                                        <code>,</code>(@lang('comma')) @lang('or')
                                                        <code>@lang('enter')</code>
                                                        @lang('key')</small>
                                                    <select name="sizes[]" class="form-control select2-auto-tokenize-size"
                                                        multiple="multiple">
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->size }}"
                                                                {{ in_array($size->id, $product->sizes ?? []) ? 'selected' : '' }}>
                                                                {{ __($size->size) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label class="form--label mb-2 required">@lang('Description')</label>
                                <textarea name="description" class="form--control trumEdit">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label class="form--label mb-2 required">@lang('Shipping & Returns')</label>
                                <textarea name="shipping_returns" class="form--control trumEdit">{{ old('shipping_returns', $product->shipping_description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label for="meta_title" class="form--label mb-2">@lang('Meta Title')</label>
                                <input class="form--control" name="meta_title" id="meta_title"
                                    value="{{ $product->meta_title }}" placeholder="@lang('Meta Title')">
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label for="meta_description" class="form--label mb-2">@lang('Meta Description')</label>
                                <textarea class="form--control" name="meta_description" id="meta_description" rows="10"
                                    placeholder="@lang('Meta Description')">{{ $product->meta_description }}</textarea>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn--base btn--lg w-100 mt-4">@lang('Update')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>

 
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
                dropdownParent: $('.base--card'),
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
                            <input type="file" name="color_images[${colorId}]" class="form--control" accept="image/*">
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
                        <div class="file-upload input-group mb-3">
                            <input type="file" name="images[]" class="form--control form-control bg--white">
                            <button class="input-group-text btn--danger input-group-text text-white border-0 remove-btn">
                                <i class="las la-times"></i>
                            </button>
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

            'use strict';
             $('.select2-auto-tokenize-size').select2({
                dropdownParent: $('.base--card'),
                tags: false,
                placeholder: "Select Sizes",
                tokenSeparators: [','],
            });
        });
    </script>
@endpush
