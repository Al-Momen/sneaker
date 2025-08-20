@forelse ($products as $item)
    <tr>
        <td>
            <img src="{{ getImage(getFilePath('product') . '/' . 'thumb_' . $item->firstImage?->image ?? '') }}"
                alt="@lang('Image')" class="rounded img-thumbnail" style="width:60px;">
        </td>

        <td>{{ __(strLimit($item->name, 30)) }}</td>

        <td>
            @if ($item->author_type == 1)
                <span class="text--primary">{{ $item->author_name }}</span>
            @else
                <a href="{{ route('admin.users.detail', $item->author_id) }}">{{ '@' . $item->author_name }}</a>
            @endif
        </td>

        <td>
            @if ($item->type == 1)
                @lang('Product')
            @else
                @lang('Auction')
            @endif
        </td>


        <td>
            {{ $general->cur_sym }}{{ showAmount($item->price) }}
        </td>

        <td>
            @if (isset($item->discount))
                {{ showAmount($item->discount) }}%
            @else
                <span>@lang('No')</span>
            @endif
        </td>

        <td>
            @php echo $item->statusBadge($item->status); @endphp
        </td>

        <td>

            <div class="button--group align-items-center">
                @if ($item->type == 2)
                    <a href="{{ route('admin.bid.list', $item->id) }}" title="@lang('Biding List')"
                        class="btn btn--base text--white">
                        <i class="fa-solid fa-list"></i>
                    </a>
                @endif

                @if ($item->status == 0)
                    <button title="@lang('Status')" type="button" class="btn btn--success confirmationBtn"
                        data-action="{{ route('admin.product.status', $item->id) }}" data-question="@lang('Are you sure to change this product status?')">
                        <i class="la la-check-circle"></i>
                    </button>
                @else
                    <button title="@lang('Status')" type="button" class="btn btn--danger confirmationBtn"
                        data-action="{{ route('admin.product.status', $item->id) }}" data-question="@lang('Are you sure to change this product status?')">
                        <i class="la la-ban"></i>
                    </button>
                @endif

                @if ($item->author_type == 1)
                    <a href="{{ route('admin.product.edit', $item->id) }}" class="btn btn--primary"><i
                            class="las la-edit"></i></a>
                @endif

                 <a href="{{ route('product.details', ['slug' => slug($item->name), 'id' => $item->id]) }}"
                    class="btn btn--primary">
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
