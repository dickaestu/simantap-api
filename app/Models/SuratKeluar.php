<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'no_surat', 'tanggal_surat', 'pengolah', 'tujuan_surat', 'perihal', 'keterangan', 'path', 'status', 'file'
    ];

    public function dispositions()
    {
        return $this->morphOne('App\Models\Disposition', 'disposable');
    }
}
