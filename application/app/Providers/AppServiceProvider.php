<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
use App\Lib\Searchable;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Language;
use App\Constants\Status;
use App\Models\Withdrawal;
use App\Models\SupportTicket;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $general                         = gs();
        $activeTemplate                  = activeTemplate();
        $viewShare['general']            = $general;
        $viewShare['activeTemplate']     = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language']           = Language::all();
        $viewShare['emptyMessage']       = 'No data';
        view()->share($viewShare);


        view()->composer('admin.components.tabs.user', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'    => User::kycUnverified()->count(),
                'kycPendingUsersCount'       => User::kycPending()->count(),
            ]);
        });
        view()->composer('admin.components.tabs.deposit', function ($view) {
            $view->with([
                'pendingDepositsCount' => Deposit::pending()->count(),
            ]);
        });
        view()->composer('admin.components.tabs.withdrawal', function ($view) {
            $view->with([
                'pendingWithdrawCount' => Withdrawal::pending()->count(),
            ]);
        });
        view()->composer('admin.support.tickets', function ($view) {
            $view->with([
                'pendingTicketCount' => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
            ]);
        });
        view()->composer('admin.components.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'    => User::kycUnverified()->count(),
                'kycPendingUsersCount'       => User::kycPending()->count(),
                'pendingTicketCount'         => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'       => Deposit::pending()->count(),
                'pendingWithdrawCount'       => Withdrawal::pending()->count(),
                'pendingOrderCount'          => Order::where('status', 2)->count(),
            ]);
        });


        view()->composer($activeTemplate . 'components.user.side_left', function ($view) {
            $view->with([
                'vendorPendingOrderCount'          => Order::where('status', 1)->whereHas('products', function ($q) {
                    $q->where('author_id', auth()->id())
                        ->where('author_type', 2);
                })
                    ->count()
            ]);
       
        });

        view()->composer('admin.components.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('read_status', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('read_status', Status::NO)->count(),
            ]);
        });

        view()->composer('includes.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if ($general->force_ssl) {
            URL::forceScheme('https');
        }

        View::addNamespace('Template', resource_path('views/presets/' . activeTemplateName()));
        View::addNamespace('Admin', resource_path('views/admin'));
        View::addNamespace('UserTemplate', resource_path('views/presets/' . activeTemplateName() . '/user'));


        Paginator::useBootstrapFive();

        // Admin Directive
        Blade::directive('adminHas', function ($expression) {
            return "<?php if(auth()->guard('admin')->check() && auth()->guard('admin')->user()->hasPermission($expression)): ?>";
        });

        Blade::directive('endadminHas', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('adminHasAny', function ($expression) {
            return "<?php if(auth()->guard('admin')->check() && collect($expression)->some(fn(\$perm) => auth()->guard('admin')->user()->hasPermission(\$perm))): ?>";
        });

        Blade::directive('endadminHasAny', function () {
            return "<?php endif; ?>";
        });
    }
}
