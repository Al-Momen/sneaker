<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->prefix('social')->group(function () {
        Route::get('login/{provider}', 'socialLogin')->name('social.login');
        Route::get('login/callback/{provider}', 'callback')->name('social.login.callback');
    });
    
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend/verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify/email', 'emailVerification')->name('verify.email');
        Route::post('verify/mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify/g2fa', 'g2faVerification')->name('go2fa.verify');
    });



    Route::middleware(['check.status'])->group(function () {

        Route::get('user/data', 'User\UserController@userData')->name('data');
        Route::post('user/data/submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');

                 Route::get('/checkout', 'getCheckOut')->name('get.checkout');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //Report

                Route::name('deposit.')->prefix('deposit/history')->group(function () {
                    Route::post('status/{id}', 'statusUpdate')->name('status');
                    Route::get('{status?}', 'depositHistory')->name('history');
                });


                Route::get('transactions', 'transactions')->name('transactions');
                Route::post('/reviews', 'reviewStore')->name('reviews.store');
                Route::get('toggle-wishlist', 'toggleWishlist')->name('wishlist.added');
                Route::get('wishlist-list', 'getWishlist')->name('get.wishlist');
                Route::post('remove-wishlist/{id}', 'removeWishlist')->name('remove.wishlist');


                //Product
                Route::controller('ProductController')->name('product.')->prefix('product')->group(function () {
                    Route::get('/create', 'create')->name('create')->middleware('client_kyc');
                    Route::post('store', 'store')->name('store')->middleware('client_kyc');
                    Route::get('edit/{id}', 'edit')->name('edit')->middleware('client_kyc');
                    Route::post('update/{id}', 'update')->name('update')->middleware('client_kyc');
                    Route::post('delete/{id}', 'imageDelete')->name('delete')->middleware('client_kyc');
                    Route::post('status/{id}', 'statusUpdate')->name('status');
                    Route::get('/{status?}', 'index')->name('index');
                });

                Route::controller('ProductController')->name('auction.product.')->prefix('auction/product')->group(function () {
                    Route::get('auction/{status?}', 'auctionProduct')->name('index');
                    Route::post('auction/status/{id}', 'statusUpdate')->name('status');
                });

                //kyc
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
            });

            Route::controller('BidController')->group(function () {
                Route::post('bid', 'bid')->name('bid');
                Route::get('biding/list/{id}', 'list')->name('bid.list');
                Route::get('my/bidding-history', 'myBiddingHistory')->name('my.bid.history');
                Route::get('winning/history', 'winningHistory')->name('bid.winning.history');
            });


            //orders
            Route::controller('OrderController')->name('orders.')->prefix('orders')->group(function () {

                Route::get('order-detail/{id}', 'orderDetails')->name('details');
                Route::get('get-orders/{status?}', 'getOrder')->name('get');
                Route::get('get-order-details/{id}', 'getOrderDetails')->name('get.details');
                Route::post('vendor-status-change/{status}/{id}', 'vendorStatusChange')->name('vendor.status.change');
                Route::get('/{status?}', 'index')->name('index');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile/setting', 'profile')->name('profile.setting');
                Route::post('profile/setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
                Route::post('profile-image', 'profileUpdate')->name('profile.image.update');
            });


            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::get('/', 'withdrawMoney')->middleware('client_kyc');
                Route::post('/', 'withdrawStore')->name('.money')->middleware('client_kyc');
                Route::get('preview', 'withdrawPreview')->name('.preview')->middleware('client_kyc');
                Route::post('preview', 'withdrawSubmit')->name('.submit')->middleware('client_kyc');
                Route::post('history/status/{id}', 'statusUpdate')->name('.status');
                Route::get('history/{status?}', 'withdrawLog')->name('.history');
            });
        });

        // Payment
        Route::middleware('registration.complete')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/deposit', 'deposit')->name('deposit');
            Route::post('/product/payment', 'productPayment')->name('product.payment');
            Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
            Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
        });
    });
});
