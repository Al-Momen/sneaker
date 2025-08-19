 @forelse($sizes as $size)
    <tr>
        <td>#{{ $loop->iteration }}</td>

        <td>{{ $size->size }}</td>
        <td>
            @php
                echo $size->statusBadge($size->status);
            @endphp
        </td>
        <td>
            <button type="button" class="btn btn-sm editCatBtn" title="@lang('Edit')"
                data-action="{{ route('admin.size.update', $size->id) }}" data-size="{{ $size->size }}"
                data-status="{{ $size->status }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </td>
    </tr>
@empty

    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
