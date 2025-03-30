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
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "فاكهه" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "خالد",
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "دعاء" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "لحوم" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "طيور" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "اسماك" ,
                'status'=> "active" ,
            ]
        );


        Category::create(
            [
                'name'=> "البان" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "بقاله" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "مستلزمات المنزل" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "خضار" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "ليلى" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "salary" ,
                'status'=> "active" ,
            ]
        );

        Category::create(
            [
                'name'=> "other" ,
                'status'=> "active" ,
            ]
        );
    }
}
