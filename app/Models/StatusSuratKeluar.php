<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusSuratKeluar extends Model
{
    protected $table = 'status_surat_keluar';
    protected $fillable = [
        'status'
    ];
}
