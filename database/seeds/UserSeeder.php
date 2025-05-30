<?php

use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Seeder;


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
                'email'=> "khaledmostafa@mymonitor.com" ,
            ],
            [
                'first_name'=> "Khaled" ,
                'last_name'=> "Mostafa",
                'password'=> bcrypt("password7lw@MYMONITOR"),
                'family_id'=> Family::first()->id,
            ]
        );

        User::firstOrCreate(
            [
                'email'=> "doaaahmed@mymonitor.com" ,
            ],
            [
                'first_name'=> "Doaa" ,
                'last_name'=> "Ahmed",
                'password'=> bcrypt("password7lw@MYMONITOR"),
                'family_id'=> Family::first()->id,
            ]
        );
    }
}
