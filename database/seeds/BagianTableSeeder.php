<?php

use App\Models\Bagian;
use Illuminate\Database\Seeder;

class BagianTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bagian::insert(
            [
                [
                    'nama_bagian' => 'karo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'kasubag',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'paur',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'staf min',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'administrator',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
