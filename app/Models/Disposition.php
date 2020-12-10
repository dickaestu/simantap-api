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

    public function sections(){
        return $this->belongsToMany('App\Models\Bagian', 'tembusan', 'disposition_id', 'bagian_id');
    }
}
