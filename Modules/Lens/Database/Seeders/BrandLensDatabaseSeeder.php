<?php

namespace Modules\Lens\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Lens\Entities\BrandLens;
use Modules\Lens\Entities\Feature;
use Modules\Lens\Entities\FeatureBrandLens;

class BrandLensDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name' => "PIXAR",
            ],
            [
                'name' => "PIXARUV",
            ],
            [
                'name' => "PIXARBLUV",
            ]
        ];

        BrandLens::insert($brands);

        $features = [
            [
                'name' => 'Prevents reflections, presents pure vision',
            ],
            [
                'name' => 'Superhydrophobic, water repellent',
            ],
            [
                'name' => 'Anti-scratch',
            ]
        ];

        Feature::insert($features);

        foreach (BrandLens::all() as $brandLens) {
            $brandLens->features()->attach(Feature::all()->pluck('id')->toArray());
        }



        // $this->call("OthersTableSeeder");
    }
}
