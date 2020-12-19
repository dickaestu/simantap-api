<?php

use App\Models\SubBagian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Faker\Factory as Faker;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $subs = SubBagian::All();

        foreach($subs as $sub){
            for ($i = 1; $i < 4; $i++) {
                $sub->users()->create([
                    'name' => $faker->name,
                    'roles_id' => $i,
                    'email' => $faker->email,
                    'username' => $faker->userName,
                    'password' => Hash::make('1234567890')
                ]);
            }
        }
    }
}
