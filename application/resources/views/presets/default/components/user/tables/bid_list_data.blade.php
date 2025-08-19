@forelse ($bids as $item)
    <tr>
        <td data-label="@lang('SI')">
            {{ $loop->iteration }}
        </td>

        <td data-label="@lang('Bidder Name')">{{ $item?->user?->fullname }}</td>

        <td data-label="@lang('Bidding Price')">
            {{ $general->cur_sym . showAmount($item->price) }}
        </td>

        <td data-label="@lang('Email')">
            {{ $item?->user->email ?? '' }}
        </td>

        <td data-label="@lang('Mobile')">
            {{ $item?->user?->mobile ?? '' }}
        </td>

        <td data-label="@lang('Address')">
            @php
                $address =  $item->user?->address;
                $fullAddress = collect([$address?->address, $address?->state, $address?->zip, $address?->city,$address?->country])
                    ->filter()
                    ->implode(', ');
            @endphp
            {{ $fullAddress }}

        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
