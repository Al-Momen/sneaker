@forelse ($bids as $item)
    <tr>
        <td data-label="@lang('SI')">
            {{ $loop->iteration }}
        </td>

        <td data-label="@lang('Bidder Name')">{{ $item?->user?->fullname }}</td>

        <td data-label="@lang('Bidding Price')">
            {{ $general->cur_sym . showAmount($item->price) }}
        </td>

        <td data-label="@lang('Bidder')">

            @if ($item->bidWinner)
                <span class="badge badge--success">
                    @lang('Winner')
                </span>
            @else
                <span class="badge badge--warning">
                    @lang('Bidder')
                </span>
            @endif
        </td>

        <td data-label="@lang('Created at')">
            {{ showDateTime($item?->created_at) }}
        </td>

        @php
            $address = $item->user?->address;
            $fullAddress = collect([
                $address?->address,
                $address?->state,
                $address?->zip,
                $address?->city,
                $address?->country,
            ])
                ->filter()
                ->implode(', ');
        @endphp


        <td data-label="@lang('Action')">
            <a href="javascript:void(0)" class="btn btn--base btn-md action--btn detailBtn"
            data-fullname="{{ $item->user?->fullname }}"
                data-email="{{ $item->user?->email }}" data-phone="{{ $item->user?->mobile }}"
                data-address="{{ $fullAddress }}">
                <i class="fa fa-eye"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
