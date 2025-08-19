@forelse ($items as $role)
    <tr>
        <td>
            <span class="name">{{ __($role->name) }}</span>
        </td>
        <td>
            @php echo $role->statusBadge; @endphp
        </td>
        <td>
            <div class="button--group">
                <button title="@lang('Edit')" type="button" class="btn btn-sm editBtn" data-name="{{ __($role->name) }}" data-action="{{ route('admin.role.store', $role->id) }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                @if ($role->status == 0)
                    <button title="@lang('Enable')" type="button"
                        class="btn btn-sm confirmationBtn"
                        data-action="{{ route('admin.role.status', $role->id) }}"
                        data-question="@lang('Are you sure to enable this extension?')">
                        <i class="fa-regular fa-circle-check"></i>
                    </button>
                @else
                    <button title="@lang('Disable')" type="button"
                        class="btn btn-sm confirmationBtn"
                        data-action="{{ route('admin.role.status', $role->id) }}"
                        data-question="@lang('Are you sure to disable this extension?')">
                        <i class="fa-solid fa-ban"></i>
                    </button>
                @endif
                <button title="@lang('Delete')" type="button"
                    class="btn btn-sm btn--danger confirmationBtn"
                    data-action="{{ route('admin.role.delete', $role->id) }}"
                    data-question="@lang('Are you sure to delete this extension?')">
                    <i class="fa-solid fa-trash-can"></i>
                </button>

            </div>
        </td>
    </tr>
@empty

<tr>
    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
</tr>

@endforelse
