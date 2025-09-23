<?php

namespace App\Traits;

trait FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This trait basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo()
    {
        $data['withdrawVerify'] = [
            'path' => 'assets/images/verify/withdraw'
        ];
        
        $data['depositVerify'] = [
            'path'      => 'assets/images/verify/deposit'
        ];

        $data['verify'] = [
            'path'      => 'assets/verify'
        ];

        $data['default'] = [
            'path'      => 'assets/images/general/default.png',
        ];
        $data['ticket'] = [
            'path'      => 'assets/support',
        ];

        $data['logoIcon'] = [
            'path'      => 'assets/images/general',
            'size'      => '140x40',
        ];

        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['extensions'] = [
            'path'      => 'assets/images/plugins',
            'size'      => '36x36',
        ];

        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];

        $data['userProfile'] = [
            'path'      => 'assets/images/user/profile',
            'size'      => '350x300',
        ];

        $data['adminProfile'] = [
            'path'      => 'assets/admin/images/profile',
            'size'      => '400x400',
        ];

         $data['product'] = [
            'path'      =>'assets/images/backend/product',
            'size'      =>'645x700',
            'thumb'      =>'215x233',
        ];
          $data['language'] = [
            'path' => 'assets/images/language',
            'size' => '50x50'
        ];

        $data['paymentGateway'] = [
            'path'      => 'assets/images/gateway/method',
            'size'      => '400x400',
        ];
        
        $data['withdrawMethod'] = [
            'path'      => 'assets/images/withdraw/method',
            'size'      => '400x400',
        ];

        $data['banner'] = [
            'path'      =>'assets/images/frontend/banner',
        ];

        $data['testimonial'] = [
            'path'      =>'assets/images/frontend/testimonial/',
        ];

        $data['app_download_section'] = [
            'path'      =>'assets/images/frontend/app_download_section/',
        ];

        $data['contact'] = [
            'path'      =>'assets/images/frontend/contact_us/',
        ];

        $data['shape'] = [
            'path'      =>'assets/images/frontend/shape/',
        ];

        $data['icon'] = [
            'path'      =>'assets/images/frontend/icon/',
        ];

        $data['error'] = [
            'path'      =>'assets/images/errors/',
        ];

        return $data;
    }
}
