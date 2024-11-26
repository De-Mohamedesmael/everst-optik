<?php

namespace Modules\Hr\Database\Seeders;

use Modules\Hr\Entities\Employee;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $modulePermissionArray = Admin::modulePermissionArray();
        $subModulePermissionArray = Admin::subModulePermissionArray();

        $data = [];
        foreach ($modulePermissionArray as $key_module => $moudle) {
            if (!empty($subModulePermissionArray[$key_module])) {
                foreach ($subModulePermissionArray[$key_module] as $key_sub_module =>  $sub_module) {
                    $data[] = ['name' => $key_module . '.' . $key_sub_module . '.view'];
                    $data[] = ['name' => $key_module . '.' . $key_sub_module . '.create_and_edit'];
                    $data[] = ['name' => $key_module . '.' . $key_sub_module . '.delete'];
                }
            }
        }

        $insert_data = [];
        $time_stamp = Carbon::now()->toDateTimeString();
        foreach ($data as $d) {
            $d['guard_name'] = 'admin';
            $d['created_at'] = $time_stamp;
            $insert_data[] = $d;
        }
        Permission::insert($insert_data);
    }
}
