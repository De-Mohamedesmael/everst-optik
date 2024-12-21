<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Hr\Database\Seeders\JobSeeder;
use Modules\Hr\Database\Seeders\PermissionTableSeeder;
use Modules\Lens\Database\Seeders\BrandLensDatabaseSeeder;
use Modules\Setting\Database\Seeders\CurrenciesTableSeeder;
use Modules\Setting\Database\Seeders\SettingDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            PermissionTableSeeder::class,
            SettingDatabaseSeeder::class,
            CurrenciesTableSeeder::class,
            JobSeeder::class,
            BrandLensDatabaseSeeder::class,
        ]);
    }
}
