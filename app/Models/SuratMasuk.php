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

    public function status_surat()
    {
        return $this->belongsTo('App\Models\StatusSurat', 'status');
    }

    public function created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function history()
    {
        return $this->morphOne('App\Models\History', 'historable');
    }

    public function staffmin_file()
    {
        return $this->hasOne('App\Models\StaffminFile');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifable');
    }
}
