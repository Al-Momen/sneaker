@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <form action="" method="GET">
                <div class="d-flex flex-wrap gap-4">
                    <div class="flex-grow-1">
                        <label class="form--label">@lang('Transaction Number')</label>
                        <input type="text" name="search" value="{{ request()->search }}" class="form--control bg--white"
                            placeholder="@lang('Search by transactions')">
                    </div>
                    <div class="flex-grow-1">
                        <label class="form--label">@lang('Type')</label>
                        <select name="type" class="form--control from-select bg--white">
                            <option value="">@lang('All')</option>
                            <option value="+" @selected(request()->type == '+')>@lang('Plus')</option>
                            <option value="-" @selected(request()->type == '-')>@lang('Minus')</option>
                        </select>
                    </div>
                    <div class="flex-grow-1">
                        <label class="form--label">@lang('Remark')</label>
                        <select class="form--control from-select bg--white" name="remark">
                            <option value="">@lang('Any')</option>
                            @foreach ($remarks as $remark)
                                <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                    {{ __(keyToTitle($remark->remark)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-grow-1 align-self-end">
                        <button class="btn btn--base btn--lg w-100"><i class="las la-filter"></i>
                            @lang('Filter')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <div class="base--card bg--white border--none radius--8">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('TRX NO')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td data-label="@lang('TRX NO')" class="fw--500">#{{ $trx->trx }}</td>
                                <td data-label="@lang('Amount')" class="budget">
                                    <span
                                        class="@if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                        {{ $trx->trx_type }} {{ showAmount($trx->amount) }} {{ $general->cur_text }}
                                    </span>
                                </td>

                                <td data-label="@lang('Post Balance')" class="budget">
                                    {{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}
                                </td>

                                <td data-label="@lang('Date')">
                                    {{ showDateTime($trx->created_at, 'd M, Y') }}
                                </td>
                                <td data-label="@lang('Detail')">
                                    {{ __($trx->details) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" data-label="@lang('Transactions Table')" colspan="100%">
                                    {{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if ($transactions->hasPages())
        <div class="row mx-xxl-5 mx-lg-0 my-4">
            <div class="col-lg-12 justify-content-end d-flex">
                {{ $transactions->links() }}
            </div>
        </div>
    @endif
@endsection
