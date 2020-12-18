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
                    'nama_bagian' => 'subbagrenmin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'bagdalpers',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'bagbinkar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'bagwatpers',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_bagian' => 'bagpsi',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
