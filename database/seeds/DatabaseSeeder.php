<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker ;
use Illuminate\Support\Facades\DB ;


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

        $faker = Faker::create();
        foreach (range(1,100) as $index){
            DB::table('Expenses')->insert([
                'user_id'=>1 ,
                'expense_name'=>$faker->name ,
                'price'=>$faker->numberBetween(1,100) ,
                'category'=>$faker->title ,
                'comment'=>$faker->text ,
                'created_at'=>$faker->year ,
            ]);
        }

        foreach (range(1,100) as $index){
            DB::table('Users')->insert([
                'first_name'=>1 ,
                'second_name'=>$faker->name ,
                'user_name'=>$faker->email ,
                'hashed_password'=>bcrypt('secret') ,
//                'background_image'=>$faker->text ,
                'created_at'=>$faker->year ,
            ]);
        }
    }
}
