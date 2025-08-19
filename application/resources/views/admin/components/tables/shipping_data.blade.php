 @forelse($shippings as $shipping)
    <tr>
        <td>#{{ $loop->iteration }}</td>

        <td>{{ $shipping->location }}</td>
        <td>{{$general->cur_sym . showAmount($shipping->charge) }}</td>
        <td>
            @php
                echo $shipping->statusBadge($shipping->status);
            @endphp
        </td>
        <td>
            <button type="button" class="btn btn-sm editShippingBtn" title="@lang('Edit')"
                data-action="{{ route('admin.shipping.update', $shipping->id) }}" 
                data-location="{{ $shipping->location }}"
                data-charge="{{ $shipping->charge }}"
                data-status="{{ $shipping->status }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </td>
    </tr>
@empty

    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
