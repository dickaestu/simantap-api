<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'no_agenda',
        'no_surat',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
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

    public function history()
    {
        return $this->morphOne('App\Models\History', 'historable');
    }

    public function status_surat()
    {
        return $this->belongsTo('App\Models\StatusSuratKeluar', 'status');
    }

    public function bagian()
    {
        return $this->belongsTo('App\Models\Bagian', 'bagian_id');
    }

    public function user_created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
