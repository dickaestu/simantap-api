<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $fillable = ['catatan', 'kepada', 'isi_disposisi', 'created_by', 'updated_by', 'user_id'];

    public function disposable()
    {
        return $this->morphTo();
    }

    public function subSector()
    {
        return $this->belongsTo('App\Models\SubBagian', 'kepada');
    }

    //Relation to Tembusan
    // public function sections()
    // {
    //     return $this->belongsToMany('App\Models\Bagian', 'tembusan', 'disposition_id', 'bagian_id')->withTimestamps();
    // }

    public function user_created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function user_updated_by()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function staffmin()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function history()
    {
        return $this->morphOne('App\Models\History', 'historable');
    }
}
