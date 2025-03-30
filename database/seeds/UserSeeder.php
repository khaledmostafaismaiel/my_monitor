<?php

use Illuminate\Database\Seeder;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            [
                'email'=> "khaledmostafa297@gmail.com" ,
            ],
            [
                'first_name'=> "Khaled" ,
                'last_name'=> "Mostafa",
                'password'=> bcrypt("12345678"),
            ]
        );
        
        User::firstOrCreate(
            [
                'email'=> "doaagaber@gmail.com" ,
            ],
            [
                'first_name'=> "Doaa" ,
                'last_name'=> "Ahmed",
                'password'=> bcrypt("12345678"),
            ]
        );
    }
}
