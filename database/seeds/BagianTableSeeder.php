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
                    'nama_bagian' => 'kasubbagrenmin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'kabagdalpers',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'kabagbinkar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'kabagwatpers',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'kabagpsi',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
               

            ]
        );
    }
}
