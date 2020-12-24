<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBagian extends Model
{
    protected $table = 'sub_bagian';
    protected $fillable = [
        'nama', 'seq', 'bagian_id', 'status_bagian', 'atasan'
    ];

    public function jenis_bagian()
    {
        return $this->belongsTo('App\Models\Bagian', 'bagian_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function atasan_sub_bagian()
    {
        return $this->belongsTo('App\Models\SubBagian', 'atasan');
    }
}
