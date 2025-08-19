@extends('admin.layouts.app')
@section('panel')

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6 mt-md-0 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">@lang('Available Menu Items')</h3>
                                <small>@lang('Drag items from the left and drop them into the menu area on the right. Click "Update" to save changes.')</small>
                            </div>
                            <div class="card-body">
                                <ol class="simple_with_no_drop vertical">
                                    @foreach ($items as $k => $data)

                                            <li class="highlight icon-move clearfix d-flex align-items-center w-100">
                                                <i class="fas fa-expand-arrows-alt"></i>
                                                <span class="d-inline-block me-auto ms-2"> {{ __($data->title) }}</span>
                                                <i class="ms-auto d-inline-block remove-icon fa fa-times"></i>
                                                <input type="hidden" name="menu_items[]" value="{{ $data->id }}">
                                            </li>

                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __($menu->name) }} @lang('Page')</h3>
                                <small>@lang('Drop menu items here to build or rearrange the menu.')</small>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('admin.menu.assign.item.submit', $menu->id) }}" method="post">
                                    @csrf
                                    <ol class="simple_with_drop vertical sec-item">
                                        @if ($menu->items()->count() > 0)
                                            @foreach ($menu->items()->get() as $data)
                                                <li class="highlight icon-move item">
                                                    <i class="fas fa-expand-arrows-alt"></i>
                                                    <span class="d-inline-block me-auto ms-2"> {{ __($data->title) }}</span>
                                                    <i class="ms-auto d-inline-block remove-icon fa fa-times"></i>
                                                    <input type="hidden" name="menu_items[]" value="{{ $data->id }}">
                                                </li>
                                            @endforeach
                                        @endif
                                    </ol>
                                    <div class="form-group text-end mt-4">
                                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/jquery-sortable.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $("ol.simple_with_drop").sortable({
                group: 'no-drop',
                handle: '.icon-move',
                onDragStart: function($item, container, _super) {
                    if (!container.options.drop) {
                        $item.clone().insertAfter($item);
                    }
                    _super($item, container);
                }
            });

            $("ol.simple_with_no_drop").sortable({
                group: 'no-drop',
                drop: false
            });

            $("ol.simple_with_no_drag").sortable({
                group: 'no-drop',
                drag: false
            });

            $(document).on('click', ".remove-icon", function() {
                $(this).parent('.highlight').remove();
            });

        })(jQuery);
    </script>
@endpush



@push('style')
    <style>
        .span4 {
            width: 300px;
        }

        ol li.highlight {
            background: #000;
            color: #999999;
        }


        .simple_with_drop .highlight,
        .simple_with_no_drop .highlight {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        ol.vertical {
            margin: 0 0 9px 0;
            min-height: 10px;
        }

        li {
            line-height: 18px;
        }

        .icon-move {
            background-position: -168px -72px;
        }

        ol i.icon-move {
            cursor: pointer;
        }

        ol {
            display: block;
            list-style-type: decimal;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
        }

        .vertical li i {
            color: #000000;
            padding-right: 15px;
        }

        .sec-item li i {
            color: #00adad;
            padding-right: 15px;
        }

        .sec-item li i.fa-times {
            color: #00adad;
            padding-right: 15px;
        }

        ol.vertical li {
            display: block;
            margin: 10px 0;
            padding: 21px 20px;
            color: #e0e0e0;
            background: #ececec;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
        }

        ol.sec-item li {
            margin: 10px 0;
            padding: 21px 20px;
            color: #8c8c8c;
            background: #ececec;
            font-size: 24px;
            font-weight: 600;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            border-radius: 6px;
        }

        .ol.sec-item li.d-none {
            display: none !important;
        }

        [class*="span"] {
            float: left;
            margin-left: 20px;
        }

        .row {
            *zoom: 1;
        }

        .row {
            position: relative;
        }

        .dragged {
            position: absolute;
            top: 0;
            opacity: 0.5;
            z-index: 2000;
            background: #333333;
            color: #999999;
        }

        ol.vertical li i.remove-icon {
            display: none !important;
        }

        ol.sec-item li i.remove-icon {
            display: block !important;
        }

        ol.sec-item li .manage-content {
            display: none !important;
        }

        ol.vertical li span {
            font-size: 18px;
        }

        .cog-btn i {
            color: #ffffff !important
        }

        .cog-btn:hover i {
            color: #000 !important
        }
    </style>
@endpush






