@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 pb-4">
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.deposit') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Balance')
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">
                            {{ __($general->cur_sym) . showAmount($user->balance) }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.deposit.history') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Total Deposit Money')
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">
                            {{ __($general->cur_sym) . showAmount($data['totalDepositMoney']) }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.withdraw.history') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Total Withdraw Money')
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-money-bill-1-wave"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">
                            {{ __($general->cur_sym) . showAmount($data['totalWithdrawalsMoney']) }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.orders.get') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Total Orders')
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">{{ $data['totalOrders'] }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.orders.index') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('My Orders')
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-cart-plus"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">{{ $data['myOrders'] }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.product.index') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Total Products')
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-brands fa-product-hunt"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">{{ $data['total_products'] }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.auction.product.index') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Total Auction Products')
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">{{ $data['total_auctions'] }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="{{ route('user.bid.winning.history') }}">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            @lang('Total Winning Bids')
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-hammer"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">{{ $data['total_winner_bids'] }}</h6>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="row gy-4 pb-4">
        <div class="col-lg-6">
            <div class="base--card bg--white radius--8 border--none">
                <div id="dashboard--chart"></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="base--card bg--white radius--8 border--none">
                <div id="get--dashboard--chart"></div>
            </div>
        </div>
    </div>


    <div class="row gy-4 pb-4">

        <div class="col-lg-12">
            <div class="base--card bg--white radius--8 border--none">
                <h6 class="text--black">@lang('Latest Transaction')</h6>
                <div class="tbl-wrap">
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
                            @forelse($latestTransaction as $trx)
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
    </div>
@endsection


@push('script')
    <script src="{{ asset('assets/admin/js/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            "use strict";
            var options = {
                series: [{
                    name: "@lang('Product My Orders')",
                    data: <?php echo json_encode($monthlyMyOrders['quantities']); ?>
                }],
                chart: {
                    height: 370,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: '@lang('Monthly My Orders')',
                    align: 'left'
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    borderColor: '#e0e0e0',
                    position: 'back',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0
                    },
                },
                xaxis: {
                    categories: <?php echo json_encode($monthlyMyOrders['months']); ?>,
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial',
                        background: '#333',
                        color: '#fff'
                    },

                }
            };

            var chart = new ApexCharts(document.querySelector("#dashboard--chart"), options);
            chart.render();
        })(jQuery);
        
        (function() {
            "use strict";
            var options = {
                series: [{
                    name: "@lang('Product Vendor Orders')",
                    data: <?php echo json_encode($monthlyVendorOrders['quantities']); ?>
                }],
                chart: {
                    height: 370,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: '@lang('Monthly Vendor Orders')',
                    align: 'left'
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    borderColor: '#e0e0e0',
                    position: 'back',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0
                    },
                },
                xaxis: {
                    categories: <?php echo json_encode($monthlyVendorOrders['months']); ?>,
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial',
                        background: '#333',
                        color: '#fff'
                    },

                }
            };

            var chart = new ApexCharts(document.querySelector("#get--dashboard--chart"), options);
            chart.render();
        })(jQuery);
    </script>
@endpush
