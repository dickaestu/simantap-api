<?php

use App\Models\StatusSurat;
use Illuminate\Database\Seeder;

class StatusSuratSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusSurat::create([
            'status' => 'Surat masuk dibuat Karo',
        ]);

        StatusSurat::create([
            'status' => 'Surat di disposisi oleh Karo ke Kabag',
        ]);

        StatusSurat::create([
            'status' => 'Kabag mendisposisikan ke bagian Kasubag',
        ]);
        StatusSurat::create([
            'status' => 'Kasubag mendisposisikan ke bagian Paur',
        ]);
        StatusSurat::create([
            'status' => 'Paur mendisposisikan ke bagian Staff Min',
        ]);
        StatusSurat::create([
            'status' => 'Staff Min telah mengupload hasil pekerjaan',
        ]);
    }
}
