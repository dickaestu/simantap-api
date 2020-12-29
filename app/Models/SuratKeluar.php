<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'no_surat', 'tanggal_surat', 'pengolah', 'tujuan_surat',
        'perihal', 'keterangan', 'path', 'status', 'file', 'created_by', 'updated_by', 'bagian_id'
    ];

    public function dispositions()
    {
        return $this->morphOne('App\Models\Disposition', 'disposable');
    }

    public function status_surat()
    {
        return $this->belongsTo('App\Models\StatusSuratKeluar', 'status');
    }

    public function bagian()
    {
        return $this->belongsTo('App\Models\Bagian', 'bagian_id');
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
