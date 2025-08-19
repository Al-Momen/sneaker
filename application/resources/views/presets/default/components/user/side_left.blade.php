@php
    $user = auth()->user();
@endphp

<div class="sidebar-menu">
    <span class="sidebar-menu__close"><i class="las la-times"></i></span>
    <div class="logo-wrapper px-3">
        <a href="{{ route('home') }}" class="normal-logo" id="normal-logo">
            <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                alt="{{ config('app.name') }}" /></a>
    </div>

    <ul class="sidebar-menu-list">
        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.home') }}"
                class="sidebar-menu-list__link {{ Route::is('user.home') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-border-all"></i>
                </span>
                <span class="text">@lang('Dashboard')</span>
            </a>
        </li>

        <li
            class="sidebar-menu-list__item has-dropdown {{ Route::is('user.product.index') || Route::is('user.auction.product.index') || Route::is('user.bid.list') || Route::is('user.orders.get') || Route::is('user.orders.get.details') ? 'active' : '' }}">
            <a href="javascript:void(0)"
                class="sidebar-menu-list__link {{ Route::is('user.product.index') || Route::is('user.auction.product.index') || Route::is('user.bid.list') || Route::is('user.orders.get') || Route::is('user.orders.get.details') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-shop"></i>
                </span>
                <span class="text">@lang('Vendor Options')</span>
            </a>
            <div
                class="sidebar-submenu {{ Route::is('user.product.index') || Route::is('user.auction.product.index') || Route::is('user.bid.list') || Route::is('user.orders.get') || Route::is('user.orders.get.details') ? 'd-block' : '' }} ">
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ Route::is('user.product.index') ? 'active' : '' }}">
                        <a href="{{ route('user.product.index') }}"
                            class="sidebar-submenu-list__link">@lang('Products')</a>
                    </li>
                    <li
                        class="sidebar-submenu-list__item {{ Route::is('user.auction.product.index') || Route::is('user.bid.list') ? 'active' : '' }}">
                        <a href="{{ route('user.auction.product.index') }}"
                            class="sidebar-submenu-list__link">@lang('Auction')</a>
                    </li>
                    <li
                        class="sidebar-submenu-list__item {{ Route::is('user.orders.get') || Route::is('user.orders.get.details') ? 'active' : '' }}">
                        <a href="{{ route('user.orders.get') }}"
                            class="sidebar-submenu-list__link">@lang('Orders List')</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.orders.index') }}"
                class="sidebar-menu-list__link {{ Route::is('user.orders.index') || Route::is('user.orders.details') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </span>
                <span class="text">@lang('My Orders List')</span>
            </a>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.bid.winning.history') }}"
                class="sidebar-menu-list__link {{ Route::is('user.bid.winning.history') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-hammer"></i>
                </span>
                <span class="text">@lang('Winning Bids')</span>
            </a>
        </li>


        <li
            class="sidebar-menu-list__item has-dropdown {{ Route::is('user.deposit.history') || Route::is('user.deposit') ? 'active' : '' }}">
            <a href="javascript:void(0)"
                class="sidebar-menu-list__link {{ Route::is('user.deposit.history') || Route::is('user.deposit') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </span>
                <span class="text">@lang('Deposit')</span>
            </a>
            <div
                class="sidebar-submenu {{ Route::is('user.deposit.history') || Route::is('user.deposit') ? 'd-block' : '' }} ">
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ Route::is('user.deposit') ? 'active' : '' }}">
                        <a href="{{ route('user.deposit') }}" class="sidebar-submenu-list__link">@lang('Add Money')</a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ Route::is('user.deposit.history') ? 'active' : '' }}">
                        <a href="{{ route('user.deposit.history') }}"
                            class="sidebar-submenu-list__link">@lang('Payment History')</a>
                    </li>
                </ul>
            </div>
        </li>


        <li
            class="sidebar-menu-list__item has-dropdown {{ Route::is('user.withdraw') || Route::is('user.withdraw.history') ? 'active' : '' }}">
            <a href="javascript:void(0)"
                class="sidebar-menu-list__link {{ Route::is('user.withdraw') || Route::is('user.withdraw.history') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </span>
                <span class="text">@lang('Withdrawals')</span>
            </a>
            <div
                class="sidebar-submenu {{ Route::is('user.withdraw') || Route::is('user.withdraw.history') ? 'd-block' : '' }} ">
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ Route::is('user.withdraw') ? 'active' : '' }}">
                        <a href="{{ route('user.withdraw') }}"
                            class="sidebar-submenu-list__link">@lang('Withdrawal')</a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ Route::is('user.withdraw.history') ? 'active' : '' }}">
                        <a href="{{ route('user.withdraw.history') }}"
                            class="sidebar-submenu-list__link">@lang('Withdraw History')</a>
                    </li>
                </ul>
            </div>
        </li>

        <li
            class="sidebar-menu-list__item has-dropdown {{ Route::is('user.kyc.data') || Route::is('user.kyc.form') ? 'active' : '' }}">
            <a href="javascript:void(0)"
                class="sidebar-menu-list__link {{ Route::is('user.kyc.data') || Route::is('user.kyc.form') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-user-shield"></i>
                </span>
                <span class="text">@lang('KYC Setting')</span>
            </a>
            <div
                class="sidebar-submenu {{ Route::is('user.kyc.data') || Route::is('user.kyc.form') ? 'd-block' : '' }}">
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ Route::is('user.kyc.data') ? 'active' : '' }}">
                        <a href="{{ route('user.kyc.data') }}"
                            class="sidebar-submenu-list__link">@lang('KYC Data')</a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ Route::is('user.kyc.form') ? 'active' : '' }}">
                        <a href="{{ route('user.kyc.form') }}"
                            class="sidebar-submenu-list__link">@lang('KYC Form')</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.get.wishlist') }}"
                class="sidebar-menu-list__link {{ Route::is('user.get.wishlist') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-bookmark"></i>
                </span>
                <span class="text">@lang('Bookmarks')</span>
            </a>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.transactions') }}"
                class="sidebar-menu-list__link {{ Route::is('user.transactions') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                </span>
                <span class="text">@lang('Transactions')</span>
            </a>
        </li>


        <li class="sidebar-menu-list__item">
            <a href="{{ route('ticket') }}"
                class="sidebar-menu-list__link {{ Route::is('ticket') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-headset"></i>
                </span>
                <span class="text">@lang('Support Tickets')</span>
            </a>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.twofactor') }}"
                class="sidebar-menu-list__link {{ Route::is('user.twofactor') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-user-gear"></i>
                </span>
                <span class="text">@lang('2FA Security')</span>
            </a>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.profile.setting') }}"
                class="sidebar-menu-list__link {{ Route::is('user.profile.setting') ? 'active' : '' }}">
                <span class="icon">
                    <i class="fa-solid fa-user"></i>
                </span>
                <span class="text">@lang('Profile')</span>
            </a>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
                <span class="icon">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </span>
                <span class="text">@lang('Logout')</span>
            </a>
        </li>
    </ul>
</div>
