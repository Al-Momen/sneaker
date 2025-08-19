<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'groupby' => 'Dashboard',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 2,
                'name' => 'Role',
                'slug' => 'role',
                'groupby' => 'Role Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 3,
                'name' => 'Staff Management',
                'slug' => 'staff',
                'groupby' => 'Staff Mangement',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 4,
                'name' => 'User Management',
                'slug' => 'user-management',
                'groupby' => 'User Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 5,
                'name' => 'Subscriber Management',
                'slug' => 'subscriber-management',
                'groupby' => 'Subscriber Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 6,
                'name' => 'Deposit Management',
                'slug' => 'deposit-management',
                'groupby' => 'Deposit Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 7,
                'name' => 'Withdraw Management',
                'slug' => 'withdraw-management',
                'groupby' => 'Withdraw Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 8,
                'name' => 'Payment Method',
                'slug' => 'payment-method',
                'groupby' => 'Payment Method',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 9,
                'name' => 'Withdraw Method',
                'slug' => 'withdraw-method',
                'groupby' => 'Withdraw Method',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 10,
                'name' => 'Support Ticket',
                'slug' => 'support-ticket',
                'groupby' => 'Support Ticket',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 11,
                'name' => 'Reports',
                'slug' => 'reports',
                'groupby' => 'Report Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 12,
                'name' => 'Settings',
                'slug' => 'settings',
                'groupby' => 'Global Settings',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 13,
                'name' => 'Page Management',
                'slug' => 'page-management',
                'groupby' => 'Page Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 14,
                'name' => 'Section Management',
                'slug' => 'section-management',
                'groupby' => 'Section Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 15,
                'name' => 'Language Management',
                'slug' => 'language-management',
                'groupby' => 'Language Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 16,
                'name' => 'Plugin Management',
                'slug' => 'plugin-management',
                'groupby' => 'Plugin Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 17,
                'name' => 'Kyc',
                'slug' => 'kyc',
                'groupby' => 'Kyc Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 18,
                'name' => 'Admin Notification',
                'slug' => 'admin-notification',
                'groupby' => 'Topbar Notification',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'id' => 19,
                'name' => 'Website Menu Management',
                'slug' => 'website-menu-management',
                'groupby' => 'Website Menu Management',
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}

