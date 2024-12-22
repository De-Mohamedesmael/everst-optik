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
                'color'=>'4eaf30',
            ],
            [
                'name' => "PIXARUV",
                'color'=>'4eaf30',

            ],
            [
                'name' => "PIXARBLUV",
                'color'=>'1a89ca',

            ], [
                'name' => "pixarAquaV",
                'color'=>'00abc6',

            ],[
                'name' => "pixarDriveV",
                'color'=>'eb639f',

            ],[
                'name' => "slcV",
                'color'=>'215395',

            ],

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
            ,
            [
                'name' => 'Anti-dust, easy clean',
            ]
            ,
            [
                'name' => 'Anti-fog',
            ]
            ,
            [
                'name' => 'Max UV Protection',
            ],
            [
                'name' => 'Blue Light protection',
            ]
        ];

        Feature::insert($features);

        foreach (BrandLens::all() as $brandLens) {
            $brandLens->features()->attach(Feature::all()->pluck('id')->toArray());
        }



        // $this->call("OthersTableSeeder");
    }
}
