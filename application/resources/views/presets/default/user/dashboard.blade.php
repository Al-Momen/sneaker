@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 pb-4">
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                           @lang("Balance")
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">{{ __($general->cur_sym) . showAmount(auth()->user()->balance) }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Deposit
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">$78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Withdraw
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">$78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Support Ticket
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Referral Bonus
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">$78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Earnings
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">$78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Resources
                        </h6>
                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="#">
                <div class="wizard-card d-flex flex-column">
                    <div class="content-wrap d-flex align-items-center justify-content-between gap--12">
                        <h6 class="title fw--600 fs--20 mb-0 text--black">
                            Total Reference
                        </h6>

                        <div
                            class="icon-wrap d-flex justify-content-center align-items-center position-relative overflow-hidden z--1">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="amount-wrap">
                        <h6 class="amount mb-2 text--black7">78,124.00</h6>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row gy-4 pb-4">
        <div class="col-lg-12">
            <div class="base--card bg--white radius--8 border--none">
                <div id="dashboard--chart"></div>
            </div>
        </div>

    </div>

    <div class="row gy-4 pb-4">
      
        <div class="col-lg-12">
            <div class="base--card bg--white radius--8 border--none">
                <h6 class="text--black">@lang("Latest Bookings")</h6>
                <div class="tbl-wrap">
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('SI')</th>
                                <th>@lang('Villa')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Complimentary Pickup')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="SI">#</td>
                                <td data-label="Category">Mockups</td>
                                <td data-label="Type">Png</td>
                                <td data-label="Status">
                                    <span class="badge badge--warning">In Review</span>
                                </td>
                            </tr>
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
                    name: "Products Sales",
                    data: <?php echo json_encode($monthlyBooking['quantities']); ?>
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
                    text: '@lang('Monthly Product Sales')',
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
                    categories: <?php echo json_encode($monthlyBooking['months']); ?>,
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
        })();
    </script>
@endpush
