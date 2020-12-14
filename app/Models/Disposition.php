<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $fillable = ['catatan'];

    public function disposable()
    {
        return $this->morphTo();
    }

    public function sector(){
        return $this->belongsTo('App\Models\Bagian', 'kepada');
    }

    public function sections()
    {
        return $this->belongsToMany('App\Models\Bagian', 'tembusan', 'disposition_id', 'bagian_id')->withTimestamps();
    }
}
