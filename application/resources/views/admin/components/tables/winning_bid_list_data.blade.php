@forelse ($winningBids as $item)
    <tr>
        <td data-label="@lang('SI')">
            {{ $loop->iteration }}
        </td>

        <td data-label="@lang('Product Name')">{{ $item?->bid->product?->name }}</td>

        <td data-label="@lang('Bidding Price')">
            {{ $general->cur_sym . showAmount($item->bid->price) }}
        </td>

        <td data-label="@lang('Bidder')">
            <span class="badge badge--success">
                @lang('Winner')
            </span>
        </td>
          <td data-label="@lang('Created at')">
            {{ showDateTime($item->created_at) }}
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
