<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBagian extends Model
{
    protected $table = 'sub_bagian';
    protected $fillable = [
        'nama', 'seq', 'bagian_id'
    ];

    public function bagian(){
        return $this->belongsTo('App\Models\Bagian');
    }

    public function users(){
        return $this->hasMany('App\Models\User');
    }
}
