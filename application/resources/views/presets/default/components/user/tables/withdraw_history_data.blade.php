@forelse($withdraws as $withdraw)
    <tr>
        <td data-label="@lang('TRX No')">
            {{ __($withdraw->trx) }}
        </td>
        <td data-label="@lang('Gateway')">
            {{ __($withdraw->method?->name ?? '') }}
        </td>
        <td class="text-center" data-label="@lang('Date')">
            {{ showDateTime($withdraw->created_at) }}
        </td>
        <td class="text-center" data-label="@lang('Amount')">
            {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount) }}
            </span>
        </td>
        <td class="text-center" data-label="@lang('Conversion')">
            <span>{{ __($withdraw->currency) }}{{ showAmount($withdraw->final_amount) }}</span>
          ( 1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }}
            {{ __($withdraw->currency) }})

        </td>
        <td class="text-center" data-label="@lang('Status')">
            @php echo $withdraw->statusBadge @endphp
        </td>
        <td data-label="@lang('Action')">
            <button class="btn btn-sm btn--base detailBtn"
                data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                @if ($withdraw->status == 3) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                <i class="fa-solid fa-eye"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}
        </td>
    </tr>
@endforelse
