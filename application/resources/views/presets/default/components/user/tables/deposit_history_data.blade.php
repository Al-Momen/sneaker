 @forelse($deposits as $deposit)
     <tr>
         <td data-label="@lang('TRX No')">{{ __($deposit->trx) }}</td>
         <td data-label="@lang('Gateway')">{{ __($deposit->gateway?->name) }}</td>

         <td data-label="@lang('Date')" class="text-center"> {{ showDateTime($deposit->created_at) }} </td>
         <td data-label="@lang('Amount')" class="text-center">
             {{ __($general->cur_sym) . showAmount($deposit->amount) }}
         </td>
         <td data-label="@lang('Conversion')" class="text-center">
             {{ __($deposit->method_currency) . showAmount($deposit->final_amo) }}
         </td>
         <td data-label="@lang('Status')" class="text-center">
             @php echo $deposit->statusBadge @endphp
         </td>
         @php
             $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
         @endphp

         <td data-label="@lang('Action')">
             <a href="javascript:void(0)"
                 class="btn btn--base btn-md action--btn @if ($deposit->method_code >= 1000) detailBtn @else disabled @endif"
                 @if ($deposit->method_code >= 1000) data-info="{{ $details }}" @endif
                 @if ($deposit->status == 3) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                 <i class="fa fa-eye"></i>
             </a>
         </td>
     </tr>
 @empty
     <tr>
         <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
     </tr>
 @endforelse
