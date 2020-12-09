<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    protected $fillable = [
        'no_agenda', 
        'no_surat', 
        'tanggal_surat',
        'tanggal_terima',
        'sumber_surat', 
        'tujuan_surat', 
        'perihal',
        'keterangan',
        'file',
        'status',
        'bagian_id'
    ];
}
