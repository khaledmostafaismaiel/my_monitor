<?php

use Illuminate\Database\Seeder;

use App\Category;

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
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "فاكهه" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "خالد",

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "دعاء" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "لحوم" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "طيور" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "اسماك" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "البان" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "بقاله" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "مستلزمات المنزل" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "خضار" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "ليلى" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "salary" ,

            ],
            [
                'status'=> "active" ,
            ]
        );

        Category::firstOrCreate(
            [
                'name'=> "other" ,

            ],
            [
                'status'=> "active" ,
            ]
        );
    }
}
