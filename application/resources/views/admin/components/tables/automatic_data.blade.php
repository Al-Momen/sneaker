@forelse($items->sortBy('alias') as $k=>$gateway)
    <tr>
        <td>
            <div class="d-flex align-items-center justify-content-start gap-3">
                <div class="gateway--thumb">
                    @if(!empty($gateway?->image))
                        <img src="{{ getImage(getFilePath('paymentGateway') . '/' . $gateway?->image ) }}" alt="@lang('Image')">
                    @else
                        <img src="{{ getImage('assets/images/general/default.png') }}" alt="@lang('Image')">
                    @endif
                </div>

                <h6>
                    {{ __($gateway->name ?? '') }}
                </h6>
            </div>
        </td>

        <td>
            @php
                $supportedCurrencies = collect($gateway->supported_currencies)->except($gateway->currencies->pluck('currency'));
            @endphp
            <span>
                {{ $gateway->currencies()->count() }}
                @if($gateway->currencies->count() > 0)
                    <i data-bs-toggle="modal" data-bs-target="#currencyModal" class="fa-solid fa-caret-down enabled_currency c-pointer"  data-enabled_currency="{{ $gateway->currencies->pluck('currency')->toJson()}}" data-supported_currency="{{ collect($gateway->supported_currencies)->keys()->toJson() }}"></i>
                @endif
                /
                {{ $supportedCurrencies->count() }}
                <i data-bs-toggle="modal" data-bs-target="#currencyModal" class="fa-solid fa-caret-down supported_currency c-pointer" data-supported_currency="{{ collect($gateway->supported_currencies)->keys()->toJson() }}"></i>
            </span>
        </td>

        <td>
            @if ($gateway->status == 1)
                <span
                    class="text--small badge font-weight-normal badge--success">@lang('Enabled')</span>
            @else
                <span class="text--small badge font-weight-normal badge--warning">@lang('Disabled')</span>
            @endif
        </td>

        <td>
            <div class="button--group d-flex align-items-center justify-content-end gap-2 pe-3">
                <a title="@lang('Edit')"
                    href="{{ route('admin.gateway.automatic.edit', $gateway->alias) }}"
                    class="btn btn--sm edit--btn editGatewayBtn">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                @if($gateway->status)
                    <a href="javascript:void(0)" title="@lang('Disable')" data-question="@lang('Are you sure to enable this gateway?')" data-action="{{ route('admin.gateway.automatic.deactivate',$gateway->code) }}" class="btn btn--sm float-md-end confirmationBtn">
                        <i class="fa-solid fa-toggle-on"></i>
                    </a>
                @else
                    <a href="javascript:void(0)" title="@lang('Enable')" data-question="@lang('Are you sure to disable this gateway?')" data-action="{{ route('admin.gateway.automatic.activate',$gateway->code) }}" class="btn btn--sm float-md-end confirmationBtn">
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
