@forelse ($orders as $item)
    <tr>
        <td data-label="@lang('SI')">
            #{{ $loop->iteration }}
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
                <a href="{{route('user.orders.details',$item->id)}}"
                    class="btn btn--base btn-md action--btn ">
                    <i class="fa fa-eye"></i>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
