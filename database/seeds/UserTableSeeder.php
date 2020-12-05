<?php

use App\Models\User;
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

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'roles_id' => mt_rand(1, 3),
                'bagian_id' => mt_rand(1, 5),
                'email' => $faker->email,
                'username' => $faker->userName,
                'password' => Hash::make('1234567890')
            ]);
        }
    }
}
