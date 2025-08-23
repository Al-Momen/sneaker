@forelse ($myBids as $item)
    <tr>
        <td data-label="@lang('SI')">
            {{ $loop->iteration }}
        </td>

        <td data-label="@lang('Author Name')">
            
                <span class="text--primary">{{ $item->product->author_name }}</span>
           
        </td>

        <td data-label="@lang('Product Name')">{{ $item?->product?->name }}</td>
        <td data-label="@lang('Bidding Price')">
            {{ $general->cur_sym . showAmount($item->price) }}
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
