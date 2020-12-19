<?php

use Illuminate\Database\Seeder;

use App\Models\SubBagian;

class SubBagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubBagian::insert([
        //Bagian Karo
        [
            'nama' => 'karo',
            'seq' => 1,
            'bagian_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        //Bagian Subbagrenmin
        [
            'nama' => 'kasubag renmin',
            'seq' => 2,
            'bagian_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'paur ren',
            'seq' => 3,
            'bagian_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'paur mintu',
            'seq' => 3,
            'bagian_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'paur keu',
            'seq' => 3,
            'bagian_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        //Bagian Dalpers
        [
            'nama' => 'kabag dalpers',
            'seq' => 2,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag diapers',
            'seq' => 3,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag selek',
            'seq' => 3,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag pns',
            'seq' => 3,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],  
        [
            'nama' => 'kaur diapers',
            'seq' => 4,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur selek',
            'seq' => 4,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur pns',
            'seq' => 4,
            'bagian_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        //Bagian Binkar
        [
            'nama' => 'kabag binkar',
            'seq' => 2,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag pangkat',
            'seq' => 3,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag kompeten',
            'seq' => 3,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag mutjab',
            'seq' => 3,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur pangkat',
            'seq' => 4,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur kompeten',
            'seq' => 4,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur mutjab',
            'seq' => 4,
            'bagian_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        //Bagian Watpers
        [
            'nama' => 'kabag watpers',
            'seq' => 2,
            'bagian_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag rohjashor',
            'seq' => 3,
            'bagian_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag khirdinlur',
            'seq' => 3,
            'bagian_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur rohjashor',
            'seq' => 4,
            'bagian_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur khirdinlur',
            'seq' => 4,
            'bagian_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        //Bagian Psi
        [
            'nama' => 'kabag psi',
            'seq' => 2,
            'bagian_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag psipol',
            'seq' => 3,
            'bagian_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kasubag psipers',
            'seq' => 3,
            'bagian_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur psipol',
            'seq' => 4,
            'bagian_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama' => 'kaur psipers',
            'seq' => 4,
            'bagian_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        ]);
    }
}
