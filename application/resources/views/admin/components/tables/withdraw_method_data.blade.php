@forelse($items as $method)
    <tr>
        <td>
            <div class="d-flex align-items-center justify-content-start gap-3">
                <div class="gateway--thumb">
                    <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image ) }}" alt="@lang('Image')">
                </div>

                <h6>
                    {{ __($method->name ?? '') }}
                </h6>
            </div>
        </td>

        <td class="fw-semibold">{{ __($method->currency) }}</td>

        <td class="fw-semibold">{{ showAmount($method->fixed_charge) }}
            {{ __($general->cur_text) }}
            {{ 0 < $method->percent_charge ? ' + ' . showAmount($method->percent_charge) . ' %' : '' }}
        </td>

        <td class="fw-semibold">
            {{ $method->min_limit + 0 }} - {{ $method->max_limit + 0 }} {{ __($general->cur_text) }}
        </td>

        <td>
            @if ($method->status == 1)
                <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
            @else
                <span class="text--small badge font-weight-normal badge--warning">@lang('Disabled')</span>
            @endif
        </td>

        <td>
            <div class="button--group d-flex align-items-center justify-content-end gap-2 pe-3">
                <a title="@lang('Edit')" href="{{ route('admin.withdraw.method.edit', $method->id) }}" class="edit--btn btn btn--sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                @if($method->status)
                    <a href="javascript:void(0)" title="@lang('Disable')" data-question="@lang('Are you sure to disable this mehtod?')" data-action="{{ route('admin.withdraw.method.deactivate',$method->id) }}" class="btn btn--sm float-md-end confirmationBtn">
                        <i class="fa-solid fa-toggle-on"></i>
                    </a>
                @else
                    <a href="javascript:void(0)" title="@lang('Enable')" data-question="@lang('Are you sure to enable this method?')" data-action="{{ route('admin.withdraw.method.activate',$method->id) }}" class="btn btn--sm float-md-end confirmationBtn">
                        <i class="fa-solid fa-toggle-off"></i>
                    </a>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
