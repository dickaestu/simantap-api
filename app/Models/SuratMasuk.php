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
        'perihal',
        'keterangan',
        'file',
        'status',
        'created_by',
        'updated_by',
        'klasifikasi'
    ];

    public function dispositions()
    {
        return $this->morphOne('App\Models\Disposition', 'disposable');
    }

    public function created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
