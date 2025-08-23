   @php
       $lastCron = Carbon\Carbon::parse($general->last_cron)->diffInSeconds();
       $cronClass = '';
       if ($lastCron < 300) {
           $cronClass = 'badge--success';
       } elseif ($lastCron > 900) {
           $cronClass = 'badge--warning';
       }
   @endphp

   @extends('admin.layouts.app')
   @section('panel')
       @adminHas('dashboard')
           @if (isset($general->system_info) && !empty(json_decode($general->system_info)->message))
               @if (json_decode($general->system_info)->message)
                   <div class="row">
                       @foreach (json_decode($general->system_info)->message as $msg)
                           <div class="col-md-12">
                               <div class="alert border border--primary" role="alert">
                                   <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
                                   <p class="alert__message">@php echo $msg; @endphp</p>
                                   <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                       <span aria-hidden="true">×</span>
                                   </button>
                               </div>
                           </div>
                       @endforeach
                   </div>
               @endif
           @endif

           <div class="row gy-4">
               <div class="col-xl-12">
                   <div class="row gy-4">

                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative" href="{{ route('admin.product.index') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="menu-icon fa-brands fa-product-hunt"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Products')</span>
                                   <h5 class="number">{{ $widget['total_products'] }}</h5>
                               </div>
                               <span class="badge badge--success position-absolute">
                                   <i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['total_products_percent'] }}%
                               </span>
                           </a>
                       </div>

                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative"
                               href="{{ route('admin.auction.product.index') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="fa-solid fa-gavel"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Auction')</span>
                                   <h5 class="number">{{ $widget['total_auctions'] }}</h5>
                               </div>
                               <span class="badge badge--success position-absolute">
                                   <i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['total_auctions_percent'] }}%
                               </span>
                           </a>
                       </div>
                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative" href="{{ route('admin.users.active') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="dashboard-card-icon fa-solid fa-user-check"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Users')</span>
                                   <h5 class="number">{{ $widget['total_user'] }}</h5>
                               </div>
                               <span class="badge badge--success position-absolute">
                                   <i class="fa-solid fa-arrow-trend-up"></i> +{{ $widget['verified_percent'] }}%
                               </span>
                           </a>
                       </div>

                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative"
                               href="{{ route('admin.users.email.unverified') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="dashboard-card-icon fa-solid fa-envelope-open-text"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Email Unverified')</span>
                                   <h5 class="number">{{ $widget['email_unverified_users'] }}</h5>
                               </div>
                               <span class="badge badge--success position-absolute">
                                   <i class="fa-solid fa-arrow-trend-up"></i> + {{ $widget['email_unverified_percent'] }}%
                               </span>
                           </a>
                       </div>
                   </div>
               </div>


               <div class="col-xl-12">
                   <div class="row gy-4">
                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative" href="{{ route('admin.orders.index') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="dashboard-card-icon fa-solid fa-clipboard-list"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Orders')</span>
                                   <h5 class="number">
                                       {{ $widget['total_orders'] }}</h5>
                               </div>
                           </a>
                       </div>

                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative" href="{{ route('admin.bid.winner') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="dashboard-card-icon fa-solid fa-hammer"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Winning Bids')</span>
                                   <h5 class="number">{{ $widget['total_winner_bids'] }}
                                   </h5>
                               </div>
                           </a>
                       </div>

                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative" href="{{ route('admin.deposit.log') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="dashboard-card-icon fa-solid fa-hand-holding-dollar"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Deposit')</span>
                                   <h5 class="number">
                                       {{ $general->cur_sym }}{{ showAmount($widget['total_deposit_amount'], 2) }}
                                   </h5>
                               </div>
                           </a>
                       </div>

                       <div class="col-xxl-3 col-xl-4 col-md-6">
                           <a class="dashboard-widget--card position-relative" href="{{ route('admin.withdraw.log') }}">
                               <div class="dashboard-widget__icon">
                                   <i class="dashboard-card-icon fa-solid fa-sack-dollar"></i>
                               </div>
                               <div class="dashboard-widget__content">
                                   <span class="title">@lang('Total Withdraw')</span>
                                   <h5 class="number">
                                       {{ $general->cur_sym }}{{ showAmount($widget['total_withdraw_amount'], 2) }}</h5>
                               </div>
                           </a>
                       </div>
                   </div>
               </div>

               <div class="col-xl-6">
                   <div class="card bg--white br--solid">
                       <div class="card-body position-relative">
                           <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                               <h5 class="card-title mb-0">@lang('Monthly Deposit & Withdraw Report')</h5>
                           </div>
                           <div id="account-chart" data-withdrawals="{{ base64_encode(json_encode($withdrawalsChart)) }}"
                               data-deposits="{{ base64_encode(json_encode($depositsChart)) }}"></div>
                       </div>
                   </div>
               </div>

               <div class="col-xl-6">
                   <div class="card bg--white br--solid">
                       <div class="card-body position-relative">
                           <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                               <h5 class="card-title mb-0">@lang('All Orders & My Order Report')</h5>
                           </div>
                           <div id="order-chart" data-all_orders="{{ base64_encode(json_encode($ordersChart)) }}"
                               data-my_orders="{{ base64_encode(json_encode($myOrdersChart)) }}"></div>
                       </div>
                   </div>
               </div>

               <div class="col-xl-6">
                   <div class="card bg--white br--solid">
                       <div class="card-body">
                           <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                               <h5 class="card-title mb-0">@lang('Recent Transactions')</h5>
                           </div>
                           <div class="table-responsive table-responsive--sm">
                               <table class="table align-items-center style--three table--light">
                                   <thead>
                                       <tr>
                                           <th>@lang('User')</th>
                                           <th>@lang('Trx')</th>
                                           <th>@lang('Date')</th>
                                           <th>@lang('Amount')</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       @forelse($transactions as $key=>$trx)
                                           <tr>
                                               <td class="user--td">
                                                   <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                                                       <div
                                                           class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                                                           <div class="user--thumb">
                                                               @if (!empty($trx->user->image))
                                                                   <img src="{{ getImage(getFilePath('userProfile') . '/' . $trx?->user?->image) }}"
                                                                       alt="@lang('Image')">
                                                               @else
                                                                   <img src="{{ getImage('assets/images/general/avatar.png') }}"
                                                                       alt="@lang('Image')">
                                                               @endif
                                                           </div>
                                                           <div class="user--content">
                                                               <a class="text-start"
                                                                   href="{{ route('admin.report.transaction') . '?search=' . $trx->user?->username }}">
                                                                   {{ $trx->user?->fullname }}
                                                               </a>
                                                               <p class="text-start">{{ $trx?->user?->email }}</p>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </td>

                                               <td>
                                                   {{ $trx->trx }}
                                               </td>

                                               <td>
                                                   <h6>{{ showDateTime($trx->created_at, 'd M Y') }}</h6>
                                               </td>
                                               <td>
                                                   <h6>{{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}</h6>
                                               </td>
                                           </tr>
                                       @empty
                                       @endforelse
                                   </tbody>
                               </table>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-xl-6 col-md-4">
                   <div class="card bg--white br--solid">
                       <div class="card-body">
                           <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                               <h5 class="card-title mb-0">@lang('Transactions')</h5>
                           </div>

                           <div class="d-flex justify-content-center align-items-center gap-5 py-4">
                               <div
                                   class="order-info--item d-flex gap-4 flex-column justify-content-center align-items-center">
                                   <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                       <div
                                           class="number--wrap one d-flex justify-content-center align-items-center flex-shrink-0">
                                           <h2 class="m-0 text--white">{{ getAmount($widget['plus_transactions']) }}</h2>
                                       </div>
                                       <p class="fs-6">@lang('Plus Transactions')</p>
                                   </div>
                                   <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                       <div
                                           class="number--wrap two d-flex justify-content-center align-items-center flex-shrink-0">
                                           <h2 class="m-0 text--white">{{ getAmount($widget['minus_transactions']) }}</h2>
                                       </div>
                                       <p class="fs-6">@lang('Minus Transactions')</p>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>

           </div>
       @else
           <div class="row">
               <div class="col-lg-12">
                   <p>@lang('You have no permission to view the page content!')</p>
               </div>
           </div>
       @endadminHas


       {{-- cron modal --}}
       <div class="modal fade" id="cronModal" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true"
           tabindex="-1">
           <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLongTitle">@lang('Cron Job Setting Instruction')</h5>
                       <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">

                       </button>
                   </div>
                   <div class="modal-body">
                       <h3 class="text--danger text-center">@lang('Please Set Cron Job Now')</h3>
                       <p class="lead">
                           @lang('To automate the api order placement, we need to set the cron job and make sure the cron job is running properly. Set the Cron time as minimum as possible. Once per 15-30 minutes is ideal while once every minute is the best option.') </p>
                       <label class="font-weight-bold">@lang('Cron Command')</label>

                       <div class="input-group">
                           <input class="form-control" id="referralURL" name="text" type="text"
                               value="curl -s {{ route('auction.product.winners') }}" readonly>
                           <span class="input-group-text copytext btn btn--primary copyBoard pt-2" id="copyBoard">
                               @lang('Copy')
                           </span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   @endsection


   @push('breadcrumb-plugins')
       <span class="badge {{ $cronClass }}">
           @lang('Last Cron Run')
           <strong>{{ diffForHumans($general->last_cron) }}</strong>
       </span>

       <i class="fa-solid fa-circle-info" data-bs-toggle="modal" data-bs-target="#cronModal"></i>
   @endpush

   @adminHas('dashboard')
       @push('script-lib')
           <script src="{{ asset('assets/admin/js/apexcharts.min.js') }}"></script>
       @endpush

       @push('script')
           <script>
               (function($) {
                   'use strict';
                   // ==================================account chart==================================
                   const $chartData = $('#account-chart');
                   const withdrawalsEncoded = $chartData.data('withdrawals');
                   const depositsEncoded = $chartData.data('deposits');
                   const withdrawalsChart = JSON.parse(atob(withdrawalsEncoded));
                   const depositsChart = JSON.parse(atob(depositsEncoded));

                   var options = {
                       chart: {
                           type: 'bar',
                           stacked: false,
                           height: '310px'
                       },
                       grid: {
                           show: true,
                           strokeDashArray: 4,
                           borderColor: '#e0e0e0',
                           position: 'back'
                       },
                       stroke: {
                           width: 0
                       },
                       dataLabels: {
                           enabled: false
                       },
                       plotOptions: {
                           bar: {
                               columnWidth: '50%',
                               horizontal: false
                           }
                       },
                       colors: ['#FFA500', '#00A86B'],
                       series: [{
                               name: '@lang('Withdrawals')',
                               data: withdrawalsChart.values
                           },
                           {
                               name: '@lang('Deposits')',
                               data: depositsChart.values
                           }
                       ],
                       fill: {
                           opacity: 1
                       },
                       labels: depositsChart.labels,
                       xaxis: {
                           categories: depositsChart.labels,
                           title: {
                               text: '@lang('Months')',
                               style: {
                                   fontSize: '14px',
                                   fontWeight: 'bold',
                                   color: '#333'
                               }
                           },
                           labels: {
                               show: true
                           },
                           axisTicks: {
                               show: true
                           },
                           axisBorder: {
                               show: true
                           }
                       },
                       yaxis: {
                           min: 0,
                           title: {
                               text: '@lang('Amount')',
                               style: {
                                   fontSize: '14px',
                                   fontWeight: 'bold',
                                   color: '#333'
                               }
                           }
                       },
                       tooltip: {
                           shared: true,
                           intersect: false,
                           y: {
                               formatter: function(y) {
                                   return typeof y !== "undefined" ? "{{ __($general->cur_sym) }}" + y.toFixed(0) :
                                       y;
                               }
                           }
                       },
                       legend: {
                           labels: {
                               useSeriesColors: true
                           }
                       }
                   };

                   var chart = new ApexCharts(document.querySelector("#account-chart"), options);
                   chart.render();


                   // ===============================order chart===============================
                   const $orderChartData = $('#order-chart');
                   const allOrdersEncoded = $orderChartData.data('all_orders');
                   const myOrdersEncoded = $orderChartData.data('my_orders');
                   const allOrdersChart = JSON.parse(atob(allOrdersEncoded));
                   const myOrdersChart = JSON.parse(atob(myOrdersEncoded));

                   var options = {
                       chart: {
                           type: 'bar',
                           stacked: false,
                           height: '310px'
                       },
                       grid: {
                           show: true,
                           strokeDashArray: 4,
                           borderColor: '#e0e0e0',
                           position: 'back'
                       },
                       stroke: {
                           width: 0
                       },
                       dataLabels: {
                           enabled: false
                       },
                       plotOptions: {
                           bar: {
                               columnWidth: '50%',
                               horizontal: false
                           }
                       },
                       colors: ['#FFA500', '#00A86B'],
                       series: [{
                               name: '@lang('All Orders')',
                               data: allOrdersChart.values
                           },
                           {
                               name: '@lang('My Orders')',
                               data: myOrdersChart.values
                           }
                       ],
                       fill: {
                           opacity: 1
                       },
                       labels: myOrdersChart.labels,
                       xaxis: {
                           categories: myOrdersChart.labels,
                           title: {
                               text: '@lang('Months')',
                               style: {
                                   fontSize: '14px',
                                   fontWeight: 'bold',
                                   color: '#333'
                               }
                           },
                           labels: {
                               show: true
                           },
                           axisTicks: {
                               show: true
                           },
                           axisBorder: {
                               show: true
                           }
                       },
                       yaxis: {
                           min: 0,
                           title: {
                               text: '@lang('Order Amount')',
                               style: {
                                   fontSize: '14px',
                                   fontWeight: 'bold',
                                   color: '#333'
                               }
                           }
                       },
                       tooltip: {
                           shared: true,
                           intersect: false,
                           y: {
                               formatter: function(y) {
                                   return typeof y !== "undefined" ? "{{ __($general->cur_sym) }}" + y.toFixed(0) :
                                       y;
                               }
                           }
                       },
                       legend: {
                           labels: {
                               useSeriesColors: true
                           }
                       }
                   };
                   var chart = new ApexCharts(document.querySelector("#order-chart"), options);
                   chart.render();



               })(jQuery)
           </script>

           <script>
               (function($) {
                   "use strict";

                   $('.copyBoard').on('click', function() {
                       var copyText = document.getElementById("referralURL");
                       copyText.select();
                       copyText.setSelectionRange(0, 99999);
                       document.execCommand("copy");
                       notify('success', "Copied: " + copyText.value);

                   });
               })(jQuery);
           </script>
       @endpush
   @endadminHas
