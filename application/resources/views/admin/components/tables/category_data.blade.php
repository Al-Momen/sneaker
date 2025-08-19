 @forelse($categories as $category)
    <tr>
        <td>#{{ $loop->iteration }}</td>

        <td>{{ $category->name }}</td>
        <td>
            @php
                echo $category->statusBadge($category->status);
            @endphp
        </td>
        <td>
            <button type="button" class="btn btn-sm editCatBtn" title="@lang('Edit')"
                data-action="{{ route('admin.category.update', $category->id) }}" data-name="{{ $category->name }}"
                data-status="{{ $category->status }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </td>
    </tr>
@empty

    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
