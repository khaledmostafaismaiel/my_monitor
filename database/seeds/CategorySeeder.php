<?php

use App\Models\Category;
use App\Models\Family;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::firstOrCreate(
            [
                'name'=> "فواتير" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "فاكهه" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "خالد",

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "دعاء" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "لحوم" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "طيور" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "اسماك" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "البان" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "بقاله" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "مستلزمات المنزل" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "خضار" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "ليلى" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "salary" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "other" ,

            ],
            [
                'status'=> "active" ,
                'family_id'=> Family::first()->id,
            ]
        );
    }
}
