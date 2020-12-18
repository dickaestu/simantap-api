<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $table = 'bagian';

    protected $fillable = [
        'nama_bagian'
    ];

    public function roles(){
        return $this->hasMany('App\Models\Role');
    }
}
