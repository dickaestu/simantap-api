<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $fillable = ['catatan', 'kepada', 'created_by', 'updated_by'];

    public function disposable()
    {
        return $this->morphTo();
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\Bagian', 'kepada');
    }

    public function sections()
    {
        return $this->belongsToMany('App\Models\Bagian', 'tembusan', 'disposition_id', 'bagian_id')->withTimestamps();
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
