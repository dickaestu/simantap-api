<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffminFile extends Model
{
    protected $fillable = [
        'surat_masuk_id', 'file', 'catatan', 'created_by', 'updated_by'
    ];

    public function surat_masuk()
    {
        return $this->belongsTo('App\Models\SuratMasuk', 'surat_masuk_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function history(){
        return $this->morphOne('App\Models\History', 'historable');
    }
}
