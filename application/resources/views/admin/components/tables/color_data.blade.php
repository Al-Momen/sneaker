 @forelse($colors as $color)
    <tr>
        <td>#{{ $loop->iteration }}</td>

        <td>{{ $color->name }}</td>
        <td>{{ $color->code }}</td>
        <td>
            @php
                echo $color->statusBadge($color->status);
            @endphp
        </td>
        <td>
            <button type="button" class="btn btn-sm editColorBtn" title="@lang('Edit')"
                data-action="{{ route('admin.color.update', $color->id) }}" data-code="{{ $color->code }}" data-name="{{ $color->name }}"
                data-status="{{ $color->status }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </td>
    </tr>
@empty

    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
