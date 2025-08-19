<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    $notify[] = ['success', 'Cache cleared successfully.'];
    return redirect()->back()->withNotify($notify);
})->name('clear.cache');

Route::namespace('Auth')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
    });

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('admin')->group(function () {

    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request/report', 'requestReport')->name('request.report');
        Route::post('request/report', 'reportSubmit');

        Route::get('download/attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });


    // Users Manager
    Route::middleware('admin.permission:user-management')->controller('ManageUsersController')->name('users.')->prefix('manage/users')->group(function () {
        Route::get('log/{status?}', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email/verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email/unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile/unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile/verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('mobile/verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('with/balance', 'usersWithBalance')->name('with.balance');
        Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
        Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
        Route::get('kyc/verified', 'kycVerifiedUsers')->name('kyc.verified');

        Route::post('bulk-action', 'bulkActionForm')->name('bulk.action');

        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add/sub/balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::get('send/notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send/notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('notify-users', 'showNotificationAllForm')->name('notification.all');
        Route::post('notify-users', 'sendNotificationAll')->name('notification.all.send');
        Route::get('get', 'get')->name('get');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');

        // kyc
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
    });


    Route::middleware('admin.permission:website-menu-management')->name('menu.')->prefix('menu')->controller('MenuController')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id?}', 'storeOrUpdate')->name('storeorupdate');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'remove')->name('delete');
        Route::get('assign-item/{id}', 'assignMenuItem')->name('assign.item');
        Route::post('assign-item/{id}', 'assignMenuItemSubmit')->name('assign.item.submit');
    });

    Route::middleware('admin.permission:website-menu-management')->name('menuitem.')->prefix('menuitem')->controller('MenuItemController')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id?}', 'storeOrUpdate')->name('storeorupdate');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'remove')->name('delete');
    });


    Route::middleware('admin.permission:section-management')->name('custom.section.')->prefix('custom-section')->controller('CustomSectionController')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('delete/{key}', 'delete')->name('delete');
    });

    // KYC Management
    Route::middleware('admin.permission:kyc')->controller('KycController')->group(function () {
        Route::get('kyc-setting', 'setting')->name('kyc.setting');
        Route::post('kyc-setting', 'settingUpdate')->name('kyc.submit');
    });

    // Category
    Route::controller('CategoryController')->name('category.')->prefix('category')->group(function () {
        Route::get('/{status?}', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });

    // Color
    Route::controller('ColorController')->name('color.')->prefix('color')->group(function () {
        Route::get('/{status?}', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });

    // Size
    Route::controller('SizeController')->name('size.')->prefix('size')->group(function () {
        Route::get('/{status?}', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });

    Route::controller('ProductController')->name('auction.product.')->prefix('product')->group(function () {
        Route::post('auction/status/{id}', 'statusUpdate')->name('status');
        Route::get('auction/{status?}', 'auctionProduct')->name('index');
    });

    //Product
    Route::controller('ProductController')->name('product.')->prefix('product')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('delete/{id}', 'imageDelete')->name('delete');
        Route::post('status/{id}', 'statusUpdate')->name('status');
        Route::get('/{status?}', 'index')->name('index');
    });


    //Shipping
    Route::controller('ShippingController')->name('shipping.')->prefix('shipping')->group(function () {
        Route::get('/{status?}', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });


    Route::controller('BidController')->name('bid.')->prefix('bid')->group(function () {
        Route::get('{id}/bids', 'productBids')->name('list');
        Route::get('winner', 'bidWinner')->name('winner');
        Route::get('purchase', 'purchase')->name('purchase');
        Route::post('winners', 'deliveredProduct')->name('product.delivered');
        Route::post('product/re-action', 'reAction')->name('reaction');
    });


    //orders
    Route::controller('OrderController')->name('orders.')->prefix('orders')->group(function () {
        Route::get('order-detail/{id}', 'orderDetail')->name('details');
        Route::post('order-status-update/{id}', 'orderStatusUpdate')->name('status.update');
        Route::get('vendor-orders/{status?}', 'vendorOrder')->name('vendor');
        Route::get('get-orders/{status?}', 'getOrder')->name('get');
        Route::get('get-order-details/{id}', 'getOrderDetails')->name('get.details');
        Route::post('vendor-status-change/{status}/{id}', 'vendorStatusChange')->name('vendor.status.change');
        Route::post('status/{id}', 'statusUpdate')->name('status');
        Route::get('/{status?}', 'index')->name('index');
    });


    // Subscriber
    Route::middleware('admin.permission:subscriber-management')->controller('SubscriberController')->group(function () {
        Route::get('subscriber', 'index')->name('subscriber.index');
        Route::get('subscriber/send/email', 'sendEmailForm')->name('subscriber.send.email');
        Route::post('subscriber/remove/{id}', 'remove')->name('subscriber.remove');
        Route::post('subscriber/send/email', 'sendEmail')->name('subscriber.send.email');
    });

    Route::middleware('admin.permission:role')->name('role.')->prefix('role')->controller('RoleController')->group(function () {
        Route::get('/{status?}', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('permission/seeder', 'seeder')->name('seeder');
    });

    Route::middleware('admin.permission:staff')->name('staff.')->prefix('staff')->controller('StaffController')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('delete/{id}', 'remove')->name('delete');
        Route::get('permission-setup/{id}', 'setup')->name('setup');
        Route::post('permission-update/{id}', 'setupUpdate')->name('setup.update');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('permission/seeder', 'seeder')->name('seeder');
    });


    // Deposit Gateway
    Route::middleware('admin.permission:payment-method')->name('gateway.')->prefix('payment/gateways')->group(function () {
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->group(function () {
            Route::get('automatic/{status?}', 'index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'update')->name('automatic.update');
            Route::post('automatic/remove/{id}', 'remove')->name('automatic.remove');
            Route::post('automatic/activate/{code}', 'activate')->name('automatic.activate');
            Route::post('automatic/deactivate/{code}', 'deactivate')->name('automatic.deactivate');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->group(function () {
            Route::get('manual/new', 'create')->name('manual.create');
            Route::post('manual/new', 'store')->name('manual.store');
            Route::get('manual/{status?}', 'index')->name('manual.index');
            Route::get('manual/edit/{alias}', 'edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'update')->name('manual.update');
            Route::post('manual/activate/{code}', 'activate')->name('manual.activate');
            Route::post('manual/deactivate/{code}', 'deactivate')->name('manual.deactivate');
        });
    });


    // DEPOSIT SYSTEM
    Route::middleware('admin.permission:deposit-management')->name('deposit.')->controller('DepositController')->prefix('manage/deposits')->group(function () {
        Route::get('log/{status?}', 'deposit')->name('log');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::get('successful/{id}', 'successful')->name('successful');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('manage/withdrawals')->group(function () {
        Route::middleware('admin.permission:withdraw-management')->controller('WithdrawalController')->group(function () {
            Route::get('log/{status?}', 'log')->name('log');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });

        // Withdraw Method
        Route::middleware('admin.permission:withdraw-method')->controller('WithdrawMethodController')->group(function () {
            Route::get('method/create', 'create')->name('method.create');
            Route::post('method/create', 'store')->name('method.store');
            Route::get('method/{status?}', 'methods')->name('method.index');
            Route::get('method/edit/{id}', 'edit')->name('method.edit');
            Route::post('method/edit/{id}', 'update')->name('method.update');
            Route::post('method/activate/{id}', 'activate')->name('method.activate');
            Route::post('method/deactivate/{id}', 'deactivate')->name('method.deactivate');
        });
    });

    // Report
    Route::middleware('admin.permission:reports')->controller('ReportController')->group(function () {
        Route::get('report/transaction', 'transaction')->name('report.transaction');
        Route::get('report/login/history', 'loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/notification/history', 'notificationHistory')->name('report.notification.history');
        Route::get('report/email/detail/{id}', 'emailDetails')->name('report.email.details');
    });


    // Admin Support
    Route::middleware('admin.permission:support-ticket')->controller('SupportTicketController')->prefix('support')->group(function () {
        Route::get('tickets/{status?}', 'tickets')->name('ticket');
        Route::post('ticket/close/{id}', 'closeTicket')->name('ticket.close');
        Route::get('tickets/view/{id}', 'ticketReply')->name('ticket.view');
        Route::get('ticket/download/{ticket}', 'ticketDownload')->name('ticket.download');
        Route::post('ticket/delete/{id}', 'ticketDelete')->name('ticket.delete');
        Route::post('ticket/reply/{id}', 'replyTicket')->name('ticket.reply');
    });


    // Language Manager
    Route::middleware('admin.permission:language-management')->controller('LanguageController')->prefix('manage')->group(function () {
        Route::get('languages', 'langManage')->name('language.manage');
        Route::post('language', 'langStore')->name('language.manage.store');
        Route::post('language/delete/{id}', 'langDelete')->name('language.manage.delete');
        Route::post('language/update/{id}', 'langUpdate')->name('language.manage.update');
        Route::get('language/edit/{id}', 'langEdit')->name('language.key');
        Route::post('language/import', 'langImport')->name('language.import.lang');
        Route::post('language/store/key/{id}', 'storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'updateLanguageJson')->name('language.update.key');
        Route::get('language/search/', 'langSearch')->name('language.manage.search');
        Route::get('language/search/replace/', 'langSearchReplace')->name('language.manage.search.replace');
    });


    Route::middleware('admin.permission:settings')->controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('global/settings', 'index')->name('setting.index');
        Route::post('global/settings', 'update')->name('setting.update');

        //configuration
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo', 'logoIconUpdate')->name('setting.logo.icon');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit')->name('setting.cookie.update');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit')->name('setting.custom.css.update');

        //socialite credentials
        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')
            ->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')
            ->name('setting.socialite.credentials.status.update');

        // Maintenance
        Route::get('maintenance', 'maintenance')->name('setting.maintenance');
        Route::post('maintenance', 'maintenanceSubmit')->name('setting.maintenance.update');
    });


    //Notification Setting
    Route::middleware('admin.permission:settings')->name('setting.notification.')->controller('NotificationController')->prefix('notifications')->group(function () {
        //Template Setting
        Route::get('global', 'global')->name('global');
        Route::post('global/update', 'globalUpdate')->name('global.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');
    });


    // Plugin
    Route::middleware('admin.permission:plugin-management')->controller('PluginController')->name('plugins.')->prefix('plugin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });


    // SEO
    Route::middleware('admin.permission:settings')->get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {
        Route::middleware('admin.permission:section-management')->controller('FrontendController')->group(function () {
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::middleware('admin.permission:page-management')->controller('PageBuilderController')->prefix('manage')->group(function () {
            Route::get('pages', 'managePages')->name('manage.pages');
            Route::post('pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('section/{id}', 'manageSection')->name('manage.section');
            Route::post('section/{id}', 'manageSectionUpdate')->name('manage.section.update');
        });
    });
});
