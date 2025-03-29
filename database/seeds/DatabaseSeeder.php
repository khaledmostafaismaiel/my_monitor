<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker ;
use Illuminate\Support\Facades\DB ;
use App\User;
use App\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        User::create(
            [
                'first_name'=> "Khaled" ,
                'last_name'=> "Mostafa",
                'email'=> "khaledmostafa297@gmail.com" ,
                'password'=> bcrypt("12345678"),
            ]
        );
        
        User::create(
            [
                'first_name'=> "Doaa" ,
                'last_name'=> "Ahmed",
                'email'=> "doaagaber@gmail.com" ,
                'password'=> bcrypt("12345678"),
            ]
        );

        Category::create(
            [
                'name'=> "فواتير" ,
            ]
        );

        Category::create(
            [
                'name'=> "فاكهه" ,
            ]
        );

        Category::create(
            [
                'name'=> "خالد",
            ]
        );

        Category::create(
            [
                'name'=> "دعاء" ,
            ]
        );

        Category::create(
            [
                'name'=> "لحوم" ,
            ]
        );

        Category::create(
            [
                'name'=> "طيور" ,
            ]
        );

        Category::create(
            [
                'name'=> "اسماك" ,
            ]
        );

        Category::create(
            [
                'name'=> "دعاء" ,
            ]
        );

        Category::create(
            [
                'name'=> "البان" ,
            ]
        );

        Category::create(
            [
                'name'=> "بقاله" ,
            ]
        );

        Category::create(
            [
                'name'=> "مستلزمات المنزل" ,
            ]
        );

        Category::create(
            [
                'name'=> "خضار" ,
            ]
        );

        Category::create(
            [
                'name'=> "ليلى" ,
            ]
        );
    }
}
