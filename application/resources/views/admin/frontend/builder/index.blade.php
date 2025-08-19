@extends('admin.layouts.app')
@section('panel')
    @if ($pdata->is_default == 0)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.frontend.manage.pages.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $pdata->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Page Name')</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $pdata->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Page Slug')</label>
                                        <input type="text" class="form-control" name="slug"
                                            value="{{ $pdata->slug }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="row gy-4">
        <div class="col-lg-6">
            <div class="card radius--base br--solid">
                <div class="card-header">
                    <h3 class="card-title">{{ __($pdata->name) }} @lang('Page')</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.frontend.manage.section.update', $pdata->id) }}" method="post">
                        @csrf
                        <ol class="simple_with_drop vertical sec-item">
                            @if ($pdata->secs != null)
                                @foreach (json_decode($pdata->secs) as $k=>$sec)
                                    <li data-id="{{ $sec }}"

                                        class="highlight icon-move item d-flex justify-content-center align-items-center position-relative bg--white br--solid">
                                        <div class="d-flex flex-column justify-content-center align-items-center gap-2">

                                            <i class="fas fa-expand-arrows-alt"></i>
                                            <span class="d-inline-block">
                                                {{ __($sections[$sec]['name'] ?? 'N/A') }}</span>
                                            <i class="ms-auto d-inline-block remove-icon fa-regular fa-trash-can"></i>
                                            <input type="hidden" name="secs[]" value="{{ $sec }}">
                                        </div>

                                    </li>
                                @endforeach
                            @endif
                        </ol>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card radius--base br--solid">
                <div class="card-header">
                    <h3 class="card-title">@lang('Available Page Components')</h3>
                    <small>@lang('Drag sections to the right and update the page')</small>
                </div>
                <div class="card-body">
                    <ol class="simple_with_no_drop vertical d-flex flex-wrap gap-3">
                        @foreach ($sections as $k => $secs)

                        @php
                            $selectedSecs = is_string($pdata->secs ?? null) ? json_decode($pdata->secs, true) : [];
                        @endphp

                        @if (!($secs['no_selection'] ?? false) && isset($pdata) && !in_array($k, $selectedSecs))
                                <li data-id="{{ $k }}"
                                    data-builder="{{ $secs['builder'] ? '1' : '0' }}"
                                    data-url="{{ route('admin.frontend.sections', $k) }}"

                                    class="highlight icon-move clearfix d-flex justify-content-center align-items-center position-relative">
                                    <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                        <i class="fas fa-expand-arrows-alt p-0 m-0"></i>
                                        <span class="d-inline-block"> {{ __($secs['name']) }}</span>
                                        <i class="ms-auto d-inline-block remove-icon fa-regular fa-trash-can"></i>
                                        <input type="hidden" name="secs[]" value="{{ $k }}">
                                    </div>
                                    @if ($secs['builder'])
                                        <div class="float-end d-inline-block manage-content position-absolute">
                                            <a href="{{ route('admin.frontend.sections', $k) }}" target="_blank"
                                                class="cog-btn" title="@lang('Manage Content')">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </div>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ol>
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

        const sectionHTMLCache = {};

        // Cache all draggable section HTMLs from both columns
        $("li[data-id]").each(function() {
            const id = $(this).data("id");
            sectionHTMLCache[id] = $(this).prop("outerHTML");
        });

        // Initialize sortable for drop zone
        $("ol.simple_with_drop").sortable({
            group: 'no-drop',
            handle: '.icon-move',
            onDragStart: function($item, container, _super) {
                if (!container.options.drop) {
                    $item.clone().insertAfter($item);
                }
                const offset = $item.offset();
                $item.css({
                    width: $item.outerWidth(),
                    height: $item.outerHeight(),
                    position: 'relative',
                    top: offset.top,
                    left: offset.left,
                    zIndex: 1000
                });
                $item.addClass("dragging");
                _super($item, container);
            },
            onDrop: function($item, container, _super) {
                const id = $item.data("id");

                // Remove from left column if present
                $("ol.simple_with_no_drop li[data-id='" + id + "']").remove();

                $item.removeClass("dragging").css({
                    width: '',
                    height: ''
                });

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

        $(document).on('click', ".remove-icon", function () {
            const $li = $(this).closest('li');
            const id = $li.data("id");

            if ($("ol.simple_with_no_drop li[data-id='" + id + "']").length === 0) {
                const builder = $li.data("builder");
                const editUrl = $li.data("url");

                let html = `
                <li data-id="${id}" data-builder="${builder}" data-url="${editUrl}"
                    class="highlight icon-move clearfix d-flex justify-content-center align-items-center position-relative">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 position-relative">
                        <i class="fas fa-expand-arrows-alt p-0 m-0"></i>
                        <span class="d-inline-block">${$li.find("span").text()}</span>
                        <i class="ms-auto d-inline-block remove-icon fa-regular fa-trash-can"></i>
                        <input type="hidden" name="secs[]" value="${id}">
                    </div>`;

                if (builder) {
                    html += `
                    <div class="float-end d-inline-block manage-content position-absolute">
                        <a href="${editUrl}" target="_blank" class="cog-btn" title="Manage Content">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>`;
                }

                html += `</li>`;

                $("ol.simple_with_no_drop").append(html);
            }

            $li.remove();
        });


    })(jQuery);
</script>
@endpush



@push('breadcrumb-plugins')
    <a href="{{ route('admin.frontend.manage.pages') }}" class="btn btn-sm btn--primary">@lang('Back')</a>
@endpush

@push('style')
    <style>
        .dragging {
            opacity: 0.8;
            pointer-events: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .simple_with_drop .highlight,
        .simple_with_no_drop .highlight {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .icon-move {
            cursor: move;
        }

        .span4 {
            width: 300px;
        }

        ol li.highlight {
            background: #000;
            color: #999999;
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
            padding: 20px;
            color: #e0e0e0;
            background: #EFEFEF;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
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
            position: absolute;
            top: 12px;
            right: 0;
            color: #E53935;
            cursor: pointer;
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

        .simple_with_drop .placeholder {
            width: 100%;
            height: 102px;
            border: 1px solid #000;
            display: flex;
            justify-content: center;
            align-items: center;
            background: transparent;
            font-size: 16px;
        }

        .simple_with_drop .highlight {
            background: #fff;
            border: 1px solid var(--border-color) !important;
        }
    </style>
@endpush
