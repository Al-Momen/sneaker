<div class="sidebar">
    <button class="res-sidebar-close-btn"><i class="fa-solid fa-xmark"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('admin.dashboard') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>

        <div class="sidebar__menu-wrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-chart-line"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>


                @adminHas('user-management')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('admin.users.*', 3) }}">
                            <i class="menu-icon fa-regular fa-user"></i>
                            <span class="menu-title">@lang('All Users')</span>
                            @if (
                                $bannedUsersCount > 0 ||
                                    $emailUnverifiedUsersCount > 0 ||
                                    $mobileUnverifiedUsersCount > 0 ||
                                    $kycPendingUsersCount > 0 ||
                                    $kycUnverifiedUsersCount > 0)
                                <div class="blob white"></div>
                            @endif
                        </a>
                        <div class="sidebar-submenu {{ menuActive('admin.users.*', 2) }} ">
                            <ul>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.all']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.all') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('All')</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.active']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.active') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Active')</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.banned']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.banned') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title">@lang('Banned')</span>
                                        @if ($bannedUsersCount)
                                            <span
                                                class="badge rounded-pill bg--primary text-white ms-2">{{ $bannedUsersCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.email.unverified']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.email.unverified') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Email Unverified')</span>
                                        @if ($emailUnverifiedUsersCount)
                                            <span
                                                class="badge rounded-pill bg--primary text-white ms-2">{{ $emailUnverifiedUsersCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.mobile.unverified']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.mobile.unverified') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Mobile Unverified')</span>
                                        @if ($mobileUnverifiedUsersCount)
                                            <span
                                                class="badge rounded-pill bg--primary text-white ms-2">{{ $mobileUnverifiedUsersCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.kyc.unverified']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.kyc.unverified') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('KYC Unverified')</span>
                                        @if ($kycUnverifiedUsersCount)
                                            <span
                                                class="badge rounded-pill bg--primary text-white ms-2">{{ $kycUnverifiedUsersCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.kyc.pending']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.kyc.pending') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('KYC Pending')</span>
                                        @if ($kycPendingUsersCount)
                                            <span
                                                class="badge rounded-pill bg--primary text-white ms-2">{{ $kycPendingUsersCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.with.balance']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.with.balance') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('With Balance')</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive(['admin.users.notification.all']) }}">
                                    <a class="nav-link" href="{{ route('admin.users.notification.all') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Notification to Users')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endadminHas


                @adminHas('role')
                    <li class="sidebar-menu-item {{ menuActive('admin.role.*') }}">
                        <a href="{{ route('admin.role.index') }}" class="nav-link ">
                            <i class="menu-icon fa-solid fa-file-shield"></i>
                            <span class="menu-title">@lang('Role')</span>
                        </a>
                    </li>
                @endadminHas

                @adminHas('staff')
                    <li class="sidebar-menu-item {{ menuActive('admin.staff.*') }}">
                        <a href="{{ route('admin.staff.index') }}" class="nav-link ">
                            <i class="menu-icon fa-solid fa-users"></i>
                            <span class="menu-title">@lang('Staff')</span>
                        </a>
                    </li>
                @endadminHas


                @adminHas('product')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)"
                            class="{{ menuActive(['admin.product.*', 'admin.auction.product.*', 'admin.bid.list', 'admin.size.*', 'admin.color.*', 'admin.category.*', 'admin.bid.winner*'], 3) }}">
                            <i class="menu-icon fa-brands fa-product-hunt"></i>
                            <span class="menu-title">@lang('Products')</span>
                        </a>
                        <div
                            class="sidebar-submenu {{ menuActive(['admin.product.*', 'admin.auction.product.*', 'admin.bid.list', 'admin.size.*', 'admin.color.*', 'admin.category.*', 'admin.bid.winner*'], 2) }} ">
                            <ul>
                                <li class="sidebar-menu-item {{ menuActive('admin.product.*') }}">
                                    <a class="nav-link" href="{{ route('admin.product.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Products')</span>
                                    </a>
                                </li>

                                <li
                                    class="sidebar-menu-item {{ menuActive(['admin.auction.product.*', 'admin.bid.list']) }}">
                                    <a class="nav-link" href="{{ route('admin.auction.product.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Auctions')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive(['admin.category.index']) }}">
                                    <a class="nav-link" href="{{ route('admin.category.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Categories')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive(['admin.size.index']) }}">
                                    <a class="nav-link" href="{{ route('admin.size.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Sizes')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive(['admin.color.index']) }}">
                                    <a class="nav-link" href="{{ route('admin.color.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Colors')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive(['admin.bid.winner']) }}">
                                    <a class="nav-link" href="{{ route('admin.bid.winner') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Winners')</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endadminHas

                @adminHas('order')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)"
                            class="{{ menuActive(['admin.orders.*'], 3) }}">
                            <i class="menu-icon fa-solid fa-cart-arrow-down"></i>
                            <span class="menu-title">@lang('Orders')</span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive(['admin.orders.*'], 2) }} ">
                            <ul>
                                <li class="sidebar-menu-item {{ menuActive('admin.orders.index') }}">
                                    <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('All Orders')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive('admin.orders.vendor') }}">
                                    <a class="nav-link" href="{{ route('admin.orders.vendor') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Vendor Orders')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive('admin.orders.get') }}">
                                    <a class="nav-link" href="{{ route('admin.orders.get') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('In-house Orders')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endadminHas

                @adminHas('shipping')
                    <li class="sidebar-menu-item {{ menuActive('admin.shipping.*') }}">
                        <a href="{{ route('admin.shipping.index') }}" class="nav-link ">

                            <i class="menu-icon fa-solid fa-truck-fast"></i>
                            <span class="menu-title">@lang('Shipping')</span>
                        </a>
                    </li>
                @endadminHas

                @adminHas('website-menu-management')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive(['admin.menu.*', 'admin.menuitem.*'], 3) }}">
                            <i class="menu-icon fa-solid fa-bars"></i>
                            <span class="menu-title">@lang('Website Menus')</span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive(['admin.menu.*', 'admin.menuitem.*'], 2) }} ">
                            <ul>
                                <li class="sidebar-menu-item {{ menuActive('admin.menu.*') }}">
                                    <a class="nav-link" href="{{ route('admin.menu.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Menus')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive('admin.menuitem.*') }}">
                                    <a class="nav-link" href="{{ route('admin.menuitem.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Menu Items')</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endadminHas




                @adminHas('kyc')
                    <li class="sidebar-menu-item {{ menuActive('admin.kyc.setting') }}">
                        <a href="{{ route('admin.kyc.setting') }}" class="nav-link ">
                            <i class="menu-icon fa-solid fa-shield-halved"></i>
                            <span class="menu-title">@lang('KYC Setting')</span>
                        </a>
                    </li>
                @endadminHas


                @adminHas('subscriber-management')
                    <li class="sidebar-menu-item  {{ menuActive('admin.subscriber.*') }}">
                        <a href="{{ route('admin.subscriber.index') }}" class="nav-link"
                            data-default-url="{{ route('admin.subscriber.index') }}">
                            <i class="menu-icon fa-regular fa-envelope"></i>
                            <span class="menu-title">@lang('Subscribers') </span>
                        </a>
                    </li>
                @endadminHas

                @adminHas('deposit-management')
                    <li class="sidebar-menu-item {{ menuActive('admin.deposit.*') }}">
                        <a href="{{ route('admin.deposit.log') }}" class="nav-link ">
                            <i class="menu-icon fa-solid fa-wallet"></i>
                            <span class="menu-title">@lang('Deposits')</span>
                            @if (0 < $pendingDepositsCount)
                                <div class="blob white">
                                </div>
                            @endif
                        </a>
                    </li>
                @endadminHas

                @adminHas('withdraw-management')
                    <li class="sidebar-menu-item {{ menuActive(['admin.withdraw.details', 'admin.withdraw.log']) }}">
                        <a href="{{ route('admin.withdraw.log') }}" class="nav-link ">
                            <i class="menu-icon fa-regular fa-credit-card"></i>
                            <span class="menu-title">@lang('Withdrawals')</span>
                            @if (0 < $pendingWithdrawCount)
                                <div class="blob white">
                                </div>
                            @endif
                        </a>
                    </li>
                @endadminHas

                @adminHas('payment-method')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('admin.gateway.*', 3) }}">
                            <i class="menu-icon fa-solid fa-dollar-sign"></i>
                            <span class="menu-title">@lang('Payment Methods')</span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('admin.gateway.*', 2) }} ">
                            <ul>
                                <li class="sidebar-menu-item {{ menuActive('admin.gateway.automatic.index') }}">
                                    <a class="nav-link" href="{{ route('admin.gateway.automatic.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Automatic Gateways')</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item {{ menuActive('admin.gateway.manual.index') }}">
                                    <a class="nav-link" href="{{ route('admin.gateway.manual.index') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Manual Gateways')</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endadminHas

                @adminHas('withdraw-method')
                    <li class="sidebar-menu-item {{ menuActive('admin.withdraw.method.*') }}">
                        <a href="{{ route('admin.withdraw.method.index') }}" class="nav-link ">
                            <i class="menu-icon las la-dollar-sign"></i>
                            <span class="menu-title">@lang('Withdraw Methods')</span>
                        </a>
                    </li>
                @endadminHas



                @adminHas('reports')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)" class="{{ menuActive('admin.report.*', 3) }}">
                            <i class="menu-icon fa-solid fa-chart-line"></i>
                            <span class="menu-title">@lang('Reports')</span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('admin.report.*', 2) }} ">
                            <ul>
                                <li
                                    class="sidebar-menu-item {{ menuActive(['admin.report.transaction', 'admin.report.transaction.search']) }}">
                                    <a class="nav-link" href="{{ route('admin.report.transaction') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Transactions')</span>
                                    </a>
                                </li>
                                <li
                                    class="sidebar-menu-item {{ menuActive(['admin.report.login.history', 'admin.report.login.ipHistory']) }}">
                                    <a class="nav-link" href="{{ route('admin.report.login.history') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Login Activities')</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item {{ menuActive('admin.report.notification.history') }}">
                                    <a class="nav-link" href="{{ route('admin.report.notification.history') }}">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title"> @lang('Notifications')</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endadminHas

                @adminHas('support-ticket')
                    <li
                        class="sidebar-menu-item {{ menuActive(['admin.ticket', 'admin.ticket.reply', 'admin.ticket.view', 'admin.ticket.delete', 'admin.ticket.filter']) }}">
                        <a href="{{ route('admin.ticket') }}" class="nav-link ">
                            <i class="menu-icon fa-regular fa-life-ring"></i>
                            <span class="menu-title">@lang('Support Ticket')</span>
                            @if (0 < $pendingTicketCount)
                                <div class="blob white">
                                </div>
                            @endif
                        </a>
                    </li>
                @endadminHas

                @adminHas('page-management')
                    <li class="sidebar-menu-item {{ menuActive('admin.frontend.manage.*') }}">
                        <a href="{{ route('admin.frontend.manage.pages') }}" class="nav-link ">
                            <i class="menu-icon fa-solid fa-pager"></i>
                            <span class="menu-title">@lang('Pages')</span>
                        </a>
                    </li>
                @endadminHas


                @adminHas('section-management')
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)"
                            class="{{ menuActive(['admin.frontend.sections*', 'admin.custom.section.index'], 3) }}">
                            <i class="menu-icon fa-solid fa-grip"></i>
                            <span class="menu-title">@lang('Sections')</span>
                        </a>
                        <div
                            class="sidebar-submenu {{ menuActive(['admin.frontend.sections*', 'admin.custom.section.index'], 2) }} ">
                            <ul>
                                <li class="sidebar-menu-item {{ menuActive('admin.custom.section.index') }}">
                                    <a href="{{ route('admin.custom.section.index') }}" class="nav-link">
                                        <i class="menu-icon fa-solid fa-circle"></i>
                                        <span class="menu-title">@lang('Add HTML Section')</span>
                                    </a>
                                </li>
                                @php
                                    $lastSegment = collect(request()->segments())->last();
                                @endphp
                                @foreach (getPageSections(true) as $k => $secs)
                                    @if ($secs['builder'])
                                        <li class="sidebar-menu-item  @if ($lastSegment == $k) active @endif ">
                                            <a href="{{ route('admin.frontend.sections', $k) }}" class="nav-link">
                                                <i class="menu-icon fa-solid fa-circle"></i>
                                                <span class="menu-title">{{ __($secs['name']) }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endadminHas

                @adminHas('settings')
                    <li
                        class="sidebar-menu-item {{ menuActive(['admin.setting.index', 'admin.setting.logo.icon', 'admin.setting.notification.*', 'admin.seo', 'admin.setting.cookie', 'admin.setting.custom.css', 'admin.setting.maintenance']) }}">
                        <a href="{{ route('admin.setting.index') }}" class="nav-link">
                            <i class="menu-icon fa-solid fa-earth-europe"></i>
                            <span class="menu-title">@lang('Global Settings')</span>
                        </a>
                    </li>
                @endadminHas

                @adminHas('sociallite')
                    <li class="sidebar-menu-item {{ menuActive('admin.setting.socialite.credentials') }}">
                        <a href="{{ route('admin.setting.socialite.credentials') }}" class="nav-link">
                            <i class="menu-icon fa-solid fa-user-gear"></i>
                            <span class="menu-title">@lang('Social Credentials')</span>
                        </a>
                    </li>
                @endadminHas

                @adminHas('plugin-management')
                    <li class="sidebar-menu-item  {{ menuActive('admin.plugins.index') }}">
                        <a href="{{ route('admin.plugins.index') }}" class="nav-link">
                            <i class="menu-icon fa-solid fa-puzzle-piece"></i>
                            <span class="menu-title">@lang('Plugins')</span>
                        </a>
                    </li>
                @endadminHas



                @adminHas('language-management')
                    <li class="sidebar-menu-item  {{ menuActive(['admin.language.manage', 'admin.language.key']) }}">
                        <a href="{{ route('admin.language.manage') }}" class="nav-link"
                            data-default-url="{{ route('admin.language.manage') }}">
                            <i class="menu-icon fa-solid fa-language"></i>
                            <span class="menu-title">@lang('Language') </span>
                        </a>
                    </li>
                @endadminHas

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.clear.cache') }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-broom"></i>
                        <span class="menu-title">@lang('Clear Cache')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="menu-icon fa-solid fa-code-branch"></i>
                        <span class="menu-title">@lang('Panel') {{ sysInfo()['admin_version'] }}</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>



@push('script')
    <script>
        (function($) {
            'use strict';
            var $scroll = $('.sidebar__menu-wrapper');

            $('.sidebar-menu-item.active').each(function() {
                var itemPosition = $(this).offset().top - $scroll.offset().top + $scroll.scrollTop() - 110;
                $scroll.animate({
                    scrollTop: itemPosition
                }, 500);
            });
        })(jQuery);
    </script>
@endpush
