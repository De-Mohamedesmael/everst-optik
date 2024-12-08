<?php

namespace Modules\Setting\Database\Seeders;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerType;
use Modules\Hr\Entities\Employee;
use Modules\Hr\Entities\JobType;
use Modules\Setting\Entities\MoneySafe;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\StorePos;
use Modules\Setting\Entities\System;
use Spatie\Permission\Models\Permission;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $admin_data = [
            'name' => 'superadmin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $admin = Admin::create($admin_data);

        $employee_data = [
            'admin_id' => $admin->id,
            'employee_name' => $admin->name,
            'store_id' => ["1"],
            'date_of_start_working' => Carbon::now(),
            'date_of_birth' => '1995-02-03',
            'annual_leave_per_year' => '10',
            'sick_leave_per_year' => '10',
            'mobile' => '123456789',
        ];

        Employee::create($employee_data);



        $permissions = Permission::all();
        $admin->syncPermissions($permissions);



        $modules = Admin::modulePermissionArray();
        $module_settings = [];
        foreach ($modules as $key => $value) {
            $module_settings[$key] = 1;
        }
        System::insert(
            [
                ['key' => 'sender_email', 'value' => 'admin@gmail.com', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'sms_adminname', 'value' => null, 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'sms_password', 'value' => null, 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'sms_sender_name', 'value' => null, 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'time_format', 'value' => 24, 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'timezone', 'value' => 'Asia/Qatar', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'language', 'value' => 'en', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'logo', 'value' => 'logo_def.png', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'site_title', 'value' => 'Everest Optik', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'developed_by', 'value' => '<a target="_blank" href="http://www.fiverr.com/derbari">Derbari</a>', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'help_page_content', 'value' => null, 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'invoice_lang', 'value' => 'en', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'system_type', 'value' => 'pos', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'module_settings', 'value' => json_encode($module_settings), 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'tutorial_guide_url', 'value' => 'https://pos.sherifshalaby.tech', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'show_the_window_printing_prompt', 'value' => '1', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'enable_the_table_reservation', 'value' => '1', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'currency', 'value' => '119', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'numbers_length_after_dot', 'value' => '2', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'font_size_at_invoice', 'value' => 'max', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['key' => 'default_payment_type', 'value' => 'cash', 'created_by' => 1, 'date_and_time' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],


            ]

        );

        CustomerType::create([
            'name' => 'Walk in',
            'created_by' => 1,
        ]);

        Customer::create([
            'name' => 'Walk-in-customer',
            'customer_type_id' => 1,
            'mobile_number' => '12345678',
            'address' => '',
            'email' => null,
            'is_default' => 1,
            'created_by' => 1,
        ]);

        CustomerType::create([
            'name' => 'Retailer',
            'created_by' => 1,
        ]);

        $store = Store::create([
            'name' => 'Default Store',
            'location' => '',
            'phone_number' => '',
            'email' => '',
            'manager_name' => 'superadmin',
            'manager_mobile_number' => '',
            'details' => '',
            'created_by' => 1
        ]);

        StorePos::create([
            'name' => 'Default',
            'store_id' => 1,
            'admin_id' => 1,
            'created_by' => 1
        ]);

        MoneySafe::create([
            'name' => 'Bank Safe',
            'store_id' => 1,
            'currency_id' => 119,
            'type' => 'bank',
            'add_money_admins' => '[]',
            'take_money_admins' => '[]',
            'created_by' => 1,
        ]);

        JobType::insert(
            [
                ['job_title' => 'Cashier', 'date_of_creation' => Carbon::now(), 'created_by' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );
    }
}
