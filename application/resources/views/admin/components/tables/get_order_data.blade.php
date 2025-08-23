@forelse ($orders as $item)
    <tr>
        <td data-label="@lang('SI')">
            {{ $loop->iteration }}
        </td>

        <td data-label="@lang('Order Number')">
            {{ $item->order_number }}
        </td>

        <td data-label="@lang('Full Name')">{{ $item->first_name, $item->last_name }}</td>

        <td data-label="@lang('Total Price')">
            {{ $general->cur_sym . showAmount($item->total_price) }}
        </td>

        <td data-label="@lang('Email')">
            {{ $item->email ?? '' }}
        </td>

        <td data-label="@lang('Mobile')">
            {{ $item->mobile ?? '' }}
        </td>

        <td data-label="@lang('Status')">
            @php echo $item->statusBadge($item->status); @endphp
        </td>

        <td data-label="@lang('Action')">
            <div class="button--group align-items-center">
                <a href="{{ route('admin.orders.details', $item->id) }}" class="btn btn-sm" title="@lang('View')">
                    <i class="fa fa-eye"></i>
                </a>
               
                @if ($item->deposit)
                    <a href="{{ route('user.deposit.details', $item->deposit->id) }}" class="btn btn-sm"
                        title="@lang('Payment')">
                       <i class="fa-solid fa-money-bill-wave"></i>
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
