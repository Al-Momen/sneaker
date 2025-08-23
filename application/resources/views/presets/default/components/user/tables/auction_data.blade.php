@forelse ($products as $item)
    <tr>
        <td data-label="@lang('Image')">
            <img src="{{ getImage(getFilePath('product') . '/' . 'thumb_' . $item->firstImage?->image ?? '') }}"
                alt="@lang('Image')" class="rounded img-thumbnail" style="width:60px;">
        </td>

        <td data-label="@lang('Name')">
            <a href="{{ route('product.details', ['slug' => slug($item->name), 'id' => $item->id]) }}"
                class="btn text--base">
                {{ __(strLimit($item->name, 30)) }}
            </a>
        </td>

        <td data-label="@lang('Category')">
            @if (isset($item->category))
                {{ __($item->category->name) }}
            @else
                @lang('N/A')
            @endif
        </td>

        <td data-label="@lang('Starting Price')">
            {{ $general->cur_sym }}{{ showAmount($item->min_price) }}
        </td>


        <td data-label="@lang('Status')">
            @php echo $item->statusBadge($item->status); @endphp
        </td>

        <td data-label="@lang('Action')">

            <div class="button--group align-items-center">

                @if ($item->type == 2)
                    <a href="{{ route('user.bid.list', $item->id) }}" title="@lang('Biding List')"
                        class="btn btn--base text--white">
                        <i class="fa-solid fa-list"></i>
                    </a>
                @endif

                @if ($item->type == 2 && $item->start_date > now() && $item->status == 0)
                    <button title="@lang('Status')" type="button"
                        class="btn btn--success confirmationBtn text--white"
                        data-action="{{ route('user.product.status', $item->id) }}" data-question="@lang('Are you sure to change this product status?')">
                        <i class="fa-regular fa-circle-check"></i>
                    </button>
                @endif
          

                @if ($item->type == 2 && $item->start_date > now() && $item->status == 1)
                    <button title="@lang('Status')" type="button"
                        class="btn btn--danger confirmationBtn text--white"
                        data-action="{{ route('user.product.status', $item->id) }}" data-question="@lang('Are you sure to change this product status?')">
                        <i class="fa-solid fa-ban"></i>
                    </button>
                @endif

                <a href="{{ route('user.product.edit', $item->id) }}" title="@lang('Edit')"
                    class="btn btn--base text--white">
                    <i class="fa-solid fa-pen-to-square"></i></a>

            </div>
        </td>
    </tr>
@empty
    <tr>
        <td data-label="@lang('Date')" class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
