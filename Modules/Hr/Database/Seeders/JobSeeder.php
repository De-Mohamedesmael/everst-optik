<?php

namespace Modules\Hr\Database\Seeders;


use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Hr\Entities\JobType;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        JobType::insert(
            [
                ['job_title' => 'Accountant', 'date_of_creation' => Carbon::now(), 'created_by' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );
    }
}
